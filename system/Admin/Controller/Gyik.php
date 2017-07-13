<?php
namespace System\Admin\Controller;
use System\Core\AdminController;
use System\Core\View;
use System\Libs\DI;
use System\Libs\Auth;
use System\Libs\Message;
use System\Libs\Config;
use System\Libs\EventManager;

class Gyik extends AdminController {

	private $content_type_id;	

	function __construct()
	{
		parent::__construct();

		$this->content_type_id = Config::get('content_types.gyik');

		$this->loadModel('gyik_model');
		$this->loadModel('gyik_translation_model');
		$this->loadModel('gyikcategory_model');
	}
    
	public function index()
	{
		Auth::hasAccess('gyik.index', $this->request->get_httpreferer());

		$data['title'] = 'Admin gyik oldal';
		$data['description'] = 'Admin gyik oldal description';	
		$data['all_gyik'] = $this->gyik_model->findGyik(null, LANG);

		$view = new View();
		$view->add_links(array('datatable', 'bootbox', 'vframework'));
		$view->add_link('js', ADMIN_JS . 'pages/gyik.js');
		$view->render('gyik/tpl_gyik', $data);
	}
    
    /**
     * Gyik bejegyzés hozzáadása
     */
    public function create()
	{
		Auth::hasAccess('gyik.create', $this->request->get_httpreferer());

		$data['title'] = 'Admin gyik oldal';
		$data['description'] = 'Admin gyik oldal description';
		$data['category_list'] = $this->gyikcategory_model->findCategory(null, LANG);

		// címkék lekérdezése
		$this->loadModel('terms_model');
		$data['terms'] = $this->terms_model->findTerms(null, LANG);

		$view = new View();
		$view->add_links(array('bootbox', 'ckeditor', 'validation', 'select2'));
		$view->add_link('js', ADMIN_JS . 'pages/gyik_create.js');
		$view->render('gyik/tpl_gyik_create', $data);
	}

	/**
	 * Új gyik adatok az adatbázisba (feldolgozó)
	 */
	public function store()
	{
		if( $this->request->is_post() ){
			// ha nincs kategória kiválsztva, az érték null lesz (a template-ben üres string kell ha nincs kategória)
			$data['category_id'] = $this->request->get_post('gyik_category', 'integer');
			// status
			$data['status'] = $this->request->get_post('status', 'integer');
			// létrehozás ideje
			$data['create_timestamp'] = time();
			// insert a gyik táblába
			$last_insert_id = $this->gyik_model->insert($data);

			if($last_insert_id !== false) {

			// insert a gyik_translation táblába
				$translation_data['gyik_id'] = (int)$last_insert_id;

				$langcodes = Config::get('allowed_languages');
				foreach ($langcodes as $lang) {
					$translation_data['language_code'] = $lang;
					$translation_data['title'] = $this->request->get_post('title_' . $lang);
					$translation_data['description'] = $this->request->get_post('description_' . $lang, 'strip_danger_tags');
					$this->gyik_translation_model->insert($translation_data);
				}

// taxonomy
$terms = ($this->request->has_post('tags')) ? $this->request->get_post('tags') : array();
if (!empty($terms)) {
	EventManager::trigger('insert_taxonomy', array($last_insert_id, $terms, $this->content_type_id));
}

				Message::set('success', 'GYIK hozzáadva!');
				$this->response->redirect('admin/gyik');
			} else {
				Message::set('error' , 'unknown_error');
				$this->response->redirect('admin/gyik/create');
			}
		}		
	}

	/**
	 * Gyik adatok módosítása oldal
	 */
	public function edit($id)
	{
		Auth::hasAccess('gyik.edit', $this->request->get_httpreferer());
		
		$id = (int)$id;

		$data['title'] = 'Admin gyik oldal';
		$data['description'] = 'Admin gyik oldal description';
		$data['category_list'] = $this->gyikcategory_model->findCategory(null, LANG);

		$gyik = $this->gyik_model->findGyik($id);
		// átalakítjuk a kapott két nyelvi tömböt egy tömbre amiben benne van minden nyelv
		$gyik = DI::get('arr_helper')->convertMultilanguage($gyik, array('title', 'description', 'category_name'));
		$data['gyik'] = $gyik[0];

		// Címkék
        $this->loadModel('terms_model');
		$data['terms'] = $this->terms_model->findTerms(null, LANG);
        $this->loadModel('taxonomy_model');
        $data['terms_by_content_id'] = DI::get('arr_helper')->convertArrayToOneDimensional($this->taxonomy_model->getTermsByContentId($id));

		$view = new View();
		$view->add_links(array('bootbox', 'ckeditor', 'validation', 'select2'));
		$view->add_link('js', ADMIN_JS . 'pages/gyik_edit.js');
		$view->render('gyik/tpl_gyik_edit', $data);		
	}

    
    /**
     * Gyik bejegyzés módosítása
     */
	public function update($id)
	{
//var_dump($this->request->get_post());die;

		if( $this->request->is_post() ){

			$id = (int)$id;

		// az adatbázisba kerülő adatok
			$data['status'] = $this->request->get_post('status', 'integer');
			$data['category_id'] = $this->request->get_post('gyik_category', 'integer');
			$data['update_timestamp'] = time();

		// update a gyik táblában	
			$result = $this->gyik_model->update($id, $data);
		
			if($result !== false) {

			// update a gyik_translation táblában
				$langcodes = Config::get('allowed_languages');
				foreach ($langcodes as $lang) {
					
					$translation_data['title'] = $this->request->get_post('title_' . $lang);
					$translation_data['description'] = $this->request->get_post('description_' . $lang, 'strip_danger_tags');
					
					// új nyelv hozzáadása esetén meg kell nézni, hogy van-e már $lang nyelvi kódú elem ehhez az id-hez,
					// mert ha nincs, akkor nem is fogja tudni update-elni, ezért update helyett insert kell					
					if (!$this->gyik_translation_model->checkLangVersion($id, $lang)) {
						$translation_data['gyik_id'] = $id; 
						$translation_data['language_code'] = $lang; 
						$this->gyik_translation_model->insert($translation_data);
					}
					// ha már van ilyen nyelvi kódú elem
					else {
						$this->gyik_translation_model->update($id, $lang, $translation_data);
					}

				}

// taxonomy
$terms = ($this->request->has_post('tags')) ? $this->request->get_post('tags') : array();
EventManager::trigger('update_taxonomy', array($id, $terms, $this->content_type_id));


				Message::set('success', 'Bejegyzés módosítása sikerült!');
				$this->response->redirect('admin/gyik');
			} else {
				Message::set('error', 'unknown_error');
				$this->response->redirect('admin/gyik/edit/' . $id);
			}	

		}

	}  

	/**
	 *	Gyik törlése AJAX-al
	 */
	public function delete()
	{
        if($this->request->is_ajax()){

	        if(!Auth::hasAccess('gyik.delete')){
	            $this->response->json(array(
	            	'status' => 'error',
	            	'message' => 'Nincs engedélye a művelet végrehajtásához!'
	            ));
			}	            

        	// a POST-ban kapott item_id egy tömb
        	$id_arr = $this->request->get_post('item_id');
			// a sikeres törlések számát tárolja
			$success_counter = 0;
			// törölt elemek id-it tárolja
			$deleted_record_id = array();

			// bejárjuk a $id_arr tömböt és minden elemen végrehajtjuk a törlést
			foreach($id_arr as $id) {
				//átalakítjuk a integer-ré a kapott adatot
				$id = (int)$id;
				//gyik törlése	
				$result = $this->gyik_model->delete($id);
				// ha a törlési sql parancsban nincs hiba
				if($result !== false) {
					//sikeres törlés
					$success_counter += $result;
					$deleted_record_id[] = $id;
				}
				else {
					// ha a törlési sql parancsban hiba van
	                $this->response->json(array(
	                    'status' => 'error',
	                    'message_error' => 'Adatbázis lekérdezési hiba!'
		            ));
				}
			}

// taxonomy törlés
if (!empty($deleted_record_id)) {
    EventManager::trigger('delete_taxonomy', array($deleted_record_id, $this->content_type_id));
}

            $this->response->json(array(
                'status' => 'success',
                'message' => $success_counter . ' Gyik törölve.'
            ));

        }
	}

	/**
	 * Gyik kategóriák 
	 */
	public function category()
	{
		$view = new View();

		$data['title'] = 'Admin gyik oldal';
		$data['description'] = 'Admin gyik oldal description';	
		$data['category_counter'] = $this->gyik_model->categoryCounter();
		// minden kategória, minden nyelven
		$data['all_gyik_category'] = $this->gyikcategory_model->findCategory();
		$data['all_gyik_category'] = DI::get('arr_helper')->convertMultilanguage($data['all_gyik_category'], array('category_name'), 'id', 'language_code');

		$view->add_links(array('datatable', 'bootbox', 'vframework'));
		$view->add_link('js', ADMIN_JS . 'pages/gyik_category.js');
		$view->render('gyik/tpl_gyik_category', $data);	
	}

	/**
	 * Kategória hozzáadása és módosítása (AJAX)
	 */
	public function category_insert_update()
	{
		if ($this->request->is_ajax()) {

			$this->loadModel('gyikcategory_translation_model');

			// az id értéke lehet null is!
			$id = $this->request->get_post('id', 'integer');
			
			// neveket tartalmazó asszociatív tömb (hu => kategória név, en => category name)
			$new_names = $this->request->get_post('data');

			$primary_name = $new_names[LANG];
			if ($primary_name === '') {
				$this->response->json(array(
					'status' => 'error',
					'message' => 'Nem lehet üres a kategória név mező!'
				));
			}

		// kategóriák lekérdezése (annak ellenőrzéséhez, hogy már létezik-e ilyen kategória)
		// csak a "primary" nyelvnél nézi	
			$existing_categorys = $this->gyikcategory_model->findCategory(null, LANG);

			// bejárjuk a kategória neveket és összehasonlítjuk az új névvel (kisbetűssé alakítjuk, hogy ne számítson a nagybetű-kisbetű eltérés)
			foreach($existing_categorys as $value) {
				
				if (
					// insert eset  
					(is_null($id) && strtolower($primary_name) == strtolower($value['category_name'])) ||
					// update eset
					(!is_null($id) && $id != $value['id'] && strtolower($primary_name) == strtolower($value['category_name']))
				) {
					$this->response->json(array(
						'status' => 'error',
						'message' => 'Már létezik ' . $value['category_name'] . ' kategória!'
					));
				}	
			} 

		//insert (ha az $id értéke null)
			if (is_null($id)) {

				// kategória létrehozása a gyik_category táblába
				$last_insert_id = $this->gyikcategory_model->insertCategory();
								
				if ($last_insert_id !== false) {
					// kategória nevek beírása a gyik_category_translation táblába
					foreach ($new_names as $langcode => $name) {
						$this->gyikcategory_translation_model->insert($last_insert_id, $langcode, $name);
					}
					
					$this->response->json(array(
						'status' => 'success',
						'message' => 'Kategória hozzáadva.',
						'inserted_id' => $last_insert_id
					));
				} else { 
					$this->response->json(array(
						'status' => 'error',
						'message' => 'Adatbázis lekérdezési hiba!'
					));
				}
			}
		// update
			else {
				// kategória nevek beírása a gyik_category_translation táblába
				foreach ($new_names as $langcode => $name) {
					$this->gyikcategory_translation_model->update($id, $langcode, $name);
				}

				$this->response->json(array(
					'status' => 'success',
					'message' => 'Kategória módosítva.'
				));

			}
		}
	}

	/**
	 *	Kategória törlése (AJAX)
	 */
	public function category_delete()
	{
        if($this->request->is_ajax()){

	        if(!Auth::hasAccess('gyik.category_delete')){
	            $this->response->json(array(
	            	'status' => 'error',
	            	'message' => 'Nincs engedélye a művelet végrehajtásához!'
	            	));
	        }
	        	
        	$id = $this->request->get_post('item_id', 'integer');

				// ha az van beállítva, hogy kategória törlésnél az összes hozzá tartozó elemet is töröljük
				$delete_category_delete_items = false;
				if ($delete_category_delete_items) {
					// gyik bejegyzések törlése, amik a törlendő kategóriához tartoznak
					$result = $this->gyik_model->deleteWhereCategory($id);
				}

			// kategória törlése a gyik_category táblából
			// (ezzel együtt törlődnek a gyik_translation táblából is az ehhez a kategóriához tartozó translations elemek)
			$result = $this->gyikcategory_model->deleteCategory($id);
			
			if($result !== false) {
                $this->response->json(array(
                    'status' => 'success',
                    'message' => 'Kategória törölve.'
                    ));
			}
			else {
				// ha a törlési sql parancsban hiba van
                $this->response->json(array(
                    'status' => 'error',
                    'message' => 'Adatbázis lekérdezési hiba!',                  
                	));
			}

        } else {
        	$this->response->redirect('admin/error');
        }
	}	

    /**
     * (AJAX) A gyik táblában módosítja a status mező értékét
     *
     * @return void
     */
    public function change_status()
    {
        if ( $this->request->is_ajax() ) {
        	// jogosultság vizsgálat
        	if (!Auth::hasAccess('gyik.change_status')) {
				$this->response->json(array(
					"status" => 'error',
					"message" => 'Nincs engedélye a művelet végrehajtásához.'
				));			
			}        		
        	
            if ( $this->request->has_post('action') && $this->request->has_post('id') ) {
			
				$id = $this->request->get_post('id', 'integer');
				$action = $this->request->get_post('action');

				if($action == 'make_active') {
					$result = $this->gyik_model->changeStatus($id, 1);
					if($result !== false){
						$this->response->json(array(
							"status" => 'success',
							"message" => 'Az aktiválás megtörtént!'
						)); 	
					} else {
						$this->response->json(array(
							"status" => 'error',
							"message" => 'Adatbázis hiba! A gyik státusza nem változott meg!'
						));
					}
				}
				if($action == 'make_inactive') {
					$result = $this->gyik_model->changeStatus($id, 0);
					if($result !== false){
						$this->response->json(array(
							"status" => 'success',
							"message" => 'A blokkolás megtörtént!'
						)); 	
					} else {
						$this->response->json(array(
							"status" => 'error',
							"message" => 'Adatbázis hiba! A státusz nem változott meg!'
						));
					}
					
				}
			} else {
				$this->response->json(array(
					"status" => 'error',
					"message" => 'unknown_error'
				));
			}

		} else {
			$this->response->redirect('admin/error');
		}
    }	


}
?>