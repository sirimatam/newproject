<?php
require_once('connection.php');
require 'function.php';
//require 'showproduct.php';
require 'RichMenu/setrichMenuDefault.php';

print_r(carousel_product_type($db,'เดรส'));

//echo $db;

$API_URL = 'https://api.line.me/v2/bot/message/reply';
$API_URL_push = 'https://api.line.me/v2/bot/message/push';
$ACCESS_TOKEN = 'wa9sF+y4HsXJ2IqRQcTadD32XYH7lG01BLuw9O9AbkTSbdRUvC4CU6vOvAKCE4LGU0AgIBSwSyumjqfA22ZZVWQxrkmbxfDaupCQ3tPD0yrY67su+hl6Iw1oKWVpWo3JWOg7RFFphGSz3x5MY/aqMgdB04t89/1O/w1cDnyilFU='; // Access Token ค่าที่เราสร้างขึ้น
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
	   $check = 0;
        $text = $event['message']['text']; 
	$userid = $event['source']['userId'];
	$findid = pg_query($db,"SELECT cus_id FROM customer WHERE cus_id = '$userid'");
	if( pg_num_rows($findid) == 0)
	{
		pg_query($db,"INSERT INTO customer (cus_id,cus_default) VALUES ('$userid','0')");
		pg_query($db,"INSERT INTO createcart (cus_id,cart_used) VALUES ('$userid','0')");
	}
	   
	
	if ($text=='ดูและสั่งซื้อสินค้า')
	{
		
		$data = format_message($reply_token,button_all_type());
		$send_result = send_reply_message($API_URL, $POST_HEADER, $data);
		
	}

	elseif ($text=='เช็คสถานะ')
	{
		$data = button_order_status($userid);
		$data1 = format_message($reply_token,$data);
			   
		$send_result = send_reply_message($API_URL, $POST_HEADER, $data1);
		file_put_contents("php://stderr", "POST REQUEST1 =====> ".json_encode($post, JSON_UNESCAPED_UNICODE));
	}

	
 
	elseif ($text=='กางเกงขาสั้น' OR $text=='กางเกงขายาว' OR $text=='เดรส' OR $text=='เสื้อมีแขน' OR $text=='เสื้อสายเดี่ยว/แขนกุด')
	{
		$array_carousel = carousel_product_type($db,$text);
		//$post = format_message($reply_token,$array_carousel);	
	        //$send_result = send_reply_message($API_URL, $POST_HEADER, $post);
		//file_put_contents("php://stderr", "POST RESULT =====> ".$send_result);
		//file_put_contents("php://stderr", "POST REQUEST =====> ".json_encode($post, JSON_UNESCAPED_UNICODE));

		
		if(sizeof($array_carousel) > 1)
		{
			for($i=0;$i<sizeof($array_carousel);$i++)
			{
				$post = format_message($reply_token,$array_carousel);	 
				$send_result = send_reply_message($API_URL_push, $POST_HEADER, $post);
				file_put_contents("php://stderr", "POST RESULT =====> ".$send_result);

			}
		}
		else
		{
			$post = format_message($reply_token,$array_carousel[0]);	
			send_reply_message($API_URL, $POST_HEADER, $post);
			file_put_contents("php://stderr", "POST RESULT =====> ".$send_result);
		}

	}
/*	elseif ($text=='โปรโมชัน')
	{
		$post = show_promotion_product();
		send_reply_message($API_URL, $POST_HEADER, $post);

	}*/
       elseif ($text=='ตะกร้าสินค้า')
	{
	 	
		$post = format_message_v2($reply_token,carousel_cart($db,$userid));
		send_reply_message($API_URL, $POST_HEADER, $post);
	        file_put_contents("php://stderr", "POST REQUEST =====> ".json_encode($post, JSON_UNESCAPED_UNICODE));
		
	}
/*	elseif ($text=='ที่อยู่จัดส่ง')
	{
		

//send_reply_message($API_URL, $POST_HEADER, $post_body);

		
	}
	   
       elseif ($text=='ดูที่อยู่จัดส่ง')
	{
		$address = pg_query($db,"SELECT cus_description FROM customer WHERE customer.cus_id = $cusid");
	       $show_address = pg_fetch_row($address)[0];
	       $data = [
		    'replyToken' => $reply_token,
		    'messages' => [['type' => 'text', 'text' => $show_address]]
		   ];
	       
	       send_reply_message($API_URL, $POST_HEADER, $data);
	}
       elseif ($text=='แก้ไขที่อยู่')
	{
		pg_query($db,"UPDATE customer SET cus_description = $cusaddress WHERE cus_id = $cusid");
		$data = [
		    'replyToken' => $reply_token,
		    'messages' => [['type' => 'text', 'text' => 'แก้ไขที่อยู่เรียบร้อยแล้ว']]
		   ];
	       send_reply_message($API_URL, $POST_HEADER,$data);
       }  
       */
       elseif ($text=='สินค้าที่ชอบ')
	{
		$post = format_message($reply_token,carousel_show_favorite($db,$userid));
	        send_reply_message($API_URL, $POST_HEADER, $post);
	       file_put_contents("php://stderr", "POST REQUEST1 =====> ".json_encode($post, JSON_UNESCAPED_UNICODE));
	}
	/*$sku_ids = pg_query($db,'SELECT sku_id FROM stock');
	while($sku_id = pg_fetch_row($sku_ids))
	{
		if(explode(" ",$text)[0] == $sku_id[0])
		{
			$cart_qtt = explode(" ",$text)[1];
			$data = add_to_cart($sku_id[0],$userid,$cart_qtt);
			send_reply_message($API_URL, $POST_HEADER, $data);
			
		}
	}
	   
        elseif ($text=='เช็คสถานะ')
	{
		$reply_message = "6";
	}
	   
	
	$types =  pg_query($db,'SELECT prod_type FROM product GROUP BY prod_type ');
	
	while($type = pg_fetch_row($types))
	{
		if ($text == $type)
		{
			$data = carousel_product_type($text);
			$size = sizeof($data);
			for($i=0;$i<$size;$i++)
			{
				send_reply_message($API_URL, $POST_HEADER, $data[$i]);	
			}
		}	
	}
	*/
	else
	$reply_message = 'why dont you say hello to me';
   }
   else
    $reply_message = 'ระบบได้รับ '.ucfirst($event['message']['type']).' ของคุณแล้ว';
  
  }
  elseif($event['type'] == 'postback')
  {
  	$userid = $event['source']['userId'];
	$info = $event['postback']['data'];
	
	$prod_ids = pg_query($db,'SELECT prod_id FROM product');
	while($prod_id = pg_fetch_row($prod_ids))
	{
		if(explode(" ",$info)[1] == $prod_id[0])
		{
			if(explode(" ",$info)[0] == 'View')
			{
			  $data = format_message($reply_token,carousel_view_more($db,$prod_id[0]));
			  $send_result = send_reply_message($API_URL, $POST_HEADER, $data);
			  file_put_contents("php://stderr", "POST RESULT =====> ".$send_result);
			}
			if(explode(" ",$info)[0] == 'Favorite')
			{
			  add_favorite($db,$userid,$prod_id[0]);	
			}
		}
	}
	if(explode(" ",$info)[0] == 'Delete_fav')
	{
		$fav_id = explode(" ",$info)[1];
		delete_favorite($db,$fav_id);
		
	}
	if(explode(" ",$info)[0] == 'Clear')
	{
		$cartid = explode(" ",$info)[1];
		$data = format_message($reply_token,clear_cart($db,$userid,$cartid));
		send_reply_message($API_URL, $POST_HEADER, $data);
	}
	$sku_ids = pg_query($db,'SELECT sku_id FROM stock');
	while($sku_id = pg_fetch_row($sku_ids))
	{
		if(explode(" ",$info)[1] == $sku_id[0])
		{
			if(explode(" ",$info)[0] == 'Cart')
			{
			 $cart_qtt = 1;
			  $data = format_message($reply_token,add_to_cart($db,$sku_id[0],$userid,$cart_qtt));
			  send_reply_message($API_URL, $POST_HEADER, $data);
			}
			if(explode(" ",$info)[0] == 'Delete')
			{
			  delete_from_cart($db,$sku_id[0],$userid);
			  $data = ['replyToken' => $reply_token,'messages' => [['type' => 'text', 'text' => 'ลบสินค้ารหัส '.$sku_id[0].' ออกจากตะกร้าเรียบร้อยแล้ว']]];
			  send_reply_message($API_URL, $POST_HEADER, $data);
			}
		}
	}
  }
  else
   $reply_message = 'ระบบได้รับ Event '.ucfirst($event['type']).' ของคุณแล้ว';



   	
//$post_body = json_encode($data, JSON_UNESCAPED_UNICODE);
//send_reply_message($API_URL, $POST_HEADER, $post_body);

    
  
}
} 






function format_message($userid,$message)
{
	$data = ['replyToken' => $userid,'messages' =>  [$message] ];
	return $data;
}
function format_message_v2($userid,$message)
{
	$data = ['replyToken' => $userid,'messages' =>  $message ];
	return $data;
}
function format_message_push($reply_token,$message)
{
	$data = ['to' => $reply_token,'messages' =>  $message ];
	return $data;
}


function send_reply_message($url, $post_header, $post)
{
 $ch = curl_init($url);
 curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
 curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
 curl_setopt($ch, CURLOPT_HTTPHEADER, $post_header);
 curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post, JSON_UNESCAPED_UNICODE));
 curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
 $result = curl_exec($ch);
 curl_close($ch);
 return $result;
} 


 
?>
