<?php
class Languages extends Controller {

	function __construct()
	{
		parent::__construct();
        Auth::handleLogin();
		$this->loadModel('languages_model');
	}

	public function index()
	{
/*		Auth::handleLogin();

		if (!Acl::create()->userHasAccess('home_menu')) {
		exit('nincs hozzáférése');
		}

*/
		// adatok bevitele a view objektumba
		$this->view->title = 'Nyelvek oldal';
		$this->view->description = 'Nyelvek oldal description';
		
		$this->view->css_link[] = $this->make_link('css', ADMIN_ASSETS, 'plugins/bootstrap-editable/bootstrap-editable/css/bootstrap-editable.css');
		
		$this->view->js_link[] = $this->make_link('js', ADMIN_ASSETS, 'plugins/bootstrap-editable/bootstrap-editable/js/bootstrap-editable.js');

		$this->view->js_link[] = $this->make_link('js', ADMIN_JS, 'pages/languages.js');
		
		$this->view->languages = $this->languages_model->get_language_data();
		
		$this->view->render('languages/tpl_languages');
	}
	
	/**
	 * A sliderek sorrendjének módosításakor meghívott action (slider/order)
	 *
	 * Megvizsgálja, hogy a kérés xmlhttprequest volt-e (Ajax), ha igen meghívja a slider_order() metódust 
	 *
	 * @return void
	 */
	public function save()
	{
		if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
		if (isset($_POST["name"])) {
				$this->languages_model->save_language_text();
			}
		}
		
		
	}
}
?>