<?php
namespace System\Admin\Controller;
use System\Core\Admin_controller;
use System\Core\View;
use System\Libs\DI;
use System\Libs\Config;
use System\Libs\Message;

class Photo_gallery extends Admin_controller {

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
		$view = new View();

		$data['title'] = 'Fotó galériák oldal';
		$data['description'] = 'Fotó galériák oldal description';
		// kategóriák
		$data['categorys'] = $this->photocategory_model->selectAll();
		// összes rekord a photo_gallery-ból	
		$data['all_photos'] = $this->photo_gallery_model->selectAll();
// $view->debug(true);
		$view->add_link('css', ADMIN_CSS . 'pages/portfolio.css');
		$view->add_links(array('bootbox', 'mixitup', 'vframework', 'photo_gallery'));
		$view->setHelper(array('url_helper'));
		$view->render('photo_gallery/tpl_photo_gallery', $data);
	}
	
	/**
	 * Új fotó hozzáadása
	 *
	 * @return void
	 */
	public function insert()
	{
		if($this->request->has_post()) {
			
			//képkezelő objektum létrehozása (a kép a szerveren a tmp könyvtárba kerül)	
			$upload = new \System\Libs\Uploader($this->request->getFiles('upload_gallery_photo'));

			$args = array(
				'allowed' => array('image/*'),
				'image_resize' => true,
				'image_x' => $this->photo_width,
				'image_ratio_y' => true,
				'file_new_name_body' => md5(uniqid())
			);
			$filename = $upload->make($this->image_path, $args);

			if ($filename !== false) {
				// thumbnail kép készítés
				$args_thumb = array(
					'image_resize'            => true,
					'image_x'                 => $this->thumb_width,
					'image_ratio_y'           => true,
					'file_new_name_body' 	 =>  $upload->get('file_dst_name_body'),
					'file_name_body_add' 	 =>  '_thumb'
				);
				$upload->make($this->image_path, $args_thumb);

			} else {
				Message::set('error', $upload->getError());
				$this->response->redirect('admin/photo_gallery/insert');
			}


			$data['photo_filename'] =  $filename;
			$data['photo_caption'] = $this->request->get_post('photo_caption');
			$data['photo_category'] = $this->request->get_post('photo_category', 'integer');
			$data['photo_slider'] = ($this->request->has_post('photo_slider')) ? $this->request->get_post('photo_slider', 'integer') : 0;
				
			// adatbázis lekérdezés
			$result = $this->photo_gallery_model->insert($data);
		
			if($result !== false) {
	            Message::set('success', 'new_photo_gallery_success');
			} else {
	            Message::set('error', 'unknown_error');
			}

			$this->response->redirect('admin/photo-gallery');
		}
		
		$view = new View();

		$data['title'] = 'Új fotó oldal';
		$data['description'] = 'Új fotó oldal description';
		$data['categorys'] = $this->photocategory_model->selectAll();

		$view->add_links(array('bootstrap-fileupload', 'vframework', 'photo_gallery_insert_update'));
		$view->render('photo_gallery/tpl_photo_insert', $data);	
	}
	
	/**
	 * Kép adatainak szerkesztése (új kép feltöltése, szöveg módosítása, kiemelés, kategória módosítása)
	 *
	 * @return void
	 */
	public function update()
	{
		$id = (int)$this->request->get_params('id');

		if($this->request->has_post()) {
			
			if($this->request->checkFiles('upload_gallery_photo')) {
			
				//képkezelő objektum létrehozása
				$upload = new \System\Libs\Uploader($this->request->getFiles('upload_gallery_photo'));

				$args = array(
					'allowed' => array('image/*'),
					'image_resize' => true,
					'image_x' => $this->photo_width,
					'image_ratio_y' => true,
					'file_new_name_body' => md5(uniqid())
				);
				$filename = $upload->make($this->image_path, $args);

				if ($filename !== false) {
					// thumbnail kép készítés
					$args_thumb = array(
						'image_resize'            => true,
						'image_x'                 => $this->thumb_width,
						'image_ratio_y'           => true,
						'file_new_name_body' 	 =>  $upload->get('file_dst_name_body'),
						'file_name_body_add' 	 =>  '_thumb'
					);
					$upload->make($this->image_path, $args_thumb);

				} else {
                    Message::set('error', $upload->getError());
					$this->response->redirect('admin/photo_gallery/update');					
				}


				if (isset($filename)) {
					$data['photo_filename'] = $filename;
				}	
				$data['photo_caption'] = $this->request->get_post('photo_caption');
				$data['photo_category'] = $this->request->get_post('photo_category', 'integer');
				$data['photo_slider'] = ($this->request->has_post('photo_slider')) ? $this->request->get_post('photo_slider', 'integer') : 0;

				// új adatok beírása az adatbázisba (update) a $data tömb tartalmazza a frissítendő adatokat 
				$result = $this->photo_gallery_model->update($id, $data);
						
				if($result !== false) {
					
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
		}
		
		$view = new View();

		$data['title'] = 'Fotó szerkesztése oldal';
		$data['description'] = 'Fotó szerkesztése description';
		$data['categorys'] = $this->photocategory_model->selectAll();
		$data['photo'] = $this->photo_gallery_model->selectOne($id);

		$view->add_links(array('bootstrap-fileupload', 'vframework', 'photo_gallery_insert_update'));
		$view->render('photo_gallery/tpl_photo_update', $data);	
	}
	
	/**
	 *	Photo törlése AJAX-al
	 */
	public function delete_photo_AJAX()
	{
        if($this->request->is_ajax()){
	        if(1){
	        	// a POST-ban kapott item_id egy tömb
	        	$id_arr = $this->request->get_post('item_id');
				// a sikeres törlések számát tárolja
				$success_counter = 0;
		        // a sikeresen törölt id-ket tartalmazó tömb
		        $success_id = array();		
				// a sikertelen törlések számát tárolja
				$fail_counter = 0; 

		        $file_helper = DI::get('file_helper');
		        $url_helper = DI::get('url_helper');

				// bejárjuk a $id_arr tömböt és minden elemen végrehajtjuk a törlést
				foreach($id_arr as $id) {
					//átalakítjuk a integer-ré a kapott adatot
					$id = (int)$id;
					//lekérdezzük a törlendő kép nevét, hogy törölhessük a szerverről
					$photo_name = $this->photo_gallery_model->selectFilename($id);			
					//rekord törlése	
					$result = $this->photo_gallery_model->delete($id);
					
					if($result !== false) {
						// ha a törlési sql parancsban nincs hiba
						if($result > 0){
							//ha van feltöltött kép (az adatbázisban szerepel a file-név)
							if(!empty($photo_name)){
								$picture_path = Config::get('photogallery.upload_path') . $photo_name;
								$thumb_picture_path = $url_helper->thumbPath($picture_path);
								$file_helper->delete(array($picture_path, $thumb_picture_path));
							}				
							//sikeres törlés
							$success_counter += $result;
							$success_id[] = $id;
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
				}

		        // üzenetek visszaadása
		        $respond = array();
		        $respond['status'] = 'success';
		        
		        if ($success_counter > 0) {
		            $respond['message_success'] = 'Kép törölve.';
		        }
		        if ($fail_counter > 0) {
		            $respond['message_error'] = 'A képet már töröltek!';
		        }

		        // válasz
		        $this->response->json($respond);

	        } else {
	            $this->response->json(array(
	            	'status' => 'error',
	            	'message' => 'Nincs engedélye a művelet végrehajtásához!'
	            ));
	        }
        }
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
		$data['all_category'] = $this->photocategory_model->selectAll();
		$data['category_counter'] = $this->photo_gallery_model->categoryCounter();

		$view->add_links(array('bootbox', 'datatable', 'bootstrap-editable', 'vframework', 'photo_category'));
		$view->render('photo_gallery/tpl_photo_category', $data);	
	}

	/**
	 *	Kategória törlése AJAX-al
	 */
	public function delete_category_AJAX()
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
			
			$id = $this->request->get_post('id', 'integer');
			$new_name = $this->request->get_post('data');

			if($new_name == '') {
				$this->response->json(array(
					'status' => 'error',
					'message' => 'Nem lehet üres a kategória név mező!'
				));
			}	

			// kategóriák lekérdezése (annak ellenőrzéséhez, hogy már létezik-e ilyen kategória)
			$existing_categorys = $this->photocategory_model->selectAll();
			// bejárjuk a kategória neveket és összehasonlítjuk az új névvel (kisbetűssé alakítjuk, hogy ne számítson a nagybetű-kisbetű eltérés)
			foreach($existing_categorys as $value) {
				if(strtolower($new_name) == strtolower($value['category_name'])) {
					$this->response->json(array(
						'status' => 'error',
						'message' => 'Már létezik ' . $value['category_name'] . ' kategória!'
					));
				}	
			} 

			//insert
			if ($id == 0) {
				$result = $this->photocategory_model->insertCategory(array('category_name' => $new_name));

				if ($result !== false) {
					$this->response->json(array(
						'status' => 'success',
						'message' => 'Kategória hozzáadva.',
						'action' => 'insert'
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
				$result = $this->photocategory_model->updateCategory($id, $new_name);

				if ($result !== false) {
					$this->response->json(array(
						'status' => 'success',
						'message' => 'Kategória módosítva.'
					));
				} else { 
					$this->response->json(array(
						'status' => 'error',
						'message' => 'Adatbázis lekérdezési hiba!'
					));
				}
			}
		}
	}

}
?>