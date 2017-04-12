<?php
namespace System\Helper;
/**
* Number helper
*/
class Num {

	/**
	 * Nagy számokat kerekíti és alakítja át rövidebb formára: 2 000 000 - 2 M
	 * 
	 * @param string||number $n
	 * @param bool $scale - ha false, akkor nem írja a szám után az M vagy E betűt
	 */
    public function niceNumber($n, $scale = true)
    {
		// a nem numerikus karaktereket üres stringre cseréljük (ha üres string marad, akkor 0 lesz)
        $n = preg_replace('~\D~', '', $n);

        // is this a number?
        if (!is_numeric($n)) {
            return false;
        }

        if ($n >= 1000000) {
			$n = round(($n / 1000000), 2);
			if ($scale) {
				$n .= '&nbsp;M';
			}	
            return $n;
        }
        elseif ($n > 1000) {
			$n = round(($n / 1000), 0);
            if ($scale) {
				$n .= '&nbsp;E';
			}
			return $n;
        }

        return number_format($n);
    }

    /**
     * Egy szám stringet alakít át számmá
     *
     * @param string $number
     * @param integer $decimals		- lebegőpontos számoknál ennyi tizedesjegyre kerekít (56,125 -ös szám esetén:  1-es: 56.1 - 2-es: 56.13 - 3-as: 56,125)
     * @return integer || float
     */
    public function stringToNumber($number, $decimals = 1)
    {
		if (strpos($number, ',') !== false) {
			$temp = explode(',', $number);
		}
		elseif (strpos($number, '.') !== false) {
			$temp = explode('.', $number);
		}
		else {
			$result = preg_replace('~\D~', '', $number);
			if ($result === '') {
				$result = 0;
			}
			// visszaad egy egész számot
			return intval($result);
		}
		
		// ha volt a stringben ',' vagy '.' karakter
		if (isset($temp)) {
			foreach($temp as &$value) {
				$value = preg_replace('~\D~', '', $value);
				if ($value === '') {
					$value = 0;
				}
			}

			$result = $temp[0] . '.' . $temp[1];	
		}

		$result = number_format($result, $decimals, '.', '');
		// visszaad egy lebegőpontos számot
		return floatval($result);
    }


}
?>