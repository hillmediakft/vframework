<?php
namespace System\Admin\Model;
use System\Core\Admin_model;
use System\Libs\Config;

class Client_model extends Admin_model {

    protected $table = 'clients';
    protected $id = 'client_id';

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
    public function oneClient($id) {
        $id = (int) $id;
        $this->query->set_where('client_id', '=', $id);
        $result = $this->query->select();
        return $result[0];
    }

    /**
     * 	Egy partner minden "nyers" adatát lekérdezi
     * 	A partner módosításához kell (itt az id-kre van szükség, és nem a hozzájuk tartozó névre)	
     */
    public function allClient()
    {
        $this->query->set_orderby(array('client_order'));
        return $this->query->select();
    }

    /**
     * INSERT partner
     */
    public function insert($data)
    {
        return $this->query->insert($data);
    }

    /**
     * UPDATE partner
     */
    public function update($id, $value)
    {
        $this->query->set_where('client_id', '=', $id);
        $result = $this->query->update($value);
    }

    /**
     * DELETE partner
     */
    public function delete($id)
    {
        return $this->query->delete('client_id', '=', $id);        
    }

    /**
     * Lekérdezzük a törlendő kép nevét, hogy törölhessük a szerverről
     */
    public function selectPicture($id)
    {
        $this->query->set_columns(array('client_photo'));
        $this->query->set_where('client_id', '=', $id);
        $result = $this->query->select();
        return $result[0]['client_photo'];
    }

    /**
     * Meghatározott slider_id-hez feltöltött képek közül kiválasztja azt a sort, amelyben a 
     * legmagasabb a sorrend értéke. 
     *
     * @return int az eddigi legnagyobb sorrend szám
     */
    public function highest_order_number()
    {
        $this->query->set_columns('MAX(client_order)');
        $result = $this->query->select();
        return $result[0]['MAX(client_order)'];
    }

    /**
     * Sorrend módosítása
     */
    public function order($id, $new_order)
    {
        $this->query->set_where('client_id', '=', $id);
        $this->query->update(array('client_order' => $new_order));        
    }

    /**
     * Crew member képének vágása és feltöltése
     * Az $this->request->get_params('id') paraméter értékétől függően feltölti a kiválasztott képet
     * upload paraméter esetén: feltölti a kiválasztott képet
     * crop paraméter esetén: megvágja a kiválasztott képet és feltölti	
     *
     */
    public function client_img_upload() {
        if ($this->request->has_params('id')) {

            //include(LIBS . "/upload_class.php");

            // Kiválasztott kép feltöltése
            if ($this->request->get_params('id') == 'upload') {

                // feltöltés helye
                $imagePath = Config::get('clientphoto.upload_path');

                //képkezelő objektum létrehozása (a kép a szerveren a tmp könyvtárba kerül)	
                $handle = new \System\Libs\Upload($_FILES['img']);

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
                $handle = new \System\Libs\Upload($imgUrl);

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

}
?>