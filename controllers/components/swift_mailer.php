<?php
/*
 * SwiftMailer 3 Component
 * @author Matt Huggins
 * @version 1.0
 * @license MIT
 */

App::import('Vendor', 'Swift', array('file' => 'SwiftMailer'.DS.'Swift.php'));

class SwiftMailerComponent extends Object {
	var $controller = false;

	var $layout        = 'email/email'; // XXX
	var $viewPath      = 'email';

	var $smtpType      = 'open';       // open, ssl, tls
	var $smtpUsername  = '';
	var $smtpPassword  = '';
	var $smtpHost      = '';  // specify host or leave blank to auto-detect
	var $smtpPort      = null;         // null to auto-detect, otherwise specify (e.g.: 25 for open, 465 for ssl, etc.)
	var $smtpTimeout   = 10;           // seconds before timeout occurs

	var $sendmailCmd   = null;         // null to auto-detect, otherwise manually defined (e.g.: '/usr/sbin/sendmail -bs')

	var $from          = null;
	var $fromName      = null;
	var $to            = null;         // Each of $to, $cc, and $bcc should all be formatted as an array of
	var $cc            = null;         // key => value pairs that represent email address/name. e.g.:
	var $bcc           = null;         //   array('bob@google.com'=>'Bob Smith', 'joe@yahoo.com'=>'Joe Shmoe')


	function startup(&$controller) {
		$this->controller =& $controller;
	}


	function _connect($method) { // smtp, sendmail, native
		// Create the appropriate Swift mailer object based upon the connection type.
		switch ($method) {
			case 'smtp':
				return $this->_connectSMTP();
			case 'sendmail':
				return $this->_connectSendmail();
			case 'native': default:
				return $this->_connectNative();
		}
	}


	function _connectNative() {
		App::import('Vendor', 'Swift_Connection_NativeMail', array('file' => 'SwiftMailer'.DS.'Swift'.DS.'Connection'.DS.'NativeMail.php'));

		// Return the swift mailer object.
		return new Swift(new Swift_Connection_NativeMail());
	}


	function _connectSendmail() {
		App::import('Vendor', 'Swift_Connection_Sendmail', array('file' => 'SwiftMailer'.DS.'Swift'.DS.'Connection'.DS.'Sendmail.php'));

		// Auto-detect the sendmail command to use if not specified.
		if (empty($this->sendmailCmd)) {
			$this->sendmailCmd = Swift_Connection_Sendmail::AUTO_DETECT;
		}

		// Return the swift mailer object.
		return new Swift(new Swift_Connection_Sendmail($this->sendmailCmd));
	}


	function _connectSMTP() {
		App::import('Vendor', 'Swift_Connection_SMTP', array('file' => 'SwiftMailer'.DS.'Swift'.DS.'Connection'.DS.'SMTP.php'));

		// Detect SMTP host if not provided.
		if (empty($this->smtpHost)) {
			$this->smtpHost = Swift_Connection_SMTP::AUTO_DETECT;
		}

		// Detect SMTP port if not provided.
		if (empty($this->smtpPort)) {
			$this->smtpPort = Swift_Connection_SMTP::AUTO_DETECT;
		}

		// Determine what type of connection to use (open, ssl, tls).
		switch ($this->smtpType) {
			case 'ssl':
				$smtpType = Swift_Connection_SMTP::ENC_SSL; break;
			case 'tls':
				$smtpType = Swift_Connection_SMTP::ENC_TLS; break;
			case 'open': default:
				$smtpType = Swift_Connection_SMTP::ENC_OFF;

		}

		// Create the swift mailer object, and prepare authentication if required.
		$smtp =& new Swift_Connection_SMTP($this->smtpHost, $this->smtpPort, $smtpType);
		$smtp->setTimeout($this->smtpTimeout);

		if (!empty($this->smtpUsername)) {
			$smtp->setUsername($this->smtpUsername);
			$smtp->setPassword($this->smtpPassword);
		}

		// Return the swift mailer object.
		return new Swift($smtp);
	}


	function _getBodyText($view) {
		// Temporarily store vital variables used by the controller.
		$tmpLayout = $this->controller->layout;
		$tmpAction = $this->controller->action;
		$tmpOutput = $this->controller->output;
		$tmpRender = $this->controller->autoRender;

		// Render the plaintext email body.
		ob_start();
		$this->controller->output = null;
		$body = $this->controller->render($this->viewPath . DS . $view . '_text', $this->layout . '_text');
		ob_end_clean();

		// Restore the layout, view, output, and autoRender values to the controller.
		$this->controller->layout = $tmpLayout;
		$this->controller->action = $tmpAction;
		$this->controller->output = $tmpOutput;
		$this->controller->autoRender = $tmpRender;

		return $body;
	}


	function _getBodyHTML($view) {
		// Temporarily store vital variables used by the controller.
		$tmpLayout = $this->controller->layout;
		$tmpAction = $this->controller->action;
		$tmpOutput = $this->controller->output;
		$tmpRender = $this->controller->autoRender;

		// Render the HTML email body.
		ob_start();
		$this->controller->output = null;
		$body = $this->controller->render($this->viewPath . DS . $view . '_html', $this->layout . '_html');
		ob_end_clean();

		// Restore the layout, view, output, and autoRender values to the controller.
		$this->controller->layout = $tmpLayout;
		$this->controller->action = $tmpAction;
		$this->controller->output = $tmpOutput;
		$this->controller->autoRender = $tmpRender;

		return $body;
	}


	function send($view = 'default', $subject = '', $method = 'smtp') {
		// Create the message, and set the message subject.
		$message =& new Swift_Message($subject);

		// Append the HTML and plain text bodies.
		$bodyHTML = $this->_getBodyHTML($view);
		$bodyText = $this->_getBodyText($view);

		$message->attach(new Swift_Message_Part($bodyHTML, "text/html"));
		$message->attach(new Swift_Message_Part($bodyText, "text/plain"));

		// Set the from address/name.
		$from =& new Swift_Address($this->from, $this->fromName);

		// Create the recipient list.
		$recipients =& new Swift_RecipientList();

		// Add all TO recipients.
		if (!empty($this->to)) {
			if (is_array($this->to)) {
				foreach($this->to as $address => $name) {
					$recipients->addTo($address, $name);
				}
			} else {
				$recipients->addTo($this->to, $this->to);
			}
		}

		// Add all CC recipients.
		if (!empty($this->cc)) {
			if (is_array($this->cc)) {
				foreach($this->cc as $address => $name) {
					$recipients->addCc($address, $name);
				}
			} else {
				$recipients->addCc($this->cc, $this->cc);
			}
		}

		// Add all BCC recipients.
		if (!empty($this->bcc)) {
			if (is_array($this->bcc)) {
				foreach($this->bcc as $address => $name) {
					$recipients->addBcc($address, $name);
				}
			} else {
				$recipients->addBcc($this->bcc, $this->bcc);
			}
		}

		// Attempt to send the email.
		$mailer =& $this->_connect($method);
		$result = $mailer->send($message, $recipients, $from);
		$mailer->disconnect();

		return $result;
	}
}
?>