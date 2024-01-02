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

class VariableHandler {
  
  public $var_arr = array();
  
  public function __construct() {
    $this->var_arr['base_path']         =   getcwd().DIRECTORY_SEPARATOR;
    $this->var_arr['lib_path']          =   $this->base_path.'lib'.DIRECTORY_SEPARATOR;
    $this->var_arr['lib_info']          =   'Application Powered By: TechMVC';
    $this->var_arr['module']            =   false;
    $this->var_arr['html']              =   true;
  } 

  public function v($var, $value = null) {     
    if($value !== null) {
      $this->var_arr[$var] = $value;  
    } 
    else {       
      if(isset($this->var_arr[$var])) {         
        return($this->var_arr[$var]);       
      } 
      else {         
        return(false);       
      }     
    }   
  }    
  
  public function __get($var) {     
    return($this->v($var));   
  }    
  
  public function __set($var, $value) {     
    $this->v($var, $value);   
  }    
  
  public function __isset($var) {     
    return(isset($this->var_arr[$var]));   
  }    
  
  public function __unset($var) {     
    if(isset($this->var_arr[$var])) {       
      unset($this->var_arr[$var]);       
      return true;     
    } 
    else       
      return false;   
  } 
}

global $var;
$var = new VariableHandler();
