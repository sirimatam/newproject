<?php

$ACCESS_TOKEN = 'wa9sF+y4HsXJ2IqRQcTadD32XYH7lG01BLuw9O9AbkTSbdRUvC4CU6vOvAKCE4LGU0AgIBSwSyumjqfA22ZZVWQxrkmbxfDaupCQ3tPD0yrY67su+hl6Iw1oKWVpWo3JWOg7RFFphGSz3x5MY/aqMgdB04t89/1O/w1cDnyilFU=';
$RICH_URL = 'https://api.line.me/v2/bot/richmenu';
$rich_area = array(
		  array('bounds'=> array( 'x'=>'0','y'=>'0','width' => 824,'height' => 776 ), 'action' => array('type'=> 'message', 'text' =>'ดูและสั่งซื้อสินค้า')),
		  array('bounds'=> array( 'x'=>'833','y'=>'0','width' => 814,'height' => 785 ), 'action' => array('type'=> 'message', 'text' =>'โปรโมชัน')),
		  array('bounds'=> array( 'x'=>'1686','y'=>'0','width' => 814,'height' => 785 ), 'action' => array('type'=> 'message', 'text' =>'ตะกร้าสินค้า')),
		  array('bounds'=> array( 'x'=>'10','y'=>'817','width' => 1242,'height' => 869 ), 'action' => array('type'=> 'message', 'text' =>'ที่อยู่จัดส่ง')),
      array('bounds'=> array( 'x'=>'862','y'=>'798','width' => 795,'height' => 888 ), 'action' => array('type'=> 'message', 'text' =>'สินค้าที่ชอบ')),
		  array('bounds'=> array( 'x'=>'1686','y'=>'807','width' => 814,'height' => 879 ), 'action' => array('type'=> 'message', 'text' =>'เช็คสถานะ'))
		  );
      
$rich_object = array('size'=> array('width'=>2500,'height'=>1686),'selected'=> true ,
			     'name'=>'rich_menu','chatBarText'=>'Menu','areas'=>  $rich_area );
           
$rich_obj_req = json_encode($rich_object, JSON_UNESCAPED_UNICODE);



?>
