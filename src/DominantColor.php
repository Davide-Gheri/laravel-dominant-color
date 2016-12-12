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
	public $color;
	
	/**
	 * The Base64 code of the GIF image
	 * from the given color
	 * it is a string containing the encoding of a 1x1 px GIF image
	 *
	 * @var string
	 */
	public $gif;
	
	
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
		if (strlen($color) != 6){
			throw new \Exception('Invalid color format, color must be hex code without "#"');
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
		$this->gif =  'data:image/gif;base64,' . base64_encode( hex2bin( $gif ) );
		
		return $this;
	}
	
}