<?php
//require_once('connection.php');
//require 'function.php';
//require 'showproduct.php';
$RICH_URL = 'https://api.line.me/v2/bot/richmenu';

//echo $db;

$API_URL = 'https://api.line.me/v2/bot/message/reply';
$ACCESS_TOKEN = 'lBX5YbEdwZ498JOXn+dInNH+7+WS2y7zSGQx77c8nmWwV+jhqYTJHzKm6i9yxK+zU0AgIBSwSyumjqfA22ZZVWQxrkmbxfDaupCQ3tPD0ypZNc0WdUfeobmpMs5EhxVg5/s6SdVQ42+Dy4OE4+WJOAdB04t89/1O/w1cDnyilFU='; // Access Token ค่าที่เราสร้างขึ้น
$POST_HEADER = array('Content-Type: application/json', 'Authorization: Bearer ' . $ACCESS_TOKEN);
/*$request = file_get_contents('php://input');   // Get request content
$request_array = json_decode($requeostgres cannot delst, true);   // Decode JSON to Array

if ( sizeof($request_array['events']) > 0 )
{
 foreach ($request_array['events'] as $event)
 {
  $reply_message = '';
  $reply_token = $event['replyToken']; */

$rich_area = array(
        array('bounds'=> array( 'x'=>'0','y'=>'0','width' => 824,'height' => 776 ), 'action' => array('type'=> 'message', 'text' =>'ดูและสั่งซื้อสินค้า')),
	array('bounds'=> array( 'x'=>'833','y'=>'0','width' => 814,'height' => 785 ), 'action' => array('type'=> 'message', 'text' =>'โปรโมชัน')),
	array('bounds'=> array( 'x'=>'1686','y'=>'0','width' => 814,'height' => 785 ), 'action' => array('type'=> 'message', 'text' =>'ตะกร้าสินค้า')),
	array('bounds'=> array( 'x'=>'10','y'=>'817','width' => 1242,'height' => 869 ), 'action' => array('type'=> 'message', 'text' =>'ที่อยู่จัดส่ง')),
	array('bounds'=> array( 'x'=>'862','y'=>'798','width' => 795,'height' => 888 ), 'action' => array('type'=> 'message', 'text' =>'สินค้าที่ชอบ')),
	array('bounds'=> array( 'x'=>'1686','y'=>'807','width' => 814,'height' => 879 ), 'action' => array('type'=> 'message', 'text' =>'เช็คสถานะ'))
		  );

$rich_object = array('size'=> array('width'=>2500,'height'=>1686),'selected'=> true ,
				     'name'=>'rich_menu','chatBarText'=>'Menu','areas'=>  $rich_area );

$rich_obj_req = json_encode($rich_object, JSON_UNESCAPED_UNICODE);	
$richmenu_id = create_rich_menu($RICH_URL,$ACCESS_TOKEN,$rich_obj_req); //เหมือนว่าทุกครั้งที่ deploy จะได้ richmenuid ใหม่กลับมา	  

file_put_contents("php://stderr", "POST JSON ===> ".$richmenu_id);
	 /*
  if ( $event['type'] == 'message' ) 
  {
   if( $event['message']['type'] == 'text' )
   {
        $text = $event['message']['text']; 
	$userid = $event['source']['userId'];
	$findid = pg_query($db,"SELECT * FROM Customer WHERE cus_id = $userid");
	if( sizeof(pg_fetch_row($findid)[0] == 0)
	{
		pg_query($db,"INSERT INTO Customer (cus_id) VALUES ($userid)");
		pg_query($db,"INSERT INTO Createcart VALUES (cus_id) VALUES $userid");
	}
	
	if ($text=='ดูและสั่งซื้อสินค้า')
	{
    
		$post = button_all_type();
		send_reply_message($API_URL, $POST_HEADER, $post);
	}	
	elseif ($text=='โปรโมชัน')
	{
		$post = show_promotion_product();
		send_reply_message($API_URL, $POST_HEADER, $post);

	}
       elseif ($text=='ตะกร้าสินค้าที่บันทึกไว้')
	{
		$reply_message = "3";
	}
	elseif ($text=='ที่อยู่จัดส่ง')
	{
		

send_reply_message($API_URL, $POST_HEADER, $post_body);

		
	}
	   
       elseif ($text=='ดูที่อยู่จัดส่ง')
	{
		$address = pg_query($db,"SELECT cus_description FROM Customer WHERE Customer.cus_id = $cusid");
	       $show_address = pg_fetch_row($address)[0];
	       $data = [
		    'replyToken' => $reply_token,
		    'messages' => [['type' => 'text', 'text' => $show_address]]
		   ];
	       
	       send_reply_message($API_URL, $POST_HEADER, $post_body);
	}
       elseif ($text=='แก้ไขที่อยู่')
	{
		pg_query($db,"UPDATE Customer SET cus_description = $cusaddress WHERE cus_id = $cusid ");
		$data = [
		    'replyToken' => $reply_token,
		    'messages' => [['type' => 'text', 'text' => 'แก้ไขที่อยู่เรียบร้อยแล้ว']]
		   ];
	       send_reply_message($API_URL, $POST_HEADER, $post_body);
       }  
       
       elseif ($text=='สินค้าที่ชอบ')
	{
		$post = carousel_show_favorite($userid);
	        send_reply_message($API_URL, $POST_HEADER, $post);
	}
	   
        elseif ($text=='เช็คสถานะ')
	{
		$reply_message = "6";
	}
	$types =  pg_query($db,'SELECT prod_type FROM Product GROUP BY prod_type ');
	
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
   
	else
	$reply_message = 'why dont you say hello to me';
   }
   else
    $reply_message = 'ระบบได้รับ '.ucfirst($event['message']['type']).' ของคุณแล้ว';
  
  }
  elseif($event['type'] == 'postback')
  {
	$info = $event['postback']['data'];
	$prod_ids = pg_query($db,'SELECT prod_id FROM Product');
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
  }
  else
   $reply_message = 'ระบบได้รับ Event '.ucfirst($event['type']).' ของคุณแล้ว';



   	
$post_body = json_encode($data, JSON_UNESCAPED_UNICODE);
send_reply_message($API_URL, $POST_HEADER, $post_body);

    
  
}
} 

*/

function create_rich_menu($post_url, $ACCESS_TOKEN , $post_body)
{
	$curl = curl_init();
    curl_setopt_array($curl, array(
      CURLOPT_URL => $post_url,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_POSTFIELDS => $post_body,
      CURLOPT_HTTPHEADER => array(
        "authorization: Bearer ".$ACCESS_TOKEN,
        "cache-control: no-cache",
        "content-type: application/json; charset=UTF-8",
      ),
    ));
 
 $result = curl_exec($curl);
 $err = curl_error($curl);
 	
 curl_close($curl);
	
 if ($err) {
        return $err;
    } else {
    	return $result;
    }	
}		 

function format_message($message)
{
	$data = ['replyToken' => $reply_token,'messages' => [ $message ]];
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
