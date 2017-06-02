<?php
namespace System\Admin\Controller;
use System\Core\AdminController;
use System\Core\View;
use System\Libs\DI;
use System\Libs\Auth;
use System\Libs\Message;
use System\Libs\Config;
use System\Libs\Uploader;

class Blog extends AdminController {

	function __construct()
	{
		parent::__construct();
		$this->loadModel('blog_model');
		$this->loadModel('blog_translation_model');
		$this->loadModel('blogcategory_model');
	}
    
	public function index()
	{
		Auth::hasAccess('blog.index', $this->request->get_httpreferer());

		$data['title'] = 'Admin blog oldal';
		$data['description'] = 'Admin blog oldal description';	
		$data['all_blog'] = $this->blog_model->findBlog(null, LANG);
//var_dump($data['all_blog']);die;
		$view = new View();
		//$view->setHelper(array('str', 'arr'));
// $view->debug(true);		
		$view->add_links(array('datatable', 'bootbox', 'vframework'));
		$view->add_link('js', ADMIN_JS . 'pages/blog.js');
		$view->render('blog/tpl_blog', $data);
	}
    
    /**
     * Blog bejegyzés hozzáadása
     */
    public function create()
	{
		Auth::hasAccess('blog.create', $this->request->get_httpreferer());

		$data['title'] = 'Admin blog oldal';
		$data['description'] = 'Admin blog oldal description';
		$data['category_list'] = $this->blogcategory_model->findCategory(null, LANG);

		$view = new View();
		$view->setHelper(array('html_admin_helper'));
		$view->add_links(array('bootstrap-fileinput', 'ckeditor', 'vframework'));
		$view->add_link('js', ADMIN_JS . 'pages/blog_insert.js');
		$view->render('blog/tpl_blog_insert', $data);
	}

	/**
	 * Új blog adatok az adatbázisba (feldolgozó)
	 */
	public function store()
	{
		if( $this->request->is_post() ){

			// kép feltöltési hiba vizsgálata
			if($this->request->checkUploadError('upload_blog_picture')){
				Message::set('error', $this->request->getFilesError('upload_blog_picture'));
				$this->response->redirect('admin/blog/create');				
			}
/*			
if($this->request->checkUploadError('upload_blog_picture')){
	foreach ($this->request->getFileError('upload_blog_picture') as $filename => $error_msg) {
		$message = $filename . ' - ' . Message::show($error_msg);
		Message::set('error', $message);
		$this->response->redirect('admin/blog/create');				
	}
}
*/
			// kép feltöltése
			if ($this->request->hasFiles('upload_blog_picture')) {
				$dest_image = $this->_uploadPicture($this->request->getFiles('upload_blog_picture'));
				if ($dest_image === false) {
					$this->response->redirect('admin/blog/create');
				}
			} else {
				Message::set('error', 'uploaded_missing');
				$this->response->redirect('admin/blog/create');
			}


		// insert a blog táblába
			// a blog táblába kerülő adatok
			$data['status'] = $this->request->get_post('status', 'integer');
			//$data['title'] = $this->request->get_post('blog_title');
			//$data['body'] = $this->request->get_post('blog_body', 'strip_danger_tags');
			$data['picture'] = $dest_image;
			// ha nincs kategória kiválsztva, az érték null lesz (a template-ben üres string kell ah nincs kategória)
			$data['category_id'] = $this->request->get_post('blog_category', 'integer');
			
			$data['add_date'] = date('Y-m-d-G:i');

			$last_insert_id = $this->blog_model->insert($data);

			if($last_insert_id !== false) {

			// insert a blog_translation táblába
				$translation_data['blog_id'] = (int)$last_insert_id;

				$langcodes = Config::get('allowed_languages');
				foreach ($langcodes as $lang) {
					$translation_data['language_code'] = $lang;
					$translation_data['title'] = $this->request->get_post('blog_title_' . $lang);
					$translation_data['body'] = $this->request->get_post('blog_body_' . $lang, 'strip_danger_tags');
					$this->blog_translation_model->insert($translation_data);
				}

				Message::set('success', 'Blog hozzáadása sikerült!');
				$this->response->redirect('admin/blog');
			} else {
				Message::set('error' , 'unknown_error');
				$this->response->redirect('admin/blog/create');
			}
		}		
	}

	/**
	 * Blog adatok módosítása oldal
	 */
	public function edit($id)
	{
		Auth::hasAccess('blog.edit', $this->request->get_httpreferer());
		
		$id = (int)$id;

		$view = new View();

		$data['title'] = 'Admin blog oldal';
		$data['description'] = 'Admin blog oldal description';
		$data['category_list'] = $this->blogcategory_model->findCategory(null, LANG);

		$blog = $this->blog_model->findBlog($id);
		// átalakítjuk a kapott két nyelvi tömböt egy tömbre amiben benne van minden nyelv
		$blog = DI::get('arr_helper')->convertMultilanguage($blog, array('title', 'body', 'category_name'), 'id', 'language_code');
		$data['blog'] = $blog[0];

		$view->setHelper(array('html_admin_helper'));		
		$view->add_links(array('bootstrap-fileinput', 'ckeditor', 'vframework'));
		$view->add_link('js', ADMIN_JS . 'pages/blog_update.js');
		$view->render('blog/tpl_blog_update', $data);		
	}

    
    /**
     * Blog bejegyzés módosítása
     */
	public function update($id)
	{
		if( $this->request->is_post() ){

			$id = (int)$id;

			// fájl feltöltési hiba ellenőrzése
			if($this->request->checkUploadError('upload_blog_picture')){
				Message::set('error', $this->request->getFilesError('upload_blog_picture'));
				$this->response->redirect('admin/blog/edit/' . $id);				
			}
			// kép feltöltése (ellenőrizzük, hogy van-e feltöltött kép)
			if($this->request->hasFiles('upload_blog_picture')) {
				$dest_image = $this->_uploadPicture($this->request->getFiles('upload_blog_picture'));
				if($dest_image === false) {
					$this->response->redirect('admin/blog/edit/' . $id);
				}
			}

		// az adatbázisba kerülő adatok
			$data['status'] = $this->request->get_post('status', 'integer');
			
			// ha van új feltöltött kép
			if(isset($dest_image)) {
				$url_helper = DI::get('url_helper');
				$data['picture'] = $dest_image;
	            // régi kép adatai (ezt használjuk a régi kép törléséhez, ha új kép lett feltöltve)
	            $old_img_path = Config::get('blogphoto.upload_path') . $this->request->get_post('old_img');
	            $old_thumb_path = $url_helper->thumbPath($old_img_path);
			}
			
			$data['category_id'] = $this->request->get_post('blog_category', 'integer');
			$data['add_date'] = date('Y-m-d-G:i');

		// update a blog táblában	
			$result = $this->blog_model->update($id, $data);
		
			if($result !== false) {

			// update a blog_translation táblában
				$langcodes = Config::get('allowed_languages');
				foreach ($langcodes as $lang) {
					
					$translation_data['title'] = $this->request->get_post('blog_title_' . $lang);
					$translation_data['body'] = $this->request->get_post('blog_body_' . $lang, 'strip_danger_tags');
					
					// új nyelv hozzáadása esetén meg kell nézni, hogy van-e már $lang nyelvi kódú elem ehhez az id-hez,
					// mert ha nincs, akkor nem is fogja tudni update-elni, ezért update helyett insert kell					
					if (!$this->blog_translation_model->checkLangVersion($id, $lang)) {
						$translation_data['blog_id'] = $id; 
						$translation_data['language_code'] = $lang; 
						$this->blog_translation_model->insert($translation_data);
					}
					// ha már van ilyen nyelvi kódú elem
					else {
						$this->blog_translation_model->update($id, $lang, $translation_data);
					}

				}

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
				$this->response->redirect('admin/blog/edit/' . $id);
			}	

		}

	}  


	/**
	 *	Blog törlése AJAX-al
	 */
	public function delete()
	{
        if($this->request->is_ajax()){

	        if(!Auth::hasAccess('blog.delete')){
	            $this->response->json(array(
	            	'status' => 'error',
	            	'message' => 'Nincs engedélye a művelet végrehajtásához!'
	            ));
			}	            

        	// a POST-ban kapott item_id egy tömb
        	$id_arr = $this->request->get_post('item_id');
			
			// a sikeres törlések számát tárolja
			$success_counter = 0;
			
			// helperek példányosítása
			$file_helper = DI::get('file_helper');
			$url_helper = DI::get('url_helper');

			// bejárjuk a $id_arr tömböt és minden elemen végrehajtjuk a törlést
			foreach($id_arr as $id) {
				//átalakítjuk a integer-ré a kapott adatot
				$id = (int)$id;
				//lekérdezzük a törlendő blog képének a nevét, hogy törölhessük a szerverről
				$photo_name = $this->blog_model->findPicture($id);
				//blog törlése	
				$result = $this->blog_model->delete($id);
				// ha a törlési sql parancsban nincs hiba
				if($result !== false) {
					//ha van feltöltött képe a bloghoz (az adatbázisban szerepel a file-név)
					if(!empty($photo_name)){
						$picture_path = Config::get('blogphoto.upload_path') . $photo_name;
						$thumb_picture_path = $url_helper->thumbPath($picture_path);
						// képek törlése
						$file_helper->delete(array($picture_path, $thumb_picture_path));
					}				
					//sikeres törlés
					$success_counter += $result;
				}
				else {
					// ha a törlési sql parancsban hiba van
	                $this->response->json(array(
	                    'status' => 'error',
	                    'message_error' => 'Adatbázis lekérdezési hiba!'
		            ));
				}
			}

            $this->response->json(array(
                'status' => 'success',
                'message' => $success_counter . ' blog törölve.'
            ));

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
		$data['category_counter'] = $this->blog_model->categoryCounter();
		// minden kategória, minden nyelven
		$data['all_blog_category'] = $this->blogcategory_model->findCategory();
		$data['all_blog_category'] = DI::get('arr_helper')->convertMultilanguage($data['all_blog_category'], array('category_name'), 'id', 'language_code');

		$view->add_links(array('datatable', 'bootbox', 'vframework'));
		$view->add_link('js', ADMIN_JS . 'pages/blog_category.js');
		$view->render('blog/tpl_blog_category', $data);	
	}

	/**
	 * Kategória hozzáadása és módosítása (AJAX)
	 */
	public function category_insert_update()
	{
		if ($this->request->is_ajax()) {

			$this->loadModel('blogcategory_translation_model');

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
			$existing_categorys = $this->blogcategory_model->findCategory(null, LANG);

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

				// kategória létrehozása a blog_category táblába
				$last_insert_id = $this->blogcategory_model->insertCategory();
								
				if ($last_insert_id !== false) {
					// kategória nevek beírása a blog_category_translation táblába
					foreach ($new_names as $langcode => $name) {
						$this->blogcategory_translation_model->insert($last_insert_id, $langcode, $name);
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
				// kategória nevek beírása a blog_category_translation táblába
				foreach ($new_names as $langcode => $name) {
					$this->blogcategory_translation_model->update($id, $langcode, $name);
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

	        if(!Auth::hasAccess('blog.category_delete')){
	            $this->response->json(array(
	            	'status' => 'error',
	            	'message' => 'Nincs engedélye a művelet végrehajtásához!'
	            	));
	        }
	        	
        	$id = $this->request->get_post('item_id', 'integer');

					// ha az van beállítva, hogy kategória törlésnél az összes hozzá tartozó elemet is töröljük
						$delete_category_delete_items = false;
						if ($delete_category_delete_items) {

						// lekérdezzük a törlendő képek nevét
							$photo_names_temp = $this->blog_model->findPictureWhereCategory($id);

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
						
						}


			// kategória törlése a blog_category táblából
			// (ezzel együtt törlődnek a blog_translation táblából is az ehhez a kategóriához tartozó translations elemek)
			$result = $this->blogcategory_model->deleteCategory($id);
			
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

    /**
     * (AJAX) A blog táblában módosítja a status mező értékét
     *
     * @return void
     */
    public function change_status()
    {
        if ( $this->request->is_ajax() ) {
        	// jogosultság vizsgálat
        	if (!Auth::hasAccess('blog.change_status')) {
				$this->response->json(array(
					"status" => 'error',
					"message" => 'Nincs engedélye a művelet végrehajtásához.'
				));			
			}        		
        	
            if ( $this->request->has_post('action') && $this->request->has_post('id') ) {
			
				$id = $this->request->get_post('id', 'integer');
				$action = $this->request->get_post('action');

				if($action == 'make_active') {
					$result = $this->blog_model->changeStatus($id, 1);
					if($result !== false){
						$this->response->json(array(
							"status" => 'success',
							"message" => 'Az aktiválás megtörtént!'
						)); 	
					} else {
						$this->response->json(array(
							"status" => 'error',
							"message" => 'Adatbázis hiba! A hír státusza nem változott meg!'
						));
					}
				}
				if($action == 'make_inactive') {
					$result = $this->blog_model->changeStatus($id, 0);
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