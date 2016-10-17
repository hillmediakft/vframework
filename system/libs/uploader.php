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
		Uploader::checkError();

		Uploader::isImage();

		Uploader::calcHeight();
		Uploader::calcWidth();
		Uploader::getRatio();
		Uploader::getRatioResized();
		Uploader::cleanRatioResized();

		Uploader::cleanTemp();
		


*/
class Uploader
{
	/**
	 * A feltöltő objektumot tartalmazza
	 */
	private $handle;

	/**
	 * Módosított képarányú kép képaránya
	 */
	private $ratio_resized;

	/**
	 * A hibát tartalmazza
	 */
	private $error = null;

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
	 * Hiba meglétének vizsgálata
	 */
	public function checkError()
	{
		return !is_null($this->error);
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
		return round($width / $this->ratio_resized);
	}

	/**
	 * Kép szélességének kiszámítása a magasság és a képarány szorzásával
	 */
	public function calcWidth($height)
	{
		return round($height * $this->ratio_resized);
	}

	/**
	 * Eredeti (forrás kép) képarány visszaadása
	 */
	public function getRatio()
	{
		return $this->image_src_x / $this->image_src_y;
	}

	/**
	 * Átméretezett képarány visszaadása
	 */
	public function getRatioResized()
	{
		return $this->ratio_resized;
	}

	/**
	 * Képarány változó reset
	 */
	public function cleanRatioResized()
	{
		$this->ratio_resized = null;
	}

	/**
	 * Készít egy objektumot az upload osztályból 
	 */
	public function forge($file)
	{
		return new Upload($file);
	}

	/**
	 * Vizsgálja a feltöltés sikerességét (létezik-e a forrás file)
	 */
	public function checkUpload()
	{
		if ($this->handle->uploaded) {
			return true;
		} else {
            $this->setError($this->handle->error);
			return false;	
		}
	}

	/**
	 * Átméretezés beállítása
	 * Ha csak az egyik méretet adjuk meg, akkor a másiknak null értéket kell adni!
	 *
	 * @param integer $width - kép szélessége
	 * @param integer $height - kép magassága
	 */
	public function resize($width, $height)
	{
		if ($this->isImage()) {
			$this->handle->image_resize = true;
			if (!is_null($width) && !is_null($height)) {
				$this->handle->image_x = $width;
				$this->handle->image_y = $height;
				// módosított képarány
				$this->ratio_resized = $width / $height;
			}
			elseif (!is_null($width) && is_null($height)) {
				$this->handle->image_x = $width;
				$this->handle->image_ratio_y = true;
			}
			elseif (is_null($width) && !is_null($height)) {
				$this->handle->image_y = $height;
				$this->handle->image_ratio_x = true;
			}
		}	
	}

	/**
	 * Kép vágása
	 *
	 * Az első paraméter ha tömb: elfogad 4, 2 vagy 1 értéket: 'Top, Right, Bottom, Left' vagy 'TopBottom, LeftRight' vagy 'TopBottomLeftRight'.
	 * Az első paraméter ha string: 20, vagy 20px vagy 20%
	 *
	 * @param array|string 	$data
	 */
	public function crop($data)
	{
		$this->handle->image_crop = $data;
	}

	/**
	 * Kép vágása átméretezés előtt
	 *
	 * Az első paraméter ha tömb: elfogad 4, 2 vagy 1 értéket: 'Top, Right, Bottom, Left' vagy 'TopBottom, LeftRight' vagy 'TopBottomLeftRight'.
	 * Az első paraméter ha string: 20, vagy 20px vagy 20%
	 *
	 * @param array|string 	$data
	 */
	public function cropPre($data)
	{
		$this->handle->image_precrop = $data;
	}

	/**
	 * Képhez hozzáad hátteret, hogy megtartsa a beállított képarányt
	 * A metódus meghívása előtt be kell állítani a resize($width, $height) metódussal a kép méreteit
	 * 
	 * @param string $color - #ff0066
	 */
	public function cropFill($color = null)
	{
		if (!is_null($color)) {
			$this->handle->image_background_color = $color;
		}	
		
		$this->handle->image_ratio_fill = true;
	}

	/**
	 * Képből vág alul és felül, vagy a két oldalon, hogy megtartsa a beállított képarányt
	 * A metódus meghívása előtt be kell állítani a resize($width, $height) metódussal a kép méreteit	 
	 */
	public function cropExcess()
	{
		$this->handle->image_ratio_crop = true;
	}	

	/**
	 * Kép háttér színének beállítása
	 *
	 * @param string $color - #ff0066
	 */
	public function setBackgroundColor($color)
	{
		$this->handle->image_background_color = $color;
	}

	/**
	 * Engedélyezett fájl típusok beállítása
	 *
	 * @param array $allowed
	 */
	public function allowed(array $allowed)
	{
		$this->handle->allowed = $allowed;
	}

	/**
	 * Feltöltött file nevét adja vissza
	 * @return string
	 */
	public function getDestFilename()
	{
		return $this->handle->file_dst_name;
	}

	/**
	 * Feltöltött file szélességét adja vissza (px)
	 * @return integer
	 */
	public function getDestImageWidth()
	{
		return $this->handle->image_dst_x;
	}

	/**
	 * Feltöltött file magasságát adja vissza (px)
	 * @return integer
	 */
	public function getDestImageHeight()
	{
		return $this->handle->image_dst_y;
	}

	/**
	 * File mentése
	 *
	 * @param string $path 		- feltöltés helye
	 * @param string $filename 	- új file neve
	 */
	public function save($path, $filename = null)
	{
		if (!is_null($filename)) {
			$this->handle->file_new_name_body = $filename;
		}	

		$this->handle->Process($path);

		if (!$this->handle->processed) {
            $this->setError($this->handle->error);
		}
	}

	/**
	 * Fájlok törlése a szerver temp könyvtárából
	 */
	public function cleanTemp()
	{
		$this->handle->clean();
	}

}
?>