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

class Paginate {
  public $display_type = 'full';
  public function __construct($param) {
    $count_sql =  $param['#sql']['count'];
    $cs = db_select(array(
      '#sql'  =>  $count_sql,
      'list'  =>  false,
    ));
    $this->total_count = $cs ['data']['count'];
    
    $this->item_per_page = $param['item_per_page'];
    $this->current_page = (false === sget('paginate_current_page'))?0:sget('paginate_current_page');
    $this->limit_start = ($this->current_page * $this->item_per_page);
    $dataset_sql =  $param['#sql']['dataset'].' LIMIT '.$limit_start.' '.($limit_start+$item_per_page);
    $ds =  db_select(array(
      '#sql'  =>  $dataset_sql,
      'list'  =>  true,
    ));
  }
  
  public function Page_Number() {
    if('full' == $this->display_type) {
    
    }
    else {
      if($this->total_count <= ($this->current_page * $this->item_per_page)){
        exit('Only Prev');
      }/*
      if() {
        if(0 == $this->current_page * $this->item_per_page) {
          exit('Only Next');
        }
      }*/
    }
  }
}
