<?php echo '<?xml version="1.0" encoding="UTF-8"?>'?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="de" lang="de">
<head>
    <title>LW: <?php echo $title_for_layout?></title>
    <?php 
      echo $html->charset('utf-8');
      echo $html->css(array('default', 'linktypes'));
      echo $html->css('print_default', null, array('media'=>'print'));
      echo $scripts_for_layout;
      echo $html->meta('Neue Links', '/links/index.rss', array('type'=>'rss'));

      $keys = Configure::read('Site.Keywords');
      if (isset($keywords)) { $keys = $keywords.', '.$keys; }
      echo $html->meta('keywords', $keys);
      
      if (isset($description)) {
          App::import('Vendor', 'text', array('file' => 'text.php'));
          $description = truncate(preg_replace('/[\n\r]/', '', strip_bbcode($description)),100,'...');
          echo $html->meta('description', $description); 
      } else {
	      echo $html->meta('description', Configure::read('Site.Description'));
	  }

      if (isset($niceHead)) $niceHead->flush(); ?>
    <script type="text/javascript">
    var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
    document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
    </script>
    <script type="text/javascript">
    var pageTracker = _gat._getTracker("UA-115283-4");
    pageTracker._trackPageview();
    </script>
</head>
<body>
<?php echo $this->element('larpnetz', array('cache'=>'1 day')); ?>
<div id="head"><?php echo $html->link(
                  $html->image('logo.png', array('id'=>'logo', 'title'=>Configure::read('Site.Title'), 'alt'=>'')),
                  '/', array(), false, false)?>
<div id="navbar"><div id="mainnav"><a href="/links/">Links</a> - <a href="/forum/">Forum</a> - <a href="/bugs/">Bugs</a> - <a href="/links/pages/impressum">Impressum</a> - <a href="/links/mail_mes/send">Kontakt</a></div></div>
</div>
<div class="colmask holygrail">
    <div class="colmid">
        <div class="colleft">
            <div class="contentwrap">
                <div id="content">
                    <?php 
                      $crumbs = $tracks->render();
                      if ($crumbs) echo '<p class="breadcrumbs">&raquo; '.$crumbs.'</p>';
                      ?>
                    <?php $session->flash(); ?>
                    <?php echo $content_for_layout; ?>
                </div>
            </div>
            <div id="left" class="sidebar">
                <?php echo $this->element('login', array('cache'=>false)); ?>
                <?php echo $this->element('linkmenu', array('cache'=>false)); ?>
                <?php echo $this->element('mod_newlinks', array('cache'=>false)); ?>
                <!-- ?php echo $this->element('larpkalender', array('cache'=>'1 hour')); ? -->
                <h1 class="help">Mithelfen!</h1>
                <div class="box"><ul>
                <li><a href="/bugs">Fehler melden</a></li>
                <li><a href="/forum">Forum</a></li>
                </ul></div>
                <?php echo $this->element('webring', array('cache'=>false)); ?>
            </div>
            <div id="right" class="sidebar">
                <?php echo $this->element('latest_links', array('cache'=>'15 min')); ?>
                <?php echo $this->element('latest_comments', array('cache'=>'15min')); ?>
                <?php echo $this->element('most_active', array('cache'=>'12 hour')); ?>
            </div>
        </div>
    </div>
</div>

<div id="foot">
<div class="copyright">&copy; 1998-<?php echo date('Y')?> by <?php echo Configure::read('Site.Title')?></div>
<div class="buttons"><?php echo $html->link(
                                $html->image('cake.power.gif', array('title'=>'', 'alt'=>'cakePHP')),
                                'http://www.cakephp.org/', array(), false, false)?></div>
<?php echo $this->element('stats', array('cache'=>'15 min')); ?>
</div><a href="http://www.larp-welt.de/office/larpies.php"><!-- glance-bohemian --></a>
</body>
</html>
