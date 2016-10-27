<?php 
namespace System\Libs;

/**
 * Képkezelő osztály
 *
 *	Metódusok:
 *
 *		Uploader::setError();
 *		Uploader::getError();
 *		Uploader::checkError();
 *		Uploader::checkUpload();
 *		Uploader::isImage();
 *
 *		Uploader::calcHeight();
 *		Uploader::calcWidth();
 *		Uploader::getRatio();
 *		Uploader::getRatioResized();
 *		Uploader::cleanRatioResized();
 *		Uploader::forge();
 *
 *		Uploader::cleanTemp();
 *		
 *
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
	public function cropRatio()
	{
		$this->handle->image_ratio_crop = true;
	}

	/**
	 * Képből vág alul és felül, vagy a két oldalon, hogy megtartsa a beállított képarányt
	 *
	 * @param integer $width - kép szélessége
	 * @param integer $height - kép magassága 
	 */
	public function cropToSize($width, $height)
	{
		$this->resize($width, $height);
		$this->handle->image_ratio_crop = true;
	}

	/**
	 * Képből vág alul és felül, vagy a két oldalon, hogy megtartsa a beállított képarányt
	 *
	 * @param integer $width - kép szélessége
	 * @param integer $height - kép magassága
	 * @param string $color - #ff0066	 	  
	 */
	public function cropFillToSize($width, $height, $color = null)
	{
		$this->resize($width, $height);
		if (!is_null($color)) {
			$this->handle->image_background_color = $color;
		}	
		$this->handle->image_ratio_fill = true;
	}	

	/**
	 * Kép háttér és default színének beállítása
	 *
	 * @param string $param - background | default
	 * @param string $color - #ff0066
	 */
	public function color($param, $color)
	{
		$property = 'image_' . $param . '_color';
		$this->handle->$property = $color;
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
	 * Kép effect-ek hozzáadása
     *
	 *	brightness 			- if set, corrects the brightness. value between -127 and 127 (default: null)
	 *	contrast 			- if set, corrects the contrast. value between -127 and 127 (default: null)
	 *	opacity 			- if set, changes the image opacity. value between 0 and 100 (default: null)
	 *	tint_color  		- if set, will tint the image with a color, value as hexadecimal #FFFFFF (default: null)
	 *	overlay_color 		- if set, will add a colored overlay, value as hexadecimal #FFFFFF (default: null)
	 *	overlay_opacity 	- used when image_overlay_color is set, determines the opacity (default: 50)
	 *	negative 			- inverts the colors in the image (default: false)
	 *	greyscale 			- transforms an image into greyscale (default: false)
	 *	threshold 			- applies a threshold filter. value between -127 and 127 (default: null)
	 *	pixelate 			- pixelate an image, value is block size (default: null)
	 *	unsharp 			- applies an unsharp mask, with alpha transparency support (default: false)
	 *	unsharp_amount 		- unsharp mask amount, typically 50 - 200 (default: 80)
	 *	unsharp_radius 		- unsharp mask radius, typically 0.5 - 1 (default: 0.5)
	 *	unsharp_threshold 	- unsharp mask threshold, typically 0 - 5 (default: 1)
	 * 
	 * @param string $effect
	 * @param mixed $value
	 */
	public function effect($effect, $value)
	{
		$property = 'image_' . $effect;
		$this->handle->$property = $value;
	}

	/**
	 * Vízjel hozzáadása és beállításai
	 *
	 * picture		- adds a watermark on the image, value is a local filename. accepted files are GIF, JPG, BMP, PNG and PNG alpha
	 * x 			- absolute watermark position, in pixels from the left border. can be negative (default: null)
	 * y 			- absolute watermark position, in pixels from the top border. can be negative (default: null)
	 * position 	- watermark position withing the image, a combination of one or two from 'TBLR': top, bottom, left, right (default: null)
	 * no_zoom_in 	- prevents the watermark to be resized up if it is smaller than the image (default: true)
	 * no_zoom_out 	- prevents the watermark to be resized down if it is bigger than the image (default: false)
	 *
	 * @param string $prop
	 * @param mixed $value
	 */
	public function watermark($param, $value)
	{
		if ($param == 'picture') {
			$this->handle->image_watermark = $value;
		} else {
			$property = 'image_watermark_' . $param;
			$this->handle->$property = $value;
		}
	}

	/**
	 * Kép elforgatása
	 * Lehetséges értékek 90, 180 és 270
	 * @param integer $angle
	 */
	public function rotate(int $angle)
	{
		// if ($angle == 90 || $angle == 180 || $angle == 270) {}
		$this->handle->image_rotate = $angle;
	}

	/**
	 * Kép tükrözése 
	 * @param string $axis - 'h' horizontal vagy 'v' vertical
	 */
	public function flip($axis)
	{
		$this->handle->image_flip = $axis;
	}

	/**
	 * Képfelirat hozzáadása és paraméterek megadása
	 *
	 *	text 				- creates a text label on the image, value is a string, with eventual replacement tokens
	 *	direction 			- text label direction, either 'h' horizontal or 'v' vertical (default: 'h')
	 *	color 				- text color for the text label, in hexadecimal (default: #FFFFFF)
	 *	opacity 			- text opacity on the text label, integer between 0 and 100 (default: 100)
	 *	background 			- text label background color, in hexadecimal (default: null)
	 *	background_opacity 	- text label background opacity, integer between 0 and 100 (default: 100)
	 *	font 				- built-in font for the text label, from 1 to 5. 1 is the smallest (default: 5). Value can also be a string, which represents the path to a GDF or TTF font (TrueType).
	 *	size 				- font size for TrueType fonts, in pixels (GD1) or points (GD1) (default: 16) (TrueType fonts only)
	 *	angle 				- text angle for TrueType fonts, in degrees, with 0 degrees being left-to-right reading text(default: null) (TrueType fonts only)
	 *	x 					- absolute text label position, in pixels from the left border. can be negative (default: null)
	 *	y 					- absolute text label position, in pixels from the top border. can be negative (default: null)
	 *	position 			- text label position withing the image, a combination of one or two from 'TBLR': top, bottom, left, right (default: null)
	 *	padding 			- text label padding, in pixels. can be overridden by image_text_padding_x and image_text_padding_y (default: 0)
	 *	padding_x 			- text label horizontal padding (default: null)
	 *	padding_y 			- text label vertical padding (default: null)
	 *	alignment 			- text alignment when text has multiple lines, either 'L', 'C' or 'R' (default: 'C') (GD fonts only)
	 *	line_spacing 		- space between lines in pixels, when text has multiple lines (default: 0) (GD fonts only)
     *
	 * @param string $param
	 * @param mixed $value
	 */
	public function text($param, $value)
	{
		if ($param == 'text') {
			$this->handle->image_text = $value;
		} else {
			$property = 'image_text_' . $param;
			$this->handle->$property = $value;
		}
	}

	/**
	 * Kép convertálása más fileformátumba
	 * @param string $type - megadható értékek : 'png'|'jpeg'|'gif'|'bmp'
	 */
	public function convert($type)
	{	
		if (in_array($type, array('png', 'jpeg', 'gif', 'bmp'))) {
			$this->handle->image_convert = $type;
		}
	}

	/**
	 * quality 	- sets the compression quality for JPEG images (default: 85)
	 * size 	- set to a size in bytes, will approximate jpeg_quality so the output image fits within the size
	 *
	 * @param string $param - quality | size
	 * @param integer $value
	 */
	public function jpeg($param, $value)
	{
		$property = 'jpeg_' . $param;	
		$this->handle->jpeg_quality = (int)$value;
	}

	/**
	 * sets maximum upload size (default: upload_max_filesize from php.ini)
	 * @param integer $size
	 */
	public function maxFileSize($size)
	{
		$this->handle->file_max_size = (int)$size;
	}

	/**
	 * sets behaviour if file already exists
	 * @param bool $value
	 */
	public function overwrite($value = true)
	{
		$this->handle->file_overwrite = $value;
	}

	/**
	 * Kép (minimum) paraméterek beállítása
	 *
	 *  width 	- if set to a dimension in pixels, the upload will be invalid if the image width is lower (default: null)
	 *  height 	- if set to a dimension in pixels, the upload will be invalid if the image height is lower (default: null)
	 *  pixels 	- if set to a number of pixels, the upload will be invalid if the image number of pixels is lower (default: null)
	 *  ratio 	- if set to a aspect ratio (width/height), the upload will be invalid if the image apect ratio is lower (default: null)
	 *
	 * @param string $param
	 * @param mixed $value
	 */
	public function setMin($param, $value)
	{
		$property = 'image_min_' . $param;
		$this->handle->$property = $value;
	}

	/**
	 * Kép (maximum) paraméterek beállítása
	 *
	 *  width 	- if set to a dimension in pixels, the upload will be invalid if the image width is greater (default: null)
	 *  height 	- if set to a dimension in pixels, the upload will be invalid if the image height is greater (default: null)
	 *  pixels 	- if set to a number of pixels, the upload will be invalid if the image number of pixels is greater (default: null)
	 *  ratio 	- if set to a aspect ratio (width/height), the upload will be invalid if the image apect ratio is greater (default: null)
	 *
	 * @param string $param
	 * @param mixed $value
	 */
	public function setMax($param, $value)
	{
		$property = 'image_max_' . $param;
		$this->handle->$property = $value;
	}


	/**
	 * Cél file adatait adja vissza
	 * 
	 * @param string $param - filename | path | body | ext | pathname | width | height | type | ratio
	 */
	public function getDest($param)
	{
		$property = 'file_dst_';
		$property_image = 'image_dst_';

		switch ($param) {
			case 'filename':
				$property .= 'name';
				return $this->handle->$property;
				break;

			case 'path':
				$property .= $param;
				return $this->handle->$property;
				break;

			case 'body':
				$property .= 'name_' . $param;
				return $this->handle->$property;
				break;

			case 'ext':
				$property .= 'name_' . $param;
				return $this->handle->$property;
				break;

			case 'pathname':
				$property .= $param;
				return $this->handle->$property;
				break;

			case 'width':
				$property_image .= 'x';
				return $this->handle->$property_image;
				break;

			case 'height':
				$property_image .= 'y';
				return $this->handle->$property_image;
				break;

			case 'type':
				$property_image .= $param;
				return $this->handle->$property_image;
				break;

			case 'ratio':
				return $this->handle->image_dst_x / $this->handle->image_dst_y;
				break;
			
			default:
				return false;
				break;
		}
	}

	/**
	 * Forrásfile adatait adja vissza a paramétertől függően
	 *
	 *  filename 	- Source file name
	 *  body 		- Source file name body
	 *  ext 		- Source file extension
	 *  pathname 	- Source file complete path and name
	 *  mime 		- Source file mime type
	 *  size 		- Source file size in bytes
	 *  error 		- Upload error code
	 *
	 * Képek esetén:
	 *  width 		- Source file width in pixels
	 *  height 		- Source file height in pixels
	 *  ratio  		- Source file width / height
	 *  pixels 		- Source file number of pixels
	 *  type 		- Source file type (png, jpg, gif or bmp)
	 *  bits  		- Source file color depth
	 *
	 * @param string $param
	 */
	public function getSource($param)
	{
		$property = 'file_src_';
		$property_image = 'image_src_';

		switch ($param) {
			case 'filename':
				$property .= 'name';
				return $this->handle->$property;
				break;

			case 'body':
				$property .= 'name_' . $param;
				return $this->handle->$property;
				break;

			case 'ext':
				$property .= 'name_' . $param;
				return $this->handle->$property;
				break;

			case 'pathname':
				$property .= $param;
				return $this->handle->$property;
				break;

			case 'mime':
				$property .= $param;
				return $this->handle->$property;
				break;

			case 'size':
				$property .= $param;
				return $this->handle->$property;
				break;

			case 'error':
				$property .= $param;
				return $this->handle->$property;
				break;

			case 'width':
				$property_image .= 'x';
				return $this->handle->$property;
				break;

			case 'height':
				$property_image .= 'y';
				return $this->handle->$property;
				break;

			case 'type':
				$property_image .= $param;
				return $this->handle->$property;
				break;

			case 'pixels':
				$property_image .= $param;
				return $this->handle->$property;
				break;

			case 'bits':
				$property_image .= $param;
				return $this->handle->$property;
				break;

			case 'ratio':
				return $this->handle->image_src_x / $this->handle->image_src_y;
				break;
			
			default:
				return false;
				break;
		}
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