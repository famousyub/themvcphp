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

class TechMVCMongo {
  
  /**
   * 	Connect to MongoDB Server
   */
  public static function Connect($param, $global_handler = true) {
    global $var;
    
    // Include Global MongoDB Connection
    if(!extension_loaded('mongo')) {
      die('Error:  MongoDB PHP Extension not installed.');
    }
    /*	Initialize	MongoDB Connection	*/
    try {
	    $MongoDB_Conn = new Mongo($param['server'].':'.$param['port'], array("persist" => "techMVCBuzz"));
	    $db_name = $param['database'];
	    $MongoDB = $MongoDB_Conn->$db_name;
    }
    catch (MongoConnectionException $e) {
	    die('Error: ' . $e->getMessage());
    }
    catch (MongoException $e) {
	    die('Error: ' . $e->getMessage());
    }
    
    if(true === $global_handler) {
      $var->MongoDB = $MongoDB;
    }
    
    return $MongoDB;
  }
  
}