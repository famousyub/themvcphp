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

class TechMVCUtility {
  
  /**
   * Find date differnce
   * @param $dformat
   * @param $endDate
   * @param $beginDate
   */
  public static function dateDiff($dformat, $endDate, $beginDate) {
    $date_parts1=explode($dformat, $beginDate);
    $date_parts2=explode($dformat, $endDate);
    $start_date=gregoriantojd($date_parts1[0], $date_parts1[1], $date_parts1[2]);
    $end_date=gregoriantojd($date_parts2[0], $date_parts2[1], $date_parts2[2]);
    return ($end_date - $start_date);
  }
  
  /**
   * Find date differnce in user readable format
   * @param $start
   * @param $end
   * @param $aggregate
   * @param $ago
   */
  public static function dateDiffCustom($start, $end="NOW", $aggregate=true, $ago = 'ago') {
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

  /**
   * Shorten any string with specified limit
   * @param $str
   * @param $len
   */
  public static function Describe($str, $len = 20) {
    if(strlen($str) > $len)
      return substr($str,0,$len).'...';
    else
      return substr($str,0,$len);
  }
  
  /**
    * Process Directory Listing 
   */
  public static function ProcessDirectory($dir, $recursive = FALSE) {
    if (is_dir($dir)) {
      for ($list = array(),$handle = opendir($dir); (FALSE !== ($file = readdir($handle)));) {
        if (($file != '.' && $file != '..') && (file_exists($path = $dir.'/'.$file))) {
          if (is_dir($path) && ($recursive)) {
            $list = array_merge($list, self::ProcessDirectory($path, TRUE));
          } 
          else {
            $entry = array('filename' => $file, 'dirpath' => $dir);
            $entry['modtime'] = filemtime($path);
            do if (!is_dir($path)) {
              $entry['size'] = filesize($path);
              if (strstr(pathinfo($path,PATHINFO_BASENAME),'log')) {
                if (!$entry['handle'] = fopen($path,r)) $entry['handle'] = "FAIL";
              }  
              break;
            }
            else {
              break;
            } while (FALSE);
            $list[] = $entry;
          }
        }
      }
      closedir($handle);
      return $list;
    } 
    else 
      return FALSE;
  }
  
  /**
    * Get BaseID from a contentID
   */ 
  public static function getBaseid($id) {
    return intval(intval($id)/10000);
  }

  public static function ResetHTMLCache($uri) {
    global $var;
    if(is_array($uri)) {
      foreach($uri as $u) {
        $uri = str_replace('/',DIRECTORY_SEPARATOR,substr($u,1));
        $path = $var->cache_path.$uri.DIRECTORY_SEPARATOR.'index.html';
        if(is_file($path)) {
          unlink($path);
          $path = $var->cache_path.$uri.DIRECTORY_SEPARATOR.'index.html.gz';
          unlink($path);
          remove_directory($var->cache_path.$uri);
        }
      }
    }
    else {
      if(strstr($uri,'http:')) {
        $uri = substr(trim($uri),strlen('http://'.$var->host));
      }    
      $uri = str_replace('/',DIRECTORY_SEPARATOR,substr($uri,1));
      $path = $var->cache_path.$uri.DIRECTORY_SEPARATOR.'index.html';
      mdelete('system', '/'.$uri);
      
      if(is_file($path)) {
          unlink($path);
          $path = $var->cache_path.$uri.DIRECTORY_SEPARATOR.'index.html.gz';
          if(is_file($path)) {
            unlink($path);
          }
          remove_directory($var->cache_path.$uri);
       }
      else {
        return false;
      }
    }
    return true;
  }

  public static function HTMLCache($html = '') {
    global $var;
    if(0 == strlen($html)) {
      $uri = str_replace('/',DIRECTORY_SEPARATOR,substr($var->request_url,1));    
      if(isset($var->get) && count($var->get) > 0){ 
        $dir = $var->cache_path.$uri.urlencode($var->get_request_string).DIRECTORY_SEPARATOR;
      }
      else
        $dir = $var->cache_path.$uri;
      $path = $dir;
      
      // RETRIEVE XML info
      if(is_file($path.'/.info.json')) {
        $json_data = json_decode(file_get_contents($path.'/.info.json'), true);
        $expiry_time = $json_data['expired'];
        if(time() >= $expiry_time) {
          //  Cache Expired. Cache needs to be regenerated.
          return false;
        }
      }
      else {
        //  JSON info needs to be regenerated
        return false;
      }

      if(is_file($path.'/index.html')) {
        //  RETRIEVE FROM HTML DISKCACHE
        $var->cache = true;
        $html = file_get_contents($path.'/index.html');

        //  Output HTML to the Webserver
        print $html;
        exit();
      }
    }
    else {
      $expiry_time = $var->cache_expiry_time;
      $uri = str_replace('/',DIRECTORY_SEPARATOR,substr($var->request_url,1));
      if(isset($var->get) && count($var->get) > 0){ 
        $dir = $var->cache_path.$uri.urlencode($var->get_request_string).DIRECTORY_SEPARATOR;
      }
      else {
        $dir = $var->cache_path.$uri;
      }
      	
      $path = $dir;
      //  CREATE HTML DISK CACHE
      if(!is_dir($dir))
        mkdir($dir, 0766, true);
        
      // Save XML info
      $fp = fopen($path.'/.info.json', 'w');
      if(!$fp) {
        exit('Can not Create Cache.');
      }
      $json_data = array(
        'created' =>  time(),
        'expired' =>  time() + intval($expiry_time),
      );
      fwrite($fp,json_encode($json_data));
      fclose($fp);
      
      //  Save HTML Page
      $fp = fopen($path.'/index.html', 'w');
      fwrite($fp,$html);
      fclose($fp);
    }
  }

  public static function gen_error($param) {
	  global $var , $base_data;
	  /*
		  $param = array(
			  'type'	=>	'form',
			  'key'	  =>	'key name',
			  'error'	=>	'Data',
		  );
	  */
	  if(isset($base_data['#error'])) {
		  $error = $base_data['#error'];
	  }
	  else {
		  $error = array();
	  }
	  if(isset($param['type']) && $param['type'] != '')  {		
		  $e = (isset($param['error']))?$param['error']:'';
		  $k = (isset($param['key']))?$param['key']:'';
		  if(isset($error[$param['type']]) && is_array($error[$param['type']])) 
			  array_push_associative($error[$param['type']], array($k	=>	$e,));
		  else
			  $error[$param['type']] = array($k	=>	$e,);
	  }
	  else {
		  print "*** [ PART : lib_error ] ***";
		  print "No error type Specified.";
		  exit();
	  }
	  $base_data['#error'] = $error;
  }

  public static function gen_table($table)  {
    global $var;
    $thead = (isset($table['thead']) && is_array($table['thead']))?$table['thead']:false;
    $tbody = (isset($table['tbody']) && is_array($table['tbody']))?$table['tbody']:false;
    $html = '<table ';
    foreach($table as $attr =>  $value) {
      if('thead' != $attr && 'tbody' != $attr && 'colwidth' != $attr)  {
        if(is_numeric($value)) {
          $html .= $attr."=".$value;
        }
        else  {
          $html .= $attr."=".$value;
          $html .= $attr."=\"".$value."\"";
        }
      }
    }
    $html .= ' ><thead><tr>';
    foreach($thead as $head) {
       $html .= '<th>'.$head.'</th>';
    }
    $html .= '</tr></thead><tbody>';
    foreach($tbody as $values) {
      $html .=  '<tr>';
      foreach($values as $v)  {
       $html .= '<td>'.$v.'</td>';
      }
      $html .=  '</tr>';
    }
    $html .= '</tbody></table>';
    return $html;
  }
  
}
