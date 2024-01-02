<?php

/*
******************************************************************************************   

  Package            : TechMVC  [ Web Application Framework ]
  Version            : 3.2.1
      
  Lead Architect     :  [ ayoub@gmail.com ]     
  Year               : 2011 - 2012                                                       


  Copyright (C) 2009 - 2011 by theyub


  
******************************************************************************************   
*/

error_reporting(E_ALL | E_STRICT);
ini_set('max_execution_time', 8);
ini_set('upload_max_filesize', '8M');
ini_set('display_errors', true); 

global $var;
require_once 'lib'.DIRECTORY_SEPARATOR.'shared'.DIRECTORY_SEPARATOR.'vars.php';
require_once 'Application'.DIRECTORY_SEPARATOR.'Config'.DIRECTORY_SEPARATOR.'Config.php';
require_once 'lib'.DIRECTORY_SEPARATOR.'shared'.DIRECTORY_SEPARATOR.'loader.php';

/*    To enable and disable system debug mode   */
$var->debug = false;

$var->auto_redirect = array(
  '/'     =>  '/home'
);

//  Set Language Info
$var->language_preference = (isset($_GET['language']) && 'spanish' == $_GET['language'])?'spanish':'english';

//  Initiate Base System...
TechMVC::processRequest();

//  Initiate  template System...
if($var->html) {
  $page_size = TechMVC::html_page();
}

exit();

?>
