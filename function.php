<?php


function get_datetime()
{
	date_default_timezone_set("Asia/Bangkok");
	$date = date("Y-m-d");
	$time = date("H:i:s");
	return [$date,$time];
}


function get_user_content($msgid, $post_header)
{
	$get_url = 'https://api.line.me/v2/bot/message/'.$msgid.'/content';	
	$ch = curl_init($get_url);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
	curl_setopt($ch, CURLOPT_HTTPHEADER, $post_header);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //do not output directly, use variable
	curl_setopt($ch, CURLOPT_ENCODING, "");
	curl_setopt($ch, CURLOPT_MAXREDIRS, 10); 
	curl_setopt($ch, CURLOPT_TIMEOUT, 30); 
	curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, "");
	
	$response = curl_exec($ch);
	curl_close($ch);
}
	   
function show_promotion_product($db) 
{ 
   $promo = pg_query($db,"SELECT * FROM product WHERE prod_price>prod_pro_price"); 
   $num = pg_num_rows($promo);
   
   //if($num>10)
   //{
	$promo_top = pg_query($db,"SELECT * FROM product WHERE prod_price>prod_pro_price ORDER BY ((prod_price-prod_pro_price)/prod_price) DESC LIMIT 10");  
   	$promo_num = pg_num_rows($promo_top);
   /*}
   else
   {
   	$promo_top = pg_query($db,"SELECT * FROM product WHERE prod_price>prod_pro_price ORDER BY ((prod_price-prod_pro_price)/prod_price) DESC ");  
   	$promo_num = pg_num_rows($promo_top);
   } */
	
   $promo_list = array();	
   $run = 0;
   while($promo_list_single = pg_fetch_row($promo_top))
   {
	$promo_list[$run] = $promo_list_single;
	$run++;
   }
   $running = 0;
   
        $datas = [];
	$datas['type'] = 'template';
        $datas['altText'] = 'this is a carousel template';
        $datas['template']['type'] = 'carousel';
      for ($i=0; $i<$run;$i++)
     {
        
        $datas['template']['columns'][$i]['thumbnailImageUrl'] = $promo_list[$i][2]; 
        $datas['template']['columns'][$i]['title'] = $promo_list[$i][1];
        $datas['template']['columns'][$i]['text'] = $promo_list[$i][4];
	$datas['template']['columns'][$i]['actions'][0]['type'] = 'postback';
        $datas['template']['columns'][$i]['actions'][0]['label'] = 'รายละเอียดเพิ่มเติม';
        $datas['template']['columns'][$i]['actions'][0]['text'] = 'view more';
        $datas['template']['columns'][$i]['actions'][0]['data'] =  'View '.$promo_list[$i][0];
        $datas['template']['columns'][$i]['actions'][1]['type'] = 'postback';
        $datas['template']['columns'][$i]['actions'][1]['label'] = 'บันทึกเป็น Favorite';
        $datas['template']['columns'][$i]['actions'][1]['text'] = 'บันทึกเป็น Favorite';   
        $datas['template']['columns'][$i]['actions'][1]['data'] = 'Favorite '.$promo_list[$i][0];
     }
     
     return $datas;
   
}   
	   
  
function button_all_type($db)
  {
	  $query = pg_query($db,"SELECT prod_type FROM product GROUP BY prod_type");
	  $run = 0;	
	  $type = Array();
	   while($each = pg_fetch_row($query)[0])
	   {
		   $type[$run] = $each;
		   $run++;
	   }
	   
	  $data = [];
	  $data['type'] = 'flex';
	  $data['altText'] = 'Flex Message';
	  $data['contents']['type'] = 'bubble';
	  $data['contents']['direction'] = 'ltr';
	  $data['contents']['header']['type'] = 'box';
	  $data['contents']['header']['layout'] = 'vertical';
	  $data['contents']['header']['contents'][0]['type'] = 'text';
	  $data['contents']['header']['contents'][0]['text'] = 'เลือกประเภทสินค้า';
	  $data['contents']['header']['contents'][0]['align'] = 'center';
	  $data['contents']['header']['contents'][0]['weight'] = 'bold';
	  $data['contents']['body']['type'] = 'box';
	  $data['contents']['body']['layout'] = 'vertical';
	  
	  for ($i=0;$i<$run;$i++)
	  {
	  $data['contents']['body']['contents'][$i]['type'] = 'button';
	  $data['contents']['body']['contents'][$i]['action']['type'] = 'message';
	  $data['contents']['body']['contents'][$i]['action']['label'] = $type[$i];
	  $data['contents']['body']['contents'][$i]['action']['text'] = $type[$i];
	  }
	  
   return $data;
  }  
  

function button_pay_track()
{
	$data = [];
	$data['type'] = 'template';
	$data['altText'] = 'this is a buttons template';
	$data['template']['type'] = 'buttons';
	$data['template']['actions'][0]['type'] = 'message';
	$data['template']['actions'][0]['label'] = "แจ้งโอนเงิน";
	$data['template']['actions'][0]['text'] = "แจ้งโอนเงิน";
	$data['template']['actions'][1]['type'] = 'message';
	$data['template']['actions'][1]['label'] = "เช็คสถานะพัสดุ";
	$data['template']['actions'][1]['text'] = "เช็คสถานะพัสดุ";
	$data['template']['text'] = "กรุณาเลือกเมนู";
	
	return $data;
		
}

function button_order_status($cus_id)
  {
    $data = [];
    $data['type'] = 'template';
    $data['altText'] = 'this is a buttons template';
    $data['template']['type'] = 'buttons';
    $data['template']['action'][0]['type'] = 'message';
    $data['template']['action'][0]['label'] = 'เลขที่บัญชีของร้าน';
    $data['template']['action'][0]['text'] = 'เลขที่บัญชีของร้าน';
    $data['template']['action'][1]['type'] = 'message';
    $data['template']['action'][1]['label'] = 'แจ้งโอนเงิน';
    $data['template']['action'][1]['text'] = 'แจ้งโอนเงิน';
    $data['template']['action'][2]['type'] = 'message';
    $data['template']['action'][2]['label'] = 'เช็คสถานะการจัดส่ง';
    $data['template']['action'][2]['text'] = 'เช็คสถานะการจัดส่ง';
    $data['template']['text'] = 'กรุณาเลือกหัวข้อที่สนใจ';
	  
    return $data;
  }




function show_address($db,$cusid)
{
	$query = pg_query($db,"SELECT * FROM customer WHERE cus_id = '$cusid' ");
	$check = 0;
	if(pg_num_rows($query)==1)
	 { 
	   $cus_primary = pg_fetch_row($query)[0];
	   pg_query($db,"UPDATE customer SET cus_default = '1' WHERE cus_primary = '$cus_primary' ");  // in case ลบอันปัจจุบัน อันที่เหลือ =1
		
	   $address = pg_fetch_row(pg_query($db,"SELECT cus_description FROM customer WHERE cus_primary = '$cus_primary' "))[0];	
	   if(strlen($address) == 0)
		 { $address = 'กรุณาเพิ่ม ชื่อ นามสกุล และที่อยู่จัดส่ง'; }
	
	 }
	
	else
	{	
	$current = pg_query($db,"SELECT cus_description FROM customer WHERE cus_id = '$cusid' AND cus_default = '1'"); //ปัจจุบัน
	$address = pg_fetch_row($current)[0];
	if(strlen($address)=='')
	{
		$other_prim = pg_fetch_row(pg_query($db,"SELECT * FROM customer WHERE cus_id = '$cusid' LIMIT 1"))[0];
	        pg_query($db,"UPDATE customer SET cus_default = '1' WHERE cus_primary = '$other_prim' AND cus_id = '$cusid' ");
		// set อันแรกให้เป็นปจบ
		$address = pg_fetch_row(pg_query($db,"SELECT cus_description FROM customer WHERE cus_id = '$cusid' AND cus_default = '1'"))[0];		   
	}
	
	$other = pg_query($db,"SELECT cus_description FROM customer WHERE cus_id = '$cusid' AND cus_default = '0'");				   
					   
		
	$check = 1;	
	}
	
	$datas = [];
	$datas['type'] = 'template';
	$datas['altText'] = 'this is a carousel template';
	$datas['template']['type'] = 'carousel';
	$datas['template']['columns'][0]['title'] = 'ชื่อและที่อยู่จัดส่งปัจจุบัน';
	$datas['template']['columns'][0]['text'] = $address;
	$datas['template']['columns'][0]['actions'][0]['type'] = 'message';
	$datas['template']['columns'][0]['actions'][0]['label'] = 'เพิ่มชื่อและที่อยู่ใหม่';
	$datas['template']['columns'][0]['actions'][0]['text'] = 'เพิ่มชื่อและที่อยู่ใหม่';
	$datas['template']['columns'][0]['actions'][1]['type'] = 'postback';
	$datas['template']['columns'][0]['actions'][1]['label'] = 'ลบชื่อและที่อยู่นี้';
	$datas['template']['columns'][0]['actions'][1]['text'] = 'ลบชื่อและที่อยู่นี้';
	$datas['template']['columns'][0]['actions'][1]['data'] = 'ลบชื่อและที่อยู่นี้###'.$address.'###'.$cusid;	
	
	if($check==1)
	{ 
    
	   $a = 0;	
	   $address_array = [];
	   while($other_address = pg_fetch_row($other)[0])
	   {
		   $address_array[$a] = $other_address;
		   $a++;
	   }
	   
	   for($i=1;$i<=$a;$i++)	
	   {
		
		$datas['template']['columns'][$i]['title'] = 'ชื่อและที่อยู่จัดส่งเพิ่มเติม';
		$datas['template']['columns'][$i]['text'] = $address_array[$i-1];
		$datas['template']['columns'][$i]['actions'][0]['type'] = 'postback';
		$datas['template']['columns'][$i]['actions'][0]['label'] = 'ตั้งเป็นที่อยู่จัดส่งปัจจุบัน';
		$datas['template']['columns'][$i]['actions'][0]['text'] = 'ตั้งเป็นที่อยู่จัดส่งปัจจุบัน';
		$datas['template']['columns'][$i]['actions'][0]['data'] = 'ตั้งเป็นที่อยู่จัดส่งปัจจุบัน###'.$address_array[$i-1].'###'.$cusid;
		$datas['template']['columns'][$i]['actions'][1]['type'] = 'postback';
		$datas['template']['columns'][$i]['actions'][1]['label'] = 'ลบชื่อและที่อยู่นี้';
		$datas['template']['columns'][$i]['actions'][1]['text'] = 'ลบชื่อและที่อยู่นี้';
		$datas['template']['columns'][$i]['actions'][1]['data'] = 'ลบชื่อและที่อยู่นี้###'.$address_array[$i-1].'###'.$cusid;
	   }
		
	
	}
	return $datas;
		
		
}
	
function carousel_product_type($db,$type) // $type = Prod_type FROM Product
{ 
  // how to check whether prod_qtt > 0
   $pd_type = pg_query($db,"SELECT * FROM product WHERE prod_type = '$type'");  
   $num_carousel = pg_num_rows($pd_type);
   //$list = pg_fetch_row($pd_type);
   $prod = array();
   $prod_num = 0;
   //$times = $num_carousel/10;
   $running = 0;
   $carousel = array();
   if($num_carousel <=10)
   {
	$datas = [];
        $datas['type'] = 'template';
        $datas['altText'] = 'this is a carousel template';
        $datas['template']['type'] = 'carousel';
	//$datas['template']['actions'] = [];
	while($list = pg_fetch_row($pd_type))
	{
		$prod[$prod_num] = $list;
		$prod_num++;
	}
      for ($i=0; $i<$num_carousel;$i++)
     {
  	
        $datas['template']['columns'][$i]['thumbnailImageUrl'] = $prod[$i][2]; 
        $datas['template']['columns'][$i]['title'] = $prod[$i][1];
        $datas['template']['columns'][$i]['text'] = $prod[$i][4];
        $datas['template']['columns'][$i]['actions'][0]['type'] = 'postback';
        $datas['template']['columns'][$i]['actions'][0]['label'] = 'รายละเอียดเพิ่มเติม';
        $datas['template']['columns'][$i]['actions'][0]['text'] = 'view more';
        $datas['template']['columns'][$i]['actions'][0]['data'] =  'View '.$prod[$i][0];
        $datas['template']['columns'][$i]['actions'][1]['type'] = 'postback';
        $datas['template']['columns'][$i]['actions'][1]['label'] = 'บันทึกเป็น Favorite';
        $datas['template']['columns'][$i]['actions'][1]['text'] = 'บันทึกเป็น Favorite';   
        $datas['template']['columns'][$i]['actions'][1]['data'] = 'Favorite '.$prod[$i][0];
     }
     $carousel[0] = $datas;
     return $carousel;
   }
   else
   {
   while( $running < $num_carousel)  
   {
	   $datas = [];
        $datas['type'] = 'template';
        $datas['altText'] = 'this is a carousel template';
        $datas['template']['type'] = 'carousel';
	//$datas['template']['actions'] = [];
	while($list = pg_fetch_row($pd_type))
	{
		$prod[$prod_num] = $list;
		$prod_num++;
	}
     for ($i=0;$i<10;$i++)
     {
      
        $datas['template']['columns'][$i]['thumbnailImageUrl'] = $prod[$i][2]; 
        $datas['template']['columns'][$i]['title'] = $prod[$i][1];
        $datas['template']['columns'][$i]['text'] = $prod[$i][4];
        $datas['template']['columns'][$i]['actions'][0]['type'] = 'postback';
        $datas['template']['columns'][$i]['actions'][0]['label'] = 'รายละเอียดเพิ่มเติม';
        $datas['template']['columns'][$i]['actions'][0]['text'] = 'view more';
        $datas['template']['columns'][$i]['actions'][0]['data'] =  'View '.$prod[$i][0];
        $datas['template']['columns'][$i]['actions'][1]['type'] = 'postback';
        $datas['template']['columns'][$i]['actions'][1]['label'] = 'บันทึกเป็น Favorite';
        $datas['template']['columns'][$i]['actions'][1]['text'] = 'บันทึก '.$prod[$i][1].' เป็น Favorite';   
        $datas['template']['columns'][$i]['actions'][1]['data'] = 'Favorite '.$prod[$i][0];
        $running++;
     }
     $carousel[ceil($running-10)/10] = $datas;
   }
    return $carousel;
   }
}
  
  
  
function carousel_view_more($db,$prod_id) 
{
  
  $pd_name = pg_fetch_row(pg_query($db,"SELECT prod_name FROM Product WHERE prod_id = '$prod_id'"))[0];
  $pd_des = pg_fetch_row(pg_query($db,"SELECT prod_description FROM Product WHERE prod_id = '$prod_id'"))[0];
  $pd_sku = pg_query($db,"SELECT * FROM stock WHERE stock.prod_id = '$prod_id'");
  //$list = pg_fetch_row($pd_sku);
  $num_carousel = pg_num_rows($pd_sku);
  $sku = array();
  $sku_num =0;
  //$times = $num_carousel/10;
   $running = 0;
   $carousel = array();
  if($num_carousel <=10)
   {
	$datas = [];
        $datas['type'] = 'template';
        $datas['altText'] = 'this is a carousel template';
        $datas['template']['type'] = 'carousel';
	while($list = pg_fetch_row($pd_sku))
	{
		$sku[$sku_num] = $list;
		$sku_num++;
	}
      for ($i=0; $i<$num_carousel;$i++)
     {
     
        $datas['template']['columns'][$i]['thumbnailImageUrl'] = $sku[$i][5]; 
        $datas['template']['columns'][$i]['title'] = $pd_name;
        $datas['template']['columns'][$i]['text'] = $pd_des."\n".$sku[$i][3]."  Stock : ".$sku[$i][2];
        $datas['template']['columns'][$i]['actions'][0]['type'] = 'postback';
        $datas['template']['columns'][$i]['actions'][0]['label'] = 'สั่งลงตะกร้า';
        $datas['template']['columns'][$i]['actions'][0]['text'] = 'บันทึก'.$pd_name.' '.$sku[$i][3].' ลงตะกร้าเรียบร้อยแล้ว';
        $datas['template']['columns'][$i]['actions'][0]['data'] = 'Cart '.$sku[$i][0];
	/*$datas['template']['columns'][$i]['actions'][0]['type'] = 'postback';
        $datas['template']['columns'][$i]['actions'][0]['label'] = 'สั่งสินค้ามากกว่า 1 ชิ้น';
        $datas['template']['columns'][$i]['actions'][0]['text'] = "กรุณาพิมพ์รหัสสินค้า เว้นวรรค ตามด้วยจำนวนสินค้าที่ต้องการ เช่น A001 4";
	$datas['template']['columns'][$i]['actions'][0]['data'] = 'สั่งสินค้ามากว่า 1 ชิ้น';*/
        $datas['template']['columns'][$i]['actions'][1]['type'] = 'message';
        $datas['template']['columns'][$i]['actions'][1]['label'] = 'ดูสินค้าอื่น';
        $datas['template']['columns'][$i]['actions'][1]['text'] = 'ดูและสั่งซื้อสินค้า';  
     }
     //$carousel[0] = $datas;
     return $datas;
   }
   else
   {
   while( $running < $num_carousel)  
   {
     for ($i=0; $i<10;$i++)
     {
        $datas = [];
        $datas['type'] = 'template';
        $datas['altText'] = 'this is a carousel template';
        $datas['template']['type'] = 'carousel';
        $datas['template']['columns'][$i]['thumbnailImageUrl'] = $list[$i][$sku_img]; 
        $datas['template']['columns'][$i]['title'] = $pd_name;
        $datas['template']['columns'][$i]['text'] = $list[$i][$prod_description]."</br>".$list[$i][$sku_color]."ขนาด".$list[$i][$sku_size]."</br>".$list[$i][$sku_qtt];
        $datas['template']['columns'][$i]['actions'][0]['type'] = 'postback';
        $datas['template']['columns'][$i]['actions'][0]['label'] = 'สั่งสินค้า 1 ชิ้น';
        $datas['template']['columns'][$i]['actions'][0]['text'] = 'บันทึก'.$pd_name.' '.$list[$i][$sku_color].' ลงตะกร้าเรียบร้อยแล้ว';
        $datas['template']['columns'][$i]['actions'][0]['data'] = 'Cart '.$list[$i][$sku_id];
	$datas['template']['columns'][$i]['actions'][0]['type'] = 'postback';
        $datas['template']['columns'][$i]['actions'][0]['label'] = 'สั่งสินค้ามากกว่า 1 ชิ้น';
        $datas['template']['columns'][$i]['actions'][0]['text'] = "กรุณาพิมพ์รหัสสินค้า เว้นวรรค ตามด้วยจำนวนสินค้าที่ต้องการ เช่น A001 4";
	$datas['template']['columns'][$i]['actions'][0]['data'] = 'สั่งสินค้ามากว่า 1 ชิ้น';
        $datas['template']['columns'][$i]['actions'][1]['type'] = 'message';
        $datas['template']['columns'][$i]['actions'][1]['label'] = 'ดูสินค้าอื่น';
        $datas['template']['columns'][$i]['actions'][1]['text'] = 'ดูและสั่งซื้อสินค้า';    
        $running++;
     }
     $carousel[ceil($running-10)/10] = $datas;
   }  
   }
  
  
}
  
//if message['text'] == 'Favorite'.$prod_id
  
function add_favorite($db,$cus_id,$prod_id)
  {
    /* check fav cannot more than 10 */
   $check = pg_query($db,"SELECT * FROM favorite WHERE favorite.cus_id = '$cus_id'");
    $count = pg_num_rows($check);
    if($count>=10){ return $reply_msg = 'คุณสามารถ Favorite ได้ 10 รายการเท่านั้น';}  
    //end of function
    else{
    pg_query($db,"INSERT INTO favorite (cus_id,prod_id) VALUES ('$cus_id','$prod_id')");
    }
  }  
  
  function carousel_show_favorite($db,$cus_id)
  {
    $check = pg_query($db,"SELECT * FROM favorite WHERE favorite.cus_id = '$cus_id'"); 
    //$list = pg_fetch_row($check);
    $i = 0;
    $prod_array = array();
    $fav = array();
    while ($list = pg_fetch_row($check))
    {
	    $prod = pg_fetch_row(pg_query($db,"SELECT * FROM product WHERE prod_id = '$list[2]'")); 
	    $prod_array[$i] = $prod; 
	    $fav[$i] = $list[0];
	    $i++;
    }
    
    
    $datas = [];
    $datas['type'] = 'template';
    $datas['altText'] = 'this is a carousel template';
    $datas['template']['type'] = 'carousel';    
    for ($i=0; $i<pg_num_rows($check);$i++)
     {
        
        $datas['template']['columns'][$i]['thumbnailImageUrl'] = $prod_array[$i][2]; 
        $datas['template']['columns'][$i]['title'] = $prod_array[$i][1]; //check prod_name ว่าต้องมี [$i] มั้ย
        $datas['template']['columns'][$i]['text'] = $prod_array[$i][4];
        $datas['template']['columns'][$i]['actions'][0]['type'] = 'postback';
        $datas['template']['columns'][$i]['actions'][0]['label'] = 'รายละเอียดเพิ่มเติม';
        $datas['template']['columns'][$i]['actions'][0]['text'] = 'view more';
        $datas['template']['columns'][$i]['actions'][0]['data'] =  'View '.$prod_array[$i][0];
        $datas['template']['columns'][$i]['actions'][1]['type'] = 'postback';
        $datas['template']['columns'][$i]['actions'][1]['label'] = 'ลบออกจาก Favorite';
        $datas['template']['columns'][$i]['actions'][1]['text'] = 'Delete '.$fav[$i].'ออกจาก Favorite เรียบร้อย';  
        $datas['template']['columns'][$i]['actions'][1]['data'] =  'Delete_fav '.$fav[$i];
     }
    return $datas;
  }
  
  /* if message['text'] == delete.$fav_id' */
  function delete_favorite($db,$fav_id)
  {
    pg_query($db,"DELETE FROM favorite WHERE fav_id = '$fav_id'");
  }
  function delete_from_cart($db,$sku_id,$cus_id,$cart_qtt)
  {
    $cart_avail = pg_fetch_row(pg_query($db,"SELECT cartp_id FROM createcart WHERE cus_id = '$cus_id' AND cart_used = '0'"))[0];
    pg_query("DELETE FROM cart_product WHERE sku_id = '$sku_id' AND cartp_id = '$cart_avail'");
    $sku_qtt_now = pg_fetch_row(pg_query($db,"SELECT sku_qtt FROM stock WHERE sku_id = '$sku_id'"))[0];
    $sku_qtt_new = $sku_qtt_now+$cart_qtt;
    pg_query($db,"UPDATE stock SET sku_qtt = '$sku_qtt_new' WHERE sku_id = '$sku_id'");
  }
  
  

  
  
  
  
  
  
  
  
  
//if message['text'] == 'Cart'.$sku_id
function add_to_cart($db,$sku_id,$cus_id,$cart_qtt)
  {
    /* check cart cannot more than 10 */
    $cartp_id = pg_fetch_row(pg_query($db,"SELECT cartp_id FROM createcart WHERE cart_used = '0' AND cus_id = '$cus_id'"))[0];
    $check = pg_query($db,"SELECT * FROM cart_product WHERE cartp_id = '$cartp_id'");
    $count = pg_num_rows($check);
    if($count>=10){ return $reply_msg = 'คุณสามารถเพิ่มสินค้าลงตะกร้า ได้ 10 รายการเท่านั้น';}  
    //end of function
    else{
    $sku_qtt_now = pg_fetch_row(pg_query($db,"SELECT sku_qtt FROM stock WHERE sku_id = '$sku_id'"))[0];
    $sku_qtt_new = $sku_qtt_now-$cart_qtt;
    pg_query($db,"UPDATE stock SET sku_qtt = '$sku_qtt_new' WHERE sku_id = '$sku_id'"); //ยังไม่ได้ใส่กรณีซื้อSKUเดียวกันสองตัว
    pg_query($db,"INSERT INTO cart_product (cartp_id,sku_id,cart_prod_qtt) VALUES ('$cartp_id','$sku_id','$cart_qtt')"); //ยังไม่ได้ใส่กรณีซื้อSKUเดียวกันสองตัว
    }
  }    
  
//ยังแก้ไม่เสร็จ  
function carousel_cart($db,$cus_id)
{
    $cartid = pg_fetch_row(pg_query($db,"SELECT cartp_id FROM createcart WHERE cus_id = '$cus_id' AND cart_used = '0'"))[0];
    $skuid = pg_query($db,"SELECT sku_id FROM cart_product WHERE cart_product.cartp_id = '$cartid'");
    $skuarray = array();
    $run1 = 0;
    $total = pg_num_rows($skuid);
    while($aaa = pg_fetch_row($skuid)[0])
    {
	    $sku_detail = pg_fetch_row(pg_query($db,"SELECT * FROM stock WHERE sku_id = '$aaa'"));
	    $skuarray[$run1] = $sku_detail;
	    $run1++;
    }
    
	  //$pdid = pg_fetch_row(pg_query($db,"SELECT (prod_id,prod_name,prod_description) FROM Product WHERE Stock"));
    $namearray = array();
    $run2 = 0;
    
    for($i=0; $i<pg_num_rows($skuid);$i++)
    {
	 $prod_id = $skuarray[$i][1];
	 $x = pg_fetch_row(pg_query($db,"SELECT prod_name FROM product WHERE prod_id = '$prod_id'"))[0];
	 $y = pg_fetch_row(pg_query($db,"SELECT prod_description FROM product WHERE prod_id = '$prod_id'"))[0];
	 $z = pg_fetch_row(pg_query($db,"SELECT prod_pro_price FROM product WHERE prod_id = '$prod_id'"))[0];
	 $namearray[$run2][0] = $skuarray[$i][1];
	 $namearray[$run2][1] = $x;
	 $namearray[$run2][2] = $y;
	 $namearray[$run2][3] = $z;
	 $run2++;
    }
    //$pd = pg_fetch_result(pg_query($db,'SELECT (prod_id,prod_name,prod_description) FROM Product WHERE Stock.prod_id = Product.prod_id AND Cart_product.cartp_id = $cartid AND '));
    if(pg_num_rows($skuid) == 0)
    {
	 $data = [];
	 $data[0] = ['type' => 'text', 'text' => 'ไม่พบสินค้าในตะกร้า กรุณาเลือกสินค้าลงตะกร้า']; 
	 return $data;
    }
    else{
	$push_array = [];
	    
        $datas = [];
	$datas['type'] = 'template';
        $datas['altText'] = 'this is a carousel template';
        $datas['template']['type'] = 'carousel';        
    for ($i=0; $i<pg_num_rows($skuid);$i++)
     {	
        $datas['template']['columns'][$i]['thumbnailImageUrl'] = $skuarray[$i][5]; 
        $datas['template']['columns'][$i]['title'] = $namearray[$i][1];
        $datas['template']['columns'][$i]['text'] = $namearray[$i][2]."\n".$skuarray[$i][3]." จำนวน 1 ชิ้น";
        $datas['template']['columns'][$i]['actions'][0]['type'] = 'postback';
        $datas['template']['columns'][$i]['actions'][0]['label'] = 'ลบออกจาก ตะกร้า';
        $datas['template']['columns'][$i]['actions'][0]['text'] = 'ลบสินค้ารหัส'.$skuarray[$i][0].'ออกจากตะกร้า';  
        $datas['template']['columns'][$i]['actions'][0]['data'] =  'Delete '.$skuarray[$i][0];
     }
	
	 
	    
	/*    
	    
     
	$datas3 = [];
	$datas3['type'] = 'template';
        $datas3['altText'] = 'this is a confirm template';
        $datas3['template']['type'] = 'confirm';
        $datas3['template']['actions'][0]['type'] = 'postback';
        $datas3['template']['actions'][0]['label'] = 'สั่งซื้อ';
        $datas3['template']['actions'][0]['text'] = 'สั่งซื้อ';  
        $datas3['template']['actions'][0]['data'] =  'Order '.$cartid;
	$datas3['template']['actions'][1]['type'] = 'postback';
        $datas3['template']['actions'][1]['label'] = 'ล้างตะกร้า';
        $datas3['template']['actions'][1]['text'] = 'ล้างตะกร้า';  
        $datas3['template']['actions'][1]['data'] =  'Clear '.$cartid;
	$datas3['template']['text'] = 'สินค้า'.$total.' ชิ้น';
        */
    $push_array[0] = $datas;
    //$push_array[1] = $datas2;	
   // $push_array[2] = $datas3;
    return $datas;
    }
	
  }
	  
function flex_cart_beforeorder($db,$userid) //ต้องดึงไรมาใช้บ้างนิ    
{
	$cartpid = pg_fetch_row(pg_query($db,"SELECT cartp_id FROM createcart WHERE cart_used = 0 AND cus_id = '$userid' LIMIT 1 ORDER BY cartp_id DESC"))[0];
	$cartp_array = pg_query($db,"SELECT sku_id FROM cart_product WHERE cartp_id = '$cartp_id'");
	$skuid_array = array();
	$i = 0;
	while($cartp = pg_fetch_row($cartp_array)[0])
	{
		$skuid_array[$i] = $cartp;
		$i++;
	}
	$pdid_array = array();
	$sku_color = array();
	$run =0;
	foreach( $skuid_array as $skuid)
	{
		$pdid_array[$run] = pg_fetch_row(pg_query($db,"SELECT prod_id FROM stock WHERE sku_id = '$skuid'"))[0];
		$sku_color[$run] = pg_fetch_row(pg_query($db,"SELECT sku_color FROM stock WHERE sku_id = '$skuid'"))[0];
		$run++;
	}
	$running = 0;
	$pd = [];
	$total = 0;
	foreach ( $pdid_array as $pdid )
	{
		$x = pg_fetch_row(pg_query($db,"SELECT prod_id FROM product WHERE prod_id = '$pdid'"))[0];
		$y = pg_fetch_row(pg_query($db,"SELECT prod_name FROM product WHERE prod_id = '$pdid'"))[0];
		$z = pg_fetch_row(pg_query($db,"SELECT prod_pro_price FROM product WHERE prod_id = '$pdid'"))[0];
		$pd[$running][0] = $x;
		$pd[$running][1] = $y;
		$pd[$running][2] = $z;
		$total += $z;
		$running++;
	}
	
	
	$data = [];
	$data['type'] = 'flex';
	$data['altText'] = 'Flex Message';
	$data['contents']['type'] = 'bubble';
	$data['contents']['header']['type'] = 'box';
	$data['contents']['header']['layout'] = 'vertical';
	$data['contents']['header']['contents'][0]['type'] = 'text';
	$data['contents']['header']['contents'][0]['text'] = 'สรุปรายการสั่งซื้อ';
	$data['contents']['header']['contents'][0]['size'] = 'lg';
	$data['contents']['header']['contents'][0]['align'] = 'center';
	$data['contents']['header']['contents'][0]['weight'] = 'bold';
	$data['contents']['body']['type'] = 'box';
	$data['contents']['body']['layout'] = 'vertical';
	$data['contents']['body']['contents'][0]['type'] = 'box';
	$data['contents']['body']['contents'][0]['layout'] = 'baseline';
	$data['contents']['body']['contents'][0]['flex'] = 0;
	$data['contents']['body']['contents'][0]['contents'][0]['type'] = 'text';
	$data['contents']['body']['contents'][0]['contents'][0]['text'] = 'รวม'; //prod_name
	$data['contents']['body']['contents'][0]['contents'][0]['margin'] = 'sm';
	$data['contents']['body']['contents'][0]['contents'][0]['weight'] = 'regular';
	$data['contents']['body']['contents'][0]['contents'][1]['type'] = 'text';
	$data['contents']['body']['contents'][0]['contents'][1]['text'] = $total.' บาท'; //prod_name
	$data['contents']['body']['contents'][0]['contents'][1]['margin'] = 'sm';
	$data['contents']['body']['contents'][0]['contents'][1]['weight'] = 'regular';
	$data['contents']['body']['contents'][0]['contents'][1]['align'] = 'end';
	
	for($i=0;$i<pg_num_rows($cartp_array);$i++)
	{
		$data['contents']['header']['contents'][$i+1]['type'] = 'box';
		$data['contents']['header']['contents'][$i+1]['layout'] = 'baseline';
		$data['contents']['header']['contents'][$i+1]['flex'] = 0;
		$data['contents']['header']['contents'][$i+1]['contents'][0]['type'] = 'text';
		$data['contents']['header']['contents'][$i+1]['contents'][0]['text'] = $pd[$i][1].' '.$sku_color[$i]; //prod_name
		$data['contents']['header']['contents'][$i+1]['contents'][0]['margin'] = 'sm';
		$data['contents']['header']['contents'][$i+1]['contents'][0]['weight'] = 'regular';
		$data['contents']['header']['contents'][$i+1]['contents'][1]['type'] = 'text';
		$data['contents']['header']['contents'][$i+1]['contents'][1]['text'] = $pd[$i][2].' บาท'; //prod_name
		$data['contents']['header']['contents'][$i+1]['contents'][1]['margin'] = 'sm';
		$data['contents']['header']['contents'][$i+1]['contents'][1]['weight'] = 'regular';
		$data['contents']['header']['contents'][$i+1]['contents'][1]['align'] = 'end';
	}
	///อันนี้เพิ่งเติม
	$data['contents']['footer']['type'] = 'box';
	$data['contents']['footer']['layout'] = 'vertical';
	$data['contents']['footer']['contents'][0]['type'] = 'spacer';
	$data['contents']['footer']['contents'][0]['layout'] = 'xxl';
	$data['contents']['footer']['contents'][1]['type'] = 'button';
	$data['contents']['footer']['contents'][1]['action'][0]['type'] = 'postback';
	$data['contents']['footer']['contents'][1]['action'][0]['label'] = 'สั่งซื้อ';
	$data['contents']['footer']['contents'][1]['action'][0]['text'] = 'สั่งซื้อ';
	$data['contents']['footer']['contents'][1]['action'][0]['data'] = 'Order '.$cartid;
	$data['contents']['footer']['contents'][1]['color'] = '#E5352E';
	$data['contents']['footer']['contents'][1]['style'] = 'primary';
	$data['contents']['footer']['contents'][2]['type'] = 'button';
	$data['contents']['footer']['contents'][2]['action'][0]['type'] = 'postback';
	$data['contents']['footer']['contents'][2]['action'][0]['label'] = 'ล้างตะกร้า';
	$data['contents']['footer']['contents'][2]['action'][0]['text'] = 'ล้างตะกร้า';
	$data['contents']['footer']['contents'][2]['action'][0]['data'] = 'Clear '.$cartid;
	
	return $data;
	
}




function flex_order($db,$order_id,$cartp_id)
{
	
	//$order_array = pg_fetch_row($db,"SELECT * FROM order WHERE order_id = '$order_id'");
	//$cartp_id = $order_array[1];
	$cartp_array = pg_query($db,"SELECT sku_id FROM cart_product WHERE cartp_id = '$cartp_id'");
	$skuid_array = array();
	$i = 0;
	while($cartp = pg_fetch_row($cartp_array)[0])
	{
		$skuid_array[$i] = $cartp;
		$i++;
	}
	$pdid_array = array();
	$sku_color = array();
	$run =0;
	foreach( $skuid_array as $skuid)
	{
		$pdid_array[$run] = pg_fetch_row(pg_query($db,"SELECT prod_id FROM stock WHERE sku_id = '$skuid'"))[0];
		$sku_color[$run] = pg_fetch_row(pg_query($db,"SELECT sku_color FROM stock WHERE sku_id = '$skuid'"))[0];
		$run++;
	}
	$running = 0;
	$pd = [];
	$total = 0;
	foreach ( $pdid_array as $pdid )
	{
		$x = pg_fetch_row(pg_query($db,"SELECT prod_id FROM product WHERE prod_id = '$pdid'"))[0];
		$y = pg_fetch_row(pg_query($db,"SELECT prod_name FROM product WHERE prod_id = '$pdid'"))[0];
		$z = pg_fetch_row(pg_query($db,"SELECT prod_pro_price FROM product WHERE prod_id = '$pdid'"))[0];
		$pd[$running][0] = $x;
		$pd[$running][1] = $y;
		$pd[$running][2] = $z;
		$total += $z;
		$running++;
	}
	
	
	$data = [];
	$data['type'] = 'flex';
	$data['altText'] = 'Flex Message';
	$data['contents']['type'] = 'bubble';
	$data['contents']['header']['type'] = 'box';
	$data['contents']['header']['layout'] = 'vertical';
	$data['contents']['header']['contents'][0]['type'] = 'text';
	$data['contents']['header']['contents'][0]['text'] = 'รหัสใบสั่งซื้อที่ '.$order_id;
	$data['contents']['header']['contents'][0]['size'] = 'lg';
	$data['contents']['header']['contents'][0]['align'] = 'center';
	$data['contents']['header']['contents'][0]['weight'] = 'bold';
	$data['contents']['body']['type'] = 'box';
	$data['contents']['body']['layout'] = 'vertical';
	$data['contents']['body']['contents'][0]['type'] = 'box';
	$data['contents']['body']['contents'][0]['layout'] = 'baseline';
	$data['contents']['body']['contents'][0]['flex'] = 0;
	$data['contents']['body']['contents'][0]['contents'][0]['type'] = 'text';
	$data['contents']['body']['contents'][0]['contents'][0]['text'] = 'รวม'; //prod_name
	$data['contents']['body']['contents'][0]['contents'][0]['margin'] = 'sm';
	$data['contents']['body']['contents'][0]['contents'][0]['weight'] = 'regular';
	$data['contents']['body']['contents'][0]['contents'][1]['type'] = 'text';
	$data['contents']['body']['contents'][0]['contents'][1]['text'] = $total.' บาท'; //prod_name
	$data['contents']['body']['contents'][0]['contents'][1]['margin'] = 'sm';
	$data['contents']['body']['contents'][0]['contents'][1]['weight'] = 'regular';
	$data['contents']['body']['contents'][0]['contents'][1]['align'] = 'end';
	
	for($i=0;$i<pg_num_rows($cartp_array);$i++)
	{
		$data['contents']['header']['contents'][$i+1]['type'] = 'box';
		$data['contents']['header']['contents'][$i+1]['layout'] = 'baseline';
		$data['contents']['header']['contents'][$i+1]['flex'] = 0;
		$data['contents']['header']['contents'][$i+1]['contents'][0]['type'] = 'text';
		$data['contents']['header']['contents'][$i+1]['contents'][0]['text'] = $pd[$i][1].' '.$sku_color[$i]; //prod_name
		$data['contents']['header']['contents'][$i+1]['contents'][0]['margin'] = 'sm';
		$data['contents']['header']['contents'][$i+1]['contents'][0]['weight'] = 'regular';
		$data['contents']['header']['contents'][$i+1]['contents'][1]['type'] = 'text';
		$data['contents']['header']['contents'][$i+1]['contents'][1]['text'] = $pd[$i][2].' บาท'; //prod_name
		$data['contents']['header']['contents'][$i+1]['contents'][1]['margin'] = 'sm';
		$data['contents']['header']['contents'][$i+1]['contents'][1]['weight'] = 'regular';
		$data['contents']['header']['contents'][$i+1]['contents'][1]['align'] = 'end';
	}
	///อันนี้เพิ่งเติม
	$data['contents']['footer']['type'] = 'box';
	$data['contents']['footer']['layout'] = 'vertical';
	$data['contents']['footer']['contents'][0]['type'] = 'spacer';
	$data['contents']['footer']['contents'][0]['layout'] = 'xxl';
	$data['contents']['footer']['contents'][1]['type'] = 'button';
	$data['contents']['footer']['contents'][1]['action'][0]['type'] = 'postback';
	$data['contents']['footer']['contents'][1]['action'][0]['label'] = 'สั่งซื้อ';
	$data['contents']['footer']['contents'][1]['action'][0]['text'] = 'สั่งซื้อ';
	$data['contents']['footer']['contents'][1]['action'][0]['data'] = 'Order '.$cartid;
	$data['contents']['footer']['contents'][1]['color'] = '#E5352E';
	$data['contents']['footer']['contents'][1]['style'] = 'primary';
	$data['contents']['footer']['contents'][2]['type'] = 'button';
	$data['contents']['footer']['contents'][2]['action'][0]['type'] = 'postback';
	$data['contents']['footer']['contents'][2]['action'][0]['label'] = 'ล้างตะกร้า';
	$data['contents']['footer']['contents'][2]['action'][0]['text'] = 'ล้างตะกร้า';
	$data['contents']['footer']['contents'][2]['action'][0]['data'] = 'Clear '.$cartid;

	
	
	
	return $data;
	
}
    
    
    
    
function add_to_order($db,$cus_id,$cart_avail)
{
	
	$order_id = uniqid();
	//$cart_avail = pg_fetch_row(pg_query($db,"SELECT cartp_id FROM createcart WHERE cus_id = '$cus_id' AND cart_used = '0'"))[0];
	$skuids = pg_query($db,"SELECT sku_id FROM cart_product WHERE cartp_id = '$cart_avail'");
	$total_price = 0;
	while($skuid = pg_fetch_row($skuids)[0])
	{
		$prod_id = pg_fetch_row(pg_query($db,"SELECT prod_id FROM stock WHERE sku_id='$skuid'"))[0];
		$prod_price = pg_fetch_row(pg_query($db,"SELECT prod_pro_price FROM product WHERE product.prod_id='$prod_id'"))[0];
		$total_price += $prod_price; 
	}
	date_default_timezone_set("Asia/Bangkok");
	$time = date("H:i:s");
	$date = date("Y-m-d") ;
	pg_query($db,"INSERT INTO orderlist (order_id,cartp_id,total_price,order_date,order_time,order_status) VALUES ('$order_id','$cart_avail','$total_price','$date','$time','waiting for payment')");
	pg_query($db,"UPDATE createcart SET cart_used = '1' WHERE cartp_id = '$cart_avail'");
	pg_query($db,"INSERT INTO createcart (cus_id,cart_used) VALUES ('$cus_id','0')");
	return $order_id;
	
}
function clear_cart($db,$cart_qtt,$cart_avail)
{
	$sku_array = pg_query($db,"SELECT sku_id FROM cart_product WHERE cartp_id = '$cart_avail'");
	while($sku_id = pg_fetch_row($sku_array)[0])
	{
		$sku_qtt_now = pg_fetch_row(pg_query($db,"SELECT sku_qtt FROM stock WHERE sku_id = '$sku_id'"))[0];
    		$sku_qtt_new = $sku_qtt_now+$cart_qtt;
   		pg_query($db,"UPDATE stock SET sku_qtt = '$sku_qtt_new' WHERE sku_id = '$sku_id'");
	}
	pg_query("DELETE FROM cart_product WHERE cartp_id = '$cart_avail'");
	$data = ['type' => 'text', 'text' => 'ล้างตะกร้าเรียบร้อยแล้ว'];
	return $data;
}
  
  
  
  
  
  
  
  
?>
