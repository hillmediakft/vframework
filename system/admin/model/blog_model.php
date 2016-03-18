<?php
class blog_model extends Admin_model {

	function __construct()
	{
		parent::__construct();
	}
   
   
   
	/**
	 *	Visszaadja a blog tábla tartalmát
	 *	Ha kap egy id paramétert (integer), akkor csak egy sort ad vissza a táblából
	 *
	 *	@param $id Integer 
	 */
	public function blog_query($id = null)
	{
		$this->query->reset(); 
		$this->query->set_table(array('blog')); 
		$this->query->set_columns('*'); 
		if(!is_null($id)){
			$id = (int)$id;
			$this->query->set_where('blog_id', '=', $id); 
		}
		return $this->query->select(); 
	}

	/**
	 *	Visszaadja a blog tábla egy kategóriájának elemeit
	 *	Ha kap egy id paramétert (integer), akkor csak egy sort ad vissza a táblából
	 *
	 *	@param $id Integer 
	 */
	public function blog_query2($id = null)
	{
		$this->query->reset(); 
		$this->query->set_table(array('blog')); 
		$this->query->set_columns(array('blog.blog_id','blog.blog_title','blog.blog_body','blog.blog_picture','blog.blog_add_date', 'blog_category.category_name')); 
		$this->query->set_join('left', 'blog_category', 'blog.blog_category', '=', 'blog_category.category_id'); 
		if(!is_null($id)){
			$id = (int)$id;
			$this->query->set_where('blog.blog_id', '=', $id); 
		}
		
		return $this->query->select(); 
	}

	/**
	 *	Visszaadja a blog_category tábla tartalmát
	 *	Ha kap egy id paramétert (integer), akkor csak egy sort ad vissza a táblából
	 *
	 *	@param $id Integer 
	 */
	public function category_query($id = null)
	{
		$this->query->reset(); 
		$this->query->set_table(array('blog_category')); 
		$this->query->set_columns('*'); 
		if(!is_null($id)){
			$id = (int)$id;
			$this->query->set_where('category_id', '=', $id); 
		}
		return $this->query->select(); 
	}

	/**
	 *	Visszaadja a blog táblából a blog_category oszlopot
	 */
	public function category_counter_query()
	{
		$this->query->reset(); 
		$this->query->set_table(array('blog')); 
		$this->query->set_columns('blog_category'); 
		return $this->query->select(); 
	}
   
   
	public function insert()
	{
		// kép feltöltése
		if(isset($_FILES['upload_blog_picture'])) {
			// kép feltöltése, upload_slider_picture() metódussal (visszatér a feltöltött kép elérési útjával, vagy false-al)
			$dest_image = $this->upload_blog_picture($_FILES['upload_blog_picture']);
			
			if($dest_image === false){
				return false;
			}
		}
		else {
			throw new Exception('Hiba blog kep feltoltesekor: Nem letezik a \$_FILES[\'upload_blog_picture\'] elem!');
			return false;
		}

	// az adatbázisba kerülő adatok
		$data['blog_title'] = $this->request->get_post('blog_title');
		$data['blog_body'] = $this->request->get_post('blog_body', 'strip_danger_tags');
		$data['blog_picture'] = $dest_image;
		$data['blog_category'] = $this->request->get_post('blog_category');
		$data['blog_add_date'] = date('Y-m-d-G:i');

	// adatbázis lekérdezés	
		$this->query->reset();
		$this->query->set_table(array('blog'));
		$result = $this->query->insert($data);
	
	// ha sikeres az insert visszatérési érték true
		if($result) {
			Message::set('success', 'Blog hozzáadása sikerült!');
            return true;
		}
		else {
			Message::set('error' , 'unknown_error');
			return false;
		}
	}

	public function update($id)
	{
		// kép feltöltése
		if(isset($_FILES['upload_blog_picture']) && $_FILES['upload_blog_picture']['error'] != 4) {
			// kép feltöltése, upload_slider_picture() metódussal (visszatér a feltöltött kép elérési útjával, vagy false-al)
			$dest_image = $this->upload_blog_picture($_FILES['upload_blog_picture']);
			
			$new_picture = true;
			
			if($dest_image === false){
				return false;
			}

		}
		/*
		else {
			throw new Exception('Hiba blog kep feltoltesekor: Nem letezik a \$_FILES[\'upload_blog_picture\'] elem!');
			return false;
		}
		*/


	// az adatbázisba kerülő adatok
		$data['blog_title'] = $this->request->get_post('blog_title');
		$data['blog_body'] = $this->request->get_post('blog_body', 'strip_danger_tags');
		
		// ha van új feltöltött kép
		if(isset($new_picture)){
			$data['blog_picture'] = $dest_image;
            // régi kép adatai (ezt használjuk a régi kép törléséhez, ha új kép lett feltöltve)
            $old_img_path = Config::get('blogphoto.upload_path') . $this->request->get_post('old_img');
            $old_thumb_path = Util::thumb_path($old_img_path);
		}
		
		$data['blog_category'] = $this->request->get_post('blog_category');
		$data['blog_add_date'] = date('Y-m-d-G:i');

	// adatbázis lekérdezés	
		$this->query->reset();
		$this->query->set_table(array('blog'));
		$this->query->set_where('blog_id','=', $id);
		$result = $this->query->update($data);
	
		if($result >= 0) {
            // megvizsgáljuk, hogy létezik-e új feltöltött kép
            if (isset($new_picture)) {
                //régi képek törlése
                if (!Util::del_file($old_img_path)) {
                    Message::log('A ' . $old_img_path . ' kép nem törlődött!');
                };
                if (!Util::del_file($old_thumb_path)) {
                    Message::log('A ' . $old_thumb_path . ' kép nem törlődött!');
                };
            }

			Message::set('success', 'Bejegyzés módosítása sikerült!');
			return true;
		}
		else {
			Message::set('error', 'unknown_error');
            return false;
		}	
	}
	
	
    /**
     * Blog törlése AJAX-al
     *
     * @param string $id     ez lehet egy szám, vagy felsorolás pl: 23 vagy 12,14,36
     */
	public function delete_blog_AJAX($id)
	{
		// a sikeres törlések számát tárolja
		$success_counter = 0;
        // a sikeresen törölt id-ket tartalmazó tömb
        $success_id = array();		
		// a sikertelen törlések számát tárolja
		$fail_counter = 0; 

        // a paraméterként kapott stringből tömböt csinálunk a , karakter mentén
        $data_arr = explode(',', $id);
		
		// bejárjuk a $data_arr tömböt és minden elemen végrehajtjuk a törlést
		foreach($data_arr as $value) {
			//átalakítjuk a integer-ré a kapott adatot
			$value = (int)$value;
			
			//lekérdezzük a törlendő blog képének a nevét, hogy törölhessük a szerverről
			$this->query->reset();
			$this->query->set_table('blog');
			$this->query->set_columns(array('blog_picture'));
			$this->query->set_where('blog_id', '=', $value);
			$photo_name = $this->query->select();			

			//blog törlése	
			$this->query->reset();
			$this->query->set_table(array('blog'));
			//a delete() metódus integert (lehet 0 is) vagy false-ot ad vissza
			$result = $this->query->delete('blog_id', '=', $value);
			
			if($result !== false) {
				// ha a törlési sql parancsban nincs hiba
				if($result > 0){
					//ha van feltöltött képe a bloghoz (az adatbázisban szerepel a file-név)
					if(!empty($photo_name[0]['blog_picture'])){
					
						$picture_path = Config::get('blogphoto.upload_path') . $photo_name[0]['blog_picture'];
						$thumb_picture_path = Util::thumb_path($picture_path);
					
						$del_result = Util::del_file($picture_path);
						$del_thumb_result = Util::del_file($thumb_picture_path);
					
						//kép file törlése a szerverről (ha az Util::del_file() falsot ad vissza nem tudtuk törölni a képet... hibaüzenet)
						if(!$del_result){
                            Message::log('A blogbejegyzés képe nem létezik, vagy nem törölhető! - ' . $picture_path);
						}
						//kép file törlése a szerverről (ha az Util::del_file() falsot ad vissza nem tudtuk törölni a képet... hibaüzenet)
						if(!$del_thumb_result){
                            Message::log('A blogbejegyzés nézőképe nem létezik, vagy nem törölhető! - ' . $thumb_picture_path);
						}
					}				
					//sikeres törlés
					$success_counter += $result;
					$success_id[] = $value;
				}
				else {
					//sikertelen törlés
					$fail_counter += 1;
				}
			}
			else {
				// ha a törlési sql parancsban hiba van
                return array(
                    'status' => 'error',
                    'message_error' => 'Hibas sql parancs: nem sikerult a DELETE lekerdezes az adatbazisbol!',                  
                );
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
        return $respond;	
	}



	/*------------ KATEGÓRIÁK ---------------*/

	/**
	 * Kategória hozzáadása és módosítása
	 *
	 * @param integer $id
	 * @param string $new_name
	 * @return array
	 */
	public function category_insert_update($id, $new_name)
	{
		$new_name = trim($new_name);

		if($new_name == '') {
			return array(
				'status' => 'error',
				'message' => 'Nem lehet üres a kategória név mező!'
			);
		}	

		// kategóriák lekérdezése (annak ellenőrzéséhez, hogy már létezik-e ilyen kategória)
		$existing_categorys = $this->category_query();
		// bejárjuk a kategória neveket és összehasonlítjuk az új névvel (kisbetűssé alakítjuk, hogy ne számítson a nagybetű-kisbetű eltérés)
		foreach($existing_categorys as $value) {
			if(strtolower($new_name) == strtolower($value['category_name'])) {
				return array(
					'status' => 'error',
					'message' => 'Már létezik ' . $value['category_name'] . ' kategória!'
				);
			}	
		} 

		//insert
		if ($id == 0) {
			
			$this->query->reset();
			$this->query->set_table(array('blog_category'));
			$result = $this->query->insert(array('category_name' => $new_name));

			if ($result) {
				return array(
					'status' => 'success',
					'message' => 'Kategória hozzáadva.',
					'action' => 'insert'
				);
			}
			if($result === false){ 
				return array(
					'status' => 'error',
					'message' => 'Adatbázis lekérdezési hiba!'
				);
			}
		}
		// update
		else {
			$this->query->reset();
			$this->query->set_table(array('blog_category'));
			$this->query->set_where('category_id', '=', $id);
			$result = $this->query->update(array('category_name' => $new_name));

			if ($result >= 0) {
				return array(
					'status' => 'success',
					'message' => 'Kategória módosítva.'
				);
			}
			if($result === false){ 
				return array(
					'status' => 'error',
					'message' => 'Adatbázis lekérdezési hiba!'
				);
			}
		}
	}


    /**
     * Blog kategória törlése
     *
     * @param string $id     ez egy szám
     */
	public function category_delete($id)
	{
		// a sikeres törlések számát tárolja
		$success_counter = 0;
		// a sikertelen törlések számát tárolja
		$fail_counter = 0; 

			// lekérdezzük a törlendő képek nevét
			$this->query->reset();
			$this->query->set_table('blog');
			$this->query->set_columns(array('blog_picture'));
			$this->query->set_where('blog_category', '=', $id);
			$photo_names_temp = $this->query->select();			

			$photo_names = array();
			foreach ($photo_names_temp as $key => $value) {
				$photo_names[] = $value['blog_picture'];
			}
			unset($photo_names_temp);

			// képekhez tartozó rekordok törlése
			$this->query->reset();
			$this->query->set_table(array('blog'));
			$result = $this->query->delete('blog_category', '=', $id);
			
			// képek törlése
			if($result !== false) {
				if($result > 0){

					foreach($photo_names as $value){
					
						$picture_path = Config::get('blogphoto.upload_path') . $value;
						$thumb_picture_path = Util::thumb_path($picture_path);
					
						$del_result = Util::del_file($picture_path);
						$del_thumb_result = Util::del_file($thumb_picture_path);
					
						//kép file törlése a szerverről (ha az Util::del_file() falsot ad vissza nem tudtuk törölni a képet... hibaüzenet)
						if(!$del_result){
                            Message::log('A kép nem létezik, vagy nem törölhető! - ' . $picture_path);
						}
						//kép file törlése a szerverről (ha az Util::del_file() falsot ad vissza nem tudtuk törölni a képet... hibaüzenet)
						if(!$del_thumb_result){
                            Message::log('A nézőkép nem létezik, vagy nem törölhető! - ' . $thumb_picture_path);
						}
					}				
				}
			}

			// kategória törlése
			$this->query->reset();
			$this->query->set_table(array('blog_category'));
			//a delete() metódus integert (lehet 0 is) vagy false-ot ad vissza
			$result = $this->query->delete('category_id', '=', $id);
			
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
                return array(
                    'status' => 'error',
                    'message_error' => 'Hibas sql parancs: nem sikerult a DELETE lekerdezes az adatbazisbol!',                  
                );
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
        return $respond;	
	} // delete END


/*------------------------------------*/



	/**
	 *	Blog képet méretezi és tölti fel a szerverre (thumb képet is)
	 *	(ez a metódus az update() és insert() metódusokban hívódik meg!)
	 *
	 *	@param	$files_array	Array ($_FILES['valami'])
	 *	@return	String (kép elérési útja) or false
	 */
	private function upload_blog_picture($files_array)
	{
		//include(LIBS . "/upload_class.php");
		// feltöltés helye
		$imagePath = Config::get('blogphoto.upload_path');
		// kép szélesség
		$width = Config::get('blogphoto.width', 600);
		// kép magasság
		$height = Config::get('blogphoto.height', 400);
		// nézőképkép szélesség
		$width_thumb = Config::get('blogphoto.thumb_width', 150);

		
		//képkezelő objektum létrehozása (a kép a szerveren a tmp könyvtárba kerül)	
		$handle = new Upload($files_array);
					
		//file átméretezése, vágása, végleges helyre mozgatása
		if ($handle->uploaded) {
		// kép paramétereinek módosítása
			$handle->file_auto_rename 		 = true;
			$handle->file_safe_name 		 = true;
			$handle->allowed = array('image/*');
			$handle->file_new_name_body   	 = "blog_" . rand();
			$handle->image_resize            = true;
			$handle->image_x                 = $width;
			$handle->image_y                 = $height;
			//$handle->image_ratio_y           = true;
			
			//képarány meghatározása a nézőképhez
			$ratio = ($handle->image_x / $handle->image_y);

		// Blog kép készítése
			$handle->Process($imagePath);
			if ($handle->processed) {
				//kép új neve (ezzel tér vissza a metódus, ha nincs hiba!)
				$dest_image = $handle->file_dst_name;
			} else {
                Message::set('error', $handle->error);
				return false;
			}
			
		// Nézőkép készítése
			//nézőkép nevének megadása (kép új neve utána _thumb)	
			$handle->file_new_name_body		 = $handle->file_dst_name_body;
			$handle->file_name_body_add   	 = '_thumb';
			
			$handle->image_resize            = true;
			$handle->image_x                 = $width_thumb;
			$handle->image_y           		 = round($handle->image_x / $ratio);
			//$handle->image_ratio_y           = true;

			$handle->Process($imagePath);
			if ($handle->processed) {
				//temp file törlése a szerverről
				$handle->clean();
			} else {
                Message::set('error', $handle->error);
				return false;
			}
		} else {
            Message::set('error', $handle->error);
			return false;	
		}
		
		// ha nincs hiba visszadja a feltöltött kép nevét
		return $dest_image;	
	}	
}
?>