<?php
class Slider_model extends Admin_model {

    private $slider_width; //slider kép szélessége
    private $slider_height; //slider kép magassága
    private $slider_thumb_width; //slider nézőkép szélessége

    /**
     * Constructor, létrehozza az adatbáziskapcsolatot
     */
    function __construct() {
        parent::__construct();
        $this->slider_width = Config::get('slider.width', 1170);
        $this->slider_height = Config::get('slider.height', 420);
        $this->slider_thumb_width = Config::get('slider.thumb_width', 200);
    }

    /**
     * Slide-ok adatainak lekérdezése, a slider_order sorrend szerint
     * 	
     * @return Array (az összes slide minden adata a "slider_order" szerint rendezve)
     */
    public function all_slides_query() {
        $this->query->reset();
        $this->query->set_table(array('slider'));
        $this->query->set_columns();
        $this->query->set_orderby(array('slider_order'));
        return $this->query->select();
    }

    /**
     * 	Egy slide adatait kérdezi le az adatbázisból
     *
     * 	@param	$id  Int (egy id szám)
     * 	@return	Array
     */
    public function one_slide_query($id) {
        $this->query->reset();
        $this->query->set_table(array('slider'));
        $this->query->set_columns(array('id', 'active', 'picture', 'target_url', 'title', 'text'));
        $this->query->set_where('id', '=', $id);
        $result = $this->query->select();
        return $result[0];
    }

    /**
     * Slide adatainak módosítása
     *
     * @param 	$id	Int	
     * @return 	bool
     */
    public function update_slide($id) {
        // új kép mutatója (a false a kezdőértéke)	
        $new_picture = false;

        //ha van új kép feltöltve
        if (isset($_FILES['update_slide_picture'])) {
            // ha a hibakód 4, akkor nem lett kijelölve feltöltendő file (vagyis nem akarunk képet módosítani)
            // ha nem 4-es a hibakód, akkor sikeres, vagy valami gond van a feltöltéssel
            if ($_FILES['update_slide_picture']['error'] != 4) {
                $new_picture = true;

                // kép feltöltése, upload_slider_picture() metódussal (visszatér a feltöltött kép nevével, vagy false-al)
                $dest_image_name = $this->upload_slider_picture($_FILES['update_slide_picture']);

                if ($dest_image_name === false) {
                    return false;
                }
            }
        } else {
            throw new Exception('Hiba slide kep modositasakor: Nem letezik a \$_FILES[\'update_slide_picture\'] elem!');
            return false;
        }

        // adatok beállítása
        $data['active'] = $this->request->get_post('slider_status', 'integer');

        if ($new_picture) {
            $data['picture'] = $dest_image_name;
            // régi kép adatai (ezt használjuk a régi kép törléséhez, ha új kép lett feltöltve)
            $old_img_path = Config::get('slider.upload_path') . $this->request->get_post('old_img');
            $old_thumb_path = Util::thumb_path($old_img_path);
        }

        $data['text'] = htmlentities($this->request->get_post('slider_text'), ENT_QUOTES, "UTF-8");
        $data['title'] = htmlentities($this->request->get_post('slider_title'), ENT_QUOTES, "UTF-8");
        $data['target_url'] = $this->request->get_post('slider_link');

        // új adatok beírása az adatbázisba (update) a $data tömb tartalmazza a frissítendő adatokat 
        $this->query->reset();
        $this->query->set_table(array('slider'));
        $this->query->set_where('id', '=', $id);
        $result = $this->query->update($data);

        // ha sikeres az adatbázisba írás
        if ($result == 1) {
            // megvizsgáljuk, hogy létezik-e új feltöltött kép
            if ($new_picture) {
                //régi képek törlése
                if (!Util::del_file($old_img_path)) {
                    Message::log('A ' . $old_img_path . ' kép nem törlődött!');
                };
                if (!Util::del_file($old_thumb_path)) {
                    Message::log('A ' . $old_thumb_path . ' kép nem törlődött!');
                };
            }
            // sikeres adatbázisba írás és kép feltöltés esetén!!!!
            Message::set('success', 'slide_update_success');
            return true;
        } elseif ($result == 0) {
            Message::set('success', 'Nem történt módosítás!');
            return true;
        } else {
            Message::set('error', 'unknown_error');
            return false;
        }
    }


    /**
     * Sliderek sorrendjének módosítása
     *
     * @param string $order     id=sorszám&id=sorszám....
     * @return array 
     */
    public function slider_order($order)
    {
        // átalakítjuk a stringet tömbre
        parse_str($order, $order_array);
        foreach ($order_array as $id => $new_order) {
            $this->query->reset();
            $this->query->set_table(array('slider'));
            $this->query->set_where('id', '=', $id);
            $this->query->update(array('slider_order' => $new_order));
        }
        return array('status' => 'success');
    }










    /**
     * 	Új slider hozzáadása	
     *
     * 	@return bool
     */
    public function insert_slide() {
        if (isset($_FILES['upload_slide_picture'])) {
            // kép feltöltése, upload_slider_picture() metódussal (visszatér a feltöltött kép elérési útjával, vagy false-al)
            $dest_image_name = $this->upload_slider_picture($_FILES['upload_slide_picture']);

            if ($dest_image_name === false) {
                return false;
            }
        } else {
            throw new Exception('Hiba slide kep feltoltesekor: Nem letezik a \$_FILES[\'upload_slide_picture\'] elem!');
            return false;
        }

        //adatok bevitele az adatbázisba
        $data['active'] = $this->request->get_post('slider_status', 'integer');
        $data['slider_order'] = ($this->slide_highest_order_number()) + 1;
        $data['picture'] = $dest_image_name;
        //$data['target_url'] = "";
        $data['text'] = htmlentities($this->request->get_post('slider_text'), ENT_QUOTES, "UTF-8");
        $data['title'] = htmlentities($this->request->get_post('slider_title'), ENT_QUOTES, "UTF-8");
        $data['target_url'] = $this->request->get_post('slider_link');

        // adatbázis lekérdezés	
        $this->query->reset();
        $this->query->set_table(array('slider'));
        $result = $this->query->insert($data);

        // ha sikeres az insert visszatérési érték true
        if ($result) {
            Message::set('success', 'new_slide_success');
            return true;
        } else {
            Message::set('error', 'unknown_error');
            return false;
        }
    }

                            /**
                             * 	Slide törlése a slider táblából
                             *
                             * 	@param	$id String or Integer
                             * 	@return	tru vagy false
                             */
                            public function delete_slide($id) {
                                // kép nevének lekérdezése
                                $this->query->reset();
                                $this->query->set_table(array('slider'));
                                $this->query->set_columns(array('picture'));
                                $this->query->set_where('id', '=', $id);
                                $result = $this->query->select();

                                $image_name = $result[0]['picture'];
                                $image_path = Config::get('slider.upload_path') . $image_name;
                                $image_thumb_path = Util::thumb_path($image_path);

                                // slider törlése
                                $this->query->reset();
                                $this->query->set_table(array('slider'));
                                $this->query->set_where('id', '=', $id);
                                $result = $this->query->delete();

                                // ha sikeres a törlés 1 a vissaztérési érték
                                if ($result == 1) {
                                    //régi képek törlése
                                    if (!Util::del_file($image_path)) {
                                        Message::log($image_path . ' kép nem törlődött!');
                                    };
                                    if (!Util::del_file($image_thumb_path)) {
                                        Message::log($image_thumb_path . ' kép nem törlődött!');
                                    };

                                    Message::set('success', 'slide_delete_success');
                                    return true;
                                } else {
                                    throw new Exception('Hiba slide torlesekor: a DELETE lekerdezes nem sikerult!');
                                    return false;
                                }
                            }






    /**
     * Slider törlése AJAX-al
     *
     * @param string $id     ez lehet egy szám, vagy felsorolás pl: 23 vagy 12,14,36
     */
    public function delete_slider_AJAX($id)
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
            $this->query->set_table('slider');
            $this->query->set_columns(array('picture'));
            $this->query->set_where('id', '=', $value);
            $photo_name = $this->query->select();           

            //blog törlése  
            $this->query->reset();
            $this->query->set_table(array('slider'));
            //a delete() metódus integert (lehet 0 is) vagy false-ot ad vissza
            $result = $this->query->delete('id', '=', $value);
            
            if($result !== false) {
                // ha a törlési sql parancsban nincs hiba
                if($result > 0){
                    //ha van feltöltött képe a bloghoz (az adatbázisban szerepel a file-név)
                    if(!empty($photo_name[0]['picture'])){
                    
                        $picture_path = Config::get('slider.upload_path') . $photo_name[0]['picture'];
                        $thumb_picture_path = Util::thumb_path($picture_path);
                    
                        $del_result = Util::del_file($picture_path);
                        $del_thumb_result = Util::del_file($thumb_picture_path);
                    
                        //kép file törlése a szerverről (ha az Util::del_file() falsot ad vissza nem tudtuk törölni a képet... hibaüzenet)
                        if(!$del_result){
                            Message::log('A kép nem törölhető! - ' . $picture_path);
                        }
                        //kép file törlése a szerverről (ha az Util::del_file() falsot ad vissza nem tudtuk törölni a képet... hibaüzenet)
                        if(!$del_thumb_result){
                            Message::log('A kép nem törölhető! - ' . $thumb_picture_path);
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
            $respond['message_success'] = 'A slide törölve.';
        }
        if ($fail_counter > 0) {
            $respond['message_error'] = 'A slide-ot már töröltek!';
        }

        // respond tömb visszaadása
        return $respond;    
    }












    /**
     * Meghatározott slider_id-hez feltöltött képek közül kiválasztja azt a sort, amelyben a 
     * legmagasabb a sorrend értéke. 
     *
     * @return int az eddigi legnagyobb sorrend szám
     */
    public function slide_highest_order_number() {
        $this->query->reset();
        $this->query->set_table(array('slider'));
        $this->query->set_columns('MAX(slider_order)');
        $result = $this->query->select();

        return $result[0]['MAX(slider_order)'];
    }

    /**
     * 	Slide képet méretezi és tölti fel a szerverre (thumb képet is)
     * 	(ez a metódus az update_slide() és add_slide() metódusokban hívódik meg!)
     *
     * 	@param	$files_array	Array ($_FILES['valami'])
     * 	@return	String (kép elérési útja) or false
     */
    private function upload_slider_picture($files_array) {
        //include(LIBS . "/upload_class.php");
        // feltöltés helye
        $imagePath = Config::get('slider.upload_path');
        //képkezelő objektum létrehozása (a kép a szerveren a tmp könyvtárba kerül)	
        $handle = new Upload($files_array);
        // fájlneve utáni random karakterlánc
        $suffix = md5(uniqid());

        //file átméretezése, vágása, végleges helyre mozgatása
        if ($handle->uploaded) {
            // kép paramétereinek módosítása
            $handle->file_auto_rename = true;
            $handle->file_safe_name = true;
            $handle->allowed = array('image/*');
            $handle->file_new_name_body = "slide_" . $suffix;
            $handle->image_resize = true;
            $handle->image_x = $this->slider_width; //slider kép szélessége
            $handle->image_y = $this->slider_height; //slider kép magassága
            //$handle->image_ratio_y           = true;
            //képarány meghatározása a nézőképhez
            $ratio = ($handle->image_x / $handle->image_y);

            // Slide kép készítése
            $handle->Process($imagePath);
            if ($handle->processed) {
                //kép elérési útja és új neve (ezzel tér vissza a metódus, ha nincs hiba!)
                //$dest_imagePath = $imagePath . $handle->file_dst_name;
                $dest_image_name = $handle->file_dst_name;
            } else {
                Message::set('error', $handle->error);
                return false;
            }

            // Nézőkép készítése
            //nézőkép nevének megadása (kép új neve utána _thumb)	
            $handle->file_new_name_body = $handle->file_dst_name_body;
            $handle->file_name_body_add = '_thumb';

            $handle->image_resize = true;
            $handle->image_x = $this->slider_thumb_width; //slider nézőkép szélessége
            $handle->image_y = round($handle->image_x / $ratio);
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
        return $dest_image_name;
    }

}
?>