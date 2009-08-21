<?php
/**
 * User Profiles
 *
 * Model for storing of user profiles.
 *
 * All of this data is public!
 *
 * @author $Author: Marcus.Ertl $
 * @version $Rev: 58 $
 */

class Profile extends AppModel {
    var $name = 'Profile';

    var $belongsTo = array('User');

    var $actsAs = array('Image'=>array(
                    'baseDir' => 'upload',
                    'fields'=>array(
                          'image'=>array(
                                'thumbnail'=>array('create'=>false),
                                'resize'=>array('width'=>'500'),
                                'allow_enlarge'=>false,
                                'aspect'=>true
                                ),
                           'icon'=>array(
                                'thumbnail'=>array('create'=>false),
                                'resize'=>array('height'=>'32'),
                                'allow_enlarge'=>false,
                                'aspect'=>true
                                )
                            )
                      )
                  );



}

?>