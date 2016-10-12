<?php 
namespace System\Libs;

/**
    // beállítható tulajdonságok az upload osztályban

        $this->file_new_name_body       = null;     // replace the name body
        $this->file_name_body_add       = null;     // append to the name body
        $this->file_name_body_pre       = null;     // prepend to the name body
        $this->file_new_name_ext        = null;     // replace the file extension
        $this->file_safe_name           = true;     // format safely the filename
        $this->file_force_extension     = true;     // forces extension if there isn't one
        $this->file_overwrite           = false;    // allows overwritting if the file already exists
        $this->file_auto_rename         = true;     // auto-rename if the file already exists
        $this->dir_auto_create          = true;     // auto-creates directory if missing
        $this->dir_auto_chmod           = true;     // auto-chmod directory if not writeable
        $this->dir_chmod                = 0777;     // default chmod to use

        $this->no_script                = true;     // turns scripts into test files
        $this->mime_check               = true;     // checks the mime type against the allowed list

        // these are the different MIME detection methods. if one of these method doesn't work on your
        // system, you can deactivate it here; just set it to false
        $this->mime_fileinfo            = true;     // MIME detection with Fileinfo PECL extension
        $this->mime_file                = true;     // MIME detection with UNIX file() command
        $this->mime_magic               = true;     // MIME detection with mime_magic (mime_content_type())
        $this->mime_getimagesize        = true;     // MIME detection with getimagesize()

        // get the default max size from php.ini
        $this->file_max_size = ;

        $this->image_resize             = false;    // resize the image
        $this->image_convert            = '';       // convert. values :''; 'png'; 'jpeg'; 'gif'; 'bmp'

        $this->image_x                  = 150;
        $this->image_y                  = 150;
        $this->image_ratio              = false;    // keeps aspect ratio with x and y dimensions
        $this->image_ratio_crop         = false;    // keeps aspect ratio with x and y dimensions, filling the space
        $this->image_ratio_fill         = false;    // keeps aspect ratio with x and y dimensions, fitting the image in the space, and coloring the rest
        $this->image_ratio_pixels       = false;    // keeps aspect ratio, calculating x and y so that the image is approx the set number of pixels
        $this->image_ratio_no_zoom_in   = false;
        $this->image_ratio_no_zoom_out  = false;
        $this->image_ratio_x            = false;    // calculate the $image_x if true
        $this->image_ratio_y            = false;    // calculate the $image_y if true
        $this->png_compression          = null;
        $this->jpeg_quality             = 85;
        $this->jpeg_size                = null;
        $this->image_interlace          = false;
        $this->preserve_transparency    = false;
        $this->image_is_transparent     = false;
        $this->image_transparent_color  = null;
        $this->image_background_color   = null;
        $this->image_default_color      = '#ffffff';
        $this->image_is_palette         = false;

        $this->image_max_width          = null;
        $this->image_max_height         = null;
        $this->image_max_pixels         = null;
        $this->image_max_ratio          = null;
        $this->image_min_width          = null;
        $this->image_min_height         = null;
        $this->image_min_pixels         = null;
        $this->image_min_ratio          = null;

        $this->image_brightness         = null;
        $this->image_contrast           = null;
        $this->image_opacity            = null;
        $this->image_threshold          = null;
        $this->image_tint_color         = null;
        $this->image_overlay_color      = null;
        $this->image_overlay_opacity    = null;
        $this->image_overlay_percent    = null;
        $this->image_negative           = false;
        $this->image_greyscale          = false;
        $this->image_pixelate           = null;
        $this->image_unsharp            = false;
        $this->image_unsharp_amount     = 80;
        $this->image_unsharp_radius     = 0.5;
        $this->image_unsharp_threshold  = 1;

        $this->image_text               = null;
        $this->image_text_direction     = null;
        $this->image_text_color         = '#FFFFFF';
        $this->image_text_opacity       = 100;
        $this->image_text_percent       = 100;
        $this->image_text_background    = null;
        $this->image_text_background_opacity = 100;
        $this->image_text_background_percent = 100;
        $this->image_text_font          = 5;
        $this->image_text_x             = null;
        $this->image_text_y             = null;
        $this->image_text_position      = null;
        $this->image_text_padding       = 0;
        $this->image_text_padding_x     = null;
        $this->image_text_padding_y     = null;
        $this->image_text_alignment     = 'C';
        $this->image_text_line_spacing  = 0;

        $this->image_reflection_height  = null;
        $this->image_reflection_space   = 2;
        $this->image_reflection_color   = '#ffffff';
        $this->image_reflection_opacity = 60;

        $this->image_watermark          = null;
        $this->image_watermark_x        = null;
        $this->image_watermark_y        = null;
        $this->image_watermark_position = null;
        $this->image_watermark_no_zoom_in  = true;
        $this->image_watermark_no_zoom_out = false;

        $this->image_flip               = null;
        $this->image_rotate             = null;
        $this->image_crop               = null;
        $this->image_precrop            = null;

        $this->image_bevel              = null;
        $this->image_bevel_color1       = '#FFFFFF';
        $this->image_bevel_color2       = '#000000';
        $this->image_border             = null;
        $this->image_border_color       = '#FFFFFF';
        $this->image_border_opacity     = 100;
        $this->image_border_transparent = null;
        $this->image_frame              = null;
        $this->image_frame_colors       = '#FFFFFF #999999 #666666 #000000';
        $this->image_frame_opacity      = 100;

        $this->forbidden = array();
        $this->allowed = array();


        $this->file_src_name      = '';
        $this->file_src_name_body = '';
        $this->file_src_name_ext  = '';
        $this->file_src_mime      = '';
        $this->file_src_size      = '';
        $this->file_src_error     = '';
        $this->file_src_pathname  = '';
        $this->file_src_temp      = '';

        $this->file_dst_path      = '';
        $this->file_dst_name      = '';
        $this->file_dst_name_body = '';
        $this->file_dst_name_ext  = '';
        $this->file_dst_pathname  = '';

        $this->image_src_x        = null;
        $this->image_src_y        = null;
        $this->image_src_bits     = null;
        $this->image_src_type     = null;
        $this->image_src_pixels   = null;
        $this->image_dst_x        = 0;
        $this->image_dst_y        = 0;
        $this->image_dst_type     = '';			

	METÓDUSOK:
		Uploader::setError();
		Uploader::getError();
		Uploader::get();
		Uploader::isImage();
		Uploader::calcHeight();
		Uploader::calcWidth();
		Uploader::getRatio();
		Uploader::cleanRatio();
		Uploader::make();
		Uploader::cleanTemp();

*/
class Uploader
{
	/**
	 * A feltöltő objektumot tartalmazza
	 */
	private $handle;

	/**
	 * Feltöltött kép képaránya
	 */
	private $ratio;

	/**
	 * A hibát tartalmazza
	 */
	private $error;

	/**
	 * Feltöltő objektum létrehozása
	 */
	function __construct($file)
	{
		$this->handle = new Upload($file);
	}

	/**
	 * Hiba beállítása
	 */
	public function setError($error)
	{
		$this->error = $error;
	}

	/**
	 * Hiba visszaadása
	 */
	public function getError()
	{
		return $this->error;
	}

	/**
	 * A paraméterben megadott tulajdonság értékét adja vissza
	 *
	 * Bizonyos elemek csak a make() metódus első meghívása után kapnak értéket
	 * pl: 'file_dst_path', 'file_dst_name', 'file_dst_name_body', 'file_dst_name_ext'
	 */
	public function get($value)
	{
		return $this->handle->$value;
	}

	/**
	 * Visszaadja, hogy kép-e a feltöltött file
	 */
	public function isImage()
	{
		return $this->handle->file_is_image;
	}

	/**
	 * Kép magasságának kiszámítása a szélesség és a képarány elosztásával
	 */
	public function calcHeight($width)
	{
		return round($width / $this->ratio);
	}

	/**
	 * Kép szélességének kiszámítása a magasság és a képarány szorzásával
	 */
	public function calcWidth($height)
	{
		return round($height * $this->ratio);
	}

	/**
	 * Képarány visszaadása
	 */
	public function getRatio()
	{
		return $this->ratio;
	}

	/**
	 * Képarány változó reset
	 */
	public function cleanRatio()
	{
		$this->ratio = null;
	}


	/**
	 * A feltöltött kép paramétereinek megadása és végleges helyre mozgatása
	 *
	 *	Fontosabb beállítások:
	 *	array(
	 *		'image_x' => 150, // def: 150
	 *		'image_y' => 150, // def: 150
	 *		'image_ratio' => false, // def: false
	 *		'image_ratio_x' => false, // def: false
	 *		'image_ratio_y' => false, // def: false
	 *		'image_ratio_crop' => false, // def: false
	 *		'image_ratio_fill' => false, // def: false
	 *		'file_auto_rename' => true, // def: true
	 *		'file_safe_name' => true,	// def: true
	 *		'file_overwrite' => false, // def: false
	 *		'allowed' => array('image/*'), // def: szinte minden file típus
	 *		'image_resize' => false, // def: false
	 *		'file_new_name_body' => 'valami_' . rand(), // def: null
	 *		'file_name_body_add' => '_thumb', // def: null
	 *		'file_name_body_pre' => 'thumb_', // def: null
	 *		'file_new_name_ext' => 'jpeg', // def: null
	 *		'maxsize' => 1024, // max size from php.ini
	 *		'jpeg_quality' => 85, // def: 85
	 *	);
	 *
	 * @param string $path - elérési út, ahová a file-t fel kell tölteni
	 * @param array $args - file paraméterei
	 * @return string|false (ha nincs hiba visszaadja a feltöltött file nevét)
	 */
	public function make($path, array $args)
	{
		if ($this->handle->uploaded) {

			// beállítások megadása az args tömb alapján
			foreach ($args as $key => $value)
			{
				$this->handle->$key = $value;
			}

			// képarány meghatározása az első képnél
			if (is_null($this->ratio)) {
				$this->ratio = $this->handle->image_x / $this->handle->image_y;
			}

			$this->handle->Process($path);
			if ($this->handle->processed) {
				// ha nincs hiba visszaadja a feltöltött file nevét
				return $this->handle->file_dst_name;
			} else {
	            $this->setError($this->handle->error);
				return false;
			}

		} else {
            $this->setError($this->handle->error);
			return false;			
		}
	}

	/**
	 * Képek törlése a szerver temp könyvtárából
	 */
	public function cleanTemp()
	{
		$this->handle->clean();
	}

	public function __destruct()
	{
		$this->cleanTemp();
	}
}
?>