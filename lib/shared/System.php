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

class TechMVCCore {
  /**
    * Load Custom Models for the Application
   */
  public static function LoadModel($model = false) {
    global $var;
    
    foreach($var->available_uri as $controllers) {
      //  include related interfaces if exists
      if(file_exists($var->interfaces_path.ucfirst($controllers).DIRECTORY_SEPARATOR.ucfirst($controllers).'.php')) {        
        require_once($var->interfaces_path.ucfirst($controllers).DIRECTORY_SEPARATOR.ucfirst($controllers).'.php');        
      }
      
      //  include related models if exists
      if(file_exists($var->models_path.ucfirst($controllers).DIRECTORY_SEPARATOR.ucfirst($controllers).'.php')) {
        require_once($var->models_path.ucfirst($controllers).DIRECTORY_SEPARATOR.ucfirst($controllers).'.php');        
      }
    }
    return true;
  }
  
  /**
    * Include a custom library
   */
  public static function LoadLibrary($lib, $shared = false) {
    global $var;  
    
    /*  determine library type  */
    $part = (true === $shared)?'shared':'usr';
    set_include_path($var->lib_path.$part);
    $lib_base_path = $var->lib_path.$part.DIRECTORY_SEPARATOR;
    
    /*  If the passed Library name is not a path then use Default Path to include Library Automatically   */
    if(strstr($lib, '/') || strstr($lib, '.php') ) {
      require_once($lib_base_path.$lib);
    }
    else {
      require_once($lib_base_path.$lib.DIRECTORY_SEPARATOR.$lib.'.php');
    }
    
    return true;
  }
  
  /**
    * Parse URL headers
   */
  public static function ParseHeader() {
    global $var,$base_data;
    //  $_SERVER['REQUEST_URI'] = strtolower($_SERVER['REQUEST_URI']);
    @header('X-Powered-By: TechMVC/'.TECHMVC_VERSION.'/PHP'.phpversion());
    //  SET HEADERS AND OTHER IMP VALUES INTO SYSTEM VAR

    if(strlen($_SERVER['PHP_SELF']) > 10) {
      $var->base_uri_disk_path = substr($_SERVER['PHP_SELF'], 0, strpos($_SERVER['PHP_SELF'], substr($_SERVER['PHP_SELF'], -10, 10)));    
      $_SERVER['REQUEST_URI'] = str_replace($var->base_uri_disk_path, '', $_SERVER['REQUEST_URI']);
    }
    if('/' == $_SERVER['REQUEST_URI']) {
      $_SERVER['REQUEST_URI'] = '/home';
    }
    $var->uri = $_SERVER['REQUEST_URI'];
    $var->host = strtolower($_SERVER['HTTP_HOST']);
    $var->remote_ip = (isset($_SERVER['HTTP_X_REAL_IP']))?$_SERVER['HTTP_X_REAL_IP']:$_SERVER['REMOTE_ADDR'];
    if(isset($_SERVER['HTTP_USER_AGENT'])) {
      $var->user_agent = $_SERVER['HTTP_USER_AGENT'];
    }
    if(isset($_SERVER['REDIRECT_URL']))
      $var->request_url = strtolower($_SERVER['REDIRECT_URL']);
    else
      $var->request_url = '/';
    if(isset($_SERVER['HTTP_REFERER']))
      $var->http_referer = $_SERVER['HTTP_REFERER'];
    else
      $var->http_referer = '/';

    //  SECTION URLS INTO PARTS
    $t = explode('?', $_SERVER['REQUEST_URI']); 
    $url_parts = explode('/', self::parse_link_extension(strtolower($t[0])));  
    if(isset($url_parts[1]) && $url_parts[1] != '') {
      $var->sec1 = $url_parts[1];
      if(isset($url_parts[2]) && $url_parts[2] != '') {
        $var->sec2 = $url_parts[2];
        if(isset($url_parts[3]) && $url_parts[3] != '') {
          $var->sec3 = $url_parts[3];
          if(isset($url_parts[4]) && $url_parts[4] != '') {
            $var->sec4 = $url_parts[4];
            if(isset($url_parts[5]) && $url_parts[5] != '') {
              $var->sec5 = $url_parts[5];
              if(isset($url_parts[6]) && $url_parts[6] != '') {
                $var->sec6 = $url_parts[6];
                if(isset($url_parts[7]) && $url_parts[7] != '') {
                  $var->sec7 = $url_parts[7];
                  if(isset($url_parts[7]) && $url_parts[7] != '') {
                    $var->sec8 = $url_parts[8];
                    if(isset($url_parts[9]) && $url_parts[9] != '') {
                      $var->sec9 = $url_parts[9];
                      if(isset($url_parts[10]) && $url_parts[10] != '') {
                        $var->sec10 = $url_parts[10];
                      }
                    }
                  }
                }
              }
            }          
          }
        }
      }
    }
    
    //  SET $_GET VALUES INTO SYSTEM VAR IN APPROPRIATE FORMAT
    if(isset($t[1]) && $t[1] != '') {
      $var->get_request_string = $t[1];
      $get_tmp = explode('&',$t[1]);
      $get_arr = array();
      foreach($get_tmp as $get) {
        $get_temp = explode('=',$get);
        if(!empty($get_temp[1]) && !empty($get_temp[0]))
          $get_arr[$get_temp[0]] = $get_temp[1];
      }
      $var->get = $get_arr;
    }
    
    //  SET $_POST VALUES INTO SYSTEM VAR IN APPROPRIATE FORMAT
    $var->post = $_POST;
    $var->files = $_FILES;
  }

  public static function parse_link_extension($url_part) {
    global $var;
    $t = explode('.', $url_part);
    $allow_extension = $var->allow_extension;
    $url_part = trim($t[0]);
    if(isset($t[1]) && '' != $t[1]) {
      if(count(array_intersect($t, $allow_extension)) == 0) {
        exit('Extension not Supported.');
      }
    }
    return $url_part;
  }

  /**
    * Set new Theme from any module
   */
  public static function setTheme($themeid = 'default') {
    global $var;
    $var->themeid         =   $themeid;
    $var->theme_url       =   $var->base_uri_disk_path.'/'.$var->theme_base_url.$themeid.'/';
    $var->css_url         =   $var->theme_url.'css/';
    $var->js_url          =   $var->theme_url.'js/';
    $var->icon_url        =   $var->theme_url.'icon/';
    $var->swf_url         =   $var->theme_url.'icon/';
    $var->theme_path      =   $var->theme_base_path.$themeid.DIRECTORY_SEPARATOR;
    return true;
  }
  
  public static function processRequest() {
    global $var,$base_data;
    
    $user_defined = $var->available_uri;
    
    if(!$var->module) {
      self::ParseHeader();
      
      //  Set Media URL and Asset URL with respect to the disk path
      $var->media_url = (!stristr($var->media_url, 'http://'))?$var->base_uri_disk_path.'/'.$var->media_url:$var->media_url;
      self::setTheme($var->themeid);
      
      /* Setup 301 URL Autoredirect 
      if(!empty($var->auto_redirect[$var->uri])) {
        redirect($var->auto_redirect[$var->uri]);
      }*/
      
      /* If Page available in Cache and not expired then deliver from cache */
      if(true === $var->cache) {
        Utility::HTMLCache();
      }
    }
    
     // Custom Modification to Show Dynamic Page
    if(!$var->module && $var->site && gen_custom_page()) {
      exit();
    }
    
    //  URL Masking
    $var->uri_nonmasked = $var->uri;
    $var->sec1_nonmasked = $var->sec1;
    if(!empty($var->available_uri[$var->sec1])) {
      $func = $var->available_uri[$var->sec1];
      $var->sec1 = $var->available_uri[$var->sec1];
    }
    else {
      ErrorHandler::URL('404');
    }
    
    if(!empty($var->sec1)) {
      if(in_array($func ,array_keys($user_defined))) {      
        if(is_file($var->controllers_path.ucfirst($func).DIRECTORY_SEPARATOR.ucfirst($func).'.php')) {
          require_once($var->controllers_path.ucfirst($func).DIRECTORY_SEPARATOR.ucfirst($func).'.php');        
          /*  Check whether the static method is callable */
          if(is_callable($func.'::init')) {
            call_user_func($func.'::init');
            self::validate_global_var();
            return true;
          }
          else {
            exit('Module base init function not defined : /'.$func.'"');
          }
        }
        else  {        
          exit('Module base file not found : /'.$func.'"');
        }
      }
      else {
        $path = $var->base_path.$var->sec1.DIRECTORY_SEPARATOR.$var->sec2.DIRECTORY_SEPARATOR;
        if(is_dir($path)) {
          $f = $path.$var->sec3;
          if(is_file($f)) {
	          $data = file_get_contents($f);
	          $t = explode('.',$var->sec3);
	          $ext = $t[count($t)-1];
	          if($ext == 'xml')
	            header('Content-type: text/xml');
	          else if($ext == 'html')
	            header('Content-type: text/html');
	          else
	            header('Content-type: text/plain');
	          print $data;
	          exit;
	        }
        }
      }
      exit('Page doesn\'t Exists.');
    }
  }

  public static function processAction($loop = true)  {
    global $var;
    if(true === $loop) {
      if(isset($var->sec5) && '' != $var->sec5 && !is_numeric($var->sec5)) {
        if(is_callable($var->sec1.'::'.$var->sec2.'_'.$var->sec3.'_'.$var->sec4.'_'.$var->sec5.'Action')) {
          call_user_func($var->sec1.'::'.$var->sec2.'_'.$var->sec3.'_'.$var->sec4.'_'.$var->sec5.'Action');
          return true;
        }
      }
      if(isset($var->sec4) && '' != $var->sec4 && !is_numeric($var->sec4)) {
        if(is_callable($var->sec1.'::'.$var->sec2.'_'.$var->sec3.'_'.$var->sec4.'Action')) {
          call_user_func($var->sec1.'::'.$var->sec2.'_'.$var->sec3.'_'.$var->sec4.'Action');
          return true;
        }
      }
      if(isset($var->sec3) && '' != $var->sec3 && !is_numeric($var->sec3)) {
        if(is_callable($var->sec1.'::'.$var->sec2.'_'.$var->sec3.'Action')) {
          call_user_func($var->sec1.'::'.$var->sec2.'_'.$var->sec3.'Action');
          return true;
        }
      }
    }
    
    /**
      * Check Whether the Static Method is callable
     */
    if(is_callable($var->sec1.'::'.$var->sec2.'Action')) {
      call_user_func($var->sec1.'::'.$var->sec2.'Action');
      return true;
    }
    else  {
      if(is_callable($loop)) {
        call_user_func($loop);
        return true;
      }
      ErrorHandler::URL('404');
    }
  }

  public static function reset_global_settings() {
    global $var,$base_data;
    unset($var);
    $var = $base_data['var_bck'];
    $var->module = false;
    self::ParseHeader();
    return true;
  }

  public static function update_global_settings($uri) {
    global $var;
    global $base_data;
    $var->uri = $_SERVER['REQUEST_URI'];
    $base_data['var_bck'] = $var;
    $var->module = true;
    $t = explode('?',$uri);  
    $url_parts = explode('/',$t[0]);
    if(isset($url_parts[1]) && $url_parts[1] != '') {
      $var->sec1 = $url_parts[1];
      if(isset($url_parts[2]) && $url_parts[2] != '') {
        $var->sec2 = $url_parts[2];
        if(isset($url_parts[3]) && $url_parts[3] != '') {
          $var->sec3 = $url_parts[3];
          if(isset($url_parts[4]) && $url_parts[4] != '') {
            $var->sec4 = $url_parts[4];
            if(isset($url_parts[5]) && $url_parts[5] != '') {
              $var->sec5 = $url_parts[5];
              if(isset($url_parts[6]) && $url_parts[6] != '') {
                $var->sec6 = $url_parts[6];
              }
              else {
                unset($var->sec6);
              }
            }
            else {
              unset($var->sec5);
            }          
          }
          else {
            unset($var->sec4);
          }
        }
        else {
          unset($var->sec3);
        }
      }
      else {
        unset($var->sec2);
      }
    }
    else {
      unset($var->sec1);
    }
    return true;
  }

  public static function validate_global_var() {
    global $var;
    if($var->xml) {
      $var->html = false;
      $var->xml = true;
      return true;
    }
  }
  
  /**
   * Redirect control to any specified page/url
   * @param string $url
   */
  public static function Redirect($url) {
    global $var;
    if($target = Session::Get('target_redirect_url')) {
      skill('target_redirect_url');
      header('Location: '.$var->base_uri_disk_path.$target);
      exit();
    }
    if(is_array($url)) {
      if(isset($url[1])) {
        sset('target_redirect_url', $var->base_uri_disk_path.$url[1]);
      }
      header('Location: '.$var->base_uri_disk_path.$url[0]);
      exit();
    }
    if(stristr($url, 'http://') || stristr($url, 'https://')) {
      header('Location: '.$url);
    }
    else {
      header('Location: '.$var->base_uri_disk_path.$url);
    }
    exit();
  }
  
  /**
    * Execute a Module and Generate Output
   */
  protected static function module($uri,$return = true) {
    global $var,$base_data;
    if(isset($var->cache) && $var->cache) {
      Session::Set('html_cache',true);
    }
    else {
      Session::Set('html_cache',false);
    }
    self::update_global_settings($uri);
    $var->uri = $uri;
    $t = explode('?',$uri);
    //  SET $_GET VALUES INTO SYSTEM VAR IN APPROPRIATE FORMAT
    unset($var->get);
    if(isset($t[1]) && $t[1] != '') {
      $var->get_request_string = $t[1];
      $get_tmp = explode('&',$t[1]);
      foreach($get_tmp as $get) {
        $get_temp = explode('=',$get);
        $get_arr[$get_temp[0]] = $get_temp[1];
      }
      $var->get = $get_arr;
    }
    
    self::processRequest();

    self::reset_global_settings();
    $var->module = false;
    $var->cache = Session::Get('html_cache');
    return true;
  }  
}
