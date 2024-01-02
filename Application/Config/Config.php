<?php

/*
******************************************************************************************   

  Package            : TechMVC  [ Web Application Framework ]
  Version            : 3.1.1
      
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

/*  List of Database Servers Available to the Project   */
$db_serverlist = array(
  'localhost',
);

$var->db = array(
  'server'    => $db_serverlist[rand(0,(count($db_serverlist)-1))],
  'user'      => 'root',
  'password'  => '',
  'database'  => '',
);
 
$var->html = true;

/*    Enable HTML 5 Support   */
$var->EnableHTML5 = true;
 
$domain = 'techmvc.com';
$var->domain = $domain;

$var->themeid = 'default';

//  To use session features please make it true
$var->session = true;

/*  System Defined Filesystem Paths  */
$var->language_path         =   $var->base_path.'Application'.DIRECTORY_SEPARATOR.'Language'.DIRECTORY_SEPARATOR;
$var->controllers_path      =   $var->base_path.'Application'.DIRECTORY_SEPARATOR.'Controllers'.DIRECTORY_SEPARATOR;
$var->models_path           =   $var->base_path.'Application'.DIRECTORY_SEPARATOR.'Models'.DIRECTORY_SEPARATOR;
$var->interfaces_path       =   $var->base_path.'Application'.DIRECTORY_SEPARATOR.'Interfaces'.DIRECTORY_SEPARATOR;
$var->theme_base_path       =   $var->base_path.'theme'.DIRECTORY_SEPARATOR;
$var->media_path            =   $var->base_path.'media'.DIRECTORY_SEPARATOR;
$var->tmp_path              =   $var->base_path.'tmp'.DIRECTORY_SEPARATOR;
$var->index_path            =   $var->tmp_path.'index'.DIRECTORY_SEPARATOR;
$var->cache_path            =   $var->tmp_path.'cache'.DIRECTORY_SEPARATOR.$domain.DIRECTORY_SEPARATOR;

/*  System Defined URLs  */
$var->theme_base_url  =   'theme/';
$var->media_url       =   'media/';

$var->MergeCSS = true;
$var->MergeJS = true;
/*  Load Theme Based javascript library or framework  & CSS   */
$var->ui_style = array(
  'default' =>  array(
    'js'  =>  array(
      'jquery.min.js',
    ),
    'css'  =>  array(
      'test.css',
    ),
  ),
);

/*  To Search bot know whether to cache the page  */
$var->bot_index = 'index';

/*  Setup Meta tags for whole site and can be overwritten from any page for that page  */
$var->defaultMetaTags = array(
  'title'         =>  'TechMVC Web Application Framework',
  'keywords'      =>  'TechMVC, Techunits, MVC2, HTML 5 Enabled, Open Graph Enabled',
  'description'   =>  'TechMVC - Web Application API Framework - HTML 5 Supported along with Open Graph Support',
);
$var->title = 'TechMVC - Web Application API Framework - HTML 5 Supported';
$var->defaultCharacterSet = 'UTF-8';

/*  List of Executable URL    */
$var->available_uri = array(  
  'home'        =>  'home',
);

//  Webpage Cache Info
$var->cache = false;                //  The following options will be available if the $var->cache is set to true.
$var->cache_expiry_time = 3600;     //  seconds

$var->allow_extension = array('html', 'htm', 'aspx', 'asp', 'jsp', 'jspx', 'php', 'pro');

$var->URLErrorHandler = array(
//  '404'	=>  'URL:/home',
  '404' =>  'Requested URL not registered. Please contact development team.',
);
