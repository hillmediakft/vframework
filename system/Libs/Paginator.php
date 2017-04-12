<?php

namespace System\Libs;

/**
 * 	Paginator class v1.0
 *
 * 	Használat (példa):
 * 		 include(pagine_class.php');
 *
 *    paginátor objektum létrehozása (GET paraméter neve, limit)
 *  	 $pagine = new Paginator('oldal', 9);
 *
 *    adatok lekérdezése az adatbázisból
 *       $data = lekérdező_metódus($pagine->get_limit(), $pagine->get_offset());
 *
 *    összes rekord száma az adatbázisban VAGY a szűrési feltételeknek megfelelő összes rekord száma (ha van szűrés)
 *       $összes_eredmény_szama = $count_query();
 *    VAGY 
 * 		 $szűrt_eredmények_szama = $filter_count_query();
 *
 *    összes elem számának megadása (paraméter az összes elem, szűrésnél a szűrt elemek száma)     
 * 		 $pagine->set_total($összes_vagy_szűrt_eredmények_szama);
 *
 *    lapozó linkek html
 *       $this->view->pagine_links = $pagine->page_links($uri_path);
 */
class Paginator {

    /**
     * 	GET paraméter neve pl.: oldal (?oldal=5)
     */
    private $pagename;

    /**
     * 	egyszerre ennyi elem jelenik meg az oldalon
     */
    private $limit;

    /**
     * 	ettől a sorszámtól kezdi kiveni a sorokat az adatbázisból
     */
    private $offset;

    /**
     * 	A jelenlegi oldal mindkét oldalán hány elem legyen az oldalszámozásnál
     */
    private $stages;

    /**
     * 	sorok száma a táblában (max ennyi oldal lehet, ha egy oldalon csak egy elem van)
     */
    private $total_pages;

    /**
     * 	oldal száma
     */
    private $page_id;

    /**
     * 	query string
     */
    private $querystring;

    /**
     * CONSTRUCTOR
     *
     * @param  string   $pagename 	GET paraméter neve ami a lapozáshoz kapcsolódik
     * @param  integer  $limit 		egyszerre ennyi elem jelenik meg az oldalon	
     * @param  integer  $stages 	a jelenlegi oldal mindkét oldalán hány elem legyen az oldalszámozásnál
     */
    function __construct($pagename, $limit = 10, $stages = 2) {
        $this->pagename = $pagename;
        $this->limit = $limit;
        $this->stages = $stages;
        $this->_set_page_id();
    }

    /**
     * Visszaadja az offset-et
     *
     * @return integer
     */
    public function get_offset()
    {
        return ($this->page_id * $this->limit) - $this->limit;
    }

    /**
     * Visszaadja a limitet
     *
     * @return integer
     */
    public function get_limit()
    {
        return $this->limit;
    }

    /**
     * Beállítja a page_id tulajdonság értékét (az oldal számát)
     */
    private function _set_page_id()
    {
        $this->page_id = (int) (!isset($_GET[$this->pagename]) ? 1 : $_GET[$this->pagename]);
        $this->page_id = ($this->page_id == 0 ? 1 : $this->page_id);
    }

/**
 * Az oldal számát adja vissza
 */
public function getPageId()
{
    return $this->page_id;
}

/**
 * Az összes elem és a limit alapján visszaadja az oldalak számát
 */
public function getNumberOfPages($total_record, $limit)
{
    return (int)ceil($total_record / $limit);
}

    /**
     * A tábla összes rekordjának a számát adja meg a $total_pages tulajdonságnak 
     *
     * @param integer	$total_pages
     */
    public function set_total($total_pages)
    {
        $this->total_pages = $total_pages;
    }

    /**
     * 	Link létrehozása, amihez kapcsolódik az oldalszámláló paraméter (page=2)
     */
    private function _get_path($uri_path)
    {
        $this->querystring = '?';
        // ha van query string
        if (!empty($_GET)) {
            $params = $_GET;
            if (isset($params[$this->pagename])) {
                unset($params[$this->pagename]);
            }

            // query string összeállítása 
            //$this->querystring .= http_build_query($params);
            //$this->querystring .= '&';

            foreach ($params as $k => $v) {
                if (is_array($v)) {
                    foreach ($v as $key => $value) {
                        $this->querystring .= $k . '[]=' . $value . '&';
                    }
                } else {
                    $this->querystring .= $k . '=' . $v . '&';
                }
            }


            unset($params);
            return $uri_path . $this->querystring;
        } else {
            // ha nincs query string		
            return $uri_path . $this->querystring;
        }
    }

    /**
     * Oldalszámozás megjelenítése 
     *
     * @param string $uri_path 		elérési út megadása ehhez fog csatlakozni az oldalszám paraméter
     * @return string 				a html menu
     */
    public function page_links($uri_path)
    {
        // útvonal létrehozása, ehhez fog csatlakozni a pl.: pagename=23	
        $path = $this->_get_path($uri_path);

        // Initial page num setup
        $prev = $this->page_id - 1;
        $next = $this->page_id + 1;
        $lastpage = $this->getNumberOfPages($this->total_pages, $this->limit);
        $LastPagem1 = $lastpage - 1;

        $paginate = '';
        //csak akkor fűzi hozzá a string-hez a többi elemet, ha az utolsó oldalon legalább 1 elem van
        if ($lastpage > 1) {

            $paginate .= '<ul class="pages-listing">' . "\n";
            // A vissza linkje
            if ($this->page_id > 1) {
                $paginate.= '<li><a href="' . $path . $this->pagename . '=' . $prev . '"><i class="fa fa-angle-double-left"></i></a></li>';
            } else {
                $paginate.= '<li><a href="javascript:void"><i class="fa fa-angle-double-left"></i></a></li>';
            }
            // Oldalak	
            if ($lastpage < (7 + ($this->stages * 2))) { // Not enough pages to breaking it up
                for ($counter = 1; $counter <= $lastpage; $counter++) {
                    if ($counter == $this->page_id) {
                        $paginate .= '<li><a class="active" href="javascript:void">' . $counter . '</a></li>';
                    } else {
                        $paginate .= '<li><a href="' . $path . $this->pagename . '=' . $counter . '">' . $counter . '</a></li>';
                    }
                }
            } elseif ($lastpage > (5 + ($this->stages * 2))) { // Enough pages to hide a few?
                // Beginning only hide later pages
                if ($this->page_id < (1 + ($this->stages * 2))) {
                    for ($counter = 1; $counter < 4 + ($this->stages * 2); $counter++) {
                        if ($counter == $this->page_id) {
                            $paginate .= '<li><a class="active" href="javascript:void">' . $counter . '</a></li>';
                        } else {
                            $paginate .= '<li><a href="' . $path . $this->pagename . '=' . $counter . '">' . $counter . '</a></li>';
                        }
                    }
                    $paginate .= ' ... ';
                    $paginate .= '<li><a href="' . $path . $this->pagename . '=' . $LastPagem1 . '">' . $LastPagem1 . '</a></li>';
                    $paginate .= '<li><a href="' . $path . $this->pagename . '=' . $lastpage . '">' . $lastpage . '</li></a>';
                }
                // Middle hide some front and some back
                elseif ($lastpage - ($this->stages * 2) > $this->page_id && $this->page_id > ($this->stages * 2)) {
                    $paginate .= '<li><a href="' . $path . $this->pagename . '=1">1</a></li>';
                    $paginate .= '<li><a href="' . $path . $this->pagename . '=2">2</a></li>';
                    $paginate .= ' ... ';
                    for ($counter = $this->page_id - $this->stages; $counter <= $this->page_id + $this->stages; $counter++) {
                        if ($counter == $this->page_id) {
                            $paginate .= '<li><a class="active" href="javascript:void">' . $counter . '</a></li>';
                        } else {
                            $paginate .= '<li><a href="' . $path . $this->pagename . '=' . $counter . '">' . $counter . '</a></li>';
                        }
                    }
                    $paginate .= ' ... ';
                    $paginate .= '<li><a href="' . $path . $this->pagename . '=' . $LastPagem1 . '">' . $LastPagem1 . '</a></li>';
                    $paginate .= '<li><a href="' . $path . $this->pagename . '=' . $lastpage . '">' . $lastpage . '</a></li>';
                }
                // End only hide early pages
                else {
                    $paginate.= '<li><a href="' . $path . $this->pagename . '=1">1</a></li>';
                    $paginate.= '<li><a href="' . $path . $this->pagename . '=2">2</a></li>';
                    $paginate.= ' ... ';
                    for ($counter = $lastpage - (2 + ($this->stages * 2)); $counter <= $lastpage; $counter++) {
                        if ($counter == $this->page_id) {
                            $paginate.= '<li><a class="active" href="javascript:void">' . $counter . '</a></li>';
                        } else {
                            $paginate.= '<li><a href="' . $path . $this->pagename . '=' . $counter . '">' . $counter . '</a></li>';
                        }
                    }
                }
            }

            // A következő linkje
            if ($this->page_id < $counter - 1) {
                $paginate.= '<li><a href="' . $path . $this->pagename . '=' . $next . '"><i class="fa fa-angle-double-right"></i></a></li>';
            } else {
                $paginate.= '<li><a href="javascript:void"><i class="fa fa-angle-double-right"></i></a></li>';
            }

            $paginate.= '</ul>' . "\n";
        }
        return $paginate;
    }

}

?>