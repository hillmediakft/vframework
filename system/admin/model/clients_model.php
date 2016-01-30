<?php

class Clients_model extends Model {

    /**
     * Constructor, létrehozza az adatbáziskapcsolatot
     */
    function __construct() {
        parent::__construct();
    }

    /**
     * 	Egy kolléga minden "nyers" adatát lekérdezi
     * 	A kolléga módosításához kell (itt az id-kre van szükség, és nem a hozzájuk tartozó névre)	
     */
    public function one_client_query($id) {
        $id = (int) $id;
        $this->query->reset();
        $this->query->set_table(array('clients'));
        $this->query->set_columns('*');
        $this->query->set_where('client_id', '=', $id);
        $result = $this->query->select();
        return $result[0];
    }

    /**
     * 	Egy partner minden "nyers" adatát lekérdezi
     * 	A partner módosításához kell (itt az id-kre van szükség, és nem a hozzájuk tartozó névre)	
     */
    public function all_client_query() {

        $this->query->reset();
        $this->query->set_table(array('clients'));
        $this->query->set_columns('*');
        return $this->query->select();
    }

    /**
     * 	Partner hozzáadása
     */
    public function insert_client() {
        $data = $this->request->get_post();
        unset($data['submit_new_client']);
        $error_counter = 0;
        //megnevezés ellenőrzése	
        if (empty($data['client_name'])) {
            $error_counter++;
            Message::set('error', 'A partner neve nem lehet üres!');
        }
        if (empty($data['img_url'])) {
            $error_counter++;
            Message::set('error', 'töltsön fel logót!');
        }

        if (isset($data['img_url']) && $data['img_url'] != '') {
            $data['client_photo'] = $data['img_url'];
        }
        unset($data['img_url']);


        if ($error_counter == 0) {

            // új adatok az adatbázisba
            $this->query->reset();
//            $this->query->debug(true);
            $this->query->set_table(array('clients'));
            $this->query->insert($data);

            Message::set('success', 'Partner sikeresen hozzáadva.');
            return true;
        } else {
            // nem volt minden kötelező mező kitöltve
            return false;
        }
    }

    /**
     * 	Partner módosítása
     *
     * 	@param integer	$id
     */
    public function update_client($id) {
        $data = $this->request->get_post();
        unset($data['submit_update_client']);
        $id = (int) $id;

        $error_counter = 0;
        //megnevezés ellenőrzése	
        if (empty($data['client_name'])) {
            $error_counter++;
            Message::set('error', 'A partner neve nem lehet üres!');
        }
        if (empty($data['client_link'])) {
            $error_counter++;
            Message::set('error', 'Adjon meg linket!');
        }


        if ($error_counter == 0) {
            if (isset($data['img_url']) && $data['img_url'] != '') {
                $data['client_photo'] = $data['img_url'];
                unset($data['img_url']);
            } else {
                $data['client_photo'] = $data['old_img'];
                unset($data['img_url']);
            }
            $old_img_name = $data['old_img'];
            unset($data['old_img']);

            // új adatok az adatbázisba
            $this->query->reset();
            $this->query->set_table(array('clients'));
            $this->query->set_where('client_id', '=', $id);
            $result = $this->query->update($data);

            if ($result >= 0) {
                // megvizsgáljuk, hogy létezik-e új feltöltött kép és a régi kép, nem a default
                if ($this->request->has_post('img_url')) {
                    //régi képek törlése
                    if (!Util::del_file($old_img_name)) {
                        Message::set('error', 'unknown_error');
                    }
                }
                Message::set('success', 'Partner adatai módosítva!');
                return true;
            }
        } else {
            // ha valamilyen hiba volt a form adataiban
            return false;
        }
    }

    /**
     * 	Partner törlése
     */
    public function delete_client($id) {
        // a sikeres törlések számát tárolja
        $success_counter = 0;
        // a sikertelen törlések számát tárolja
        $fail_counter = 0;

        $image_to_delete = $this->get_client_image_name($id);

        //felhasználó törlése	
        $this->query->reset();
        $this->query->set_table(array('clients'));
        //a delete() metódus integert (lehet 0 is) vagy false-ot ad vissza
        $result = $this->query->delete('client_id', '=', $id);

        if ($result !== false) {
            // ha a törlési sql parancsban nincs hiba
            if ($result > 0) {
                //sikeres törlés
                if (!Util::del_file($image_to_delete['client_photo'])) {
                    Message::set('error', 'Kép nem törölhető!');
                }
                $success_counter += $result;
            } else {
                //sikertelen törlés
                $fail_counter += 1;
            }
        } else {
            // ha a törlési sql parancsban hiba van
            throw new Exception('Hibas sql parancs: nem sikerult a DELETE lekerdezes az adatbazisbol!');
            return false;
        }

        // üzenetek eltárolása
        if ($success_counter > 0) {
            Message::set('success', $success_counter . ' partner törlése sikerült.');
        }
        if ($fail_counter > 0) {
            Message::set('error', $fail_counter . ' partner törlése nem sikerült!');
        }

        // default visszatérési érték (akkor tér vissza false-al ha hibás az sql parancs)	
        return true;
    }

    /**
     * Crew member képének vágása és feltöltése
     * Az $this->request->get_params('id') paraméter értékétől függően feltölti a kiválasztott képet
     * upload paraméter esetén: feltölti a kiválasztott képet
     * crop paraméter esetén: megvágja a kiválasztott képet és feltölti	
     *
     */
    public function client_img_upload() {
        if (null !== $this->request->get_params('id')) {

            include(LIBS . "/upload_class.php");

            // Kiválasztott kép feltöltése
            if ($this->request->get_params('id') == 'upload') {

                // feltöltés helye
                $imagePath = Config::get('clientphoto.upload_path');

                //képkezelő objektum létrehozása (a kép a szerveren a tmp könyvtárba kerül)	
                $handle = new Upload($_FILES['img']);

                if ($handle->uploaded) {
                    // kép paramétereinek módosítása
                    $handle->file_auto_rename = true;
                    $handle->file_safe_name = true;
                    //$handle->file_new_name_body   	 = 'lorem ipsum';
                    $handle->allowed = array('image/*');
                    $handle->image_resize = true;
                    $handle->image_x = Config::get('clientphoto.width', 150);
                    $handle->image_ratio_y = true;

                    //végrehajtás: kép átmozgatása végleges helyére
                    $handle->Process($imagePath);

                    if ($handle->processed) {
                        //temp file törlése a szerverről
                        $handle->clean();

                        $response = array(
                            "status" => 'success',
                            //"url" => $handle->file_dst_name,
                            "url" => $imagePath . $handle->file_dst_name,
                            "width" => $handle->image_dst_x,
                            "height" => $handle->image_dst_y
                        );
                        return json_encode($response);
                    } else {
                        $response = array(
                            "status" => 'error',
                            "message" => $handle->error . ': Can`t upload File; no write Access'
                        );
                        return json_encode($response);
                    }
                } else {
                    $response = array(
                        "status" => 'error',
                        "message" => $handle->error . ': Can`t upload File; no write Access'
                    );
                    return json_encode($response);
                }
            }


            // Kiválasztott kép vágása és vágott kép feltöltése
            if ($this->request->get_params('id') == 'crop') {

                // a croppic js küldi ezeket a POST adatokat 	
                $imgUrl = $_POST['imgUrl'];
                // original sizes
                $imgInitW = $_POST['imgInitW'];
                $imgInitH = $_POST['imgInitH'];
                // resized sizes
                //kerekítjük az értéket, mert lebegőpotos számot is kaphatunk és ez hibát okozna a kép generálásakor
                $imgW = round($_POST['imgW']);
                $imgH = round($_POST['imgH']);
                // offsets
                // megadja, hogy mennyit kell vágni a kép felső oldalából
                $imgY1 = $_POST['imgY1'];
                // megadja, hogy mennyit kell vágni a kép bal oldalából
                $imgX1 = $_POST['imgX1'];
                // crop box
                $cropW = $_POST['cropW'];
                $cropH = $_POST['cropH'];
                // rotation angle
                //$angle = $_POST['rotation'];
                //a $right_crop megadja, hogy mennyit kell vágni a kép jobb oldalából
                $right_crop = ($imgW - $imgX1) - $cropW;
                //a $bottom_crop megadja, hogy mennyit kell vágni a kép aljából
                $bottom_crop = ($imgH - $imgY1) - $cropH;

                // feltöltés helye
                $imagePath = Config::get('clientphoto.upload_path');

                //képkezelő objektum létrehozása (a feltöltött kép elérése a paraméter)	
                $handle = new Upload($imgUrl);

                // fájlneve utáni random karakterlánc
                $suffix = md5(uniqid());

                if ($handle->uploaded) {

                    // kép paramétereinek módosítása
                    //$handle->file_auto_rename 		 = true;
                    //$handle->file_safe_name 		 = true;
                    //$handle->file_name_body_add   	 = '_thumb';
                    $handle->file_new_name_body = "client_" . $suffix;
                    //kép átméretezése
                    $handle->image_resize = true;
                    $handle->image_x = $imgW;
                    $handle->image_ratio_y = true;
                    //utána kép vágása
                    $handle->image_crop = array($imgY1, $right_crop, $bottom_crop, $imgX1);

                    //végrehajtás: kép átmozgatása végleges helyére
                    $handle->Process($imagePath);

                    if ($handle->processed) {
                        // vágatlan forrás kép törlése az upload/user_photo mappából
                        $handle->clean();

                        $response = array(
                            "status" => 'success',
                            //"url" => $handle->file_dst_name
                            "url" => $imagePath . $handle->file_dst_name
                        );
                        return json_encode($response);
                    } else {
                        $response = array(
                            "status" => 'error',
                            "message" => $handle->error . ': Can`t upload File; no write Access'
                        );
                        return json_encode($response);
                    }
                } else {
                    $response = array(
                        "status" => 'error',
                        "message" => $handle->error . ': Can`t upload File; no write Access'
                    );
                    return json_encode($response);
                }
            }
        }
    }

    /**
     * 	Partnerhez tartozó kép elérési útvonalának lekérdezése - partner törléséhez
     * 	@para   integer $id partner id-je
     * @return string törlendő kép elérési útvonala	
     */
    public function get_client_image_name($id) {
        $id = (int) $id;
        $this->query->reset();
        $this->query->set_table(array('clients'));
        $this->query->set_columns('client_photo');
        $this->query->set_where('client_id', '=', $id);
        $result = $this->query->select();
        return $result[0];
    }

}

?>