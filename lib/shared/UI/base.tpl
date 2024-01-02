<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <?= $base_data['#meta_data']  ?>
    
    <title> <?=  $var->title  ?> - <?=  $var->host  ?> </title>
    
    <?=  $base_data['#ui_style']['css']  ?>
    
    <?=  $base_data['#ui_style']['js']  ?>
  </head>
  
  <body>
  
    <div id="wrapper">
    
      <div id="header">
        <?=	self::HTMLBlock('#header')	?>
      </div>
      <div class="spacer"></div>
	  
      <div id="body">
        <?=	self::HTMLBlock('#body')	?>
      </div>
      <div class="spacer"></div>

      <div id="footer">
        <?=	self::HTMLBlock('#footer')	?>
      </div>
      
    </div>
  </body>
  
</html>
