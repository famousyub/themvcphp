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

require_once ($var->lib_path.'shared'.DIRECTORY_SEPARATOR.'Define.php');

require_once ($var->lib_path.'shared'.DIRECTORY_SEPARATOR.'Error.php');

require_once ($var->lib_path.'shared'.DIRECTORY_SEPARATOR.'Functions_Deprecated.php');

require_once ($var->lib_path.'shared'.DIRECTORY_SEPARATOR.'Utility.php'); 

require_once ($var->lib_path.'shared'.DIRECTORY_SEPARATOR.'Validate.php');

require_once ($var->lib_path.'shared'.DIRECTORY_SEPARATOR.'Session.php');

require_once ($var->lib_path.'shared'.DIRECTORY_SEPARATOR.'Database'.DIRECTORY_SEPARATOR.'Database.php');

require_once ($var->lib_path.'shared'.DIRECTORY_SEPARATOR.'System.php');

require_once ($var->lib_path.'shared'.DIRECTORY_SEPARATOR.'Form.php');

require_once ($var->lib_path.'shared'.DIRECTORY_SEPARATOR.'Image.php');

require_once ($var->lib_path.'shared'.DIRECTORY_SEPARATOR.'Minify.php');

require_once ($var->lib_path.'shared'.DIRECTORY_SEPARATOR.'Output.php');

require_once ($var->lib_path.'shared'.DIRECTORY_SEPARATOR.'Benchmark'.DIRECTORY_SEPARATOR.'Benchmark.php');

/*	Extended Loader	*/
require_once ($var->lib_path.'usr'.DIRECTORY_SEPARATOR.'Loader.php');

/*  Initialize Models */
TechMVC::LoadModel();

/*  Initiaize Required Services */
if(true === $var->session) {
  Session::Init();
}
