<?php

class Home extends TechMVC implements HomeInterface {

  public static function init() {
    global $var;
    if(!isset($var->sec2)) {
      self::startAction();
    }
    else {
      self::processAction();
    }
  }

  /**
    *   URL:  /home && /home/start
    *   Function:  startAction() 
    */
  public static function startAction() {
    global $var;
    $param = array();
    $var->cache = false;
    
    //HomeModel::Test();
    
    /*  test benchmarking */
    /*
    self::LoadLibrary('Benchmark', true);
    
    TechMVCBenchmark::Start('hati');
    for($i=0; $i <= 10000; $i++)
      $arr[rand()] = rand();
    TechMVCBenchmark::End('hati');
    self::Display(TechMVCBenchmark::getResult('hati'));
    exit();
    */
    
    //  setup custom link tags: assume global
    self::createCustomLinkTags(array(
      array(
        'rel'	  =>	'dns-prefetch',
        'href'	=>	'http://www.techmvc.com'
      ),
      array(
        'rel'	  =>	'canonical',
        'href'	=>	'https://www.techmvc.com'
      ),
      array(
        'rel'	  =>	'shortcut icon',
        'type'	=>	'image/x-icon',
        'href'	=>	'http://www.techunits.com/favicon.ico'
      ),
    ));
    
    //  setup custom link tags: assume controller based append
    self::createCustomLinkTags(array(
      array(
        'rel'	  =>	'dns-prefetch',
        'href'	=>	'http://www.techunits.com'
      ),
    ));
    
    self::template($var->uri, 'home/home.tpl', $param, false);
  }
}
