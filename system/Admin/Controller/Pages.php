<?php
namespace System\Admin\Controller;
use System\Core\AdminController;
use System\Core\View;
use System\Libs\Auth;
use System\Libs\Config;
use System\Libs\DI;
use System\Libs\Message;

class Pages extends AdminController {

	function __construct()
	{
		parent::__construct();
		$this->loadModel('pages_model');
	}

	public function index()
	{
		Auth::hasAccess('pages.index', $this->request->get_httpreferer());

		$data['title'] = 'Admin pages oldal';
		$data['description'] = 'Admin pages oldal description';
		$data['all_pages'] = $this->pages_model->findPage(null, LANG);

		$view = new View();
		$view->add_links(array('vframework'));
		$view->add_link('js', ADMIN_JS . 'pages/pages.js');
		$view->render('pages/tpl_pages', $data);
	}
	

	/**
	 *	Oldal hozzáadása
	 */
	public function insert()
	{
			if($this->request->is_post()) {

				$data['title'] = $this->request->get_post('title');
            	$data['body_edit'] = $this->request->has_post('body_edit') ? $this->request->get_post('body_edit', 'integer') : 0;
				$data['friendlyurl'] = $this->request->get_post('friendlyurl');
            	// insert a pages táblába
				$last_insert_id = $this->pages_model->insert($data);

				if ($last_insert_id !== false) {
				// insert a pages_translation táblába
					$translation_data['page_id'] = (int)$last_insert_id;

					$langcodes = Config::get('allowed_languages');
					foreach ($langcodes as $lang) {
						$translation_data['language_code'] = $lang;
						$translation_data['metatitle'] = $this->request->get_post('metatitle_' . $lang);
						$translation_data['metadescription'] = $this->request->get_post('metadescription_' . $lang);
						$translation_data['metakeywords'] = $this->request->get_post('metakeywords_' . $lang);
						$this->pages_model->insertContent($translation_data);
					}					

		            Message::set('success', 'Oldal hozzáadva.');
					$this->response->redirect('admin/pages');

				} else {
		            Message::set('error', 'unknown_error');
					$this->response->redirect('admin/pages/insert');
				}
			}	
		
		$data['title'] = 'Oldal hozzáadása';
		$data['description'] = 'Oldal hozzáadása description';
		
		$view = new View();
		$view->render('pages/tpl_page_insert', $data);
	}


	/**
	 *	Oldal adatainak módosítása
	 */
	public function update($id)
	{
		Auth::hasAccess('pages.update', $this->request->get_httpreferer());

		$id = (int) $id;

			if($this->request->is_post()) {
				
				$langcodes = Config::get('allowed_languages');
				foreach ($langcodes as $lang) {
					if ($this->request->has_post('body_' . $lang)) {
						$translation_data['body'] = $this->request->get_post('body_' . $lang, 'strip_danger_tags');
					}
					$translation_data['metatitle'] = $this->request->get_post('metatitle_' . $lang);
					$translation_data['metadescription'] = $this->request->get_post('metadescription_' . $lang);
					$translation_data['metakeywords'] = $this->request->get_post('metakeywords_' . $lang);
					
					// új nyelv hozzáadása esetén meg kell nézni, hogy van-e már $lang nyelvi kódú elem ehhez az id-hez,
					// mert ha nincs, akkor nem is fogja tudni update-elni, ezért update helyett insert kell					
					if (!$this->pages_model->_checkLangVersion('pages_translation', 'page_id', $id, $lang)) {
						$translation_data['page_id'] = $id;
						$translation_data['language_code'] = $lang;
						$this->pages_model->insertContent($translation_data);
					} else {
						$this->pages_model->updateContent($id, $lang, $translation_data);
					}

				}
				
	            Message::set('success', 'page_update_success');
				$this->response->redirect('admin/pages');
			}	
		
		$view = new View();
		
		$data['title'] = 'Oldal szerkesztése';
		$data['description'] = 'Oldal szerkesztése description';
		$page = $this->pages_model->findPage($id);
		$page = DI::get('arr_helper')->convertMultilanguage($page, array('body', 'metatitle' , 'metadescription', 'metakeywords'), 'id', 'language_code');
		$data['page'] = $page[0];

		$view->add_links(array('bootbox', 'ckeditor', 'vframework'));
		$view->add_link('js', ADMIN_JS . 'pages/page_update.js');
		$view->render('pages/tpl_page_update', $data);
	}

}
?>