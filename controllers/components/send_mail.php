<?php
/**
 * SendMail uses SwiftMailer to send out mails.
 *
 * Just a wrapper around SwiftMailer for shorter usage.
 * Fills missing parameters (to, from, subject) with
 * common values.
 *
 * If not set, from and to defaults to the sites webmaster
 * set in config/app.php.
 *
 * @author $Author: Marcus.Ertl $
 * @version $Rev: 89 $
 */
class SendMailComponent extends object {
    var $components = array('SwiftMailer');

    /**
     * Sends mail, using SwiftMailer.
     *
     * @param array $options array('from'=>'', 'to'=>'', 'subject'=>'', 'layout'=>'')
     * @return bool False if error on send, else true.
     */
    function send($options) {
        if (key_exists('from', $options)) {
            foreach ($options['from'] as $mail => $name) {
                $this->SwiftMailer->from = $mail;
                $this->SwiftMailer->fromName = $name;
            }
        } else {
            $this->SwiftMailer->from = Configure::read('Site.Webmaster.Email');
            $this->SwiftMailer->fromName = Configure::read('Site.Webmaster.Name');
        }

        if (key_exists('to', $options)) {
            $to = $options['to'];
        } else {
            $to = array(Configure::read('Site.Webmaster.Email')=>Configure::read('Site.Webmaster.Name'));
        }

        $this->SwiftMailer->to = $to;

        if (!key_exists('subject', $options)) $options['subject'] = Configure::read('Site.Title');

        if (!$this->SwiftMailer->send($options['layout'], $options['subject'])) {
            $this->log('Error sending email "'.$options['layout'].'".', LOG_ERROR);
            return false;
        }
        return true;
    }

}

?>
