<?php
namespace System\Admin\Controller;
use System\Core\AdminController;
use System\Core\View;
use System\Libs\DI;
use System\Libs\Auth;
use System\Libs\Config;
use System\Libs\Message;
use System\Libs\Uploader;

class PhotoGallery extends AdminController {

	private $photo_width;
	private $thumb_width;
	private $image_path;

	function __construct()
	{
		parent::__construct();
		$this->loadModel('photo_gallery_model');
		$this->loadModel('photocategory_model');

		$this->photo_width = Config::get('photogallery.width', 800);
		$this->thumb_width = Config::get('photogallery.thumb_width', 320);
		$this->image_path = Config::get('photogallery.upload_path');		
	}

	public function index()
	{
		$data['title'] = 'Fotó galériák oldal';
		$data['description'] = 'Fotó galériák oldal description';
		// kategóriák
		$data['categories'] = $this->photocategory_model->findCategory(null, LANG);
		// összes rekord a photo_gallery-ból	
		$data['all_photos'] = $this->photo_gallery_model->findPhoto(null, LANG);

		$view = new View();
		$view->add_link('css', ADMIN_CSS . 'pages/portfolio.css');
		$view->add_links(array('bootbox', 'mixitup', 'vframework', 'photo_gallery'));
		$view->setHelper(array('url_helper'));
		$view->render('photo_gallery/tpl_photo_gallery', $data);
	}
	
	/**
	 * Új fotó hozzáadása oldal
	 *
	 * @return void
	 */
	public function create()
	{
		//Auth::hasAccess('photo.insert', $this->request->get_httpreferer());

		$data['title'] = 'Új fotó oldal';
		$data['description'] = 'Új fotó oldal description';
		$data['categorys'] = $this->photocategory_model->findCategory(null, LANG);
//var_dump($data['categorys']);die;

		$view = new View();
		$view->setHelper(array('html_admin_helper'));
		$view->add_links(array('bootstrap-fileinput', 'vframework', 'photo_gallery_insert_update'));
		$view->render('photo_gallery/tpl_photo_insert', $data);	
	}
	
	/**
	 * Új kép tárolása
	 */
	public function store()
	{
		if(!$this->request->is_post()) {
			$this->response->redirect('admin/error');
		}


		// kép feltöltési hiba vizsgálata
		if($this->request->checkUploadError('upload_gallery_photo')){
			Message::set('error', $this->request->getFilesError('upload_gallery_photo'));
			$this->response->redirect('admin/photo-gallery/insert');				
		}

		// kép feltöltése
		if ($this->request->hasFiles('upload_gallery_photo')) {
			$filename = $this->_uploadImage($this->request->getFiles('upload_gallery_photo'));
			if ($filename === false) {
				$this->response->redirect('admin/photo-gallery/insert');
			}
		} else {
			Message::set('error', 'uploaded_missing');
			$this->response->redirect('admin/photo-gallery/insert');
		}

		// adatok a photo_gallery táblába
		$data['filename'] =  $filename;
		$data['category_id'] = $this->request->get_post('photo_category', 'integer');
		$data['slider'] = ($this->request->has_post('photo_slider')) ? $this->request->get_post('photo_slider', 'integer') : 0;
		
		// adatbázis lekérdezés
		$last_insert_id = $this->photo_gallery_model->insert($data);

		if($last_insert_id !== false) {
			
			// model metöltése
			$this->loadModel('photo_gallery_translation_model');

			// insert a photo_gallery_translation táblába
			$translation_data['photo_id'] = (int)$last_insert_id;

			$langcodes = Config::get('allowed_languages');
			foreach ($langcodes as $lang) {
				$translation_data['language_code'] = $lang;
				$translation_data['caption'] = $this->request->get_post('photo_caption_' . $lang);
				$this->photo_gallery_translation_model->insert($translation_data);
			}

            Message::set('success', 'new_photo_gallery_success');
			$this->response->redirect('admin/photo-gallery');
		} else {
            Message::set('error', 'unknown_error');
			$this->response->redirect('admin/photo-gallery/insert');
		}
	}

	/**
	 * Kép adatok módosítása oldal
	 */
	public function edit($id)
	{
		$id = (int)$id;

		$data['title'] = 'Fotó szerkesztése oldal';
		$data['description'] = 'Fotó szerkesztése description';
		$data['categories'] = $this->photocategory_model->findCategory(null, LANG);
		$photo = $this->photo_gallery_model->findPhoto($id);
		$photo = DI::get('arr_helper')->convertMultilanguage($photo, array('caption'), 'id', 'language_code');
		$data['photo'] = $photo[0];

		$view = new View();
		$view->setHelper(array('html_admin_helper'));
		$view->add_links(array('bootstrap-fileinput', 'vframework', 'photo_gallery_insert_update'));
		$view->render('photo_gallery/tpl_photo_update', $data);	
	}


	/**
	 * Kép adatainak módosítása
	 *
	 * @return void
	 */
	public function update($id)
	{
		$id = (int)$id;

		if (!$this->request->is_post()) {
			$this->response->redirect('admin/error');
		}


		// fájl feltöltési hiba ellenőrzése
		if($this->request->checkUploadError('upload_gallery_photo')){
			Message::set('error', $this->request->getFilesError('upload_gallery_photo'));
			$this->response->redirect('admin/photo-gallery/edit/' . $id);				
		}
		// kép feltöltése (ellenőrizzük, hogy van-e feltöltött kép)
		if($this->request->hasFiles('upload_gallery_photo')) {
			$filename = $this->_uploadImage($this->request->getFiles('upload_gallery_photo'));
			if($filename === false) {
				$this->response->redirect('admin/photo-gallery/edit/' . $id);
			}
		}


		// adatok a photo_gallery táblába
		if (isset($filename)) {
			$data['filename'] = $filename;
		}
		$data['category_id'] = $this->request->get_post('photo_category', 'integer');
		$data['slider'] = ($this->request->has_post('photo_slider')) ? $this->request->get_post('photo_slider', 'integer') : 0;
		
		// új adatok beírása a photo_gallery táblába 
		$result = $this->photo_gallery_model->update($id, $data);
				
		if($result !== false) {
			// model betöltése		
			$this->loadModel('photo_gallery_translation_model');	

		// update a photo_gallery_translation táblában
			$langcodes = Config::get('allowed_languages');
			foreach ($langcodes as $lang) {
				
				$translation_data['caption'] = $this->request->get_post('photo_caption_' . $lang);
				
				// új nyelv hozzáadása esetén meg kell nézni, hogy van-e már $lang nyelvi kódú elem ehhez az id-hez,
				// mert ha nincs, akkor nem is fogja tudni update-elni, ezért update helyett insert kell					
				if (!$this->photo_gallery_translation_model->checkLangVersion($id, $lang)) {
					$translation_data['photo_id'] = $id; 
					$translation_data['language_code'] = $lang; 
					$this->photo_gallery_translation_model->insert($translation_data);
				}
				// ha már van ilyen nyelvi kódú elem
				else {
					$this->photo_gallery_translation_model->update($id, $lang, $translation_data);
				}
			}

			// régi kép törlése
			if(isset($filename)){
				$old_img = $this->image_path . $this->request->get_post('old_photo');
				$old_img_thumb = DI::get('url_helper')->thumbPath($old_img);
				DI::get('file_helper')->delete(array($old_img, $old_img_thumb));
			}
            
            Message::set('success', 'photo_update_success');
		} else {
            Message::set('error', 'unknown_error');
		}

		$this->response->redirect('admin/photo-gallery');

	}
	
	/**
	 *	Photo törlése AJAX-al
	 */
	public function delete_photo()
	{
        if(!$this->request->is_ajax()){
        	$this->response->redirect('admin/error');
        }

       	if (!Auth::hasAccess('photogallery.delete_photo')) {
            $this->response->json(array(
            	'status' => 'error',
            	'message' => 'Nincs engedélye a művelet végrehajtásához!'
            ));
       	} 	


    	// a POST-ban kapott item_id egy tömb
    	$id_arr = $this->request->get_post('item_id');
		// a sikeres törlések számát tárolja
		$success_counter = 0;
	
        $file_helper = DI::get('file_helper');
        $url_helper = DI::get('url_helper');

		// bejárjuk a $id_arr tömböt és minden elemen végrehajtjuk a törlést
		foreach($id_arr as $id) {
			//átalakítjuk a integer-ré a kapott adatot
			$id = (int)$id;
			//lekérdezzük a törlendő kép nevét, hogy törölhessük a szerverről
			$photo_name = $this->photo_gallery_model->findFilename($id);			
			//rekord törlése	
			$result = $this->photo_gallery_model->delete($id);
			
			if($result !== false) {
				//ha van feltöltött kép (az adatbázisban szerepel a file-név)
				if(!empty($photo_name)){
					$picture_path = Config::get('photogallery.upload_path') . $photo_name;
					$thumb_picture_path = $url_helper->thumbPath($picture_path);
					$file_helper->delete(array($picture_path, $thumb_picture_path));
				}				
				//sikeres törlés
				$success_counter += $result;
			}
			else {
				// ha a törlési sql parancsban hiba van
                $this->response->json(array(
                    'status' => 'error',
                    'message_error' => 'Adatbázis lekérdezési hiba!',                  
                ));
			}
		}

        $this->response->json(array(
            'status' => 'success',
            'message' => $success_counter . ' kép törölve.'
        ));

	}

/********---------------------------------------*********/

	/**
	 * Kategóriák listája
	 */
	public function category()
	{
		$view = new View();

		$data['title'] = 'Admin fotó kategóriák oldal';
		$data['description'] = 'Admin fotó kategóriák oldal description';	
		$all_category = $this->photocategory_model->findCategory();
		$data['all_category'] = DI::get('arr_helper')->convertMultilanguage($all_category, array('category_name'), 'id', 'language_code');
		$data['category_counter'] = $this->photo_gallery_model->categoryCounter();
//var_dump($data);die;
		$view->add_links(array('bootbox', 'datatable', 'bootstrap-editable', 'vframework', 'photo_category'));
		$view->render('photo_gallery/tpl_photo_category', $data);	
	}

	/**
	 *	Kategória törlése AJAX-al
	 */
	public function delete_category()
	{
        if($this->request->is_ajax()){
	        if(1){
	        	// a POST-ban kapott user_id egy tömb
	        	$id = $this->request->get_post('item_id', 'integer');
				// a sikeres törlések számát tárolja
				$success_counter = 0;
				// a sikertelen törlések számát tárolja
				$fail_counter = 0; 

				// lekérdezzük a törlendő képek nevét
				$photo_names_temp = $this->photo_gallery_model->selectFilenameWhereCategory($id);			

				$photo_names = array();
				foreach ($photo_names_temp as $key => $value) {
					$photo_names[] = $value['photo_filename'];
				}
				unset($photo_names_temp);

				// képekhez tartozó rekordok törlése
				$result = $this->photo_gallery_model->deleteWhereCategory($id);

				// képek törlése
				if($result !== false) {
					if($result > 0){

						$file_helper = DI::get('file_helper');
						$url_helper = DI::get('url_helper');
						$upload_path = Config::get('photogallery.upload_path');

						foreach($photo_names as $value)
						{
							$picture_path = $upload_path . $value;
							$thumb_picture_path = $url_helper->thumbPath($picture_path);
							$file_helper->delete(array($picture_path, $thumb_picture_path));
						}				
					}
				}

				// kategória törlése
				$result = $this->photocategory_model->deleteCategory($id);
				
				if($result !== false) {
					// ha a törlési sql parancsban nincs hiba
					if($result > 0){
						$success_counter += $result;
					}
					else {
						//sikertelen törlés
						$fail_counter++;
					}
				}
				else {
					// ha a törlési sql parancsban hiba van
	                $this->response->json(array(
	                    'status' => 'error',
	                    'message_error' => 'Hibas sql parancs: nem sikerult a DELETE lekerdezes az adatbazisbol!',                  
	                ));
				}

		        // üzenetek visszaadása
		        $respond = array();
		        $respond['status'] = 'success';
		        
		        if ($success_counter > 0) {
		            $respond['message_success'] = 'Kategória törölve.';
		        }
		        if ($fail_counter > 0) {
		            $respond['message_error'] = 'A kategóriát már törölték!';
		        }

		        // respond tömb visszaadása
		   		$this->response->json($respond);

	        } else {
	            $this->response->json(array(
	            	'status' => 'error',
	            	'message' => 'Nincs engedélye a művelet végrehajtásához!'
	            ));
	        }
        }
	}

	/**
	 * Kategória hozzáadása és módosítása (AJAX)
	 */
	public function category_insert_update()
	{
		if ($this->request->is_ajax()) {

			$this->loadModel('PhotoCategory_translation_model');

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
			$existing_categorys = $this->photocategory_model->findCategory(null, LANG);
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

			//insert
			if (is_null($id)) {
				
				// kategória létrehozása a photo_category táblába
				$last_insert_id = $this->photocategory_model->insertCategory();
								
				if ($last_insert_id !== false) {
					// kategória nevek beírása a photo_category_translation táblába
					foreach ($new_names as $langcode => $name) {
						$this->PhotoCategory_translation_model->insert($last_insert_id, $langcode, $name);
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

				// kategória nevek beírása a photo_category_translation táblába
				foreach ($new_names as $langcode => $name) {
					$this->PhotoCategory_translation_model->update($id, $langcode, $name);
				}

				$this->response->json(array(
					'status' => 'success',
					'message' => 'Kategória módosítva.'
				));

			}
		}
	}

	/**
	 * Kép feltöltés
	 * @param array $files_array - $_FILES['valami']
	 * @return string
	 */
	private function _uploadImage($files_array)
	{
		$upload_path = Config::get('photogallery.upload_path');	
		$photo_width = Config::get('photogallery.width', 800);
		$photo_height = Config::get('photogallery.height', 600);

		$image = new Uploader($files_array);
	
		$newfilename = md5(uniqid());
		$image->allowed(array('image/*'));
		$image->cropToSize($photo_width, $photo_height);
		$image->save($upload_path, $newfilename);

		$dest_filename = $image->getDest('filename');

		if ($image->checkError()) {
			Message::set('error', $image->getError());
			return false;
		} else {
			$thumb_width = Config::get('photogallery.thumb_width', 320);
			$thumb_height = Config::get('photogallery.thumb_height', 240);
			
			$image->cropToSize($thumb_width, $thumb_height);
			$image->save($upload_path, $newfilename . '_thumb');
		}
		// visszatér a kép nevével
		return $dest_filename;
	}

}
?>