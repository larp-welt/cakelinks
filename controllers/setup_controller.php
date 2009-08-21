<?php

uses('model' . DS . 'connection_manager');

class SetupController extends AppController {

    var $uses = array('Group', 'User');
    var $components = array('ControllerList');
    var $helpers = array('Html', 'Form', 'Javascript', 'NiceHead');


    function beforeFilter() {
        parent::beforeFilter();
        // $this->Auth->allow('*');
        $this->layout = 'admin';
    }


    function index() {
        $this->pageTitle = 'Setup';
    }


    function database() {
        $this->_database();

        $this->Session->setFlash('Datenbank angelegt!');
        $this->redirect('/setup/index');
        exit(0);
    }


    function users_groups() {
        $this->_users_groups();

        $this->Session->setFlash('Benutzer und Gruppen angelegt!');
        $this->redirect('/setup/index');
        exit(0);
    }


    function rights() {
        $this->_rights();

        $this->Session->setFlash('Rechte einerichtet!');
        $this->redirect('/setup/index');
        exit(0);
    }


    function samples() {
        $this->_samples();

        $this->Session->setFlash('Beispieldaten angelegt!');
        $this->redirect('/setup/index');
        exit(0);
    }


    function all() {
        $this->_database();
        $this->_users_groups();
        $this->_rights();
        $this->_samples();

        $this->Session->setFlash('Konfiguration abgeschlossen!');
        $this->redirect('/setup/index');
        exit(0);
    }


    function _database() {
        $db = ConnectionManager::getDataSource('default');

        if(!$db->isConnected()) {
            echo 'Could not connect to database. Please check the settings in app/config/database.php and try again';
            exit();
        }

        $this->__executeSQLScript($db, CONFIGS.'sql'.DS.'tables.sql');
        $db->query('TRUNCATE TABLE aros');
        $db->query('TRUNCATE TABLE acos');
        $db->query('TRUNCATE TABLE aros_acos');
    }


    function _users_groups() {
        $db = ConnectionManager::getDataSource('default');
        $db->query('TRUNCATE TABLE groups');
        $db->query('TRUNCATE TABLE users');
        $db->query('TRUNCATE TABLE aros');
        $db->query('TRUNCATE TABLE acos');

        $this->Group->create();
        $group['name'] = 'Bewerber';
        $group['parent_id'] = null;
        $this->Group->save($group);

        $this->Group->create();
        $group['name'] = 'Mitglied';
        $group['parent_id'] = 1;
        $this->Group->save($group);

        $this->Group->create();
        $group['name'] = 'Moderatoren';
        $group['parent_id'] = 2;
        $this->Group->save($group);

        $this->Group->create();
        $group['name'] = 'Administratoren';
        $group['parent_id'] = 3;
        $this->Group->save($group);

        $this->User->create();
        $user['username'] = 'admin';
        $user['email'] = $user['email2'] = 'admin@larp-welt.de';
        $user['password'] = SHA1("sf6LlljfFSBTHR99vrnout3ze332o46sa8E355366vnacadmin");
        $user['secret'] = $user['secret2'] = '123456';
        $user['group_id'] = 4;
        $this->User->save($user);

        $this->User->create();
        $user['username'] = 'moderator';
        $user['email'] = $user['email2'] = 'mod@larp-welt.de';
        $user['password'] = SHA1("sf6LlljfFSBTHR99vrnout3ze332o46sa8E355366vnacmoderator");
        $user['secret'] = $user['secret2'] = '123456';
        $user['group_id'] = 3;
        $this->User->save($user);

        $this->User->create();
        $user['username'] = 'member';
        $user['email'] = $user['email2'] = 'member@larp-welt.de';
        $user['password'] = SHA1("sf6LlljfFSBTHR99vrnout3ze332o46sa8E355366vnacmember");
        $user['secret'] = $user['secret2'] = '123456';
        $user['group_id'] = 2;
        $this->User->save($user);

        $this->User->create();
        $user['username'] = 'superuser';
        $user['email'] = $user['email2'] = 'superuser@larp-welt.de';
        $user['password'] = SHA1("sf6LlljfFSBTHR99vrnout3ze332o46sa8E355366vnacsuperuser");
        $user['secret'] = $user['secret2'] = '123456';
        $user['group_id'] = null;
        $this->User->save($user);
    }


    function _samples() {
        $db = ConnectionManager::getDataSource('default');

        if(!$db->isConnected()) {
            echo 'Could not connect to database. Please check the settings in app/config/database.php and try again';
            exit();
        }

        $db->query('TRUNCATE TABLE links');
        $db->query('TRUNCATE TABLE tags');
        $db->query('TRUNCATE TABLE links_tags');
        $this->__executeSQLScript($db, CONFIGS.'sql'.DS.'samples.sql');
    }


    function _rights() {
        $aco = new Aco();

        $db = ConnectionManager::getDataSource('default');
        $db->query('TRUNCATE TABLE aros_acos');

        $data = $this->ControllerList->get();

        $aco->create();
        $aco->save(array('parent_id'=>null, 'alias'=>'Site'));
        @$site = $aco->node('Site');
        $site = Set::extract($site, "0.Aco.id");

        foreach ($data as $ctrl=>$actions) {
            $aco->create();
            $aco->save(array('parent_id'=>$site, 'alias'=>$ctrl));
            @$parent = $aco->node($ctrl);
            $parent = Set::extract($parent, "0.Aco.id");
            foreach ($actions as $act) {
                $aco->create();
                $aco->save(array('parent_id'=>$parent, 'alias'=>$act));
            }
        }

        // Superuser
        //
        $this->Acl->allow(array('model' => 'User', 'foreign_key' => 4), 'Site', '*');

        // Links
        // -> Member
        $this->Acl->allow(array('model' => 'Group', 'foreign_key' => 2), 'Site/Links/add', '*');
        $this->Acl->allow(array('model' => 'Group', 'foreign_key' => 2), 'Links/edit', '*'); /* Owner */
        $this->Acl->allow(array('model' => 'Group', 'foreign_key' => 2), 'Users/edit', '*'); /* Owner */
        $this->Acl->allow(array('model' => 'Group', 'foreign_key' => 2), 'Comments/add', '*');
        //$this->Acl->allow(array('model' => 'Group', 'foreign_key' => 2), 'Comments/edit', '*'); /* Owner */

        // -> Moderator
        $this->Acl->allow(array('model' => 'Group', 'foreign_key' => 3), 'Links/mod_index', '*');
        $this->Acl->allow(array('model' => 'Group', 'foreign_key' => 3), 'Links/mod_edit', '*');
        $this->Acl->allow(array('model' => 'Group', 'foreign_key' => 3), 'Links/mod_newlinks', '*');

        $this->Acl->allow(array('model' => 'Group', 'foreign_key' => 3), 'Tags/mod_index', '*');
        $this->Acl->allow(array('model' => 'Group', 'foreign_key' => 3), 'Tags/mod_edit', '*');
        $this->Acl->allow(array('model' => 'Group', 'foreign_key' => 3), 'Tags/mod_delete', '*');
        $this->Acl->allow(array('model' => 'Group', 'foreign_key' => 3), 'Tags/mod_view', '*');

        // -> Administratoren
        $this->Acl->allow(array('model' => 'Group', 'foreign_key' => 4), 'Site/Setup', '*');
        $this->Acl->allow(array('model' => 'Group', 'foreign_key' => 4), 'Users/admin_index', '*');
        $this->Acl->allow(array('model' => 'Group', 'foreign_key' => 4), 'Users/admin_edit', '*');
        $this->Acl->allow(array('model' => 'Group', 'foreign_key' => 4), 'Site/Groups', '*');
        $this->Acl->allow(array('model' => 'Group', 'foreign_key' => 4), 'Site/Desktop', '*');
    }



    function __executeSQLScript($db, $fileName) {
        $statements = file_get_contents($fileName);
        $statements = explode(';', $statements);

        foreach ($statements as $statement) {
            if (trim($statement) != '') {
                $db->query($statement);
            }
        }
    }



}
?>
