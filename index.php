<?php
require_once('connection.php');
//require 'showproduct.php';

echo $db;

$API_URL = 'https://api.line.me/v2/bot/message/reply';
$ACCESS_TOKEN = 'lBX5YbEdwZ498JOXn+dInNH+7+WS2y7zSGQx77c8nmWwV+jhqYTJHzKm6i9yxK+zU0AgIBSwSyumjqfA22ZZVWQxrkmbxfDaupCQ3tPD0ypZNc0WdUfeobmpMs5EhxVg5/s6SdVQ42+Dy4OE4+WJOAdB04t89/1O/w1cDnyilFU='; // Access Token ค่าที่เราสร้างขึ้น
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
	$userid = $event['source']['userId'];
	$findid = pg_query($db,"SELECT * FROM Customer WHERE cus_id = '$userid'");
	if( pg_fetch_result($findid) == 0)
	{
		pg_query($db,"INSERT INTO Customer (cus_id) VALUES ('$userid')");
	}
	
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
   
	else
	$reply_message = 'why dont you say hello to me';
   }
   else
    $reply_message = 'ระบบได้รับ '.ucfirst($event['message']['type']).' ของคุณแล้ว';
  
  }
  else
   $reply_message = 'ระบบได้รับ Event '.ucfirst($event['type']).' ของคุณแล้ว';


 if( strlen($reply_message) > 0 )
  {
   if($reply_message == '1')
   {
   	$data = [
	'replyToken' => $reply_token,
	'messages' => [
[
  "type" => "flex",
  "altText" => "Flex Message",
  "contents" => [
    "type" => "bubble",
    "direction" => "ltr",
    "header" => [
      "type" => "box",
      "layout" => "vertical",
      "contents" => [
        [
          "type" => "text",
          "text" => "เลือกประเภทสินค้า",
          "align" => "center",
          "weight" => "bold"
        ]
      ]
    ],
    "body" => [
      "type" => "box",
      "layout" => "vertical",
      "contents" => [
        [
          "type" => "button",
          "action" => [
            "type" => "message",
            "label" => "สายเดี่ยว/แขนกุด",
            "text" => "เสื้อสายเดี่ยว/แขนกุด"
          ]
        ],
        [
          "type" => "button",
          "action" => [
            "type" => "message",
            "label" => "เสื้อมีแขน",
            "text" => "เสื้อมีแขน"
          ]
        ],
        [
          "type" => "button",
          "action" => [
            "type" => "message",
            "label" => "เดรส",
            "text" => "เดรส"
          ]
        ],
        [
          "type" => "button",
          "action" => [
            "type" => "message",
            "label" => "กางเกงขาสั้น",
            "text" => "กางเกงขาสั้น"
          ]
        ],
        [
          "type" => "button",
          "action" => [
            "type" => "message",
            "label" => "กางเกงขายาว",
            "text" => "กางเกงขายาว"
          ]
        ]
      ]
    ]
  ]
]
]];
$post_body = json_encode($data, JSON_UNESCAPED_UNICODE);
file_put_contents("php://stderr", "POST REQUEST =====> ".$post_body);
$send_result = send_reply_message($API_URL, $POST_HEADER, $post_body);
echo "Result: ".$send_result."\r\n";
file_put_contents("php://stderr", "POST RESULT =====> ".$send_result);
    
   }


}
}
} 
function send_reply_message($url, $post_header, $post_body)
{
 $ch = curl_init($url);
 curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
 curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
 curl_setopt($ch, CURLOPT_HTTPHEADER, $post_header);
 curl_setopt($ch, CURLOPT_POSTFIELDS, $post_body);
 curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
 $result = curl_exec($ch);
 curl_close($ch);
 return $result;
}
 
?>
