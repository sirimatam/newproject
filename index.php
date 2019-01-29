<?php
require_once('connection.php');
require 'function.php';
//require 'showproduct.php';
require 'RichMenu/setrichMenuDefault.php';


//echo $db;

$API_URL = 'https://api.line.me/v2/bot/message/reply';
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
        $text = $event['message']['text']; 
	$userid = $event['source']['userId'];
	$findid = pg_query($db,"SELECT cus_id FROM customer WHERE cus_id = $userid");
	if( pg_fetch_result($findid) == 0)
	{
		pg_query($db,"INSERT INTO customer (cus_id) VALUES ('$userid')");
		pg_query($db,"INSERT INTO createcart (cus_id) VALUES ('$userid')");
	}
	
	if ($text=='ดูและสั่งซื้อสินค้า')
	{
    		
		//$post = button_all_type();
		$post = test();
		$send_result = send_reply_message($API_URL, $POST_HEADER, $post);
		echo "Result: ".$send_result."\r\n";
	}
/*	elseif ($text=='กางเกงขาสั้น' OR $text=='กางเกงขายาว' OR $text=='เดรส' OR $text=='เสื้อมีแขน' OR $text=='เสื้อสายเดี่ยว/แขนกุด')
	{
		$post = carousel_product_type($text);
		send_reply_message($API_URL, $POST_HEADER, $post);

	}
	elseif ($text=='โปรโมชัน')
	{
		$post = show_promotion_product();
		send_reply_message($API_URL, $POST_HEADER, $post);

	}
       elseif ($text=='ตะกร้าสินค้าที่บันทึกไว้')
	{
		$post = carousel_cart($userid,$cartp_id);
		send_reply_message($API_URL, $POST_HEADER, $post);
	}
	elseif ($text=='ที่อยู่จัดส่ง')
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
       
       elseif ($text=='สินค้าที่ชอบ')
	{
		$post = carousel_show_favorite($userid);
	        send_reply_message($API_URL, $POST_HEADER, $post);
	}
	$sku_ids = pg_query($db,'SELECT sku_id FROM stock');
	while($sku_id = pg_fetch_row($sku_ids))
	{
		if(explode(" ",$text)[0] == $sku_id)
		{
			$cart_qtt = explode(" ",$text)[1];
			$data = add_to_cart($sku_id,$userid,$cart_qtt);
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
/*
//   elseif (substr($text,0,6) =='addcus') //comment
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
	   // comment
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
	
/*	$prod_ids = pg_query($db,'SELECT prod_id FROM product');
	while($prod_id = pg_fetch_row($prod_ids))
	{
		if(explode(" ",$info)[1] == $prod_id)
		{
			if(explode(" ",$info)[0]) == 'View')
			{
			  $data = carousel_view_more($prod_id);
			  send_reply_message($API_URL, $POST_HEADER, $data);
			}
			if(explode(" ",$text)[0]) == 'Favorite')
			{
			  add_favorite($prod_id,$userid);	
			}
		}
	}
	$sku_ids = pg_query($db,'SELECT sku_id FROM stock');
	while($sku_id = pg_fetch_row($sku_ids))
	{
		if(explode(" ",$info)[1] == $sku_id)
		{
			if(explode(" ",$info)[0]) == 'Cart')
			{
			  $cart_qtt = 1;
			  $data = add_to_cart($sku_id,$userid,$cart_qtt);
			  send_reply_message($API_URL, $POST_HEADER, $data);
			}
			if(explode(" ",$info)[0]) == 'Delete')
			{
			  delete_from_cart($sku_id,$userid);
			  $data = $data = ['replyToken' => $reply_token,'messages' => [['type' => 'text', 'text' => 'ลบสินค้ารหัส '.$sku_id.' ออกจากตะกร้าเรียบร้อยแล้ว']]];
			  send_reply_message($API_URL, $POST_HEADER, $data);
			}
		}
	}*/
  }
  else
   $reply_message = 'ระบบได้รับ Event '.ucfirst($event['type']).' ของคุณแล้ว';



   	
//$post_body = json_encode($data, JSON_UNESCAPED_UNICODE);
//send_reply_message($API_URL, $POST_HEADER, $post_body);

    
  
}
} 






function format_message($message)
{
	$data = ['replyToken' => $reply_token,'messages' => [ $message ]];
	return $data;
}

function test()
{
$data = array();
	$data['type'] = 'template';
	$data['altText'] = 'this is a buttons template';
	$data['template']['type'] = 'buttons';
	$data['template']['actions']['type'] = 'message';
	$data['template']['actions']['label'] = 'แก้ไขที่อยู่จัดส่ง';
	$data['template']['actions']['text'] = 'แก้ไขที่อยู่';
	$data['template']['title'] = 'ที่อยู่จัดส่งปัจจุบัน';
	$data['template']['text'] = $address;
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
