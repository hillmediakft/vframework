<?php
namespace System\Core;

use System\Libs\DI;
use System\Libs\Message;
use System\Libs\Auth;

class Application {
	
	protected $request;
	
	public function __construct() 
	{
		// request objektum visszaadása (itt már létrejön az uri és router objektum is!)
		$this->request = DI::get('request');

		// area állandó létrehozása
		define('AREA', $this->request->get_uri('area'));

		// Betöltjük az aktuális nyelvnek megfelelő üzenet fájlt
		Message::init('messages_' . AREA, $this->request->get_uri('langcode'));
        
        // Megadjuk az Auth osztály alapbeállításait ('auth.php' config file betöltése)
		Auth::init('auth');

		// route-ok megadása, controller file betöltése és a megfelelő action behívása
		$this->_loadController();
	}


	private function _loadController()
	{
		$router = DI::get('router');

		/* **************************************************** */
		/* *************** SITE ******************************* */
		/* **************************************************** */
		if (AREA == 'site') {

			$router->get('/', 'home@index');	

			$router->set404('error@index');		






		}
		/* **************************************************** */
		/* ****************** ADMIN *************************** */
		/* **************************************************** */
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
				$router->match('GET|POST','/pages/update/:id', 'pages@update', array('id'));	
			
			// content	
				$router->get('/content', 'content@index');	
				$router->match('GET|POST', '/content/edit/:id', 'content@edit', array('id'));	
			
			// user	
				$router->get('/user', 'user@index');
				$router->match('GET|POST', '/user/insert', 'user@insert');
				$router->match('GET|POST', '/user/profile/:id', 'user@profile', array('id'));
				$router->post('/user/delete', 'user@delete');
				$router->post('/user/change_status', 'user@change_status');
				$router->post('/user/user_img_upload/(upload)', 'user@user_img_upload', array('upload'));
				$router->post('/user/user_img_upload/(crop)', 'user@user_img_upload', array('crop'));
				$router->match('GET|POST', '/user/user_roles', 'user@user_roles');
				$router->match('GET|POST', '/user/edit_roles/:id', 'user@edit_roles', array('id'));
			
			// photo gallery	
				$router->get('/photo-gallery', 'photo_gallery@index');
				$router->post('/photo-gallery/delete_photo', 'photo_gallery@delete_photo');
				$router->post('/photo-gallery/delete_category', 'photo_gallery@delete_category');
				$router->match('GET|POST', '/photo-gallery/insert', 'photo_gallery@insert');
				$router->match('GET|POST', '/photo-gallery/update/:id', 'photo_gallery@update', array('id'));
				$router->get('/photo-gallery/category', 'photo_gallery@category');

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
				$router->get('/clients', 'clients@index');
				$router->post('/clients/client_img_upload/(upload)', 'clients@client_img_upload', array('upload'));
				$router->post('/clients/client_img_upload/(crop)', 'clients@client_img_upload', array('crop'));
				$router->post('/clients/delete', 'clients@delete');
				$router->match('GET|POST', '/clients/insert', 'clients@insert');
				$router->match('GET|POST', '/clients/update/:id', 'clients@update', array('id'));
				$router->post('/clients/order', 'clients@order');

			// file manager	
				$router->get('/file_manager', 'file_manager@index');

			// settings	
				$router->match('GET|POST', '/settings', 'settings@index');

			// user manual	
				$router->get('/user-manual', 'user_manual@index');
				
			// languages	
				$router->get('/languages', 'languages@index');
				$router->post('/languages/save', 'languages@save');

			// newsletter	
				$router->get('/newsletter', 'newsletter@index');
				$router->get('/newsletter/newsletter_stats', 'newsletter@newsletter_stats');
				$router->post('/newsletter/delete', 'newsletter@delete');
				$router->match('GET|POST', '/newsletter/insert', 'newsletter@insert');
				$router->match('GET|POST', '/newsletter/update/:id', 'newsletter@update', array('id'));

			// blog	
				$router->get('/blog', 'blog@index');
				$router->post('/blog/delete', 'blog@delete');
				$router->match('GET|POST', '/blog/insert', 'blog@insert');
				$router->match('GET|POST', '/blog/update/:id', 'blog@update', array('id'));
				$router->get('/blog/category', 'blog@category');
				$router->post('/blog/category_insert_update', 'blog@category_insert_update');
				$router->post('/blog/category_delete', 'blog@category_delete');

			// error	
				$router->set404('error@index');	

			});

		}


		$dispatcher = new \System\Libs\Dispatcher();
		$dispatcher->setControllerNamespace('System\\' . ucfirst(AREA) . '\Controller\\');

		// before útvonalak bejárása, a megadott elemek futtatása
		$before_callbacks = $router->runBefore();
		$dispatcher->dispatch($before_callbacks);

		// útvonalak bejárása, controller példányosítása, action indítása
		$callback = $router->run();

		//Auth::hasAccess( $callback[0]['controller'] . '.' .  $callback[0]['action'], $this->request->get_httpreferer() );

		$dispatcher->dispatch($callback);

	}

} // osztály vége
?>