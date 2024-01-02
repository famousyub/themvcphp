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

class TechMVCBenchmark {

  /**
    * Start Benchmarking Process
    */
  public static function Start($identifier = false) {
    global $var;
    if(false === $identifier) {
      exit('Invalid Identifier');
    }
    $dataset[$identifier]['start']['time'] = microtime(true); 
    $dataset[$identifier]['start']['memory'] = memory_get_usage(true); 
    $var->BenchmarkData = $dataset;
    return true;
  }
  
  /**
    * End Benchmarking Process
    */
  public static function End($identifier = false) {
    global $var;
    if(false === $identifier) {
      exit('Invalid Identifier');
    }
    $dataset = $var->BenchmarkData;
    $dataset[$identifier]['end']['time'] = microtime(true);     
    $dataset[$identifier]['end']['memory'] = memory_get_usage(true);
    $var->BenchmarkData = $dataset;
    return true;
  }
  
  /**
    * Get Benchmarking Result
    */
  public static function getResult($identifier = false) {
    global $var;
    if(false === $identifier) {
      exit('Invalid Identifier');
    }
    return array(
      'identifier'      =>  $identifier,
      'execution_time'  =>  ($var->BenchmarkData[$identifier]['end']['time'] - $var->BenchmarkData[$identifier]['start']['time']) ,
      'memory_usage'    =>  ($var->BenchmarkData[$identifier]['end']['memory'] - $var->BenchmarkData[$identifier]['start']['memory']) / 1024,
    );
  }
  
}
