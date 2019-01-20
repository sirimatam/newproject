<?php
require_once('connection.php');
require 'showproduct.php';

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
	
	$greeting = array('Hi','Hello','ดีจ้า','สวัสดี','สวัสดีครับ');
	
	$correct = 0;
	foreach ($greeting as $value)
	{
		if ($text == $value)
		{
			$correct = 1;
		}
	}
	if ($correct == 1)
	{
		$reply_message = 'Hi,what is you name';
	}
	elseif ($text=='button')
	{
		$reply_message = "1";
	}
	
	
	else
	$reply_message = 'why dont you say hello to me';
   }
   else
    $reply_message = 'ระบบได้รับ '.ucfirst($event['message']['type']).' ของคุณแล้ว';
  
  }
  else
   $reply_message = 'ระบบได้รับ Event '.ucfirst($event['type']).' ของคุณแล้ว';
 }
	 


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
