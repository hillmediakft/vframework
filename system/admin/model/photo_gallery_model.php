<?php 
class Photo_gallery_model extends Admin_model {

	private $photo_width;
	private $thumb_width;
	private $image_path;

	/**
     * Constructor, létrehozza az adatbáziskapcsolatot
     */
	function __construct()
	{
		parent::__construct();
		$this->photo_width = Config::get('photogallery.width', 800);
		$this->thumb_width = Config::get('photogallery.thumb_width', 320);
		$this->image_path = Config::get('photogallery.upload_path');
	}
	
	/**
	 *	Egy kép adatait kérdezi le az adatbázisból ha van id paraméter (photo_gallery tábla)
	 *	Paraméter nélkül az összes kép adatát lekérdezi
	 *
	 *	@param	$id a kép rekordjának azonosítója
	 *	@return	array
	 */
	public function photo_data_query($id = null)
	{
		$this->query->reset();
		$this->query->set_table(array('photo_gallery'));
		$this->query->set_columns('*');
		if(!is_null($id)){
			$this->query->set_where('photo_id', '=', $id);
		}
		return $this->query->select();
	}	

	/**
	 * Fotó hozzáadása, kép feltötése és adatok mentése
	 *
	 * @return boolean - true ha sikeres a mentés, false ha hiba történt
	 */
	public function insert_photo()
	{
		//képkezelő objektum létrehozása (a kép a szerveren a tmp könyvtárba kerül)	
		$handle = new Upload($_FILES['upload_gallery_photo']);

			if ($handle->uploaded) {
			
				$handle->allowed = array('image/*');
				
				$random_number = md5(date('Y-m-d H:i:s:u'));
			    
				$handle->image_resize            = true;
				$handle->image_ratio_y           = true;
				$handle->image_x                 = $this->photo_width; //kép szélessége;
				$handle->file_new_name_body 	 = $random_number;
							
				//végrehajtás: kép átmozgatása végleges helyére
				$handle->Process($this->image_path);
				
				$filename = $handle->file_dst_name;

				if ($handle->processed) {
											
					$handle->image_resize            = true;
					$handle->image_ratio_y           = true;
					$handle->image_x                 = $this->thumb_width; // nézőkép szélessége
					$handle->file_new_name_body 	 =  $random_number . '_thumb';
							
					//végrehajtás: kép átmozgatása végleges helyére
					$handle->Process($this->image_path);
					$handle->clean();
					
				} else {
                    Message::set('error', $handle->error);
				return false;
				}
	
			} else {
                Message::set('error', $handle->error);
				return false;
			}
		
		$data['photo_filename'] =  $filename;
		$data['photo_caption'] = $this->request->get_post('photo_caption');
		$data['photo_category'] = $this->request->get_post('photo_category', 'integer');
		
		if( $this->request->has_post('photo_slider') ) {
			$data['photo_slider'] = $this->request->get_post('photo_slider', 'integer');
		} else {
			$data['photo_slider'] = 0;
		}	
				
		$this->query->reset();
		$this->query->set_table(array('photo_gallery'));
		$result = $this->query->insert($data);
	
		if($result) {
            Message::set('success', 'new_photo_gallery_success');
			return true;
		}
		else {
            Message::set('error', 'unknown_error');
			return false;
		}
	}
	
	/**
	 * Kép adatainak módosítása
	 *
	 * @param 	int $id	
	 * @return 	boolean
	 */
	public function update_photo($id)
	{
		$flag = false;
	
		if(isset($_FILES['upload_gallery_photo'])  && $_FILES['upload_gallery_photo']['tmp_name'] != '') {
		
			$flag = true;

			//képkezelő objektum létrehozása (a kép a szerveren a tmp könyvtárba kerül)	
			$handle = new Upload($_FILES['upload_gallery_photo']);

			if ($handle->uploaded) {
			
				$handle->allowed = array('image/*');
				
				$random_number = md5(date('Y-m-d H:i:s:u'));
			    $handle->jpeg_quality 			 = 80;
				$handle->image_resize            = true;
				$handle->image_ratio_y           = true;
				$handle->image_x                 = $this->photo_width; //kép szélessége;
				$handle->file_new_name_body = $random_number;
							
				//végrehajtás: kép átmozgatása végleges helyére
				$handle->Process($this->image_path);
				
				$filename = $handle->file_dst_name;

				if ($handle->processed) {
					
					$handle->jpeg_quality 			 = 80;						
					$handle->image_resize            = true;
					$handle->image_ratio_y           = true;
					$handle->image_x                 = $this->thumb_width; // nézőkép szélessége
					$handle->file_new_name_body =  $random_number . '_thumb';
							
					//végrehajtás: kép átmozgatása végleges helyére
					$handle->Process($this->image_path);
					$handle->clean();
					
				} else {
                    Message::set('error', 'Nem sikerült a feltöltés! Hiba: ' . $handle->error);
				return false;
				}
	
			} else {
                Message::set('error', 'Nem sikerült a feltöltés! Hiba: ' . $handle->error);
				return false;
			}		
		}
		
		if ($flag) {
			$data['photo_filename'] = $filename;
		}	
		$data['photo_caption'] = $this->request->get_post('photo_caption');
		$data['photo_category'] = $this->request->get_post('photo_category', 'integer');
		if( $this->request->has_post('photo_slider') ) {
			$data['photo_slider'] = $this->request->get_post('photo_slider', 'integer');
		} else {
			$data['photo_slider'] = 0;
		} 

		$old_img = $this->image_path . $this->request->get_post('old_photo');

		// új adatok beírása az adatbázisba (update) a $data tömb tartalmazza a frissítendő adatokat 
		$this->query->reset();
		$this->query->set_table(array('photo_gallery'));
		$this->query->set_where('photo_id', '=', $id);
		$result = $this->query->update($data);
				
		if($result) {
			if($flag){
				unlink($old_img); 
				unlink(Util::thumb_path($old_img));
			}
            Message::set('success', 'photo_update_success');
			return true;
		}
		else {
            Message::set('error', 'unknown_error');
			return false;
		}

	}
	
    /**
     * Photo törlése AJAX-al
     *
     * @param string $id     ez lehet egy szám, vagy felsorolás pl: 23 vagy 12,14,36
     */
	public function delete_photo_AJAX($id)
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
			
			//lekérdezzük a törlendő kép nevét, hogy törölhessük a szerverről
			$this->query->reset();
			$this->query->set_table('photo_gallery');
			$this->query->set_columns(array('photo_filename'));
			$this->query->set_where('photo_id', '=', $value);
			$photo_name = $this->query->select();			

			// törlése	
			$this->query->reset();
			$this->query->set_table(array('photo_gallery'));
			//a delete() metódus integert (lehet 0 is) vagy false-ot ad vissza
			$result = $this->query->delete('photo_id', '=', $value);
			
			if($result !== false) {
				// ha a törlési sql parancsban nincs hiba
				if($result > 0){
					//ha van feltöltött kép (az adatbázisban szerepel a file-név)
					if(!empty($photo_name[0]['photo_filename'])){
					
						$picture_path = Config::get('photogallery.upload_path') . $photo_name[0]['photo_filename'];
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
					//sikeres törlés
					$success_counter += $result;
					$success_id[] = $value;
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

        // respond tömb visszaadása
        return $respond;	
	} // delete END


/*------------ KATEGÓRIÁK KEZELÉSE ---------------*/

	/**
	 *	Visszaadja a photo_category tábla tartalmát
	 *	Ha kap egy id paramétert (integer), akkor csak egy sort ad vissza a táblából
	 *
	 *	@param $id Integer 
	 */
	public function category_query($id = null)
	{
		$this->query->reset(); 
		$this->query->set_table(array('photo_category')); 
		$this->query->set_columns('*'); 
		if(!is_null($id)){
			$id = (int)$id;
			$this->query->set_where('category_id', '=', $id); 
		}
		return $this->query->select(); 
	}

	/**
	 *	Visszaadja a photo_gallery táblából a photo_category oszlopot
	 */
	public function category_counter_query()
	{
		$this->query->reset(); 
		$this->query->set_table(array('photo_gallery')); 
		$this->query->set_columns('photo_category'); 
		return $this->query->select(); 
	}

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
			$this->query->set_table(array('photo_category'));
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
			$this->query->set_table(array('photo_category'));
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
     * Photo kategória törlése
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
			$this->query->set_table('photo_gallery');
			$this->query->set_columns(array('photo_filename'));
			$this->query->set_where('photo_category', '=', $id);
			$photo_names_temp = $this->query->select();			
			$photo_names = array();
			foreach ($photo_names_temp as $key => $value) {
				$photo_names[] = $value['photo_filename'];
			}
			unset($photo_names_temp);

			// képekhez tartozó rekordok törlése
			$this->query->reset();
			$this->query->set_table(array('photo_gallery'));
			$result = $this->query->delete('photo_category', '=', $id);
			
			// képek törlése
			if($result !== false) {
				if($result > 0){

					foreach($photo_names as $value){
					
						$picture_path = Config::get('photogallery.upload_path') . $value;
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
			$this->query->set_table(array('photo_category'));
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

}
?>