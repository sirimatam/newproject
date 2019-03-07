<?php
require_once('connection.php');
require 'user_function.php';
require 'store_function.php';
require 'RichMenu/uploadandsetrichMenuDefault.php';


$richMenuId1 = "richmenu-ff58dd0a3a6e5f68cfc40afae5abe6ad"; //page1
$richMenuId2= "richmenu-b5605d39250019a4ad9734dffc7d23ef"; //page2


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
	   
	out_of_time($db);
	   
	if ($text=='ค้นหาสินค้า')
	{
	        $data = format_message($reply_token,button_all_type($db));
		file_put_contents("php://stderr", "POST RESULT =====>".json_encode($data));
		send_reply_message($API_URL, $POST_HEADER, $data);
		
	}
	elseif ($text=='เวลา')
	{
		/*
		$data = format_message($reply_token,['type'=>'text','text' => date("H:i:s") ]);
		pg_query($db,"UPDATE product SET prod_price = 300 WHERE prod_id = '6'"); */
		
		date_default_timezone_set("Asia/Bangkok");
		$time = date("H:i:s");
		$date = date("Y-m-d");
		$exp_date = date("Y-m-d", strtotime("+2 days", strtotime("2019-02-21")));
	    	if($date >= $exp_date )
		{
			if($time > "10:00:30") {
			pg_query($db,"DELETE FROM orderlist WHERE order_id = '5c80a6'");}
		}
		
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
	       $first = carousel_cart($db,$userid);
	       $check_cartp = pg_fetch_row(pg_query($db,"SELECT cartp_id FROM createcart WHERE cart_used = '0' AND cus_id = '$userid' "))[0];
	       $check = pg_fetch_row(pg_query($db,"SELECT * from cart_product WHERE cartp_id = '$check_cartp' "))[0];
	       if( $check == '' )
			  {	        
			          send_reply_message($API_URL, $POST_HEADER, format_message($reply_token,$first));
			  }
		else{
	        $show = [carousel_cart($db,$userid),flex_cart_beforeorder($db,$userid)];
	 	$post = format_message_v2($reply_token,$show);	        
		send_reply_message($API_URL, $POST_HEADER, $post);
	        file_put_contents("php://stderr", "POST REQUEST =====> ".json_encode($post, JSON_UNESCAPED_UNICODE)); }
		
	}
	elseif ($text=='ชำระเงิน')
	{
		$post1 = carousel_flex_order($db,$userid,'1');
		$post2 = ['type'=>'text','text' => 'โอนเงินไปยังที่เลขที่บัญชี bot shop Kbank 111222333 หรือพร้อมเพย์ 0812345678 แล้วอัพโหลดสลิป '];
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
	elseif ($text=='ที่ต้องจัดส่ง')
	{
		$data = format_message($reply_token,carousel_flex_order($db,$userid,'2'));
		send_reply_message($API_URL, $POST_HEADER,$data);
		file_put_contents("php://stderr", "POST ที่ต้องจัดส่ง =====> ".json_encode($data, JSON_UNESCAPED_UNICODE));
	}  
	elseif ($text=='ที่ต้องได้รับ')
	{	
//		move_to_history($db);
		$data = format_message($reply_token,carousel_flex_order($db,$userid,'3'));
		send_reply_message($API_URL, $POST_HEADER, $data);
		
		
	} 
	elseif ($text=='สินค้าที่ถูกใจ')
	{
		$post = format_message($reply_token,carousel_show_favorite($db,$userid));
	        send_reply_message($API_URL, $POST_HEADER, $post);
	       
       } 
	elseif ($text=='ประวัติการสั่งซื้อ')
	{
		$data = format_message($reply_token,carousel_flex_order($db,$userid,'4'));
		send_reply_message($API_URL, $POST_HEADER,$data);
		file_put_contents("php://stderr", "POST history =====> ".json_encode($data, JSON_UNESCAPED_UNICODE));
	       
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
	elseif(explode("=",$text)[0] == '')
	{
	$order = explode("=",$text)[1];
        $sku_ids = pg_query($db,'SELECT sku_id FROM stock');
	while($sku_id = pg_fetch_row($sku_ids))
	{
		if(explode(" ",$order)[0] == $sku_id[0])
		{
			$cart_qtt = explode(" ",$order)[1];
			$data = add_to_cart($db,$sku_id[0],$userid,$cart_qtt);
			send_reply_message($API_URL, $POST_HEADER, format_message($reply_token,$data));
			file_put_contents("php://stderr", "cart_qtt =====> ".json_encode($cart_qtt, JSON_UNESCAPED_UNICODE));
		}
	}
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
					$post = format_message_push($userid,$array_carousel);	 
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
			  $num_sku = pg_num_rows(pg_query($db,"SELECT sku_id FROM stock WHERE prod_id = '$prod_id[0]'"));
			  if ($num_sku<=10)
			  {
			  $data = format_message($reply_token,carousel_view_more($db,$prod_id[0]));
			  $send_result = send_reply_message($API_URL, $POST_HEADER, $data);
			  file_put_contents("php://stderr", "num_sku <=10  =====> ".$send_result);
			  }
			  else
			  {
			  $data = format_message_push($userid,carousel_view_more($db,$prod_id[0]));
			  $send_result = send_reply_message($API_URL_push, $POST_HEADER, $data);
			  file_put_contents("php://stderr", "num_sku >10  =====> ".$send_result);	  
			  }
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
		$data = format_message($reply_token,clear_cart($db,$cartid));
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
			  $data = ['replyToken' => $reply_token,'messages' => [['type' => 'text', 'text' => 'ลบสินค้ารหัส '.$sku_id[0].' ออกจากตะกร้าเรียบร้อยแล้ว กรุณากดเมนูตะกร้าของฉันเพื่อตรวจสอบอีกครั้ง']]];
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
function format_message_push($userid,$message)
{
	$data = ['to' => $userid,'messages' =>  $message ];
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
