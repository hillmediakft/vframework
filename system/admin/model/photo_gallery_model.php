<?php 
class Photo_gallery_model extends Admin_model {

	private $photo_width;
	private $thumb_width;

	/**
     * Constructor, létrehozza az adatbáziskapcsolatot
     */
	function __construct()
	{
		parent::__construct();
		$this->photo_width = Config::get('photogallery.width', 800);
		$this->thumb_width = Config::get('photogallery.thumb_width', 320);
	}
	
	/**
	 * Az összes kép lekérdezése
	 *
	 * @return az összes kép adatai tümbben
	 */
	public function all_photos()
	{
		// a query tulajdonság ($this->query) tartalmazza a query objektumot
		$this->query->set_table(array('photo_gallery')); 
		$this->query->set_columns(array('photo_id','photo_filename', 'photo_caption', 'photo_category','photo_slider')); 
		$result = $this->query->select(); 

		return $result;
	}
	

	/**
	 * Fotó hozzáadása, kép feltötése és adatok mentése
	 *
	 * @return boolean - true ha sikeres a mentés, false ha hiba történt
	 */
	public function save_photo()
	{
		// ******************* kép feltöltése ************************** //
		
		include(LIBS . "/upload_class.php");
		// feltöltés helye
		$imagePath = UPLOADS . "photo_gallery/";
		//képkezelő objektum létrehozása (a kép a szerveren a tmp könyvtárba kerül)	
		$handle = new Upload($_FILES['upload_gallery_photo']);

				if ($handle->uploaded) {
				
					$handle->allowed = array('image/*');
					
					$random_number = md5(date('Y-m-d H:i:s:u'));
				    
					$handle->image_resize            = true;
					$handle->image_ratio_y           = true;
					$handle->image_x                 = $this->photo_width; //kép szélessége;
					$handle->file_new_name_body = $random_number;
								
					//végrehajtás: kép átmozgatása végleges helyére
					$handle->Process($imagePath);
					
					$filename = $handle->file_dst_name;
					

					if ($handle->processed) {
												
						$handle->image_resize            = true;
						$handle->image_ratio_y           = true;
						$handle->image_x                 = $this->thumb_width; // nézőkép szélessége
						$handle->file_new_name_body =  $random_number . '_thumb';
								
						//végrehajtás: kép átmozgatása végleges helyére
						$handle->Process($imagePath);
						
						
						$handle->clean();
						
					} else {
                        Message::set('error', 'Nem sikerült a feltöltés! Hiba: ' . $handle->error);
					return false;
					}

						
		
				} else {
                    Message::set('error', 'Nem sikerült a feltöltés! Hiba: ' . $handle->error);
					return false;
				}
		
		
		
		$data['photo_filename'] =  UPLOADS . 'photo_gallery/' . $filename;
		$data['photo_caption'] = $this->request->get_post('photo_caption');
		$data['photo_category'] = $this->request->get_post('photo_category');
		if( $this->request->has_post('photo_slider') ) {
			$data['photo_slider'] = $this->request->get_post('photo_slider');
		} else {
			$data['photo_slider'] = 0;
		}	
				
		$this->query->reset();
		$this->query->set_table(array('photo_gallery'));
		$result = $this->query->insert($data);
	
		// ha sikeres az insert visszatérési érték true
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
	 *
	 * @param 	int $id	
	 * @return 	true vagy false
	 */
	public function update_photo($id)
	{
		$flag = false;
		
	
		if(isset($_FILES['upload_gallery_photo'])  && $_FILES['upload_gallery_photo']['tmp_name'] != '') {
		
		// ******************* kép feltöltése ************************** //
		$flag = true;
		include(LIBS . "/upload_class.php");
		// feltöltés helye
		$imagePath = UPLOADS . "photo_gallery/";
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
					$handle->Process($imagePath);
					
					$filename = $handle->file_dst_name;

					$data['photo_filename']=  UPLOADS . 'photo_gallery/' . $filename;

					if ($handle->processed) {
						
						$handle->jpeg_quality 			 = 80;						
						$handle->image_resize            = true;
						$handle->image_ratio_y           = true;
						$handle->image_x                 = $this->thumb_width; // nézőkép szélessége
						$handle->file_new_name_body =  $random_number . '_thumb';
								
						//végrehajtás: kép átmozgatása végleges helyére
						$handle->Process($imagePath);
						
						
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
		

		$data['photo_caption'] = $this->request->get_post('photo_caption');
		$data['photo_category'] = $this->request->get_post('photo_category');
		if( $this->request->has_post('photo_slider') ) {
			$data['photo_slider'] = $this->request->get_post('photo_slider');
		} else {
			$data['photo_slider'] = 0;
		} 

		$old_img = $this->request->get_post('old_photo');
/*		
		var_dump($id);
		echo 'POST<br>';
		var_dump($_POST);
		echo 'data:<br>';
		var_dump($data);
		die();
*/
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
	 *	Kép törlése a photo_gallery táblából
	 *
	 *	@param	$id String or Integer
	 *	@return	boolean
	 */
	public function delete_photo($id)
	{
		$this->query->reset();
		$this->query->set_table(array('photo_gallery'));
		$this->query->set_columns(array('photo_filename')); 
		$this->query->set_where('photo_id', '=', $id);
		$result = $this->query->select();
		

		$image = $result[0]['photo_filename'];
		$image_thumb = Util::thumb_path($image);

		$this->query->reset();
		$this->query->set_table(array('photo_gallery'));
		$this->query->set_where('photo_id', '=', $id);
		$result = $this->query->delete();

		// ha sikeres a törlés 1 a vissaztérési érték
		if($result == 1) {
		
			unlink($image); 
			unlink($image_thumb);
		
            Message::set('success', 'photo_delete_success');
			return true;
		}
		else {
            Message::set('error', 'unknown_error');
			return false;
		}
	}
	

	
	/**
	 *	Egy kép adatait kérdezi le az adatbázisból (photo_gallery tábla)
	 *
	 *	@param	$id a kép rekordjának azonosítója
	 *	@return	az adatok tömbben
	 */
	public function photo_data_query($id)
	{
		$this->query->reset();
		$this->query->set_table(array('photo_gallery'));
		$this->query->set_columns(array('photo_id', 'photo_filename', 'photo_caption', 'photo_category', 'photo_slider'));
		$this->query->set_where('photo_id', '=', $id);
		
		return $this->query->select();
	}
	
}
?>