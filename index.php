<?php
require_once('connection.php');
require 'function.php';
//require 'showproduct.php';
require 'RichMenu/setrichMenuDefault.php';

require 'track.class.php';


//print_r( flex_order($db,$order_id,$cartp_id));



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

        $text = $event['message']['text'];
	   
	$userid = $event['source']['userId'];
	$findid = pg_query($db,"SELECT cus_id FROM customer WHERE cus_id = '$userid' ");
	if( pg_num_rows($findid) == 0)
	{
		pg_query($db,"INSERT INTO customer (cus_id,cus_default) VALUES ('$userid','1')");
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
	       $address = explode("@@",$text)[1];
	       // check ว่า ใส่ address ครั้งแรกหรือเปล่า
	       
	       $firsttime = pg_query($db,"SELECT cus_description FROM customer WHERE cus_id = '$userid' AND cus_default = '1' ");
	       if(pg_fetch_row($firsttime)[0] == '')
	       {
		       pg_query($db,"UPDATE customer SET cus_description = '$address' WHERE cus_id = '$userid' AND cus_default = '1' ");
	       }
	       else{
	       
	       
	       
	       file_put_contents("php://stderr", "can explode ===> ".$address);
	       
	       
	        pg_query($db,"INSERT INTO customer (cus_id,cus_description,cus_default) VALUES ('$userid','$address','0') "); }
		$show = show_address($db,$userid);
		$data = format_message($reply_token,$show);
	       send_reply_message($API_URL, $POST_HEADER,$data);

       }
       
       

       elseif ($text=='สินค้าที่ชอบ')
	{
		$post = format_message($reply_token,carousel_show_favorite($db,$userid));
	        send_reply_message($API_URL, $POST_HEADER, $post);
	       file_put_contents("php://stderr", "POST REQUEST1 =====> ".json_encode($post, JSON_UNESCAPED_UNICODE));
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

	$data = explode(" ",$info);
	if($data[0] == 'ลบชื่อและที่อยู่นี้')
	{
		
		pg_query($db,"DELETE FROM Customer WHERE cus_id = '$data[2]' AND cus_description = '$data[1]' ");
		file_put_contents("php://stderr", "split  ===> ".$data );
		$show = show_address($db,$userid);
		$data = format_message($reply_token,$show);
	        send_reply_message($API_URL, $POST_HEADER,$data);
	}
	elseif(explode(" ",$info)[0] == 'ตั้งเป็นที่อยู่จัดส่งปัจจุบัน')
	{
		pg_query($db,"UPDATE Customer SET cus_default = '0' WHERE cus_id = '$data[2]' AND cus_default = '1' ");
		
		$data = explode(" ",$info);
		pg_query($db,"UPDATE Customer SET cus_default = '1' WHERE cus_id = '$data[2]' AND cus_description = '$data[1]' ");
		$show = show_address($db,$userid);
		$data = format_message($reply_token,$show);
	       send_reply_message($API_URL, $POST_HEADER,$data);
	}  
	  
	  

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
	if(explode(" ",$info)[0] == 'Order')
	{
		$cart_avail = explode(" ",$info)[1];
		$order_id = add_to_order($db,$userid,$cart_avail);
		$data = format_message($reply_token,flex_order($db,$order_id,$cart_avail));
		$send_result = send_reply_message($API_URL, $POST_HEADER, $data);
		file_put_contents("php://stderr", "POST RESULT =====> ".json_encode($data, JSON_UNESCAPED_UNICODE));
		file_put_contents("php://stderr", "POST RESULT2 =====> ".$send_result);
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
 }
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
