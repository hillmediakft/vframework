<?php
namespace System\Core;

class SiteController extends Controller {

	/**
	 * Minden site oldalon megjelenő adatokat tartalmazó tömb
	 */
    protected $global_data = array();

    /**
     * Minden site controllerben elérhető, és a nyelvi kódot tartalmazza
     */
    protected $lang = LANG;
    
    /**
     * Minden site oldali controllerben lefut
     */
    public function __construct()
    {
        parent::__construct();

        // settings betöltése és hozzárendelése a controllereken belül elérhető a global_data változóhoz
        $this->loadModel('settings_model');
        $this->global_data['settings'] = $this->settings_model->get_settings();
    }
}
?>