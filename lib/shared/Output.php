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

class TechMVC extends TechMVCCore {

  /**
   * Convert and String to Standard URL format with date stamp support
   * @param $str
   * @param $created
   */
  public static function toUrl($str,$created = false) {
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
    * Execute a Module and Generate Output
   */  
  public static function ExecuteModule($uri) {
    self::module($uri);
    print self::template($uri);
  }
  
  
  /**
    * Create AJAX tempplate
   */
  public static function AjaxTemplate($tpl, $param) {
    global $var;
    mb_http_output("UTF-8");
    ob_start("mb_output_handler");
    include($var->theme_path.'template'.DIRECTORY_SEPARATOR.$tpl);
    $html = ob_get_contents();
    ob_end_clean();
    @header('Content-type: text/html');
    print $html;
    exit();
  }
  
  
  public static function Template($uri, $tpl='', $param = array()) {
    global $var,$base_data;

    if($uri != '/' && $tpl=='' && empty($param)) {
      if(isset($base_data[$uri]))
        print $base_data[$uri];
      else
        exit('Module "'.$uri.'" not executed.');
    }
    
    else {
      //  $tpl = array('user/login.tpl', 'base/form.tpl');
      if(is_array($tpl)) {        
        foreach($tpl as $templatefile) {
          if($var->themeid) {
            //   load tpl from installed themes
            $tplpath = $var->theme_path.Utility::getBaseid($var->themeid).DIRECTORY_SEPARATOR.$var->themeid.DIRECTORY_SEPARATOR.$templatefile;
          }
          else  {
            //   load tpl from default themes
            $tplpath = $var->theme_path.'default'.DIRECTORY_SEPARATOR.'template'.DIRECTORY_SEPARATOR.$templatefile;
          }
          if(!is_file($tplpath)) {
            $tplpath = $var->ui_static_path.$templatefile;
            //  if internal default tpl found 
            if(is_file($tplpath)) {
              break;
            }
          }
          else {
            break;
          }
        }
      }
      else {
        $tplpath = $var->theme_path.'template'.DIRECTORY_SEPARATOR.$tpl;
        if(!is_file($tplpath)) {
          exit('Path Error: '.$tplpath);
        }
      }
      if(false === $var->module) {        
        ob_start();
        include($tplpath);    
        $base_data['#body'] = ob_get_contents();
        ob_end_clean();
        return true;
      }
      else if($var->module) {
        ob_start();
        include($tplpath);
        $base_data[$uri] =  ob_get_contents();
        ob_end_clean();
      }
    }
  }
  
  
  /**
    * Overwrite any CSS dynamically from page. Work for both AJAX calls & Page Refresh
    * $param = array(
    *   'div#textID'      =>  'color: red; font-weight: bold;',
    *   'div#textClass'   =>  'color: blue; font-size: 14px;  line-height: 24px;',
    * );
   */
  public static function rewriteCSS($param) {
    global $var,$base_data;
    //  TODO: valid CSS checks to be added
    $cssString = '';
    foreach($param as $dom  =>  $css) {
      $cssString .= $dom.'  { '.$css.'  } ';
    }
    $base_data['#rewrite_css'][] = $cssString;
    return $css;
  }
  
  
  /**
    * Add/Update/Remove any Metatags dynamically from controllers/models. 
    * Works only with Page Refresh
    * 
    * $param = array(
    *   'title'         =>  'My Cutsom Text',
    *   'keywords'      =>  'my, custom, text',
    *   'description'   =>  false,      //  remove this tag for current page
    *   'mytag'         =>  'This is my custom tag',      //  add new tag for current page
    *   'og:title'      =>  'My Title',      //  add new tag for current page for open graph support
    * );
   */
  public static function updateMetaTags($param) {
    global $var;
    $meta_tags = $var->defaultMetaTags;
    foreach($param as $key  =>  $value) {
      //  to remove any meta tags for any specific page just set as false
      if(false === $value) {
        unset($meta_tags[$key]);
      }
      else {
        $meta_tags[$key] = $value;
      }
    }
    $var->defaultMetaTags = $meta_tags;
    return $var->defaultMetaTags;
  }
  
  
  /**
    * Add custom Link tags dynamically from controllers/models.
    * This can add the link tags in append mode
    * Works only with Page Refresh
    * 
    * $param = array(
    *   array(
    *   	'rel'	=>	'dns-prefetch',
    *   	'href'	=>	'http://www.techunits.com'
    *   ),
    *   array(
    *   	'rel'	=>	'dns-prefetch',
    *   	'href'	=>	'http://www.techmvc.com'
    *   ),
    *   array(
    *   	'rel'	=>	'canonical',
    *   	'href'	=>	'https://www.techmvc.com'
    *   ),
    *   array(
    *   	'rel'	=>	'shortcut icon',
    *   	'type'	=>	'image/x-icon',
    *   	'href'	=>	'http://www.techunits.com/favicon.ico'
    *   ),
    * );
   */
  public static function createCustomLinkTags($param = array()) {
    global $var, $base_data;
    $tag = '';
    foreach($param as $linkInfo) {
      $tag .= '<link ';
      foreach($linkInfo as $attr  =>  $value) {
        $tag .= $attr.'="'.$value.'" ';
      }
      $tag .= '/>';
    }
    $base_data['#custom_link_tags'] = (!empty($base_data['#custom_link_tags']))?$base_data['#custom_link_tags']."\n\t".$tag:"\n\t".$tag;
    return $base_data['#custom_link_tags'];
  }
  
  
  /**
    * Show final HTML with base template + meta info + css + js included
   */
  public static function html_page($param = array()) {
    global $var, $base_data;
    @header('Content-type: text/html');
    mb_http_output("UTF-8");
    ob_start("mb_output_handler");
    ob_implicit_flush(0);
    $base_data['#ui_style'] = self::produceStyleSheetTags();
    $base_data['#meta_data']  = self::produceMetaTags();
    if(empty($base_data['#body']))
      $base_data['#body'] = '';
    if(true === $var->EnableHTML5) {
      include_once($var->lib_path.'shared'.DIRECTORY_SEPARATOR.'UI'.DIRECTORY_SEPARATOR.'base.tpl.HTML5');
    }
    else {
      include_once($var->lib_path.'shared'.DIRECTORY_SEPARATOR.'UI'.DIRECTORY_SEPARATOR.'base.tpl');
    }
    $output = ob_get_contents();
    $pagesize = ob_get_length();
    ob_end_clean();
    
    if($var->cache)
      Utility::HTMLCache($output);

    print $output;
    return array(
      'pagesize'  =>  $pagesize,
    );
  }

  
  /**
    * generate XML Page
   */
  public static function xml_page() {
    global $var;
    @header('Content-type: text/xml');
    ob_start();
    include_once($var->lib_path.'shared'.DIRECTORY_SEPARATOR.'ui'.DIRECTORY_SEPARATOR.'base.xml');
    $output = ob_get_contents();
    ob_end_clean();
    print $output;
  }

  
  /**
    * Show Formatted Output for any Datatype
   */
  public static function Display($arr , $status = false) {
    global $var;
    if(!$status) {
      echo "<pre>";
      var_dump($arr);
      echo "</pre>";
    }
    else {
      echo "<pre>";
      print_r($arr);
      echo "</pre>";
    }
  }
  
  
  /**
    * Create HTML Blocks
   */
  protected static function HTMLBlock( $part = '#header', $param = array() ) {
    global $var, $base_data;
    
    if(is_array($part)) {
      print_r($part);
	    exit();
    }
    else {
      $themeid = $var->themeid;
      $theme_colorscheme = $var->theme_colorscheme;
      
      switch($part) {
        case '#header':
          $part = 'header';
        break;
        
        case '#body':
          $part = 'base';
        break;
        
        case '#footer':
          $part = 'footer';
        break;
      }
      $tpl = $var->theme_path .$part.'.tpl';
    }
    
    mb_http_output("UTF-8");
    ob_start("mb_output_handler");
    include_once($tpl);
    $output = ob_get_contents();
    ob_end_clean();
    
    return $output;
  }
  
  
  /**
    * Create set of Meta tags and embed to page
   */
  protected static function produceMetaTags() {
    global $var;
    $meta_html = '';  
    $metaTags = (!empty($var->defaultMetaTags))?$var->defaultMetaTags:array();  
    foreach($metaTags as $key  =>  $value) {
      $meta_html .= '<meta name="'.$key.'" content="'.$value.'" />';
    }
    return $meta_html;
  }
  
  
  /**
    * Create set of link tags and embed to page
   */
  protected static function produceStyleSheetTags() {
    global $var,$base_data;
    $ui_style['css'] = '';
    $ui_style['js'] = '';
    
    /*  Include Theme Based CSS files   */
    if(true === $var->MergeCSS) {
      if(false === file_exists($var->base_path.'theme'.DIRECTORY_SEPARATOR.$var->themeid.DIRECTORY_SEPARATOR.'css'.DIRECTORY_SEPARATOR.'theme.combined.css')) {
        $cssString = '';
        if(!empty($var->ui_style[$var->themeid]['css'])) {          
          foreach($var->ui_style[$var->themeid]['css'] as $css) {
            $cssString.= file_get_contents($var->base_path.'theme'.DIRECTORY_SEPARATOR.$var->themeid.DIRECTORY_SEPARATOR.'css'.DIRECTORY_SEPARATOR.$css);
          }
        }
        /*  Load Theme.css  */
        $cssString .= file_get_contents($var->base_path.'theme'.DIRECTORY_SEPARATOR.$var->themeid.DIRECTORY_SEPARATOR.'css'.DIRECTORY_SEPARATOR.'theme.css');
        
        /*  Save Merged CSS Version */
        $cssString = trim(str_replace(array("\r\n","\r","\n","\t"), '', str_replace('icon/', '../icon/', str_replace('../', '', $cssString))));
        file_put_contents($var->base_path.'theme'.DIRECTORY_SEPARATOR.$var->themeid.DIRECTORY_SEPARATOR.'css'.DIRECTORY_SEPARATOR.'theme.combined.css', $cssString);        
      }
      /*  Add Merged file to the CSS Queue  */
      $ui_style['css'] .= '<link href="'.$var->css_url.'theme.combined.css" media="all" rel="stylesheet" type="text/css" />';
    }
    else {  
      if(!empty($var->ui_style[$var->themeid]['css'])) {
        foreach($var->ui_style[$var->themeid]['css'] as $css) {    
          $ui_style['css'] .= '<link href="'.$var->css_url.$css.'" media="all" rel="stylesheet" type="text/css" />';
        }
      }
      $ui_style['css'] .= '<link href="'.$var->css_url.'theme.css" media="all" rel="stylesheet" type="text/css" />';
    }
    
    /*  Combine Theme Based JS files */
    if(true === $var->MergeJS) {
      if(false === file_exists($var->base_path.'theme'.DIRECTORY_SEPARATOR.$var->themeid.DIRECTORY_SEPARATOR.'js'.DIRECTORY_SEPARATOR.'theme.combined.css')) {
        $jsString = '';
        if(!empty($var->ui_style[$var->themeid]['js'])) {          
          foreach($var->ui_style[$var->themeid]['js'] as $js) {
            $jsString.= file_get_contents($var->base_path.'theme'.DIRECTORY_SEPARATOR.$var->themeid.DIRECTORY_SEPARATOR.'js'.DIRECTORY_SEPARATOR.$js);
          }
        }        
        /*  Save Merged JS Version */
        $cssString = trim(str_replace(array("\r\n","\r","\n","\t"), '', str_replace('icon/', '../icon/', str_replace('../', '', $jsString))));
        file_put_contents($var->base_path.'theme'.DIRECTORY_SEPARATOR.$var->themeid.DIRECTORY_SEPARATOR.'js'.DIRECTORY_SEPARATOR.'theme.combined.js', $jsString);        
      }
      /*  Add Merged file to the JS Queue  */
      $ui_style['js'] .= '<script src="'.$var->js_url.'theme.combined.js" type="text/javascript"></script>';
    }
    else {
      if(!empty($var->ui_style[$var->themeid]['js'])) {
        foreach($var->ui_style[$var->themeid]['js'] as $js) {    
          $ui_style['js'] .= '<script src="'.$var->js_url.$js.'" type="text/javascript"></script>';
        }
      }
    }
    
    //  TPL BASE CSS REWRITE
    $r = '';
    if(!empty($base_data['#rewrite_css']))  {
      $r = '<style type="text/css">';
      foreach($base_data['#rewrite_css'] as $rcss) {
        $r .= $rcss;
      }
      $r .= '</style>';
    }
    $ui_style['css'] .= $r;
    
    return $ui_style;
  }
}
