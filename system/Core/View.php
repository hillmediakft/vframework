<?php
namespace System\Core;
use System\Libs\DI;
use System\Libs\Config;
use System\Libs\Auth;

class View {
	
	private $request;
	
	/**
	 *	Az aktuális terület neve (pl.: site vagy admin)
	 *	@var string
	 */
	private $area = '';
	
	/**
	 *	Betöltendő  template mappájának a neve
	 *	@var string
	 */
	private $dirname = '';

	/**
	 *	Betöltendő template file neve (kiterjesztés nélkül)
	 *	@var string	 
	 */
	private $filename = '';

	/**
	 *	Betöltendő template file eleresi utja: mappa/file
	 *	@var string	 
	 */
	private $view_path = '';
    
	/**
	 *	layout sablon pl.: layout1
	 *	@var string	 
	 */
	private $layout = null;
    
	/**
	 *	Ebbe a tömbbe kerülnek az adatok, amit berakunk a template-ekbe
	 *	a set() metódussal adjuk meg az adatokat
	 *	@var array	 
	 */
	private $vars = array();

	/**
	 * Kimeneti pufferelés kapcsolója
	 */
	private $lazy_render = false;

	/**
	 *	Ebbe a tömbbe kerülnek a css linkkek
	 *	@var css_link array	 
	 */
	private $css_link = array();

	/**
	 *	Ebbe a tömbbe kerülnek a javascript linkek
	 *	@var js_link array	 
	 */
	private $js_link = array();

	/**
	 * A (linkeket tartalamazó) külső file-ból behívott adatokat tartalamazza
	 */
	private $modul_link = array();

	/**
	 * Debug flag
	 */
	private $debug_flag = false;
	
/*-----------------------------------------------------------------------------*/

	/**
	 *	Constructor
	 */
	public function __construct()
	{
		// request objektum
		$this->request = DI::get('request');
		$this->area = $this->request->get_uri('area');
		// linkek tömb behívása
		$this->modul_link = include_once(CONFIG . '/links_' . $this->area . '.php');
		// default layout
		if ($this->area == 'site') {
			$layout = Config::get('layout.default_site', null);
		} elseif ($this->area == 'admin') {
			$layout = Config::get('layout.default_admin', null);
		}
		$this->set_layout($layout);
	}
	
	/**
	 * Nem definiált adattagok automatikus létrehozása (pl.: $objektum->valtozo_neve = 'lorem ipsum')
	 * Az új adattaghoz rendelt értékek a $vars tömbbe kerülnek (ebből is ki lehet nyerni az adatokat)
	 */
	public function __set($name,$value)
	{
		$this->vars[$name] = $value;
	}

	/**
	 *	Visszaadja a __set metódussal létrehozott adattagok értékeit (automatikusan)
	 *	pl.: echo $objektum->valtozo_neve;
	 */
	public function __get($name)
	{
		return $this->vars[$name];
	}
			
	/**
	 * Debug lefuttatása
	 */
	private function _runDebug($data, $par1 = true)
	{
		echo <<<HTML
			<!DOCTYPE html>
			<html lang="hu">
			<head>
				<meta http-equiv="content-type" content="text/html; charset=utf-8">
				<title>DEBUG VARS</title>
			</head>
			<body>
HTML;
		echo "<h2>A template változói: </h2><pre style='background:#DDDDDD; border:1px solid #AAAAAA; padding:10px'>";
		print_r($data);
		echo "</pre>";
		echo "<h2>CSS linkek: </h2><div style='background:#DDDDDD; border:1px solid #AAAAAA; padding:10px'>";
			var_dump($this->css_link);
		echo "</div>";
		echo "<h2>JS linkek: </h2><div style='background:#DDDDDD; border:1px solid #AAAAAA; padding:10px'>";
			var_dump($this->js_link);
		echo "</div></body></html>";
		die();			
	}


		/**
		 * HIBAKERESÉSHEZ!
		 * Megjeleníti a $vars tömb elemeit, a css és javascript linkeket, utána megállítja a program futását!
		 *
		 * @param	bool	ha értéke true, akkor csak a tömbelemek nevét írja ki
		 */
		public function debug($flag = true)
		{
			$this->debug_flag = $flag;		
		}			
 
		/**
		 * Elérési utat adja vissza sima include-hoz !!!
		 */
		public function path($filename)
		{
	    	if($filename == 'content'){
	    		$filename = $this->filename;
	    	}					

			// Elérési út megadása sima Include-hoz
	    	try{

				if (file_exists('system/' . ucfirst($this->area) . '/view/' . $this->dirname . '/' . $filename . '.php')) {
					$file_path = 'system/' . ucfirst($this->area) . '/view/' . $this->dirname . '/' . $filename . '.php';
				}
				elseif (file_exists('system/' . ucfirst($this->area) . '/view/_template/' . $filename . '.php')) {
					$file_path = 'system/' . ucfirst($this->area) . '/view/_template/' . $filename . '.php';
				}
				else {
					throw new \Exception('A ' . $filename . '.php template file nem toltheto be!');
				}

			} catch (\Exception $e)  {
				die($e->getMessage());
			}

			return $file_path;	
		}	

			    /**
			     * Tartalmi elem betöltése
			     *
			     * @param string $template  	file neve kiterjesztés nélkül (az egyedi "tartalmi" elem esetén értéke 'content' vagy $this->filename )
			     */
			/*
			    public function load($template)
			    {
			    	if($template == 'content'){
			    		$template = $this->filename;
			    	}

			    	try{

						if (file_exists('system/' . $this->area . '/view/' . $this->dirname . '/' . $template . '.php')) {
							include('system/' . $this->area . '/view/' . $this->dirname . '/' . $template . '.php');
						}
						elseif (file_exists('system/' . $this->area . '/view/_template/' . $template . '.php')) {
							include('system/' . $this->area . '/view/_template/' . $template . '.php');
						}
						else {
							throw new \Exception('A ' . $template . '.php template file nem toltheto be!');
						}

					} catch (\Exception $e)  {
						die($e->getMessage());
					}
			    }
			*/
    
    /**
     * Layout nevének megadása (egyébként a default értéke null)
     */
    public function set_layout($layout)
    {
    	$this->layout = $layout;
    }

    /**
     * Kimeneti pufferelés kapcsolóját állítja
     *
     * @param bool $flag
     */
    public function setLazyRender($flag = true)
    {
    	$this->lazy_render = (bool) $flag;
    }

    /**
	 * HTML template betöltése
	 * Ha ezen metódus meghívása előtt megadjunk layout fájlt a set_layout() metódusal, akkor azt tölti be,
	 * ha nincs layout megadva, akkor csak a paraméterben megadott template fájlt tölti be 
	 *
	 * @param string $view_path 		mappa/fileneve kiterjesztés nélkül (pl.: home/tpl_home)	 
	 */
    public function render($view_path, $data = array())
	{
		$this->view_path = $view_path;
		unset($view_path);

		try{
			// megnézzük, hogy a $this->view_path stringben van e / jel (ha a strpos() függvény nem false-ot ad vissza, akkor van benne / jel)
			if (strpos($this->view_path,'/') !== false) {
				// felbontjuk a $this->view_path stringet a / jel mentén, és az első elemet (a mappa nevét) vátozóhoz rendeljük
				list($this->dirname, $this->filename) = explode('/', $this->view_path);
			}
			else {
				throw new \Exception('Hibas parameteratadas a view objektum render() fuggvenyenek <br /> A ' . $this->view_path . '.php template file nem nyithato meg!');
			}		
		} catch (\Exception $e) {
			die($e->getMessage());
		}

		// A template-be kerülő (tömb) adatok változókká bontása
		if(!empty($data)) {
			extract($data, EXTR_PREFIX_SAME, "wddx");
		}

			// DEBUG elindítása
			if ($this->debug_flag) {
				$this->_runDebug($data);
			}

		if ($this->lazy_render) {
			ob_start();
		}	

		// INCLUDE - ha be van állítva layout template
		if(!is_null($this->layout)) {
	        try{
				if (file_exists('system/' . ucfirst($this->area) . '/view/' . $this->dirname . '/' . $this->layout . '.php')) {
					// ha van helyi layout
					include('system/' . ucfirst($this->area) . '/view/' . $this->dirname . '/' . $this->layout . '.php');
				}
				else if (file_exists('system/' . ucfirst($this->area) . '/view/_template/' . $this->layout . '.php')) {
					// template layout
					include('system/' . ucfirst($this->area) . '/view/_template/' . $this->layout . '.php');
				}
				else {
					throw new \Exception('A ' . $this->layout . '.php template file nem toltheto be!');
				} 
	        } catch (\Exception $e) {
	        	die($e->getMessage());
	        }
			
		}
		// INCLUDE - ha nincs beállítva layout template
		else {
			try{
				if (file_exists('system/' . ucfirst($this->area) . '/view/' . $this->dirname . '/' . $this->filename . '.php')) {
					include('system/' . ucfirst($this->area) . '/view/' . $this->dirname . '/' . $this->filename . '.php');
				}
				elseif (file_exists('system/' . ucfirst($this->area) . '/view/_template/' . $this->filename . '.php')) {
					include('system/' . ucfirst($this->area) . '/view/_template/' . $this->filename . '.php');
				}
				else {
					throw new \Exception('A ' . $this->filename . '.php template file nem toltheto be!');
				}
			} catch (\Exception $e)  {
				die($e->getMessage());
			}
		}

		if ($this->lazy_render) {
			return ob_get_clean();
		}
    }
	
    /**
     * Üzeneteket tartalmazó template behívása
     */
    public function renderFeedbackMessages()
    {
		// echo out the feedback messages
		require 'system/' . ucfirst($this->area) . '/view/_template/feedback.php';
	}

	/**
	 * Adat visszaadása a config-ból
	 * @param string $key
	 * @param mixed $default
	 */
	public function getConfig($key, $default = null)
	{
		return Config::get($key, $default);
	}

	/**
	 * Helperek példányosítása
	 * @param array $helpers
	 */
	public function setHelper(array $helpers)
	{
		foreach ($helpers as $helper) {
			if (!isset($this->$helper)) {
				$this->$helper = DI::get($helper);
			}
		}
	}

				/**
				 * Jogosultság ellenőrzése a template-ben
				 * @param array $permission
				 */
				public function hasAccess($permission)
				{
					return Auth::hasAccess($permission, null);
				}

	/**
	 * Template menü elem class-t active-ra állítja, ha a controller és az action a megadott paraméterekkel egyezik
	 *
	 * @param string $controller 			- vizsgálandó controller neve illetve nevek 
	 * @param string $action 				- action neve illetve nevek
	 * @param string $attribute_name 		- html elem class neve
	 */		
	public function menu_active($controller, $action = null, $attribute_name = 'active')
	{
		$active_controller = strtolower( $this->request->get_controller() );
		$active_action = strtolower( $this->request->get_action() );

		// ha csak controller van megadva paraméterként
		if (!is_null($controller) && is_null($action)) {
			
			$controller = explode('|', $controller);
			// megnézzük, hogy az aktuális controller neve benne van-e a $controller tömbben 
			if (in_array($active_controller, $controller)) {
				echo $attribute_name;
			}
			return;
		}

		// ha csak action van megadva paraméterként
		elseif (is_null($controller) && !is_null($action)) {
			
			$action = explode('|', $action);
			// megnézzük, hogy az aktuális action neve benne van-e a $action tömbben 
			if (in_array($active_action, $action)) {
				echo $attribute_name;
			}
			return;
		}

		// ha controller és action is meg van adva
		elseif (!is_null($controller) && !is_null($action)) {

			$controller = explode('|', $controller);
			$action = explode('|', $action);

			if (in_array($active_controller, $controller) && in_array($active_action, $action)) {
				echo $attribute_name;
			}
			return;
		}
	}

	/**
	 * modul linkek hozzáadása
	 *
	 * @param array $modul_names
	 */
	public function add_links($modul_names)
	{
		foreach ($modul_names as $modul) {

			if(isset($this->modul_link[$modul])){
				
				foreach ($this->modul_link[$modul] as $link_type => $link_value) {
					// ha a kulcs 'css' és az értéke nem tömb
					if ($link_type == 'css') {
						if (!is_array($link_value)) {
							$this->_set_css_link($link_value);
						} else {
							foreach ($link_value as $css_link) {
								$this->_set_css_link($css_link);
							}
						}
					}
					// ha a kulcs 'js' és az értéke nem tömb
					if ($link_type == 'js') {
						if (!is_array($link_value)) {
							$this->_set_js_link($link_value);
						} else {
							foreach ($link_value as $js_link) {
								$this->_set_js_link($js_link);
							}
						}
					}
				}

			}

		}
	}

    /**
     * 	egyedi link hozzáadása
     *
     * 	@param	string	$type	a link/script típusa: css vagy js
     * 	@param	string	$path	a link útvonala, ami állandókban van (pl.: ADMIN_CSS, SITE_ASSETS)
     * 	@param	string	$link	a link további útvonala (pl.: plugins/data-tables/DT_bootstrap.css)
     */
    public function add_link($type, $link)
    {
        switch ($type) {
            case 'css':
                $this->_set_css_link($link);
                break;
            case 'js':
                $this->_set_js_link($link);
                break;
        }
    }

    /**
     * link hozzáadása a css_link tömbhöz
     */
    private function _set_css_link($link)
    {
    	$this->css_link[] = '<link rel="stylesheet" href="' . $link . '" type="text/css" />' . "\r\n";
    }

    /**
     * link hozzáadása a js_link tömbhöz
     */
    private function _set_js_link($link)
    {
    	$this->js_link[] = '<script type="text/javascript" src="' . $link . '"></script>' . "\r\n";
    }

    /**
     * js liknek kiíratása
     */
    public function get_js_link()
    {
        foreach ($this->js_link as $value) {
            echo $value;
        }
    }

    /**
     * css linkek kiíratása
     */
    public function get_css_link()
    {
        foreach ($this->css_link as $value) {
            echo $value;
        }
    }

} // end class
?>