<?php

$ACCESS_TOKEN = 'wa9sF+y4HsXJ2IqRQcTadD32XYH7lG01BLuw9O9AbkTSbdRUvC4CU6vOvAKCE4LGU0AgIBSwSyumjqfA22ZZVWQxrkmbxfDaupCQ3tPD0yrY67su+hl6Iw1oKWVpWo3JWOg7RFFphGSz3x5MY/aqMgdB04t89/1O/w1cDnyilFU=';
$RICH_URL = 'https://api.line.me/v2/bot/richmenu';
$rich_area = array(
		  array('bounds'=> array( 'x'=>'0','y'=>'10','width' => 833,'height' => 833 ), 'action' => array('type'=> 'message', 'text' =>'ค้นหาสินค้า')),
		  array('bounds'=> array( 'x'=>'843','y'=>'9','width' => 814,'height' => 824 ), 'action' => array('type'=> 'message', 'text' =>'โปรโมชั่น')),
		  array('bounds'=> array( 'x'=>'1677','y'=>'0','width' => 823,'height' => 83 ), 'action' => array('type'=> 'message', 'text' =>'ตะกร้าของฉัน')),
		  array('bounds'=> array( 'x'=>'0','y'=>'853','width' => 824,'height' => 833 ), 'action' => array('type'=> 'message', 'text' =>'ชำระเงิน')),
      		  array('bounds'=> array( 'x'=>'843','y'=>'843','width' => 814,'height' => 843 ), 'action' => array('type'=> 'message', 'text' =>'เกี่ยวกับร้านค้า')),
		  array('bounds'=> array( 'x'=>'1667','y'=>'843','width' => 833,'height' => 843 ), 'action' => array('type'=> 'message', 'text' =>'หน้าถัดไป'))
		  );
$rich_object = array('size'=> array('width'=>2500,'height'=>1686),'selected'=> true ,
			     'name'=>'rich_menu','chatBarText'=>'Menu','areas'=>  $rich_area );


?>
