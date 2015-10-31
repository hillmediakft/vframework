<?php
class Slider extends Controller {

	function __construct()
	{
		parent::__construct();
        Auth::handleLogin();
		$this->loadModel('slider_model');
	}

	public function index()
	{
/*		Auth::handleLogin();

		if (!Acl::create()->userHasAccess('home_menu')) {
		exit('nincs hozzáférése');
		}

*/
		// adatok bevitele a view objektumba
		$this->view->title = 'Slider oldal';
		$this->view->description = 'Slider oldal description';
		
		$this->view->css_link[] = $this->make_link('css', ADMIN_ASSETS, 'plugins/jquery-ui/jquery-ui-1.10.3.custom.min.css');
		$this->view->js_link[] = $this->make_link('js', ADMIN_ASSETS, 'plugins/jquery-ui/jquery-ui-1.10.3.custom.min.js');
		$this->view->js_link[] = $this->make_link('js', ADMIN_ASSETS, 'plugins/bootbox/bootbox.min.js');
		$this->view->js_link[] = $this->make_link('js', ADMIN_JS, 'pages/slider.js');
		
		$this->view->slider = $this->slider_model->all_slides_query();	
		
		$this->view->render('slider/tpl_slider');
	}
	
	/**
	 * Új slide hozzáadása
	 *
	 * @return void
	 */
	public function new_slide()
	{
		if(isset($_POST['submit_new_slide'])) {
			
			$result = $this->slider_model->add_slide();
			if($result){
				Util::redirect('slider');
			} else {
				Util::redirect('slider/new_slide');
			}

		}
			
		// adatok bevitele a view objektumba
		$this->view->title = 'Új slide oldal';
		$this->view->description = 'Új slide oldal description';

		$this->view->css_link[] = $this->make_link('css', ADMIN_ASSETS, 'plugins/bootstrap-fileupload/bootstrap-fileupload.css');
		$this->view->js_link[] = $this->make_link('js', ADMIN_ASSETS, 'plugins/bootstrap-fileupload/bootstrap-fileupload.js');
		$this->view->js_link[] = $this->make_link('js', ADMIN_JS, 'pages/new_slide.js');
		
		$this->view->render('slider/tpl_new_slide');	
	}
	
	/**
	 *	A slider módosítása (kép és szövegek cseréje)
	 *
	 *	@param Int $this->registry->params['id']
	 *	@return void
	 */
	public function edit()
	{
		if(!isset($this->registry->params['id'])){
			throw new Exception('Nincs "id" nevű eleme a $params tombnek! (lekerdezes nem hajthato vegre id alapjan)');
			return false;
		}
		$id = (int)$this->registry->params['id'];
		
		if(isset($_POST['submit_update_slide'])) {
			$result = $this->slider_model->update_slide($id);
			if($result){
				Util::redirect('slider');
			}
		}
			
		// adatok bevitele a view objektumba
		$this->view->title = 'Slider szerkesztése oldal';
		$this->view->description = 'Slider szerkesztése description';

		$this->view->css_link[] = $this->make_link('css', ADMIN_ASSETS, 'plugins/bootstrap-fileupload/bootstrap-fileupload.css');
		$this->view->js_link[] = $this->make_link('js', ADMIN_ASSETS, 'plugins/bootstrap-fileupload/bootstrap-fileupload.js');
		$this->view->js_link[] = $this->make_link('js', ADMIN_JS, 'pages/edit_slide.js');
		
		$this->view->slider = $this->slider_model->one_slide_query($id);	
		
		$this->view->render('slider/tpl_edit_slide');	
		
	}
	
	/**
	 *	Slide törlése
	 *
	 */
	public function delete()
	{
		if(!isset($this->registry->params['id'])){
			throw new Exception('Nincs "id" nevű eleme a $params tombnek! (a lekerdezes nem hajthato vegre)');
			return false;
		}
		
		$id = (int)$this->registry->params['id'];
		$this->slider_model->delete_slide($id);
		Util::redirect('slider');
	}
	
	
	/**
	 * A sliderek sorrendjének módosításakor meghívott action (slider/order)
	 *
	 * Megvizsgálja, hogy a kérés xmlhttprequest volt-e (Ajax), ha igen meghívja a slider_order() metódust 
	 *
	 * @return void
	 */
	public function order()
	{
		if (Util::is_ajax()) {
			if (isset($_POST["action"]) && $_POST["action"] == "update_slider_order") {
				$this->slider_model->slider_order();
			}
		}
	}
	
}
?>