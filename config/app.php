<?php
    $config['Site'] = array(
          'Title' => 'LARP-Welt',
          'Version' => '0.1',
          'Lang' => 'de-de',
          'ItemsPerPage' => 12,
          'Webmaster' => array(
              'Name' => 'LARP-Welt',
              'Email' => 'webmaster@larp-welt.de'),
          'Keywords' => 'LARP, Links, Web, Rollenspiel',
    	  'Description' => 'LARP-Welt ist die Liverollenspielseite und Community mit dem vielleicht größten LARP-Linkverzeichnis und einem eigenen Forum.',
          'Moderators' => array(3, 4));

    $config['Member'] = array(
         'Group' => array(
            'New' => 1,
            'Approved' => 2));

    $config['Comment'] = array('StartStatus' => 1);

    $config['Clicks']['NoCountPeriod'] = 1; // days before a click from the same ip is count again

    $config['Twitter']['Username'] = 'larpwelt';
    $config['Twitter']['Password'] = 'elefant';

    $config['Links']['new_intervall'] = 21;


?>