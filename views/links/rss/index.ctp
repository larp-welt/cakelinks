<?php
App::import('Vendor', 'HTML_BBCodeParser', array('file' => 'BBCodeParser.php'));
App::import('Vendor', 'AutoPFormatter', array('file' => 'autop.php'));
    
function rss_transform($item) {
    $engine = new HTML_BBCodeParser();
    $breaker = new AutoPFormatter();
    $engine->setText($item['Link']['description']);
    $engine->parse();
    $parsed = $breaker->Parse($engine->getParsed());
    return array('title' => $item['Link']['title'],
				'link' => array('controller' => 'links', 'action' => 'view', 'ext' => '', $item['Link']['id']),
				'guid' => array('controller' => 'links', 'action' => 'view', 'ext' => '', $item['Link']['id']),
				'description' => $parsed,
				'pubDate' => $item['Link']['created'],
				);
}

$this->set('items', $rss->items($links, 'rss_transform'));
?>