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

if (!function_exists('json_encode')) {
  TechMVC::LoadLibrary('JSON.php', true);
  function json_encode($arg) {
    global $services_json;
    if (!isset($services_json)) {
      $services_json = new Services_JSON();
    }
    return $services_json->encode($arg);
  }

  function json_decode($arg) {
    global $services_json;
    if (!isset($services_json)) {
      $services_json = new Services_JSON();
    }
    return $services_json->decode($arg);
  }
}

function get_locale_string($key) {
  global $var;  
  $lang_pref = (isset($var->language_preference) && '' != $var->language_preference)?$var->language_preference:'english';
  //  Include Language Files  
  require($var->language_path.$lang_pref.'.inc');  
  return (isset($language[$lang_pref][$key]))?$language[$lang_pref][$key]:'N/A';
}

function get_host($url) {
  $matches = array();
  preg_match('@^(?:http://)?([^/]+)@i', $url, $matches);
  if(isset($matches[1]))  {
    $host = $matches[1];
    return $host;
  }
  return false;
}

function send_mail($email_to, $email_from, $email_subject='' , $email_message= '', $filepath = false, $filename = false) {
  if($filepath && $filename) {
    $fileatt_type = "application/octet-stream"; // File Type
    $headers = "From: ".$email_from;
    $file = fopen($filepath.$filename,'rb');
    $data = fread($file, filesize($filepath.$filename));
    fclose($file);
    $semi_rand = md5(time());
    $mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";
    $headers .= "\nMIME-Version: 1.0\n" .
    "Content-Type: multipart/mixed;\n" .
    " boundary=\"{$mime_boundary}\"";
    $email_message .= "This is a multi-part message in MIME format.\n\n" .
    "--{$mime_boundary}\n" .
    "Content-Type:text/html; charset=\"iso-8859-1\"\n" .
    "Content-Transfer-Encoding: 7bit\n\n" .
    $email_message . "\n\n";
    $data = chunk_split(base64_encode($data));
    $email_message .= "--{$mime_boundary}\n" .
    "Content-Type: {$fileatt_type};\n" .
    " name=\"{$filename}\"\n" .
    "Content-Transfer-Encoding: base64\n\n" .
    $data . "\n\n" .
    "--{$mime_boundary}--\n";
    $ok = @mail($email_to, $email_subject, $email_message, $headers); 
    return $ok;
  }
  else {
    $headers = 'From: '.$email_from."\r\n" . 'Content-Type:text/html;' . "\r\n" . 'Reply-To: '.$email_from."\r\n" . 'X-Mailer: PHP/' . phpversion();
    return @mail($email_to, $email_subject, $email_message, $headers);
  }
}

function array_push_associative(&$arr) {
  $args = func_get_args();
	$ret = 0;
  foreach ($args as $arg) {
      if (is_array($arg)) {
          foreach ($arg as $key => $value) {
              $arr[$key] = $value;
              $ret++;
          }
      }else{
          $arr[$arg] = "";
      }
  }
  return $ret;
}

function upload_file($param) {
  /**
    *@param:  array(
      'destination'       =>  '',
      'file_form_field'   =>  '',
      'savename'          =>  '',
    )
  */
  $dest = $param['destination'];
  $fieldname = $param['file_form_field'];
  $savename = $param['savename'];
  @mkdir($dest, 0755, true);
  if(isset($_FILES[$fieldname]['tmp_name']))
    return move_uploaded_file($_FILES[$fieldname]['tmp_name'], $dest.$savename);
  else
    return false;
}

function dateDiff($dformat, $endDate, $beginDate) {
  $date_parts1=explode($dformat, $beginDate);
  $date_parts2=explode($dformat, $endDate);
  $start_date=gregoriantojd($date_parts1[0], $date_parts1[1], $date_parts1[2]);
  $end_date=gregoriantojd($date_parts2[0], $date_parts2[1], $date_parts2[2]);
  return ($end_date - $start_date);
}

function date_diff_custom($start, $end="NOW", $aggregate=true, $ago = 'ago') {
  $sdate = strtotime($start);
  $edate = strtotime($end);
  $timeshift = '';
  $time = $edate - $sdate;
  if($time>=0 && $time<=59) {
    $timeshift = $time.' seconds '.$ago;
  } 
  else if($time>=60 && $time<=3599) {
    // Minutes + Seconds
    $pmin = ($edate - $sdate) / 60;
    $premin = explode('.', $pmin);
   
    $presec = $pmin-$premin[0];
    $sec = $presec*60;
   
    if(false === $aggregate) {
      $timeshift = $premin[0].' min '.round($sec,0).' sec ';
    }
    else {
      $timeshift = $premin[0].' min '.$ago;
    }
  } 
  else if($time>=3600 && $time<=86399) {
  // Hours + Minutes
  $phour = ($edate - $sdate) / 3600;
  $prehour = explode('.',$phour);
 
  $premin = $phour-$prehour[0];
  $min = explode('.',$premin*60);
 
  $presec = '0.'.$min[1];
  $sec = $presec*60;
  if(false === $aggregate) {
    $timeshift = $prehour[0].' hrs '.$min[0].' min '.round($sec,0).' sec ';
  }
  else {
    $timeshift = $prehour[0].' hrs '.$ago;
  }

  }
  else if($time>=86400) {
    // Days + Hours + Minutes
    $pday = ($edate - $sdate) / 86400;
    $preday = explode('.',$pday);

    $phour = $pday-$preday[0];
    $prehour = explode('.',$phour*24);

    $premin = ($phour*24)-$prehour[0];
    $min = explode('.',$premin*60);
   
    $presec = '0.'.$min[1];
    $sec = $presec*60;
   
    if(false === $aggregate) {
      $timeshift = $preday[0].' days '.$prehour[0].' hrs '.$min[0].' min '.round($sec,0).' sec ';
    }
    else {
      if($preday[0] > 7) {
        return date('d M, Y', strtotime($start));
      }
      else {
        $timeshift = $preday[0].' days '.$ago;
      }
    }
  }
  return $timeshift;
}

function remove_directory($dir) {
  if(is_dir($dir)) {
    if ($handle = opendir("$dir")) {
      while (false !== ($item = readdir($handle))) {
        if ($item != "." && $item != "..") {
          if (is_dir("$dir/$item")) {
            remove_directory("$dir/$item");
          } else {
            unlink("$dir/$item");
          }
        }
      }
      closedir($handle);
      rmdir($dir);
    }
  }
  else {
    print('RECACHE NOT POSSIBLE');
  }
}

/**
  * Convert and String to Standard URL format with date stamp support
 */
function name_to_url($str,$created = false) {
  mb_convert_encoding($str, "UTF-8");
  $str = str_replace('&','-and-',$str);
  $str = str_replace(' ','-',strtolower(trim($str)));
  $str = str_replace(',','-',$str);
  $str = str_replace('"','-',$str);
  $str = str_replace(';','-',$str);
  $str = str_replace('.','-',$str);
  $str = str_replace('`','-',$str);
  $str = str_replace('´','-',$str);
  $str = str_replace('¨','-',$str);
  $str = str_replace(':','-',$str);
  $str = str_replace('?','',$str);
  $str = str_replace('!','',$str);
  $str = str_replace('~','',$str);
  $str = str_replace('^','',$str);
  $str = str_replace('/','',$str);
  if($created) {
    $uri = date('Y/m/d', strtotime($created)).'/'.$str;
    return $uri;
  }
  return $str;
}


/**
  * Shorten any long string w.r.t specified size
 */
function describe($str,$len = 20) {
  if(strlen($str) > $len)
    return substr($str,0,$len).'...';
  else
    return substr($str,0,$len);
}
