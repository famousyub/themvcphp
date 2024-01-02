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

class TechMVCValidate {

  public static function is_valid_postdata($form) {
	  global $var;
    $flag = true; 
    $backtrace = debug_backtrace();
    $formname = strtolower($backtrace[1]['class']).'_'.$backtrace[1]['function'];     // backward compatibility
    if(!isset($var->post['formname'])) {
      return false;
    }
    if(isset($var->post['formname']) && $formname != $var->post['formname'].'Action') {
      exit('<font color="red"><b>ERROR:</b> Function Name and Form Hidden Field doesnot match.</font>');
    }
    foreach($form as $key =>  $f) {
      if(isset($key) && '' != $key && 'form' != $key && 'hidden' != $key && 'submit' != $key) {
        if(isset($f['attr']['name'])) {
          $field = $f['attr']['name'];
          $field_value = (isset($f['attr']['value']))?$f['attr']['value']:'';
          $fieldtype = (isset($f['attr']['type']))?$f['attr']['type']:'';
          $required = (isset($f['required']) && $f['required'])?$f['required']:false;
          
          if(true === $required) {
            if(strtolower($fieldtype) == 'file') {
              if(isset($_FILES[$field]['tmp_name']) && '' == $_FILES[$field]['tmp_name'] || !isset($_FILES[$field])) {
                Utility::gen_error(array(
                  'type'	=>	'form',
                  'key'		=>	$field,
                  'error'	=>	'Invalid '.$key,
                ));
                $flag = false;
              }
            }
            else {
              if(isset($var->post[$field]) && '' == $var->post[$field] || !isset($var->post[$field])) {
                Utility::gen_error(array(
                  'type'	=>	'form',
                  'key'		=>	$field,
                  'error'	=>	'Invalid '.$key,
                ));
                $flag = false;
              }
              else  {
                switch($field) {
                //  verify email address
                  case 'email':
                    $match = array();
                    if(0 == preg_match(EMAIL, $var->post[$field], $match)) {
                      Utility::gen_error(array(
                        'type'	=>	'form',
                        'key'		=>	$field,
                        'error'	=>	'Invalid '.$key,
                      ));
                      $flag = false;                  
                    }
                  break;
                  
                  case 'password':                
                    $password = $var->post[$field];
                    if(strlen($var->post[$field]) < 6) {
                      Utility::gen_error(array(
                        'type'	=>	'form',
                        'key'		=>	$field,
                        'error'	=>	'Must be greater than or equals to 6',
                      ));
                      $flag = false;                  
                    }                
                  break;
                  
                  case 'repassword':                
                    if($password != $var->post[$field]) {
                      Utility::gen_error(array(
                        'type'	=>	'form',
                        'key'		=>	$field,
                        'error'	=>	'Two passwords don\'t match',
                      ));
                      $flag = false;
                    }
                  break;
                  
                }
              }
            }
          }
        }
      }
    }
    return $flag;
  }

  public static function is_template($tpl,$com = false) {
    global $var;
    if(!$com)
      $path = $var->ui_path.$var->sec1.DIRECTORY_SEPARATOR.$tpl;
    else
      $path = $var->ui_path.$tpl;
    if(is_file($path))
      return true;
    else
      return false;
  }
  
}
