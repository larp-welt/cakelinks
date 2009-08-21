<?php
/*
 * Shows list of last visited pages.
 *
 * The last page titles and urls are stored into the session.
 */
class TracksHelper extends AppHelper {
    var $name = "TracksHelper";
    var $helpers = array('Html', 'Session');
    var $components = array('Tracks');

    var $_breadname = 'breadcrumbs';

    /*
     * render
     *
     * Renders the breadcrumbs.
     */
    function render($between = ' ... ') {
        $tracks = $this->Session->read($this->_breadname);
        $out = array();
        for ($loop=0; $loop < count($tracks); $loop++) {
            if ($this->here != $tracks[$loop][0]) {
                $out[] = '<a href="'.$tracks[$loop][0].'">'.$tracks[$loop][1].'</a>';
            } else {
                $out[] = $tracks[$loop][1];
            }
        }
        return (empty($tracks)) ? false : implode($between, $out);
    }
}
?>