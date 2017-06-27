<?php
namespace System\Core;

use System\Libs\DI;
use System\Libs\Message;
use System\Libs\Language;
use System\Libs\Auth;
use System\Libs\EventManager;
use System\Libs\Config;

class Application {

    protected $request;

    public function __construct()
    {
        // request objektum visszaadása (itt már létrejön az uri és router objektum is!)
        $this->request = DI::get('request');

        // area állandó létrehozása
        define('AREA', $this->request->get_uri('area'));
        
        // visszaadjuk a nyelvi kódot, ha nincs az url-ben akkor NULL
        $langcode = $this->request->get_uri('langcode');
        // default nyelvi kód beállítása, ha nincs nyelvi kód az url-ben
        if (is_null($langcode)) {
            $langcode = Config::get('language_default_' . AREA);
            // beállítjuk az uri objektumban is a default nyelvi kódot
            DI::get('uri')->set_langcode($langcode);
        }

        // LANG állandó létrehozása
        define('LANG', $langcode);

        // Betöltjük az aktuális nyelvnek megfelelő üzenet fájlt
        Message::init('messages_' . AREA, LANG);

            // front oldal
            if (AREA == 'site') {

                if (MULTILANG_SITE == true) {
                    // nyelvi fájl betöltése
                    Language::init(LANG, DI::get('connect'));
                }

            }


        // Megadjuk az Auth osztály alapbeállításait ('auth.php' config file betöltése)
        Auth::init('auth');

        // események inicializálása
        EventManager::init('events');

        // route-ok megadása, controller file betöltése és a megfelelő action behívása
        $this->_loadController();
    }

    private function _loadController()
    {
        $router = DI::get('router');

        /* ************* SITE ******************************* */
        if (AREA == 'site') {

            $router->get('/', 'home@index');
            $router->set404('error@index');

            $router->mount('/en', function() use ($router) {
                $router->get('/', 'home@index');
            });

            
        }
        /* **************** ADMIN *************************** */
         elseif (AREA == 'admin') {

            $router->mount('/admin', function() use ($router) {

                $router->before('GET|POST', '/?((?!login).)*', function() {
                    if (!Auth::check()) {
                        $response = DI::get('response');
                        $response->redirect('admin/login');
                    }
                });


                $router->get('/', 'home@index');
                $router->get('/home', 'home@index');

                // login logout	
                $router->match('GET|POST', '/login', 'login@index');
                $router->get('/login/logout', 'login@logout');

                // pages	
                $router->get('/pages', 'pages@index');
                $router->match('GET|POST', '/pages/update/:id', 'pages@update', array('id'));
                $router->match('GET|POST', '/pages/insert', 'pages@insert');

                // content	
                $router->get('/content', 'content@index');
                $router->get('/content/create', 'content@create');
                $router->post('/content/store', 'content@store');
                $router->get('/content/edit/:id', 'content@edit');
                $router->post('/content/update/:id', 'content@update');

                // user	
                $router->get('/user', 'user@index');
                $router->match('GET|POST', '/user/insert', 'user@insert');
                $router->match('GET|POST', '/user/profile/:id', 'user@profile', array('id'));
                $router->post('/user/delete', 'user@delete');
                $router->post('/user/deleteimage', 'User@deleteImage');
                $router->post('/user/change_status', 'user@change_status');
                $router->post('/user/user_img_upload/(upload)', 'user@user_img_upload', array('upload'));
                $router->post('/user/user_img_upload/(crop)', 'user@user_img_upload', array('crop'));
                $router->match('GET|POST', '/user/user_roles', 'user@user_roles');
                $router->match('GET|POST', '/user/edit_roles/:id', 'user@edit_roles', array('id'));

                // photo gallery	
                $router->get('/photo-gallery', 'PhotoGallery@index');
                $router->post('/photo-gallery/delete_photo', 'PhotoGallery@delete_photo');
                $router->post('/photo-gallery/delete_category', 'PhotoGallery@delete_category');
                
                $router->post('/photo-gallery/category_insert_update', 'PhotoGallery@category_insert_update');
                
                $router->match('GET', '/photo-gallery/create', 'PhotoGallery@create');
                $router->match('POST', '/photo-gallery/store', 'PhotoGallery@store');
                $router->match('GET', '/photo-gallery/edit/:id', 'PhotoGallery@edit');
                $router->match('POST', '/photo-gallery/update/:id', 'PhotoGallery@update');

                $router->get('/photo-gallery/category', 'PhotoGallery@category');

                // slider	
                $router->get('/slider', 'slider@index');
                $router->post('/slider/delete', 'slider@delete');
                $router->match('GET|POST', '/slider/insert', 'slider@insert');
                $router->match('GET|POST', '/slider/update/:id', 'slider@update', array('id'));
                $router->post('/slider/order', 'slider@order');

                // testimonials	
                $router->get('/testimonials', 'testimonials@index');
                $router->match('GET|POST', '/testimonials/insert', 'testimonials@insert');
                $router->match('GET|POST', '/testimonials/update/:id', 'testimonials@update', array('id'));
                $router->get('/testimonials/delete/:id', 'testimonials@delete', array('id'));

                // clients	
                $router->get('/clients', 'client@index');
                $router->post('/clients/client_img_upload/(upload)', 'client@client_img_upload', array('upload'));
                $router->post('/clients/client_img_upload/(crop)', 'client@client_img_upload', array('crop'));
                $router->post('/clients/delete', 'client@delete');
                $router->match('GET|POST', '/clients/insert', 'client@insert');
                $router->match('GET|POST', '/clients/update/:id', 'client@update', array('id'));
                $router->post('/clients/order', 'client@order');

                // file manager	
                $router->get('/file_manager', 'FileManager@index');

                // settings	
                $router->match('GET|POST', '/settings', 'settings@index');

                // user manual	
                $router->get('/user-manual', 'UserManual@index');

                // translations	
                $router->get('/translations', 'translations@index');
                $router->post('/translations/save', 'translations@save');
                $router->match('GET|POST', '/translations/insert', 'translations@insert');

                // newsletter	
                $router->get('/newsletter', 'newsletter@index');
                $router->get('/newsletter/newsletter_stats', 'newsletter@newsletter_stats');
                $router->post('/newsletter/delete', 'newsletter@delete');
                $router->match('GET|POST', '/newsletter/insert', 'newsletter@insert');
                $router->match('GET|POST', '/newsletter/update/:id', 'newsletter@update', array('id'));

                // Címlék (terms)
                $router->get('/terms', 'Terms@index');
                $router->post('/terms/insert_update', 'Terms@insert_update');
                $router->post('/terms/delete', 'Terms@delete');


                // blog	
                $router->get('/blog', 'blog@index');
                $router->get('/blog/create', 'blog@create');
                $router->post('/blog/store', 'blog@store');
                $router->get('/blog/edit/:id', 'blog@edit');
                $router->post('/blog/update/:id', 'blog@update');
                $router->post('/blog/delete', 'blog@delete');
                $router->post('/blog/change_status', 'blog@change_status');
                // blog kategóriák
                $router->get('/blog/category', 'blog@category');
                $router->post('/blog/category_insert_update', 'blog@category_insert_update');
                $router->post('/blog/category_delete', 'blog@category_delete');

                // GYIK 
                $router->get('/gyik', 'gyik@index');
                $router->get('/gyik/create', 'gyik@create');
                $router->post('/gyik/store', 'gyik@store');
                $router->get('/gyik/edit/:id', 'gyik@edit');
                $router->post('/gyik/update/:id', 'gyik@update');
                $router->post('/gyik/delete', 'gyik@delete');
                $router->post('/gyik/change_status', 'gyik@change_status');
                // GYIK kategóriák
                $router->get('/gyik/category', 'gyik@category');
                $router->post('/gyik/category_insert_update', 'gyik@category_insert_update');
                $router->post('/gyik/category_delete', 'gyik@category_delete');

                //documents
                $router->get('/documents', 'documents@index');
                $router->match('GET|POST', '/documents/insert', 'documents@insert');
                $router->match('GET|POST', '/documents/update/:id', 'documents@update', array('id'));
                $router->post('/documents/delete_document_AJAX', 'documents@delete_document_AJAX');
                $router->post('/documents/insert_update_data_ajax', 'documents@insert_update_data_ajax');
                $router->get('/documents/category', 'documents@category');
                $router->post('/documents/category_insert_update', 'documents@category_insert_update');
                $router->post('/documents/category_delete', 'documents@category_delete');
                $router->post('/documents/show_file_list', 'documents@show_file_list');
                $router->post('/documents/doc_upload_ajax', 'documents@doc_upload_ajax');
                $router->post('/documents/file_delete', 'documents@file_delete');
                $router->get('/documents/download/:filename', 'documents@download', array('file'));

                // error	
                $router->set404('error@index');
            });
        }

        // dispatcher objektum példányosítása
        $dispatcher = new \System\Libs\Dispatcher();
        // controller névtérének beállítása
        $dispatcher->setControllerNamespace('System\\' . ucfirst(AREA) . '\Controller\\');

        // before útvonalak bejárása, a megadott elemek futtatása
        $before_callbacks = $router->runBefore();
        $dispatcher->dispatch($before_callbacks);

        // útvonalak bejárása, controller példányosítása, action indítása
        $callback = $router->run();
        $dispatcher->dispatch($callback);
    }

}

// osztály vége
?>