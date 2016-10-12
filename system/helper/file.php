<?php
namespace System\Helper;

use System\Libs\Message;

/**
* File-műveleteket végző metódusok
*/
class File {
	
	public function __construct() { }

	/**
     * 	File, vagy file-ok törlése
     *
     * 	@param	string|array $filenames 	a törlendő file elérési útja mappa/filename.ext
     * 	@return	bool
     */
    public function delete($filenames)
    {
        $filenames = (array) $filenames;
        foreach ($filenames as $file) {
            if (is_file($file) && is_writable($file)) {
                //ha a file megnyitható és írható
                unlink($file);
            } else {
                Message::log('A ' . $file . ' kép nem törlődött!');
            }
        }
        return true;
    }


}
?>