<?php
require_once('connection.php');
require 'function.php';
//require 'showproduct.php';
require 'RichMenu/setrichMenuDefault.php';
require 'track.class.php';




/*
$trackingNumber = 'SHX306592865TH';
$track = new Trackingmore;
$track = $track->getRealtimeTrackingResults('kerry-logistics','SHX306592865TH',Array());
print_r($track);
echo '</br></br></br></br></br></br>';
$trace = $track['data']['items'][0]['lastEvent'];
print_r($trace);
echo '</br></br></br></br></br></br> above is trace // below is encode trace';

print_r(json_encode($trace));

*/

	

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
	$findid = pg_query($db,"SELECT cus_id FROM customer WHERE cus_id = '$userid' ");
	if( pg_num_rows($findid) == 0)
	{
		pg_query($db,"INSERT INTO customer (cus_id,cus_default) VALUES ('$userid','1')");
		pg_query($db,"INSERT INTO createcart (cus_id) VALUES ('$userid')");
	}
	
	if ($text=='ดูและสั่งซื้อสินค้า')
	{
		
		$data = format_message($reply_token,button_all_type());
		$send_result = send_reply_message($API_URL, $POST_HEADER, $data);
		
	}
	 /*  
	elseif ($text=='กางเกงขาสั้น' OR $text=='กางเกงขายาว' OR $text=='เดรส' OR $text=='เสื้อมีแขน' OR $text=='เสื้อสายเดี่ยว/แขนกุด')
	{
		$post = carousel_product_type($db,$text);
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
	*/
	   
	elseif ($text=='ที่อยู่จัดส่ง')
	{
		
		$show = show_address($db,$userid);
		$post = format_message($reply_token,$show);
		send_reply_message($API_URL, $POST_HEADER, $post);
		file_put_contents("php://stderr", "address  ===> ".json_encode($show));

	}
       elseif ($text=='เพิ่มชื่อและที่อยู่ใหม่')
	{
	        $ans = ['type'=>'text','text' => 'พิมพ์ @@ตามด้วยชื่อ นามสกุล และ ที่อยู่จัดส่ง เช่น'."\n".'@@น.ส.เสื้อผ้า สวยงาม บ้านเลขที่ XX ซอย XX แขวง เขต จังหวัด 10111'];
	 	$data = format_message($reply_token,$ans);
	        send_reply_message($API_URL, $POST_HEADER,$data);
       } 
       elseif (explode("@@",$text)[0] == '')   
       {
	       // check ว่า ใส่ address ครั้งแรกหรือเปล่า
	       $address = explode("@@",$text)[1];
	       pg_query($db,"INSERT INTO customer (cus_id,cus_description,cus_default) VALUES = ('$userid','$address','0') ");
		$show = show_address($db,$userid);
		$data = format_message($reply_token,$show);
	       send_reply_message($API_URL, $POST_HEADER,$data);
       }
       
       
       elseif ($text=='สินค้าที่ชอบ')
	{
		$post = carousel_show_favorite($userid);
	        send_reply_message($API_URL, $POST_HEADER, $post);
	}
	elseif ($text=='เช็คสถานะ')
	{
		$data = format_message($reply_token,button_pay_track());
		send_reply_message($API_URL, $POST_HEADER, $data);
	}
	elseif ($text=='แจ้งโอนเงิน')
	{
		$ans = ['type'=>'text','text' => 'กรุณาอัพโหลดสลิป'];
	 	$data = format_message($reply_token,$ans);
	        send_reply_message($API_URL, $POST_HEADER,$data);
	}	
        elseif ($text=='เช็คสถานะพัสดุ')
	{
		/* ทำได้ๆๆๆ 
		$trackingNumber = 'SHX306592865TH';
		$track = new Trackingmore;
		$track = $track->getRealtimeTrackingResults('kerry-logistics','SHP4003994671',Array());
		
		$data = format_message($reply_token,['type'=>'text','text'=>$track['data']['items'][0]['lastEvent']]);
		send_reply_message($API_URL, $POST_HEADER, $data);
		
		*/
		
		$payment = pg_fetch_row(pg_query($db,"SELECT check FROM payment WHERE payment.order_id = '$orderid'"))[0];
		$trackingNumber = pg_fetch_row(pg_query($db,"SELECT order_status FROM order WHERE order_id = '$orderid'"))[0];
		if(strlen($trackingNumber)==0)
		{
			if(strlen($payment) == 0)
			{ $reply = 'ยังไม่ได้รับการชำระเงิน'; }
			else { $reply = 'กำลังจัดเตรียมสินค้า';}
			$data = ['replyToken' => $reply_token, 'messages' => [['type' => 'text', 'text' => $reply ]] ];
			send_reply_message($API_URL, $POST_HEADER, $data);
		}
		else
		{
			$track = new Trackingmore;
			$track = $track->getRealtimeTrackingResults('kerry-logistics',$trackingNumber,Array());
			$data = format_message($reply_token,['type'=>'text','text'=>$track['data']['items'][0]['lastEvent']]);
			send_reply_message($API_URL, $POST_HEADER, $data);
		}
		
		
	}
	else {
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

	}


   } /*
   elseif( $event['message']['type'] == 'image' )
   {
	   
	   
	   //$cartpid = pg_fetch_row(pg_query($db,"SELECT cartp_id FROM createcart WHERE cus_id = '$userid' AND cart_used = '0' "))[0];
	   //$orderid = pg_fetch_row(pg_query($db,"SELECT order_id FROM order WHERE cartp_id = '$cartpid' AND order_status = '' "))[0];
	   
	   $imgid =  $event['message']['id']; 
	   
	   file_put_contents("php://stderr", "image id ===> ".$imgid);
	   
	   $response = get_user_content($msgid,$POST_HEADER);
	   
	   define('UPLOAD_DIR', '/image/');
	   $img = base64_encode($response); 
	   $data = base64_decode($img);
	   
	   $file = UPLOAD_DIR . $imgid . '.png';
	   	   
	   $success = file_put_contents('$file', $data);	   
	   
	   file_put_contents("php://stderr", "image 64  ===> ".json_encode($img));
	   
	   $datetime = get_datetime();
	   
	   pg_query($db,"INSERT INTO payment (pay_slip,pay_date,pay_time,order_id,pay_check) VALUES ('$imgid','$datetime[0]','$datetime[1]','order1','0')");
	   
	   $dataa = format_message($reply_token,['type'=>'text','text'=> 'hello']);
	   send_reply_message($API_URL, $POST_HEADER, $dataa);
	   
	   //$get = get_user_content($GET_url,$POST_HEADER);
	   

	   //pg_guery($db,"UPDATE payment SET pay_slip = $get WHERE payment.order_id = $orderid ");
	   

	   
	   
	   
	   
	   
   } */
  
  elseif($event['type'] == 'postback')
  {
  	$userid = $event['source']['userId'];
	$info = $event['postback']['data'];
	
	if(explode(" ",$info)[0] == 'ลบชื่อและที่อยู่นี้')
	{
		$data = explode(" ",$info);
		pg_query($db,"DELETE FROM Customer WHERE cus_id = '$data[2]' AND cus_description = '$data[1]' ");
	}
	elseif(explode(" ",$info)[0] == 'ตั้งเป็นที่อยู่จัดส่งปัจจุบัน')
	{
		pg_query($db,"UPDATE Customer SET cus_default = '0' WHERE cus_id = '$data[2]' AND cus_default = '1' ");
		
		$data = explode(" ",$info);
		pg_query($db,"UPDATE Customer SET cus_default = '1' WHERE cus_id = '$data[2]' AND cus_description = '$data[1]' ");
	}  
	  
	  
	$prod_ids = pg_query($db,'SELECT prod_id FROM product');
	while($prod_id = pg_fetch_row($prod_ids))
	{
		if(explode(" ",$info)[1] == $prod_id)
		{
			if(explode(" ",$info)[0] == 'View')
			{
			  $data = carousel_view_more($prod_id);
			  send_reply_message($API_URL, $POST_HEADER, $data);
			}
			if(explode(" ",$text)[0] == 'Favorite')
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
			if(explode(" ",$info)[0] == 'Cart')
			{
			  $cart_qtt = 1;
			  $data = add_to_cart($sku_id,$userid,$cart_qtt);
			  send_reply_message($API_URL, $POST_HEADER, $data);
			}
			if(explode(" ",$info)[0] == 'Delete')
			{
			  delete_from_cart($sku_id,$userid);
			  $data = $data = ['replyToken' => $reply_token,'messages' => [['type' => 'text', 'text' => 'ลบสินค้ารหัส '.$sku_id.' ออกจากตะกร้าเรียบร้อยแล้ว']]];
			  send_reply_message($API_URL, $POST_HEADER, $data);
			}
		}
	}
  }
 }
}
}





function format_message($reply_token,$message)
{
	$data = ['replyToken' => $reply_token,'messages' =>  [$message] ];
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
