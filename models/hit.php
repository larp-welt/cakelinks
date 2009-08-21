<?php

    class Hit extends AppModel {
        var $name = 'Hit';

        var $belongsTo = array('Link'=>array('counterCache'=>false));
    }

?>