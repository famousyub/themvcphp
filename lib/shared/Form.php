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

class TechMVCForm {
  
  /**
    * generate whole form
   */ 
  public static function gen_form($param = array()) {
    global $var, $base_data;
	  $error = (isset($base_data['#error']['form']))?$base_data['#error']['form']:array();
	  $form = '';
    $form .= '<style type="text/css">'.
              '.form {'.
                'padding: 3px; font-weight: bold; font-size: 12.5px;'.
              '}'.
              '</style>';
    $method  = (!isset($param['form']['method']) || isset($param['form']['method']) && $param['form']['method'] == 'POST')?'POST':'GET';
    $action  = (!isset($param['form']['action']))?'':$param['form']['action'];
    $enctype = (!isset($param['form']['enctype']))?'':'enctype="'.$param['form']['enctype'].'"';
    $form   .= '<form action="'.$action.'" method="'.$method.'"'.$enctype.'><div id="generic_form_field_container"></div><table><tbody>';
    foreach($param as $key  =>  $field) {
		  if(strtolower(trim($key)) != 'form') {
        if(strtolower($key) == 'hidden' || strtolower($key) == 'reset' || strtolower($key) == 'submit' || strstr(strtolower($key),'div')) {
          $form .= '<tr><td class="form" colspan=3></td><td >';
        }
        else if($field['tag'] == 'textarea') {
          if( isset($field['required']) && $field['required'] ) {
					  $form .= '<tr><td class="form" colspan=3>'.ucfirst($key).' * </td></tr><tr><td class="form" colspan=3> </td><td>';
				  }
          else  {
            $form .= '<tr><td class="form" colspan=3>'.ucfirst($key).'</td></tr><tr><td class="form" colspan=3> </td><td>';
          }
        }
        else {
				  if( isset($field['required']) && $field['required'] ) {
					  $form .= '<tr><td class="form" colspan=3>'.ucfirst($key).' * </td><td>';
				  }
				  else {
					  $form .= '<tr><td class="form" colspan=3>'.ucfirst($key).'</td><td>';
				  }
        }
        if($field['tag'] == 'input') {
          $form .= '<input ';
          foreach($field['attr'] as $attr =>  $value) {
					  if($attr == 'name') {
						  $field_name = $value;						
					  }
            if(is_numeric($value))
              $form .= $attr.'='.$value.' ';
            else
              $form .= $attr.'="'.$value.'" ';
          }				
				  $e = (isset($error[$field_name]))?$error[$field_name]:'';
          $form .= '/> ';
          //if($e != '')  {
            $form .= '<span id="span_'.$field_name.'" style="font-size: 12px; color: #E00; padding-left: 	5px;">&nbsp;'.$e.'</span>';
          //}
        }
        if($field['tag'] == 'captcha') {
	
          $form .= $field['attr']['captcha'];
          if($e != '')  {
            $form .= '<span style="font-size: 12px; color: #E00; padding-left: 	5px;">'.$e.'</span>';
          }
        }
        if($field['tag'] == 'div') {
          $form .= '<div ';
          $val_data = '';
          foreach($field['attr'] as $attr =>  $value) {
            if($attr != 'value') {
              if(is_numeric($value))
                $form .= $attr.'='.$value.' ';
              else
                $form .= $attr.'="'.$value.'" ';
            }
            else if($attr == 'value') {
              $val_data = $value;
            }
          }
          $form .= '>'.$val_data.'</div>';
        }
        if($field['tag'] == 'textarea') {
          $form .= '<textarea ';
          $val_data = '';
          foreach($field['attr'] as $attr =>  $value) {
            if($attr == 'name') {
						  $field_name = $value;						
					  }
            if($attr != 'value') {
              if(is_numeric($value))
                $form .= $attr.'='.$value.' ';
              else
                $form .= $attr.'="'.$value.'" ';
            }
            else if($attr == 'value') {
              $val_data = $value;
            }
          }
          $e = (isset($error[$field_name]))?$error[$field_name]:'';
          $form .= '>'.$val_data.'</textarea> ';
          if($e != '')  {
            $form .= '<span style="font-size: 12px; color: #E00; padding-left: 	5px;">'.$e.'</span>';
          }
        }
        if($field['tag'] == 'select') {
          $form .= '<select ';
          foreach($field['attr'] as $attr =>  $value) {
            if($attr == 'name') {
						  $field_name = $value;						
					  }
            if($attr != 'option') {
              if(is_numeric($value))
                $form .= $attr.'='.$value.' ';
              else
                $form .= $attr.'="'.$value.'" ';
            }
          }
          $form .= '>';
          foreach($field['option'] as $option =>  $value) {
            if(isset($field['selected']) && $option == $field['selected'])
              $form .= '<option value="'.$option.'" selected="selected">'.$value.'</option>';
            else
              $form .= '<option value="'.$option.'">'.$value.'</option>';
          }
          $e = (isset($error[$field_name]))?$error[$field_name]:'';
          $form .= '</select>';
          if($e != '')  {
            $form .= '<span style="font-size: 12px; color: #E00; padding-left: 	5px;">'.$e.'</span>';
          }
        }
        if($field['tag'] == 'radio') {
          foreach($field['option'] as $key  =>  $option) {
            $form .= '<div style="padding: 1px;">'.$key.'<input type="radio" name="'.$option['name'].'" /></div>';
          }
				  $e = (isset($error[$field_name]))?$error[$field_name]:'';
          if($e != '')  {
            $form .= '<span style="font-size: 12px; color: #E00; padding-left: 	5px;">'.$e.'</span>';
          }
        }
        if($field['tag'] == 'checkbox') {
          if(isset($field['attr']['style'])) {
            $form .= '<div style="'.$field['attr']['style'].'">';
          }
          else {
            $form .= '<div style="padding; 5px;">';
          }
          foreach($field['option'] as $key  =>  $option) {
            if(isset($option['checked']) && $option['checked']) {
              $form .= '<div style="padding: 3px; float: left;">'.$key.'<input type="checkbox" value="'.$option['value'].'" name="'.$option['name'].'[]" checked="checked" /></div>';
            }
            else  {
              $form .= '<div style="padding: 3px; float: left;">'.$key.'<input type="checkbox" value="'.$option['value'].'" name="'.$option['name'].'[]" /></div>';
            }
          }
          $form .= '</div>';
				  $e = (isset($error[$field_name]))?$error[$field_name]:'';
          if($e != '')  {
            $form .= '<span style="font-size: 12px; color: #E00; padding-left: 	5px;">'.$e.'</span>';
          }
        }
        
        
        if($field['tag'] == 'imagecaptcha') {
	
          $form .= $field['attr']['imagecaptcha'];
          if($e != '')  {
            $form .= '<span style="font-size: 12px; color: #E00; padding-left: 	5px;">'.$e.'</span>';
          }
        }
        
        
        $form .= '<td></tr>';
      }
    }
    if(isset($var->forgot_password) && $var->forgot_password) {
      $form  .=  '<tr><td class="form" colspan=3></td><td><a style="text-decoration: underline; font-weight: bold; font-size: 11px;" href="/user/forgot_password">Forgot my password !</a></td></tr>';
    }
    $form  .=  '</tbody></table><div style="font-size: 9px;">* Required</div></form>';
    return $form;
  }
  
  
  /**
    * generate a single field for a FORM
   */  
  public static function gen_form_field($key, $form) {
    global $var, $base_data;
	  $error = (isset($base_data['#error']['form']))?$base_data['#error']['form']:array();	
    if(!isset($form[$key]))
      exit('The specified key is not found in the form: '.$key);
    $html = '';
    $field = $form[$key];
    
    /*
     *  FOR INPUT TYPE TEXT BOXES
     */
    if($field['tag'] == 'input') {
      $html .= '<input ';
      foreach($field['attr'] as $attr =>  $value) {
			  if($attr == 'name') {
				  $field_name = $value;						
			  }
        if(is_numeric($value))
          $html .= $attr.'='.$value.' ';
        else
          $html .= $attr.'="'.$value.'" ';
      }				
		  $e = (isset($error[$field_name]))?$error[$field_name]:'';
      $html .= '/> ';
      if(isset($field['privacy']) && $field['privacy'] != ''){
        $html .= '<span id="privacy_'.$field_name.'" class="gen_form_privacy">Privacy<input type="hidden" name="hidden_privacy_'.$field_name.'" value="0"/></span>';
      }
      if($e != '')  {
        $html .= '<span id="span_'.$field_name.'" class="gen_form_error">&nbsp;'.$e.'</span>';
      }
    }
    
    /*
     *  FOR TEXTAREA
     */
    if($field['tag'] == 'textarea') {
      $html .= '<textarea ';
      $val_data = '';
      foreach($field['attr'] as $attr =>  $value) {
        if($attr == 'name') {
				  $field_name = $value;						
			  }
        if($attr != 'value') {
          if(is_numeric($value))
            $html .= $attr.'='.$value.' ';
          else
            $html .= $attr.'="'.$value.'" ';
        }
        else if($attr == 'value') {
          $val_data = $value;
        }
      }
      $e = (isset($error[$field_name]))?$error[$field_name]:'';
      $html .= '>'.$val_data.'</textarea> ';
      if(isset($field['privacy']) && $field['privacy'] != ''){
        $html .= '<span id="privacy_'.$field_name.'" class="gen_form_privacy">Privacy<input type="hidden" name="hidden_privacy_'.$field_name.'" value="0"/></span>';
      }
      if($e != '')  {
        $html .= '<span class="gen_form_error">'.$e.'</span>';
      }
    }
    
    /*
     *  THE COMBO BOX ( SELECT BOX )
     */
    if($field['tag'] == 'select') {
      $html .= '<select ';
      foreach($field['attr'] as $attr =>  $value) {
        if($attr == 'name') {
          $field_name = $value;						
        }
        if($attr != 'option') {
          if(is_numeric($value))
            $html .= $attr.'='.$value.' ';
          else
            $html .= $attr.'="'.$value.'" ';
        }
      }
      $html .= '>';
      foreach($field['option'] as $option =>  $value) {
        if(isset($field['selected']) && $option == $field['selected'])
          $html .= '<option value="'.$option.'" selected="selected">'.$value.'</option>';
        else
          $html .= '<option value="'.$option.'">'.$value.'</option>';
      }
      $e = (isset($error[$field_name]))?$error[$field_name]:'';
      $html .= '</select>';
      if(isset($field['privacy']) && $field['privacy'] != ''){
        $html .= '<span id="privacy_'.$field_name.'" class="gen_form_privacy">Privacy<input type="hidden" name="hidden_privacy_'.$field_name.'" value="0"/></span>';
      }
      if($e != '')  {
        $html .= '<span style="font-size: 12px; color: #E00; padding-left: 	5px;">'.$e.'</span>';
      }
    }
      
    /*
     *  RADIO  BUTTON
     */
    if($field['tag'] == 'radio') {
      foreach($field['option'] as $key  =>  $option) {
        $html .= '<div style="padding: 1px;">'.$key.'<input type="radio" name="'.$option['name'].'" /></div>';
      }
      $e = (isset($error[$field_name]))?$error[$field_name]:'';
      if($e != '')  {
        $html .= '<span style="font-size: 12px; color: #E00; padding-left: 	5px;">'.$e.'</span>';
      }
    }
    /*
     *  CHECK  BOX
     */
    if($field['tag'] == 'checkbox') {
      if(isset($field['attr']['style'])) {
        $html .= '<div style="'.$field['attr']['style'].'">';
      }
      else {
        $html .= '<div style="padding; 5px;">';
      }
      foreach($field['option'] as $key  =>  $option) {
        if(isset($option['checked']) && $option['checked']) {
          $html .= '<div style="padding: 3px; float: left;">'.$key.'<input type="checkbox" value="'.$option['value'].'" name="'.$option['name'].'[]" checked="checked" /></div>';
        }
        else  {
        $html .= '<div style="padding: 3px; float: left;">'.$key.'<input type="checkbox" value="'.$option['value'].'" name="'.$option['name'].'[]" /></div>';
        }
      }
      $html .= '</div>';
      $e = (isset($error[$field_name]))?$error[$field_name]:'';
      if($e != '')  {
        $html .= '<span style="font-size: 12px; color: #E00; padding-left: 	5px;">'.$e.'</span>';
      }
    }    
    return $html;
  }
  
}
