<?php
require_once('connection.php');
require('showproduct.php');

echo $db;

$API_URL = 'https://api.line.me/v2/bot/message/reply';
$ACCESS_TOKEN = 'bynvLZ7UxlKYCGVpVOYubZOpxcke5djwTi5YicHDHdi2GbSIw36ffB6EmHJsnRcpU0AgIBSwSyumjqfA22ZZVWQxrkmbxfDaupCQ3tPD0yo+jNrkAftMeqHPq9YJ8DYB1+H5l8XRHCkB1yp95AyBGgdB04t89/1O/w1cDnyilFU=
'; // Access Token ค่าที่เราสร้างขึ้น
$POST_HEADER = array('Content-Type: application/json', 'Authorization: Bearer ' . $ACCESS_TOKEN);
$request = file_get_contents('php://input');   // Get request content
$request_array = json_decode($request, true);   // Decode JSON to Array

if ( sizeof($request_array['events']) > 0 )
{
 foreach ($request_array['events'] as $event)
 {
  $reply_message = '';
  $reply_token = $event['replyToken'];
  if ( $event['type'] == 'message' ) 
  {
   if( $event['message']['type'] == 'text' )
   {
    $text = $event['message']['text'];
	
	if ($text=='ดู และ สั่งซื้อสินค้า')
	{
		$reply_message = "1";
	}
	
	elseif ($text=='สินค้าโปรโมชั่น')
	{
		$reply_message = "2";
	}
  elseif ($text=='ตะกร้าสินค้าที่บันทึกไว้')
	{
		$reply_message = "3";
	}
	elseif ($text=='เพิ่ม/แก้ไขที่อยู่จัดส่ง')
	{
		$reply_message = "4";
	}
  elseif ($text=='wishlist')
	{
		$reply_message = "5";
	}
  elseif ($text=='เช็คสถานะจ่ายเงิน/พัสดุ')
	{
		$reply_message = "6";
	}
/*	elseif (substr($text,0,6) =='addcus')
	{
		list($order, $cusid, $cusname, $cuslast, $cuspic) = split(" ", $text, 5);
		//$cardata = explode(" ",$text);
		pg_query($db,"INSERT INTO Customer1 (cus_id,cus_name,cus_lastname,cus_pic) VALUES ($cusid,$cusname,$cuslast,$cuspic)");
		$result = pg_query($db,"SELECT cus_name FROM Customer1");
		while ($list = pg_fetch_row($result))
		{
			$cust = $list[0]."\n";
			$custlist .= $cust;
		}
		$reply_message = "$custlist";
	}
	*/
   }
	else
	$reply_message = 'why dont you say hello to me';
   }
   else
    $reply_message = 'ระบบได้รับ '.ucfirst($event['message']['type']).' ของคุณแล้ว';
  
  }
  else
   $reply_message = 'ระบบได้รับ Event '.ucfirst($event['type']).' ของคุณแล้ว';
}}

 if( strlen($reply_message) > 0 )
  {
   if($reply_message == '1')
   {
   	show_product();
   }
   elseif($reply_message == '2')
   {
   	show_product();
   }
	 
 }
?>
