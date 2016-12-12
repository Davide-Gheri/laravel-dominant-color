# laravel-dominant-color

A package that generates a base64 encoded GIF with the dominant color of the given image (for lazy loading use)

## Requires the php Imagik library to work!

## Installation

Require the package via composer

```
composer require ghero/laravel-dominant-color
```
Include the service provider within your providers in `config/app.php`.
```php
'providers' => [
    ...
    Ghero\DominantColor\DominantColorServiceProvider::class,
    ...    
];
```

Next, add the class alias to the aliases array of `config/app.php`.
```php
'aliases' => [
    ...
    'DominantColor' => Ghero\DominantColor\Facades\DominantColor::class,
    ...    
];
```

## Usage

Simply call `DominantColor::create($file);` passing a valid url to an Image or an Imagick instance

this will generate a string with the base 64 encoded 1x1 GIF 
(ex. `data:image/gif;base64,R0lGODlhAQABAIABAI2JggAAACwAAAAAAQABAAACAkQBADs=`)
The GIF is only 1 color, the dominant color of the image

You can then easily set this string as src attribute specifing the desired width and height:

```html
<img src='data:image/gif;base64,R0lGODlhAQABAIABAI2JggAAACwAAAAAAQABAAACAkQBADs=' width='200' height='200'/>
```

You can also get just the hex code (without the starting "#") of the dominant color using `$color = DominantColor::setColor($file)->color;`

To get the base 64 encoded GIF from an hex code, just use `$gif = DominantColor::setGif($color)->gif`, remember to pass an hex code WITHOUT the starting "#"


## Todo

* Create getter methods for $color and $gif with multiple output options for $color (RGB array)
* Allow passing an hex code with "#"
* Add GD and Gmagick support


