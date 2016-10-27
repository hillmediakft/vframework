<?php
namespace System\Admin\Controller;
use System\Core\Admin_controller;
use System\Core\View;
use System\Libs\DI;
use System\Libs\Message;
use System\Libs\Config;
use System\Libs\Uploader;

class Blog extends Admin_controller {

	function __construct()
	{
		parent::__construct();
		$this->loadModel('blog_model');
		$this->loadModel('blogcategory_model');
	}
    
	public function index()
	{
		$view = new View();

		$data['title'] = 'Admin blog oldal';
		$data['description'] = 'Admin blog oldal description';	
		$data['all_blog'] = $this->blog_model->selectBlog();
		//$view->setHelper(array('str', 'arr'));
// $view->debug(true);		
		$view->add_links(array('datatable', 'bootbox', 'vframework', 'blog'));
		$view->render('blog/tpl_blog', $data);
	}
    
    /**
     * Blog bejegyzés hozzáadása
     */
    public function insert()
	{
		if( $this->request->is_post() ){

			// kép feltöltési hiba vizsgálata
			if($this->request->checkUploadError('upload_blog_picture')){
				Message::set('error', $this->request->getFilesError('upload_blog_picture'));
				$this->response->redirect('admin/blog/insert');				
			}
/*			
if($this->request->checkUploadError('upload_blog_picture')){
	foreach ($this->request->getFileError('upload_blog_picture') as $filename => $error_msg) {
		$message = $filename . ' - ' . Message::show($error_msg);
		Message::set('error', $message);
		$this->response->redirect('admin/blog/insert');				
	}
}
*/
			// kép feltöltése
			if ($this->request->hasFiles('upload_blog_picture')) {
				$dest_image = $this->_uploadPicture($this->request->getFiles('upload_blog_picture'));
				if ($dest_image === false) {
					$this->response->redirect('admin/blog/insert');
				}
			} else {
				Message::set('error', 'uploaded_missing');
				$this->response->redirect('admin/blog/insert');
			}


			// az adatbázisba kerülő adatok
			$data['title'] = $this->request->get_post('blog_title');
			$data['body'] = $this->request->get_post('blog_body', 'strip_danger_tags');
			$data['category_id'] = $this->request->get_post('blog_category');
			$data['picture'] = $dest_image;
			$data['add_date'] = date('Y-m-d-G:i');

			// DB lekérdezés
			$result = $this->blog_model->insert($data);

			if($result !== false) {
				Message::set('success', 'Blog hozzáadása sikerült!');
				$this->response->redirect('admin/blog');
			} else {
				Message::set('error' , 'unknown_error');
				$this->response->redirect('admin/blog/insert');
			}
		}

		$view = new View();
		
		$data['title'] = 'Admin blog oldal';
		$data['description'] = 'Admin blog oldal description';	
		$data['category_list'] = $this->blogcategory_model->selectCategory();
		
		$view->add_links(array('bootstrap-fileupload', 'ckeditor', 'vframework', 'blog_insert'));
		$view->render('blog/tpl_blog_insert', $data);
	}
    
    /**
     * Blog bejegyzés módosítása
     */
	public function update()
	{
		if( $this->request->has_post() ){

			// fájl feltöltési hiba ellenőrzése
			if($this->request->checkUploadError('upload_blog_picture')){
				Message::set('error', $this->request->getFilesError('upload_blog_picture'));
				$this->response->redirect('admin/blog/insert');				
			}
			// kép feltöltése (ellenőrizzük, hogy van-e feltöltött kép)
			if($this->request->hasFiles('upload_blog_picture')) {
				$dest_image = $this->_uploadPicture($this->request->getFiles('upload_blog_picture'));
				if($dest_image === false) {
					$this->response->redirect('admin/blog/update');
				}
			}

		// az adatbázisba kerülő adatok
			$data['title'] = $this->request->get_post('blog_title');
			$data['body'] = $this->request->get_post('blog_body', 'strip_danger_tags');
			
			// ha van új feltöltött kép
			if(isset($dest_image)) {
				$url_helper = DI::get('url_helper');
				$data['picture'] = $dest_image;
	            // régi kép adatai (ezt használjuk a régi kép törléséhez, ha új kép lett feltöltve)
	            $old_img_path = Config::get('blogphoto.upload_path') . $this->request->get_post('old_img');
	            $old_thumb_path = $url_helper->thumbPath($old_img_path);
			}
			
			$data['category_id'] = $this->request->get_post('blog_category');
			$data['add_date'] = date('Y-m-d-G:i');

		// adatbázis lekérdezés	
			$result = $this->blog_model->update((int)$this->request->get_params('id'), $data);
		
			if($result !== false) {
	            // megvizsgáljuk, hogy létezik-e új feltöltött kép
	            if(isset($dest_image)) {
	            	$file_helper = DI::get('file_helper');
	                //régi képek törlése
	            	$file_helper->delete(array($old_img_path, $old_thumb_path));
	            }

				Message::set('success', 'Bejegyzés módosítása sikerült!');
				$this->response->redirect('admin/blog');
			} else {
				Message::set('error', 'unknown_error');
				$this->response->redirect('admin/blog/update/' . $this->request->get_params('id'));
			}	

		}

		$view = new View();

		$data['title'] = 'Admin blog oldal';
		$data['description'] = 'Admin blog oldal description';	
		$data['category_list'] = $this->blogcategory_model->selectCategory();
		$data['blog'] = $this->blog_model->selectBlog($this->request->get_params('id'));
// $view->debug(true);		
		$view->add_links(array('bootstrap-fileupload', 'ckeditor', 'vframework', 'blog_update'));
		$view->render('blog/tpl_blog_update', $data);
	}  

	/**
	 *	Blog törlése AJAX-al
	 */
	public function delete_blog_AJAX()
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
		        // a paraméterként kapott stringből tömböt csinálunk a , karakter mentén
		        //$id_arr = explode(',', $id_string);
				
				// helperek példányosítása
				$file_helper = DI::get('file_helper');
				$url_helper = DI::get('url_helper');

				// bejárjuk a $id_arr tömböt és minden elemen végrehajtjuk a törlést
				foreach($id_arr as $id) {
					//átalakítjuk a integer-ré a kapott adatot
					$id = (int)$id;
					//lekérdezzük a törlendő blog képének a nevét, hogy törölhessük a szerverről
					$photo_name = $this->blog_model->selectPicture($id);
					//blog törlése	
					$result = $this->blog_model->delete($id);
					
					if($result !== false) {
						// ha a törlési sql parancsban nincs hiba
						if($result > 0){
							//ha van feltöltött képe a bloghoz (az adatbázisban szerepel a file-név)
							if(!empty($photo_name)){
								$picture_path = Config::get('blogphoto.upload_path') . $photo_name;
								$thumb_picture_path = $url_helper->thumbPath($picture_path);
								// képek törlése
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
		            $respond['message_success'] = $success_counter . ' blog törölve.';
		        }
		        if ($fail_counter > 0) {
		            $respond['message_error'] = $fail_counter . ' blogot már töröltek!';
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
	 * Blog kategóriák 
	 */
	public function category()
	{
		$view = new View();

		$data['title'] = 'Admin blog oldal';
		$data['description'] = 'Admin blog oldal description';	
		$data['all_blog_category'] = $this->blogcategory_model->selectCategory();
		$data['category_counter'] = $this->blog_model->categoryCounter();
//$view->debug(true);			
		$view->add_links(array('datatable', 'bootbox', 'vframework', 'blog_category'));
		$view->render('blog/tpl_blog_category', $data);	
	}

	/**
	 * Kategória hozzáadása és módosítása (AJAX)
	 */
	public function category_insert_update()
	{
		if ($this->request->is_ajax()) {
			// az id értéke lehet null is!
			$id = $this->request->get_post('id');
			$new_name = $this->request->get_post('data');
			
			if ($new_name == '') {
				$this->response->json(array(
					'status' => 'error',
					'message' => 'Nem lehet üres a kategória név mező!'
				));
			}	

		// kategóriák lekérdezése (annak ellenőrzéséhez, hogy már létezik-e ilyen kategória)
			$existing_categorys = $this->blogcategory_model->selectCategory();
			// bejárjuk a kategória neveket és összehasonlítjuk az új névvel (kisbetűssé alakítjuk, hogy ne számítson a nagybetű-kisbetű eltérés)
			foreach($existing_categorys as $value) {
				if(strtolower($new_name) == strtolower($value['category_name'])) {
					$this->response->json(array(
						'status' => 'error',
						'message' => 'Már létezik ' . $value['category_name'] . ' kategória!'
					));
				}	
			} 

		//insert (ha az $id értéke null)
			if ($id == null) {
				$result = $this->blogcategory_model->insertCategory($new_name);
				
				if ($result) {
					$this->response->json(array(
						'status' => 'success',
						'message' => 'Kategória hozzáadva.',
						'inserted_id' => $result
					));
				}
				if ($result === false){ 
					$this->response->json(array(
						'status' => 'error',
						'message' => 'Adatbázis lekérdezési hiba!'
					));
				}
			}
		// update
			else {
				$result = $this->blogcategory_model->updateCategory((int)$id, $new_name);

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

	/**
	 *	Kategória törlése (AJAX)
	 */
	public function category_delete()
	{
        if($this->request->is_ajax()){
	        if(1){
	        	$id = $this->request->get_post('item_id', 'integer');

			// a sikeres törlések számát tárolja
				$success_counter = 0;
			// a sikertelen törlések számát tárolja
				$fail_counter = 0; 

			// lekérdezzük a törlendő képek nevét
				$photo_names_temp = $this->blog_model->selectPictureWhereCategory($id);

				$photo_names = array();
				foreach ($photo_names_temp as $key => $value) {
					$photo_names[] = $value['picture'];
				}
				unset($photo_names_temp);

			// blogbejegyzések törlése, amik a törlendő kategóriához tartoznak
				$result = $this->blog_model->deleteWhereCategory($id);

			// képek törlése
				if($result !== false) {
					if($result > 0){
						// helperek példányosítása
						$file_helper = DI::get('file_helper');
						$url_helper = DI::get('url_helper');

						foreach($photo_names as $value){
							$picture_path = Config::get('blogphoto.upload_path') . $value;
							$thumb_picture_path = $url_helper->thumbPath($picture_path);
							//képek file törlése
							$file_helper->delete(array($picture_path, $thumb_picture_path));
						}				
					}
				}

			// kategória törlése a blog_category táblából
				$result = $this->blogcategory_model->deleteCategory($id);
				
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
	 * Kép feltöltés
	 */
	private function _uploadPicture($files_array)
	{
		$upload_path = Config::get('blogphoto.upload_path');
		$width = Config::get('blogphoto.width', 600);
		$height = Config::get('blogphoto.height', 400);

		$image = new Uploader($files_array);
		
		// új filenév
		$newfilename = 'blog_' . md5(uniqid());
		// nagy kép
		$image->allowed(array('image/*'));
		$image->cropToSize($width, $height);
		$image->save($upload_path, $newfilename);

		$filename = $image->getDest('filename');
			
		if ($image->checkError()) {
			Message::set('error', $image->getError());
			return false;

		} else {
			// nézőkép
			$thumb_width = Config::get('blogphoto.thumb_width', 150);
			$thumb_height = $image->calcHeight($thumb_width);
			
			$image->cropToSize($thumb_width, $thumb_height);
			$image->save($upload_path, $newfilename . '_thumb');
		}

		$image->cleanTemp();

		// kép neve
		return $filename;		
	}


}
?>