<?php
namespace System\Admin\Model;
use System\Core\Admin_model;
use System\Libs\Message;
use System\Libs\Config;

class Slider_model extends Admin_model {

    protected $table = 'slider';

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
    public function allSlides()
    {
        $this->query->set_orderby(array('slider_order'));
        return $this->query->select();
    }

    /**
     * 	Egy slide adatait kérdezi le az adatbázisból
     *
     * 	@param	$id  Int (egy id szám)
     * 	@return	Array
     */
    public function oneSlide($id)
    {
        $this->query->set_columns(array('id', 'active', 'picture', 'target_url', 'title', 'text'));
        $this->query->set_where('id', '=', $id);
        $result = $this->query->select();
        return $result[0];
    }

    /**
     * INSERT
     */
    public function insert($data)
    {
        return $this->query->insert($data);        
    }

    /**
     * UPDATE
     */
    public function update($id, $data)
    {
        $this->query->set_where('id', '=', $id);
        return $this->query->update($data);
    }

    /**
     * DELETE
     */
    public function delete($id)
    {
        return $this->query->delete('id', '=', $id);        
    }

    /**
     * Sliderek sorrendjének módosítása
     *
     * @param integer $id
     * @param integer $new_order
     * @return array 
     */
    public function orderUpdate($id, $new_order)
    {
        $this->query->set_where('id', '=', $id);
        return $this->query->update(array('slider_order' => $new_order));
    }

    /**
     * Egy rekordhoz tartozó Kép nevének lekérdezése
     */
    public function selectPicture($id)
    {
        $this->query->set_columns(array('picture'));
        $this->query->set_where('id', '=', $id);
        $result = $this->query->select();         
        return $result[0]['picture'];
    }

    /**
     * Meghatározott slider_id-hez feltöltött képek közül kiválasztja azt a sort, amelyben a 
     * legmagasabb a sorrend értéke. 
     *
     * @return int az eddigi legnagyobb sorrend szám
     */
    public function slide_highest_order_number()
    {
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
    public function upload_slider_picture($files_array) {
        // feltöltés helye
        $imagePath = Config::get('slider.upload_path');
        //képkezelő objektum létrehozása (a kép a szerveren a tmp könyvtárba kerül)	
        $handle = new \System\Libs\Upload($files_array);
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