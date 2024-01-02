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

class TechMVCSession {

  /**
    * Login entry to Sessionpublic static 
   */
  public static function Login($param) {
    $_SESSION['is_online'] = true;
    foreach($param as $key  =>  $value) {
      $_SESSION[$key] = $value;
    }
  }

  /**
    * Logout from Session
   */
  public static function Logout() {
    session_destroy();
  }

  /**
    * Set Value for a Session Key
   */
  public static function Set($key, $value) {
    $_SESSION[$key] = $value;
  }

  /**
    * Get Value for a Session Key
   */
  public static function Get($key) {
    if(isset($_SESSION[$key])) {
      return($_SESSION[$key]);
    }
    return false;
  }

  /**
    * Destroy a Session Key
   */
  public static function Kill($key) {
    global $var;
    if(isset($_SESSION[$key])) {
      unset($_SESSION[$key]);
      return true;
    }
    return false;
  }

  /**
    * Start a New Session
   */
  public static function Init() {
    global $var, $sess;
    @mkdir ($var->tmp_path.'session', 0755, true);
    ini_set('session.save_path', $var->tmp_path.'session');
    ini_set('session.gc_maxlifetime', 32000000);
    session_set_cookie_params(320000000);
    session_cache_expire(1);
    @session_start();
    if('undefined' == session_id()) {
      session_regenerate_id();
    }
    $var->sessionid = session_id();
  }

  /**
    * Reload Session with New SessionID
   */
  public static function Reload($session_id) {
    session_id($session_id);
    session_write_close();
    @session_start();
  }
  
}
