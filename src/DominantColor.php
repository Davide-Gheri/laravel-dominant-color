<?php  

namespace Ghero\DominantColor;

 use \Imagick;

class DominantColor
{
	
	/**
	 * The default bites to generate a 1x1 GIF image file
	 *
	 * @vars string
	 */
	protected $header                    = '474946383961';
	protected $logical_screen_descriptor = '01000100800100';
	protected $image_descriptor          = '2c000000000100010000';
	protected $image_data                = '0202440100';
	protected $trailer                   = '3b';
	
	/**
	 * The hex code of the dominant color
	 * of the given image
	 *
	 * @var string
	 */
	private $color;
	
	/**
	 * The Base64 code of the GIF image
	 * from the given color
	 * it is a string containing the encoding of a 1x1 px GIF image
	 *
	 * @var string
	 */
	private $gif;
	
	
	/**
	 * Helper function.
	 * Set the @var $color from the given image
	 * Set the @var $gif from the newly created color
	 *
	 * @param $img
	 *
	 * @return string @var $color
	 */
	public function create($img)
	{
		$this->setColor($img);
		$this->setGIF();
		
		return $this->gif;
	}
	
	/**
	 * Find the dominant color of an image and store the hex code in @var $color
	 *
	 * @param $image Must be a valid path to an image or an instance of \Imagick
	 *
	 * @return $this
	 */
	public function setColor($image)
	{
		$is_imagick = $image instanceof Imagick;
		if(!$is_imagick){
			$image = new Imagick($image);
		}
		$image->resizeImage(250, 250, Imagick::FILTER_GAUSSIAN, 1);
		$image->quantizeImage(1, Imagick::COLORSPACE_RGB, 0, false, false);
		$image->setFormat('RGB');
		
		$this->color =  substr(bin2hex($image), 0, 6);
		
		return $this;
	}
	
	/**
	 * From the given hex code, create the string of the encoded GIF
	 *
	 * @param null $color
	 *
	 * @return $this
	 * @throws \Exception
	 */
	public function setGIF($color = null)
	{
		if (empty($color)){
			$color = $this->color;
		}
		ltrim($color, '#');
		if (strlen($color) != 6){
			throw new \Exception('Invalid color format, color must be full 6 digits hex code');
		} 
		$gif = implode( array(
			$this->header,
			$this->logical_screen_descriptor,
			$color,
			'000000',
			$this->image_descriptor,
			$this->image_data,
			$this->trailer
		) );
		$this->gif = 'data:image/gif;base64,' . base64_encode( hex2bin( $gif ) );
		
		return $this;
	}

	/**
	 * @var $gif getter 
	 *
	 * @return string
	 */
	public function getGif(){
		return $this->gif;
	}

	/**
	 * Get the color in the specified format
	 * Output options:
	 * "hex": Hex code (ex. "#FFFFFF")
	 * "rgb": Rgb format string (ex. "255,255,255")
	 * "array": Rgb format as an array (ex. ['r' => 255, 'g' =>  255, 'b' => 255])
	 * Default: plain hex code without the "#"
	 * 
	 * @var $color getter 
	 *
	 * @param string $type
	 *
	 * @return string|array
	 */
	public function getColor($type = 'string'){
		switch ($type) {
			case 'hex':
				return '#' . strtoupper($this->color);	
			break;
			case 'rgb':
				return $this->colorToRgbString();
			case 'array':
				return $this->colorToRgbArray();
			break;	
			default:
				return $this->color;
			break;
		}
		
	}

	/**
	 * Convert the @var $color in Rgb string
	 *
	 * @return string
	 */
	private function colorToRgbString(){
		list($r, $g, $b) = $this->hexToRGB($this->color);
		return "$r, $g, $b";
	}

	/**
	 * Convert the @var $color in Rgb array with named keys
	 *
	 * @return array
	 */
	private function colorToRgbArray(){
		list($r, $g, $b) = $this->hexToRGB($this->color);
		return [
			'r' => $r, 
			'g' => $g,
			'b' => $b
		];
	}

	/**
	 * Convert the @var $color in RGB array
	 *
	 * @return array
	 */
	private function hexToRGB($color){
		return sscanf($color, "%02x%02x%02x");
	}

}
