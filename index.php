<?php
require_once('connection.php');

require 'user_function.php';
require 'store_function.php';
out_of_time($db);

require 'RichMenu/uploadandsetrichMenuDefault.php';
/*
require 'track.class.php';
$track = new Trackingmore;
$track = $track->getRealtimeTrackingResults('kerry-logistics','SHX306592865TH',Array());
$trace = $track['data']['items'][0]['lastEvent']; 
file_put_contents("php://stderr", "track =====> ".$track);
print_r($track);
*/
$richMenuId1 = "richmenu-ff58dd0a3a6e5f68cfc40afae5abe6ad"; //page1
$richMenuId2= "richmenu-717a8ebccd0d4a7e0ca2c85d77a50f10"; //page2

$API_URL = 'https://api.line.me/v2/bot/message/reply';
$API_URL_push = 'https://api.line.me/v2/bot/message/push';
$ACCESS_TOKEN = 'wa9sF+y4HsXJ2IqRQcTadD32XYH7lG01BLuw9O9AbkTSbdRUvC4CU6vOvAKCE4LGU0AgIBSwSyumjqfA22ZZVWQxrkmbxfDaupCQ3tPD0yrY67su+hl6Iw1oKWVpWo3JWOg7RFFphGSz3x5MY/aqMgdB04t89/1O/w1cDnyilFU='; // Access Token ค่าที่เราสร้างขึ้น
$POST_HEADER = array('Content-Type: application/json', 'Authorization: Bearer ' . $ACCESS_TOKEN);

set_richmenu_default($richMenuId1,$ACCESS_TOKEN);


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
	
	if ($text=='ค้นหาสินค้า')
	{
		
		$data = format_message($reply_token,button_all_type($db));
		file_put_contents("php://stderr", "POST RESULT =====>".json_encode($data));
		send_reply_message($API_URL, $POST_HEADER, $data);
		
	}
	
	elseif ($text=='โปรโมชั่น')
	{
		$data = format_message($reply_token,show_promotion_product($db));
		send_reply_message($API_URL, $POST_HEADER, $data);
		file_put_contents("php://stderr", "POST REQUEST1 =====> ".json_encode($data, JSON_UNESCAPED_UNICODE));

	}
	
       elseif ($text=='ตะกร้าของฉัน')
	{
	        $show = [carousel_cart($db,$userid),flex_cart_beforeorder($db,$userid)];
	 	$post = format_message_v2($reply_token,$show);	        
		send_reply_message($API_URL, $POST_HEADER, $post);
	        file_put_contents("php://stderr", "POST REQUEST =====> ".json_encode($post, JSON_UNESCAPED_UNICODE));
		
	}
	elseif ($text=='ชำระเงิน')
	{
		$post1 = ['type'=>'text','text' => 'โอนเงินไปยังที่เลขที่บัญชี bot shop Kbank 111222333 หรือพร้อมเพย์ 0812345678 แล้วอัพโหลดสลิป '];
		$post2 = carousel_flex_order($db,$userid,'1'); 
		$data1 = format_message_v2($reply_token,[$post1,$post2]);
			   
		send_reply_message($API_URL, $POST_HEADER, $data1);
		file_put_contents("php://stderr", "POST REQUEST1 =====> ".json_encode($data1, JSON_UNESCAPED_UNICODE));
	}      
	   
	   
	elseif ($text=='เกี่ยวกับร้านค้า')
	{
		//	        send_reply_message($API_URL, $POST_HEADER,$data);
	}
	elseif ($text=='หน้าถัดไป')
	{
		unlink_richmenu($userid,$ACCESS_TOKEN);
		set_richmenu_default($richMenuId2,$ACCESS_TOKEN);
	}   
	elseif ($text=='ที่รอชำระเงิน')
	{
		$data = format_message($reply_token,carousel_flex_order($db,$userid,'1'));
		send_reply_message($API_URL, $POST_HEADER,$data);
		file_put_contents("php://stderr", "POST รอชำระเงิน =====> ".json_encode($data, JSON_UNESCAPED_UNICODE));
	}  
	elseif ($text=='ที่ต้องจัดส่ง')
	{
		$data = format_message($reply_token,carousel_flex_order($db,$userid,'2'));
		send_reply_message($API_URL, $POST_HEADER,$data);
		file_put_contents("php://stderr", "POST ที่ต้องจัดส่ง =====> ".json_encode($data, JSON_UNESCAPED_UNICODE));
	}  
	elseif ($text=='ที่ต้องได้รับ')
	{	/*
		$i = 0;
		$cartpid_query = pg_query($db,"SELECT cartp_id from createcart WHERE cus_id = '$userid' AND orderlist.order_status != 'waiting for payment' AND orderlist.order_status != 'waiting for packing' ")
		$cartpid_list = Array();
		$orderid_list = Array();
		$tracking_list = Array();
		while($list = pg_fetch_row($cartpid_query)[0])
		{
		  $cartpid_list[$i] = $list;
		  $orderid_list[$i] = pg_fetch_row(pg_query($db,"SELECT order_id FROM orderlist WHERE cartp_id = '$list'"))[0];
		  $tracking_list[$i] = pg_fetch_row(pg_query($db,"SELECT order_status FROM orderlist WHERE cartp_id = '$list'"))[0]; 
		  $i++;
		}

		$payment = pg_fetch_row(pg_query($db,"SELECT check FROM payment WHERE payment.order_id = '$orderid'"))[0];
		$trace_list = Array();
		
		for($i=0;$i<sizeof($orderid_list);$i++)
		{
			
			$trace_list[$i] = $check;
			
		} */
		
		$data = format_message($reply_token,carousel_flex_order($db,$userid,'3'));
		send_reply_message($API_URL, $POST_HEADER, $data);
		
		
	} 
	elseif ($text=='สินค้าที่ถูกใจ')
	{
		$post = format_message($reply_token,carousel_show_favorite($db,$userid));
	        send_reply_message($API_URL, $POST_HEADER, $post);
	       
       } 
	elseif ($text=='ที่อยู่จัดส่ง')
	{
		
		$show = show_address($db,$userid);
		$post = format_message($reply_token,$show);
		send_reply_message($API_URL, $POST_HEADER, $post);
		file_put_contents("php://stderr", "address  ===> ".json_encode($show));
	}   
	elseif ($text=='กลับหน้าแรก')
	{
		unlink_richmenu($userid,$ACCESS_TOKEN);
		set_richmenu_default($richMenuId1,$ACCESS_TOKEN);
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

        
	else {
	$query_pd = pg_query($db,"SELECT prod_type FROM product GROUP BY prod_type");
	$run = 0;	
	$pdtype = [];
	while($each = pg_fetch_row($query_pd)[0])
	   {
		   $pdtype[$run] = $each;
		   $run++;
	   }   
 	foreach($pdtype as $type)
	{
		if($text == $type)
		{
			$array_carousel = carousel_product_type($db,$text);
			file_put_contents("php://stderr", "array_carousel =====> ".json_encode($array_carousel));
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
				file_put_contents("php://stderr", "POST RESULT =====> ".json_encode($post));
			}
		}
	}
	}
   } 
	  /*
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
  else { }
  }
	 
  elseif($event['type'] == 'postback')
  {
  	$userid = $event['source']['userId'];
	$info = $event['postback']['data'];
	
	$cus = explode("###",$info);  
	  
	file_put_contents("php://stderr", "cus  ===> ".$cus[0] );  
	  
	if($cus[0] == 'ลบชื่อและที่อยู่นี้')
	{
		
		pg_query($db,"DELETE FROM Customer WHERE cus_id = '$cus[2]' AND cus_description = '$cus[1]' ");
		
		$show = show_address($db,$userid);
		$data = format_message($reply_token,$show);
	        send_reply_message($API_URL, $POST_HEADER,$data);
	}
	elseif($cus[0] == 'ตั้งเป็นที่อยู่จัดส่งปัจจุบัน')
	{
		pg_query($db,"UPDATE Customer SET cus_default = '0' WHERE cus_id = '$cus[2]' AND cus_default = '1' ");
		// แก้ให้อันเดิมเป็น 0
		
		pg_query($db,"UPDATE Customer SET cus_default = '1' WHERE cus_id = '$cus[2]' AND cus_description = '$cus[1]' ");
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
		$cart_qtt = 1;
		$cartid = explode(" ",$info)[1];
		$data = format_message($reply_token,clear_cart($db,$cart_qtt,$cartid));
		send_reply_message($API_URL, $POST_HEADER, $data);
	}
	if(explode(" ",$info)[0] == 'Order')
	{
		$cartp = explode(" ",$info)[1];
		$order_id = add_to_order($db,$userid,$cartp);
		$data = format_message($reply_token,flex_order($db,$order_id,$cartp));
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
			  
			   $cart_qtt = 1;
			  delete_from_cart($db,$sku_id[0],$userid,$cart_qtt);
			  $data = ['replyToken' => $reply_token,'messages' => [['type' => 'text', 'text' => 'ลบสินค้ารหัส '.$sku_id[0].' ออกจากตะกร้าเรียบร้อยแล้ว']]];
			  send_reply_message($API_URL, $POST_HEADER, $data);
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
