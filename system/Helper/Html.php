<?php

namespace System\Helper;

use System\Libs\DI;
use System\Libs\Cookie;

/**
 * Url és link helper
 */
class Html {

    /**
     * Ingatlan árának megjelenítése
     * Amennyiben csökkent az ár, a régi ár lehúzva és feketén jelenik meg
     * 
     * @param   array  $ingatlan az ingatlan adatait tartalmazó tömb
     * @return  void
     */
    public function showPrice($ingatlan) {

        $num_helper = DI::get('num_helper');

        if ($ingatlan['tipus'] == 1) {
            if (isset($ingatlan['ar_elado_eredeti']) && $ingatlan['ar_elado_eredeti'] != $ingatlan['ar_elado']) {
                $price = $num_helper->niceNumber($ingatlan['ar_elado']) . ' Ft ' . '<span class="lower-price">' . $num_helper->niceNumber($ingatlan['ar_elado_eredeti']) . ' Ft</span>';
            } else {
                $price = $num_helper->niceNumber($ingatlan['ar_elado']) . ' Ft';
            }
        } else {
            if (isset($ingatlan['ar_kiado_eredeti']) && $ingatlan['ar_kiado_eredeti'] != $ingatlan['ar_kiado']) {
                $price = $num_helper->niceNumber($ingatlan['ar_kiado']) . ' Ft ' . '<span class="lower-price">' . $num_helper->niceNumber($ingatlan['ar_kiado_eredeti']) . ' Ft</span>';
            } else {
                $price = $num_helper->niceNumber($ingatlan['ar_kiado']) . ' Ft';
            }
        }
        echo $price;
    }

    /**
     * Árcsökkentés ikon megjelenítése
     * Amennyiben csökkent az ár, egy lefelé mutató nyíl ikon + %-jel jelenik meg
     * 
     * @param   array  $ingatlan az ingatlan adatait tartalmazó tömb
     * @return  void
     */
    public function showLowerPriceIcon($ingatlan) {
        $html = '';
        if ($ingatlan['tipus'] == 1) {
            if (isset($ingatlan['ar_elado_eredeti']) && $ingatlan['ar_elado_eredeti'] != $ingatlan['ar_elado']) {
                $html .= '<span class="lower-price">% <i class="fa fa-arrow-down"></i></span>';
            }
        } elseif ($ingatlan['tipus'] == 2) {
            if (isset($ingatlan['ar_kiado_eredeti']) && $ingatlan['ar_kiado_eredeti'] != $ingatlan['ar_kiado']) {
                $html .= '<span class="lower-price">% <i class="fa fa-arrow-down"></i></span>';
            }
        }
        echo $html;
    }

    /**
     * Kedvencekhez adáskor egy szív ikon megjelenítése
     * 
     * 
     * @param   array  $ingatlan az ingatlan adatait tartalmazó tömb
     * @return  void
     */
    public function showHeartIcon($ingatlan) {
        $html = '';

        if (Cookie::is_id_in_cookie('kedvencek', $ingatlan['id'])) {
            $html .= '<div class="like"><i class="fa fa-heart"></i></div>';
        } else {
            $html .= '';
        }
        echo $html;
    }

    /**
     * Közösségi média megosztási gombok az ingatlan adatlapra
     *  
     * @param   string $image az ingatlanhoz tartozó első kép elérési útvonala
     * @param   string $title az ingatlan neve
     * @return  string  a html
     */
    public function socialMediaShare($image, $title) {
        $html = '';

        $url = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; // a megjelenített URL
        $html .= "<div id='social-share-icons'>";
        $html .= "<a href='https://www.facebook.com/sharer.php?u=$url' target='_blank' class='share_facebook'><span class='fa-stack fa-lg'><i class='fa fa-square-o fa-stack-2x'></i><i class='fa fa-facebook fa-stack-1x'></i></span></a>";
        $html .= "<a href='http://pinterest.com/pin/create/button/?url=$url/&amp;media=$image&amp;description=$title' target='_blank' class='share_pinterest'><span class='fa-stack fa-lg'><i class='fa fa-square-o fa-stack-2x'></i><i class='fa fa-pinterest fa-stack-1x'></i></span></a>";
        $html .= "<a href='https://plus.google.com/share?url=$url' onclick='javascript:window.open(this.href,'', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;' target='_blank' class='share_google'><span class='fa-stack fa-lg'><i class='fa fa-square-o fa-stack-2x'></i><i class='fa fa-google-plus fa-stack-1x'></i></span></a>";
        $html .= "<a href='http://twitter.com/home?status=$title+$url' class='share_tweet' target='_blank'><span class='fa-stack fa-lg'><i class='fa fa-square-o fa-stack-2x'></i><i class='fa fa-twitter fa-stack-1x'></i></span></a>";
        $html .= "</div>";
        return $html;
    }

}

?>