# yii2-magnific-popup

**_Widget is under development_**

This widget is wrapper for awesome jQuery plugin Magnific Popup By Dmitry Semenov http://dimsemenov.com/plugins/magnific-popup/

Magnific Popup is a responsive lightbox & dialog script with focus on performance and providing best experience for user with any device
(for jQuery or Zepto.js).
## Installing 
``` composer require porcelanosa/yii2-magnific-popup "master-dev"```
## Simple usage
in view file 
```html
	<a href="original.jpg" title="Caption text"><img  src="/thumb_img.jpg" alt="Alt"></a>
```
```php
use porcelanosa\magnificPopup\MagnificPopup;

echo MagnificPopup::widget(
	[
		'target' => '#mpup',
		'options' => [
			'delegate'=> 'a',
		]
	]
);
```
for gallery
```html
<div class="col-md-6" id="mpup">
	<a href="original.jpg" title="Caption for first"><img  src="/thumb_img.jpg" alt="Alt"></a>
	<a href="original2.jpg" title="Caption for second"><img  src="/thumb_img2.jpg" alt="Alt"></a>
</div>
```
```php
echo MagnificPopup::widget(
	[
		'target' => '#mpup',
		'options' => [
			'delegate'=> 'a',
		],
		'effect' => 'with-zoom' //for zoom effect
	]
);
```
