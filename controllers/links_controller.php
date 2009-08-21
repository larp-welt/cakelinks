<?php
/**
 * Links Controller
 *
 * @author $Author: Marcus.Ertl $
 * @version $Rev: 90 $
 */

class LinksController extends AppController {
    var $name = 'Links';

    var $uses = array('Link', 'Tag', 'Hit', 'LinksTag', 'Comment', 'User');
    var $helpers = array('Html', 'Form', 'Javascript', 'Widgets', 'Cache', 'NiceHead', 'Paginator');
    var $components = array('RequestHandler', 'Lucene', 'SendMail', 'Twitter');

    var $cacheAction = false;

    /**
     * beforeFilter
     *
     * Sets default values for Auth-Component and for pagination.
     */
    function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('index', 'jumpto', 'preview', 'latest', 'view', 'mostActive', 'search', 'broken', 'count');
        $this->paginate = array('limit'=>Configure::read('Site.ItemsPerPage'));
    }

    /**
     * Link Index
     *
     * Sets paginated list of all links or of links belonging to the given tag.
     *
     * In case of use for RSS, it returns the list.
     *
     * @param string $slug
     * @return none|array
     */
    function index($slug=null) {
        $channelData = array('title' => 'Neue Links auf '.Configure::read('Site.Title'),
            'link' => '/links/index.rss',
            'url' => '/links/index.rss',
            'description' => 'Neu angemeldete Seiten auf '.Configure::read('Site.Title').'.',
            'language' => Configure::read('Site.Lang')
            );

        if ($this->RequestHandler->isRss()) {
            $this->paginate['limit'] = 10;
            $this->paginate['order'] = 'Link.created DESC';
        }

        if (!$slug) {
            $links = $this->paginate('Link', array('or'=>array(array('Link.status'=>ACTIVE),
                                                               array('Link.status'=>WARNING))));
            $this->pageTitle = 'Alle Links';
        } else {
            $tag = $this->Tag->findBySlug($slug);
            if (empty($tag)) $this->cakeError('error404',array(array('url'=>$slug)));
            $links = $this->paginate('Link', array('LinksTag.tag_id'=>$tag['Tag']['id'],
                                                   array('or'=>array(array('Link.status'=>ACTIVE),
                                                               array('Link.status'=>WARNING)))));
            $this->pageTitle = $tag['Tag']['name'];
        }

        if (isset($this->params['requested'])) {
            return $links;
        } else {
            $this->set('keywords', $this->pageTitle);
            $this->set('tagname', $this->pageTitle);
            $this->set('channelData', $channelData);
            $this->set('links', $links);
        }
    }


    function view($id=null) {
            if (!$id) {
                $this->cakeError('error404',array(array('action'=>'view')));
            }

            $this->Link->unbindModel(array('hasMany'=>array('Hit', 'view')));

            $this->Link->id = $id;
            $link = $this->Link->find('first');

            if ($link['Link']['status']!=ACTIVE && $link['Link']['status']!=WARNING) {
                $this->cakeError('error404',array(array('action'=>'index')));
            }

            $tags = array();
            foreach ($link['Tag'] as $tag) {$tags[] = $tag['name']; }
            $keywords = $link['Link']['title'].', '.implode(', ', $tags);

            // XXX: Bruch mit MVC-Pattern... aber wie mach ich das mit Pagination?
            $this->paginate['order'] = 'Comment.created DESC';
            $comments = $this->paginate('Comment', array( 'Comment.parent_id'=>$link['Link']['id'],
                                                          'Comment.parent_model'=>'Links',
                                                          array('or'=>array(array('Comment.status'=>ACTIVE),
                                                                      array('Comment.status'=>WARNING)))));

            $description = $link['Link']['description'];
            $this->pageTitle = $link['Link']['title'];
            $this->set(compact('keywords', 'description', 'link', 'comments'));
    }


    function add() {
        if (!empty($this->data)) {
            $user = $this->Auth->user();

            $this->Link->create();
            $this->data['Link']['user_id'] = $user['User']['id'];

            if (!empty($this->data['Tag']['Tag'])) {
                foreach ($this->data['Tag']['Tag'] as &$tg) {
                     if (substr($tg, 0, 4) == 'new:') {
                          $tg = $this->Link->Tag->createIfNew(substr($tg,4));
                     }
                }
            }

			$this->data['Tag']['Tag'] = array_unique($this->data['Tag']['Tag']);
            $this->data['Link']['status'] = FRESH;

            if ($this->Link->save($this->data)) {
                $this->Session->setFlash('<p>Ihr Link wurde in unsere Datenbank eingefügt.</p>
                                          <p>Nachdem ein Moderator ihn geprüft hat, wird er frei geschalten.</p>',
                                         'default', array('action'=>'index', 'class'=>'ok'));

                /* send mails to mods */
                $mods = $this->User->getGroupmails(Configure::read('Site.Moderators'));
                $this->set('data', $this->data);
                $this->SendMail->send(array('to'=>$mods,
                                            'layout'=>'mod_newlink',
                                            'subject'=>'Neuer Link bei '.Configure::read('Site.Title')));
				$this->redirect('/');
				exit(0);
            }
        }
        $this->pageTitle = 'Link anmelden';
        $this->set('tags', $this->Link->Tag->find('list', array('conditions'=>array('id !='=>0))));
        $this->set('data', $this->data);
    }


    function edit($id=null) {
        // Two edit functions, because I want different rights for the owner.

        if (!$id && empty($this->data)) {
            $this->cakeError('error404', array(array('action'=>'edit')));
        } else {
            $link = $this->Link->read(null, $id);
            $user = $this->Auth->user();
            if ($user['User']['id'] == $link['Link']['user_id']) {
                $this->mod_edit($id);
            } else {
                $this->cakeError('error403', array(array('action'=>'edit')));
            }
        }
        $this->pageTitle = 'Link bearbeiten';
        $this->set('data', $this->data);
    }


    function broken($id=null) {
        if (!$id && empty($this->data)) {
            $this->cakeError('error404',array(array('action'=>'broken')));
        } else {
            if (isset($this->data)) {
                $this->Link->id = $id;
                $link = $this->Link->find('first', array('recursive'=>-1, 'conditions'=>array('Link.id'=>$id)));
                $this->data['Link']['url'] = $link['Link']['url'];
                $this->data['Link']['title'] = $link['Link']['title'];
                $this->data['Link']['id'] = $id;
                $this->Link->set($this->data);

                if ($this->Link->validates()) {

                    $this->Link->setStatus(BROKEN);

                    /* send mails to mods */
                    $mods = $this->User->getGroupmails(Configure::read('Site.Moderators'));
                    $this->set('data', $this->data);
                    $this->SendMail->send(array('to'=>$mods,
                                                'layout'=>'broken',
                                                'subject'=>'Broken Link bei '.Configure::read('Site.Title')));
                    $this->Session->setFlash('<p>Die E-Mail wurde an uns gesandt!</p>',
                                             'default', array('class'=>'ok'));
                    $this->redirect('/');
                    exit(0);
                }
            } else {
                $this->Link->id = $id;
                $link = $this->Link->find('first', array('recursive'=>-1, 'conditions'=>array('Link.id'=>$id)));
                $this->data['Link']['url'] = $link['Link']['url'];
                $this->data['Link']['title'] = $link['Link']['title'];
                $this->data['Link']['id'] = $id;
            }
        }
        $this->pageTitle = 'Link melden';
        $this->set('data', $this->data);
    }


    function jumpto($id=null) {
        /*
         * I'm don't using counterCache for counting hits.
         * I want to be able to delete old hits from the hits
         * table.
         */
        $this->Link->id = $id;
        $link = $this->Link->read();

        if ($link['Link']['status']!=ACTIVE && $link['Link']['status']!=WARNING) {
            $this->cakeError('error404',array(array('action'=>'jumpto')));
        }

        $this->Link->countHit();

        $this->redirect($link['Link']['url']);
        exit();
    }


    function search() {
        App::import('Vendor', 'Numbers', array('file'=>'numbers.php'));

        $conditions = array();
        $results = null;

        if (isset($this->params['url']['q'])) {
            App::import('Sanitize');

            $q = $this->params['url']['q'];
            $results = $this->Lucene->query($q);

            if (!empty($results)) {
                $ids = array();
                foreach ($results as $l) {
                    $ids[] = despell($l->Link_id);
                }

                $conditions = array("Link.id"=>$ids,
                                    array('or'=>array(array('Link.status'=>ACTIVE),
                                                      array('Link.status'=>WARNING))));

                $results = $this->paginate('Link', $conditions);
            }
        } else { $q = ''; }

        $this->set(compact('results', 'q'));
        $this->pageTitle = 'Suchen';
    }


    /* Moderation */

    function mod_edit($id=null) {
        if (!$id && empty($this->data)) {
                $this->cakeError('error404',array(array('action'=>'mod_edit')));
        }

        if (!$this->Session->check('Links.Edit.Redirect') && env('HTTP_REFERER')) {
        	$this->Session->write('Links.Edit.Redirect', $this->referer('/', true));
        }

        if (!empty($this->data)) {
                if (!empty($this->data['Tag']['Tag'])) {
                    foreach ($this->data['Tag']['Tag'] as &$tg) {
                         if (substr($tg, 0, 4) == 'new:') {
                              $tg = $this->Link->Tag->createIfNew(substr($tg,4));
                         }
                    }
                }

                if ($this->Link->save($this->data)) {
                        $this->Session->setFlash('<p>Der Link wurde gespeichert!</p>',
                                                 'default', array('class'=>'ok'));
                        if ($this->Session->check('Links.Edit.Redirect')) {
                        	$url = $this->Session->read('Links.Edit.Redirect');
                        	$this->Session->del('Links.Edit.Redirect');
                        	$this->redirect($url);
                        } else {
                        	$this->redirect('/');
                        }
                        exit(0);
                } else {
                }
        } else  {
                $this->data = $this->Link->read(null, $id);
        }
        $this->pageTitle = 'Link moderieren';
        $tags = $this->Link->Tag->find('list', array('conditions'=>array('id !='=>0)));
        $this->set(compact('tags'));
        $this->set('data', $this->data);
    }


    function mod_index() {
        $actions = $this->data;
        $work = false;

        if (!empty($actions)) {
            foreach ($actions['Link']['action'] as $id=>$action) {
                $this->Link->id = $id;
                $this->Link->unbindModel(array('hasMany' => array('Hit')));
                $this->data = $this->Link->read(null, $id);

                $old = $this->data['Link']['status'];

                switch ($action) {
                    case 'reject':
                        foreach ($this->data['Tag'] as $tag) { $temptags[] = $tag['name']; }
                        $this->Link->setStatus(DELETED);
                        $work = true;

                        if ($old == FRESH && $actions['Link']['email'][$id]) {

                            $this->set('data', $this->data);
                            $this->SendMail->send(array('to'=>array($this->data['User']['email']=>$this->data['User']['username']),
                                                        'layout'=>'mod_reject',
                                                        'subject'=>'Ihr Link bei '.Configure::read('Site.Title')));
                        }

                        break;
                    case 'publish':
                        $temptags = array();
                        foreach ($this->data['Tag'] as $tag) { $temptags[] = $tag['name']; }
                        $this->Link->setStatus(ACTIVE);
                        $work = true;

                        if ($old == FRESH) {
                            if ($actions['Link']['email'][$id]) {
                                $this->set('data', $this->data);
                                $this->SendMail->send(array('to'=>array($this->data['User']['email']=>$this->data['User']['username']),
                                                            'layout'=>'mod_publish',
                                                            'subject'=>'Ihr Link bei '.Configure::read('Site.Title')));
                            }

                            $msg = 'Neuer Link: '.$this->data['Link']['title'].' - '.$this->data['Link']['url'].' #larp, #link';
                            $this->_twitter($msg);
                        }

                        break;
                    default:
                        break;
                }
            }
            if ($work) {
                $this->Session->setFlash('<p>Die Links wurden von Ihnen moderiert!</p><p>Vielen Dank!</p>',
                                         'default', array('class'=>'ok'));
                $this->data = null;
            }
        }

        $conditions = array('and'=>array('Link.status <>'=>ACTIVE), array('Link.status <>'=>DELETED));
        $links = $this->paginate('Link', $conditions);
        $this->data = array();
        $this->pageTitle = 'Moderation';

        $this->set(compact('links', 'data'));
    }



    /* Infoboxen, Vorschau, ect. */

    function preview($parser = 'bbcode') {
        $this->layout = 'preview';
        $this->set('parser', $parser);
        $this->set('content', $this->data);
    }


    function latest($limit=8) {
        return $this->Link->find('all', array('conditions'=>array('or'=>array(array('Link.status'=>ACTIVE),
                                                                        array('Link.status'=>WARNING))),
                                               'order'=>'Link.created DESC',
                                               'limit'=>$limit,
                                               'recursive'=>-1));
    }


    function mostActive($limit=8) {
    	$conditions = array('(TO_DAYS(NOW()) - TO_DAYS(Link.created)) > 0',
                            array('or'=>array(array('Link.status'=>ACTIVE),
                                              array('Link.status'=>WARNING))));
        $fields = array('id', 'title', 'url', 'hit_count', '(hit_count/(TO_DAYS(NOW()) - TO_DAYS(created))) AS hpd');
        return $this->Link->find('all', array('conditions'=>$conditions,
                                               'order'=>'hpd DESC',
                                               'fields'=>$fields,
                                               'limit'=>$limit,
                                               'recursive'=>-1));
    }


    function mod_newlinks($limit=8) {
        return $this->Link->find('all', array('conditions'=>array('Link.status'=>FRESH),
                                               'order'=>'Link.created DESC',
                                               'limit'=>$limit,
                                               'recursive'=>-1));
    }


    function count() {
        $conditions = array(
            array('or'=>array('Link.status'=>ACTIVE),
                        array('Link.status'=>WARNING)),
            'recursive'=>-1);
        return $this->Link->find('count', $conditions);
    }


    function userlinks($user=null) {
        if (empty($user)) {
            $this->cakeError('error404',array(array('action'=>'jumpto')));
        }

        $conditions = array('Link.user_id'=>$user);

        return $this->Link->find('all', $conditions);
    }



    function _twitter($msg) {
        $this->Twitter->username = Configure::read('Twitter.Username');
        $this->Twitter->password = Configure::read('Twitter.Password');
        $this->Twitter->status_update($msg);
    }

}

?>