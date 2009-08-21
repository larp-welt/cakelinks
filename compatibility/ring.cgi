#!/usr/local/bin/perl -w
use strict;
use CGI qw(:standard);

my ($q, $cmd, $id, $location);
$q = new CGI();
$cmd = $q->param('cmd') || 'rand';
$id = $q->param('ID') || $q->param('id') || '';

$location = "http://www.larp-welt.de/links/ringsites/".lc($cmd)."/".$id;

print "Status: 302 Moved\n";
print "Location: $location\n\n";