<?php

class Ringsite extends AppModel {
    var $name = 'Ringsite';
    var $belongsTo = 'Link';


    function countHit() {
        /*
         * At the moment, we do no checks for repeatet hits from the
         * same ip.
         */

        $this->saveField('hits', $this->data['Ringsite']['hits']+1, false);
    }


    function next() {
        /*
         * Returns next link in webring. If this link is the last, just returns
         * the first link!
         */

        return $this->skip(1);
    }


    function prev() {
        /*
         * Returns previous link in webring. If this link is the last, just returns
         * the last link!
         */

        $next = $this->find('first', array('conditions'=>array('position <'=>$this->data['Ringsite']['position']),
                                           'order'=>'position DESC'));

        if (empty($next)) {
            $next = $this->find('first', array('conditions'=>array('position >='=>0),
                                               'order'=>'position DESC'));
        }

        return $next;
    }


    function rand() {
        /*
         * Returns previous link in webring. If this link is the last, just returns
         * the last link!
         */

        $next = $this->find('first', array('order'=>'position DESC'));

        $rand = rand(1, $next['Ringsite']['position']);

        $next = $this->find('first', array('conditions'=>array('position'=>$rand)));

        return $next;
    }


    function skip($steps=1) {
        /*
         * Returns next link in webring. If this link is the last, just returns
         * the first link!
         */
        $pos = $this->data['Ringsite']['position'];
        $next = null;

        for ($loop=1; $loop <= $steps; $loop++) {
            $next = $this->find('first', array('conditions'=>array('position >'=>$pos),
                                               'order'=>'position ASC'));
            if (empty($next)) {
                $next = $this->find('first', array('conditions'=>array('position >='=>0),
                                                   'order'=>'position ASC'));
            }
            $pos = $next['Ringsite']['position'];
        }
        return $next;
    }


}

?>