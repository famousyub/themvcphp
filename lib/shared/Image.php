<?php

/*
******************************************************************************************   

  Package            : TechMVC  [ Web Application Framework ]
  Version            : 3.2.1
      
  Lead Architect     : Sougata Pal. [ skall.paul@gmail.com ]     
  Year               : 2011 - 2012                                                       

  Site               : http://www.techunits.com/
  Contact / Support  : techmvc@googlegroups.com

  Copyright (C) 2009 - 2011 by TECHUNITS

  Permission is hereby granted, free of charge, to any person obtaining a copy
  of this software and associated documentation files (the "Software"), to deal
  in the Software without restriction, including without limitation the rights
  to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
  copies of the Software, and to permit persons to whom the Software is
  furnished to do so, subject to the following conditions:

  The above copyright notice and this permission notice shall be included in
  all copies or substantial portions of the Software.

  THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
  IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
  FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
  AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
  LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
  OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
  THE SOFTWARE.
  
******************************************************************************************   
*/


/*
$param = array(
  'img_src_path'    =>  './img/',
  'img_dest_path'   =>  './img/',
  'filename'        =>  'test.jpg',
  'size'            =>  array(
    'nano'    =>  array(
      'width'   =>  40,
      'height'  =>  50,
      'percent' =>  20,
      'name'    =>  'testn.jpg',
    ),
    'tiny'    =>  array(
      'width'   =>  120,
      'height'  =>  150,
      'percent' =>  40,
      'name'    =>  'testt.jpg',
    ),
    'medium'  =>  array(
      'width'   =>  400,
      'height'  =>  500,
      'percent' =>  60,
      'name'    =>  'testm.jpg',
    ),
    'big'     =>  array(
      'width'   =>  800,
      'height'  =>  1000,
      'percent' =>  80,
      'name'    =>  'testb.jpg',
    ),
  ),  
);
image_convert($param );
*/

class TechMVCImage {

  public static function Convert($param ) {
    global $var;
    
    $img_src = $param['img_src_path'];
    $img_dest = $param['img_dest_path'];
    $img_file = $param['filename'];
    $size = $param['size'];
    $percent = 0;
    $constrain = 10;
    $filename = substr($img_file,0,-4);
    
    $img = $img_src.$img_file;
    if(!is_file($img))
      return false;
      
    $x = @getimagesize($img_src.$img_file);       // get image size of img
    $sw = $x[0];      // image width
    $sh = $x[1];      // image height

    foreach($size as $key =>  $value) {
      $w = $value['width'];
      $h = $value['height'];    
  //    $percent = $value['percent'];
      if ($percent > 0) {
        $percent = $percent * 0.01;           // calculate resized height and width if percent is defined
        $w = $sw * $percent;
        $h = $sh * $percent;
      } 
      else {
        if (isset ($w) AND !isset ($h)) {
          $h = (100 / ($sw / $w)) * .01;      // autocompute height if only width is set
          $h = @round ($sh * $h);
        } 
        elseif (isset ($h) AND !isset ($w)) {
          $w = (100 / ($sh / $h)) * .01;      // autocompute width if only height is set
          $w = @round ($sw * $w);
        } 
        elseif (isset ($h) AND isset ($w) AND isset ($constrain)) {
          $hx = (100 / ($sw / $w)) * .01;     // get the smaller resulting image dimension if both height and width are set and $constrain is also set
          $hx = @round ($sh * $hx);
          $wx = (100 / ($sh / $h)) * .01;
          $wx = @round ($sw * $wx);
          if ($hx < $h) {
            $h = (100 / ($sw / $w)) * .01;
            $h = @round ($sh * $h);
          } 
          else {
            $w = (100 / ($sh / $h)) * .01;
            $w = @round ($sw * $w);
          }
        }
      }
      $im = @ImageCreateFromJPEG ($img) or // Read JPEG Image
      $im = @ImageCreateFromPNG ($img) or // or PNG Image
      $im = @ImageCreateFromGIF ($img) or // or GIF Image
      $im = false; // If image is not JPEG, PNG, or GIF
      if (!$im) {
        // We get errors from PHP's ImageCreate functions...
        // So let's echo back the contents of the actual image.
        readfile ($img);
      } 
      else {
        ob_start();
        $thumb = @ImageCreateTrueColor ($w, $h);      // Create the resized image destination
        @ImageCopyResampled ($thumb, $im, 0, 0, 0, 0, $w, $h, $sw, $sh);    // Copy from image source, resize it, and paste to image destination
        @ImageJPEG ($thumb);        // Output resized image
        $img_data = ob_get_contents();
        ob_end_clean();
        $fp = fopen($img_dest.$value['name'],'wb');
        fwrite($fp,$img_data);
        fclose($fp);
        unset($img_data);
      }
    }
  }
}
