<?php 
namespace System\Admin\Controller;
use System\Core\AdminController;
use System\Core\View;
use System\Libs\DI;
use System\Libs\Auth;
use System\Libs\Config;
use System\Libs\Message;

class Content extends AdminController {

	function __construct()
	{
		parent::__construct();
		$this->loadModel('content_model');

	}

	/**
	 * Tartalmi elemek listája
	 */
	public function index()
	{
		$data['title'] = 'Egyéb tartalom oldal';
		$data['description'] = 'Egyéb tartalom oldal description';
		$data['all_content'] = $this->content_model->findContent(null, LANG);
//var_dump($data);die;		
		$view = new View();
		$view->add_link('js', ADMIN_JS . 'pages/content.js');
		$view->render('content/tpl_content', $data);
	}
	

	/**
	 * Tartalmi elem hozzáadása oldal
	 */
	public function create()
	{
		$data['title'] = 'Egyéb tartalom hozzáadása oldal';
		$data['description'] = 'Egyéb tartalom oldal hozzáadása description';

		$view = new View();
		$view->add_links(array('ckeditor'));
		$view->add_link('js', ADMIN_JS . 'pages/content.js');
		$view->render('content/tpl_content_create', $data);
	}

	/**
	 * Tartalmi elem létrehozása az adatbázisban
	 */
	public function store()
	{
		if($this->request->is_post()) {

			$data['name'] = $this->request->get_post('name');
			$data['title'] = $this->request->get_post('title');
        	// insert a content táblába
			$last_insert_id = $this->content_model->insert($data);

			if ($last_insert_id !== false) {
			// insert a content_translation táblába
				$translation_data['content_id'] = (int)$last_insert_id;

				$langcodes = Config::get('allowed_languages');
				foreach ($langcodes as $lang) {
					$translation_data['language_code'] = $lang;
					$translation_data['body'] = $this->request->get_post('body_' . $lang, 'strip_danger_tags');
					$this->content_model->insertContentTranslation($translation_data);
				}					

	            Message::set('success', 'Tartalmi elem hozzáadva.');
				$this->response->redirect('admin/content');

			} else {
	            Message::set('error', 'unknown_error');
				$this->response->redirect('admin/content/create');
			}
		}		
	}

	/**
	 *	Tartalmi elem módosítása oldal
	 */
	public function edit($id)
	{
		//Auth::hasAccess('content.edit', $this->request->get_httpreferer());	

		$id = (int)$id;

		$data['title'] = 'Tartalom szerkesztése';
		$data['description'] = 'Tartalom szerkesztése description';
		// visszadja a szerkesztendő oldal adatait egy tömbben (page_id, page_title ... stb.)
		$content = $this->content_model->findContent($id);
		$content = DI::get('arr_helper')->convertMultilanguage($content, array('body'));
		$data['content'] = $content[0];

 // var_dump($data);die;		
		$view = new View();
		$view->add_links(array('bootbox', 'ckeditor'));
		$view->add_link('js', ADMIN_JS . 'pages/content_edit.js');
		$view->render('content/tpl_edit_content', $data);
	}

	/**
	 * Tartalmi elem módosítása az adatbázisban
	 */
	public function update($id)
	{
		if($this->request->is_post()) {
			
			$id = (int)$id;
			$data['title'] = $this->request->get_post('title');
			// title update a content táblában
			$this->content_model->update($id, $data);

			$langcodes = Config::get('allowed_languages');
			foreach ($langcodes as $lang) {
					
				$translation_data['body'] = $this->request->get_post('body_' . $lang, 'strip_danger_tags');
					
				// új nyelv hozzáadása esetén meg kell nézni, hogy van-e már $lang nyelvi kódú elem ehhez az id-hez,
				// mert ha nincs, akkor nem is fogja tudni update-elni, ezért update helyett insert kell					
				if (!$this->content_model->checkLangVersion($id, $lang)) {
					$translation_data['content_id'] = $id; 
					$translation_data['language_code'] = $lang; 
					$this->content_model->insertContentTranslation($translation_data);

				}
				// ha már van ilyen nyelvi kódú elem
				else {
					$this->content_model->updateContentTranslation($id, $lang, $translation_data);
				}

			}
			
            Message::set('success', 'page_update_success');
			$this->response->redirect('admin/content');
		}

	}

}
?>