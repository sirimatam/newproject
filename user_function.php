<?php
require 'track.class.php';
function get_user_content($msgid, $post_header)
{
	$get_url = 'https://api.line.me/v2/bot/message/'.$msgid.'/content';	
	$ch = curl_init($get_url);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
	curl_setopt($ch, CURLOPT_HTTPHEADER, $post_header);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //do not output directly, use variable
	curl_setopt($ch, CURLOPT_ENCODING, "");
	curl_setopt($ch, CURLOPT_MAXREDIRS, 10); 
	curl_setopt($ch, CURLOPT_TIMEOUT, 60); 
	curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, "");
	
	$response = curl_exec($ch);
	curl_close($ch);
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
	
  
  
function delete_favorite($db,$fav_id)
  {
    pg_query($db,"DELETE FROM favorite WHERE fav_id = '$fav_id'");
  }
function carousel_flex_order($db,$userid,$check)
{
	// check can be 1,2,3,4
	date_default_timezone_set("Asia/Bangkok");
	$time = date("H:i:s");
	$date = date("Y-m-d");
	
	$cartp_id_array = pg_query($db,"SELECT cartp_id FROM createcart WHERE createcart.cus_id = '$userid' AND createcart.cart_used = '1'");
	$run2 = 0;
	$run1 = 0;
	$cartp = array();
	$order_id = array();
	$order_price = array();
	$sku_color = array();
	$pd = array();
	$trackinglist = array();
	$datelist = array();

	while($cartp_id = pg_fetch_row($cartp_id_array)[0]) // check ทีละ cartp_id
	{
		if($check=='1') // ที่รอชำระเงิน
		{
			$a = pg_query($db,"SELECT cartp_id FROM orderlist WHERE cartp_id = '$cartp_id' AND order_status = 'waiting for payment'");
			$b = pg_query($db,"SELECT total_price FROM orderlist WHERE cartp_id = '$cartp_id' AND order_status = 'waiting for payment'");
			$c = pg_query($db,"SELECT order_id FROM orderlist WHERE cartp_id = '$cartp_id' AND order_status = 'waiting for payment'");
			$d = pg_query($db,"SELECT order_date FROM orderlist WHERE cartp_id = '$cartp_id' AND order_status = 'waiting for payment'");
			$e = pg_query($db,"SELECT order_time FROM orderlist WHERE cartp_id = '$cartp_id' AND order_status = 'waiting for payment'");
			
		}
		elseif($check=='2')//ที่ต้องจัดส่ง
		{
			$a = pg_query($db,"SELECT cartp_id FROM orderlist WHERE cartp_id = '$cartp_id' AND  order_status = 'waiting for packing'");
			$b = pg_query($db,"SELECT total_price FROM orderlist WHERE cartp_id = '$cartp_id' AND  order_status = 'waiting for packing'");
			$c = pg_query($db,"SELECT order_id FROM orderlist WHERE cartp_id = '$cartp_id' AND order_status = 'waiting for packing'");
			
		}
		elseif($check == '3')//ที่ต้องได้รับ
		{
			$a = pg_query($db,"SELECT cartp_id FROM orderlist WHERE cartp_id = '$cartp_id' AND order_status = 'shipping' ");
			$b = pg_query($db,"SELECT total_price FROM orderlist WHERE cartp_id = '$cartp_id' AND order_status = 'shipping' ");
			$c = pg_query($db,"SELECT order_id FROM orderlist WHERE cartp_id = '$cartp_id' AND order_status = 'shipping' ");
			$loop = '1';
		}
		elseif($check=='4')//history
		{
			$a = pg_query($db,"SELECT cartp_id FROM historyorder WHERE cartp_id = '$cartp_id' ORDER BY order_date DESC LIMIT 10 ");
			$b = pg_query($db,"SELECT total_price FROM historyorder WHERE cartp_id = '$cartp_id' ORDER BY order_date DESC LIMIT 10");
			$c = pg_query($db,"SELECT order_id FROM historyorder WHERE cartp_id = '$cartp_id' ORDER BY order_date DESC LIMIT 10");
			$d = pg_query($db,"SELECT order_date FROM historyorder WHERE cartp_id = '$cartp_id' ORDER BY order_date DESC LIMIT 10");
			$loop = '2';
		}
			
				
		if(pg_num_rows($a)>0 )
			{
				$aa = pg_fetch_row($a)[0]; //cartp
				$bb = pg_fetch_row($b)[0]; // price
				$cc = pg_fetch_row($c)[0]; //order id
				$dd = pg_fetch_row($d)[0]; // date
				$ee = pg_fetch_row($e)[0]; // time
				
				$cartp[$run1] = $aa;
				file_put_contents("php://stderr", " cartp_id ===> ".$cartp[$run1]);
				$order_price[$run1] = $bb;
				$order_id[$run1] = $cc;
				if($loop == '2' ) { $datelist[$run1] = $dd; } 
				$run1++;

			}
	}
	
	if($loop == '1')
		{
			for($i=0;$i<=$run1;$i++)
			{
				$trackinglist[$i] = pg_fetch_row(pg_query($db,"SELECT tracking_number FROM orderlist WHERE order_id = '$order_id[$i]' "))[0];
				
			}
		}
	
	for($k=0;$k<sizeof($cartp);$k++)
	{

		
			
		$sku_query = pg_query($db,"SELECT sku_id FROM cart_product WHERE cartp_id = '$cartp[$k]'");
		$skuid_array = array();
		$i = 0;
		
		
		while($list = pg_fetch_row($sku_query)[0])
		{
			$skuid_array[$i] = $list;
			$cartp_qtt[$i] = pg_fetch_row(pg_query($db,"SELECT cart_prod_qtt FROM cart_product WHERE cartp_id = '$cartp[$k]' AND sku_id = '$list'"))[0];
			$i++;
		}
		$pdid_array = array();
		$run =0;
		foreach( $skuid_array as $skuid)
		{
			$pdid_array[$run] = pg_fetch_row(pg_query($db,"SELECT prod_id FROM stock WHERE sku_id = '$skuid'"))[0];
			$sku_color[$k][$run] = pg_fetch_row(pg_query($db,"SELECT sku_color FROM stock WHERE sku_id = '$skuid'"))[0];
			$run++;
		}
		$running = 0;
		foreach ( $pdid_array as $pdid )
		{
			$pd_id = pg_fetch_row(pg_query($db,"SELECT prod_id FROM product WHERE prod_id = '$pdid'"))[0];
			$pd_name = pg_fetch_row(pg_query($db,"SELECT prod_name FROM product WHERE prod_id = '$pdid'"))[0];
			$pd_price = pg_fetch_row(pg_query($db,"SELECT prod_pro_price FROM product WHERE prod_id = '$pdid'"))[0]*$cartp_qtt[$running];
			$pd[$k][$running] = [$pd_id,$pd_name,$pd_price];
			$running++;
		}
	
	}
	
		
	$data = [];
	$data['type'] = 'flex';
	$data['altText'] = 'Flex Message';
	$data['contents']['type'] = 'carousel';
	
	for($j=0;$j<sizeof($cartp);$j++) // j = วนใบสั่งซื้อ
	{
	$data['contents']['contents'][$j]['type'] = 'bubble';
	$data['contents']['contents'][$j]['header']['type'] = 'box';
	$data['contents']['contents'][$j]['header']['layout'] = 'vertical';
	$data['contents']['contents'][$j]['header']['contents'][0]['type'] = 'text';
	$data['contents']['contents'][$j]['header']['contents'][0]['text'] = 'รหัสใบสั่งซื้อที่ '.$order_id[$j];
	$data['contents']['contents'][$j]['header']['contents'][0]['size'] = 'lg';
	$data['contents']['contents'][$j]['header']['contents'][0]['align'] = 'center';
	$data['contents']['contents'][$j]['header']['contents'][0]['weight'] = 'bold';
	$data['contents']['contents'][$j]['body']['type'] = 'box';
	$data['contents']['contents'][$j]['body']['layout'] = 'vertical';
	
		
		for($i=0;$i<sizeof($pd[$j]);$i++) // i = วน sku ในแต่ละใบสั่งซื้อ
		{
			$data['contents']['contents'][$j]['body']['contents'][$i]['type'] = 'box';
			$data['contents']['contents'][$j]['body']['contents'][$i]['layout'] = 'baseline';
			$data['contents']['contents'][$j]['body']['contents'][$i]['flex'] = 0;
			$data['contents']['contents'][$j]['body']['contents'][$i]['contents'][0]['type'] = 'text';
			$data['contents']['contents'][$j]['body']['contents'][$i]['contents'][0]['text'] = $skuid_array[$i].' '.$sku_color[$j][$i].' '.$cartp_qtt[$j].' ชิ้น'; //prod_name
			$data['contents']['contents'][$j]['body']['contents'][$i]['contents'][0]['margin'] = 'xs';
			$data['contents']['contents'][$j]['body']['contents'][$i]['contents'][0]['weight'] = 'regular';
			$data['contents']['contents'][$j]['body']['contents'][$i]['contents'][1]['type'] = 'text';
			$data['contents']['contents'][$j]['body']['contents'][$i]['contents'][1]['text'] = $pd[$j][$i][2].' บาท'; //prod_name
			$data['contents']['contents'][$j]['body']['contents'][$i]['contents'][1]['margin'] = 'sm';
			$data['contents']['contents'][$j]['body']['contents'][$i]['contents'][1]['weight'] = 'regular';
			$data['contents']['contents'][$j]['body']['contents'][$i]['contents'][1]['align'] = 'end';
		}
		
	$n = sizeof($pd[$j]);	
		
	$data['contents']['contents'][$j]['body']['contents'][$n]['type'] = 'box';
	$data['contents']['contents'][$j]['body']['contents'][$n]['layout'] = 'baseline';
	$data['contents']['contents'][$j]['body']['contents'][$n]['flex'] = 0;
	$data['contents']['contents'][$j]['body']['contents'][$n]['contents'][0]['type'] = 'text';
	$data['contents']['contents'][$j]['body']['contents'][$n]['contents'][0]['text'] = 'รวม'; //prod_name
	$data['contents']['contents'][$j]['body']['contents'][$n]['contents'][0]['margin'] = 'lg';
	$data['contents']['contents'][$j]['body']['contents'][$n]['contents'][0]['weight'] = 'bold';
	$data['contents']['contents'][$j]['body']['contents'][$n]['contents'][1]['type'] = 'text';
	$data['contents']['contents'][$j]['body']['contents'][$n]['contents'][1]['text'] = $order_price[$j].' บาท'; //prod_name
	$data['contents']['contents'][$j]['body']['contents'][$n]['contents'][1]['margin'] = 'lg';
	$data['contents']['contents'][$j]['body']['contents'][$n]['contents'][1]['weight'] = 'bold';			
		    
	
	if(sizeof($trackinglist)>0)
		{
		   $tracking = new Trackingmore;
		   $tracking = $tracking->getRealtimeTrackingResults('kerry-logistics',$trackinglist[$j],Array());
		   $trace = $tracking['data']['items'][0]['lastEvent'];
		   file_put_contents("php://stderr", "trace =====> ".json_encode($trace));
		   $data['contents']['contents'][$j]['footer']['type'] = 'box';
		   $data['contents']['contents'][$j]['footer']['layout'] = 'vertical';    
		   $data['contents']['contents'][$j]['footer']['contents'][0]['type'] = 'text';
		   $data['contents']['contents'][$j]['footer']['contents'][0]['text'] = 'สถานะพัสดุปัจจุบัน'."\n".$trace;
		   $data['contents']['contents'][$j]['footer']['contents'][0]['wrap'] = true;
		   $data['contents']['contents'][$j]['footer']['contents'][0]['color'] = '#0C10E1';		
   
		} 
	if($check=='1')
		{
		   $data['contents']['contents'][$j]['footer']['type'] = 'box';
		   $data['contents']['contents'][$j]['footer']['layout'] = 'horizontal';    
		   $data['contents']['contents'][$j]['footer']['contents'][0]['type'] = 'button';
		   $data['contents']['contents'][$j]['footer']['contents'][0]['action']['type'] = 'uri'; 
		   $data['contents']['contents'][$j]['footer']['contents'][0]['action']['label'] = 'อัพโหลดสลิป';
	   	   $data['contents']['contents'][$j]['footer']['contents'][0]['action']['uri']= "https://standardautocar.herokuapp.com/upload_slip.php?id='$order_id[$j]'";	
		}
	if($loop=='2')
		{
		   $data['contents']['contents'][$j]['footer']['type'] = 'box';
		   $data['contents']['contents'][$j]['footer']['layout'] = 'horizontal';    
		   $data['contents']['contents'][$j]['footer']['contents'][0]['type'] = 'text';
		   $data['contents']['contents'][$j]['footer']['contents'][0]['text'] = 'วันที่สั่งซื้อ: '.$datelist[$j]; 
		   $data['contents']['contents'][$j]['footer']['contents'][0]['color'] = '#0C10E1'; 	
		}
	
	}
	if($cartp[0] == '')
	{
		return ['type'=>'text','text' => 'ยังไม่มีใบออเดอร์ในขั้นตอนนี้'];
	} 
	else {	return $data; } 
}
function flex_order($db,$order_id,$cartp_id)
{
	
	$cartp_array = pg_query($db,"SELECT * FROM cart_product WHERE cartp_id = '$cartp_id'");
	$skuid_array = array();
	$i = 0;
	while($data = pg_fetch_row($cartp_array))
	{
		$skuid_array[$i] = $data;
		$i++;
	}
	$size = sizeof($skuid_array);
	$pdid_array = array();
	$sku_qty = array();
	
	for($r=0; $r<$size ;$r++)
	{
		$skuid = $skuid_array[$r][2];
		$pdid_array[$r] = pg_fetch_row(pg_query($db,"SELECT prod_id FROM stock WHERE sku_id = '$skuid'"))[0];
		$sku_qty[$r] = [$skuid_array[$r][2],$skuid_array[$r][3]];
	}
	
	
	
	$product = [];
	$totalprice = 0;
	
	for($t=0;$t<$size;$t++)
	{
		$pdid = $pdid_array[$t];
		$qty = $sku_qty[$t][1];
		$pdname = pg_fetch_row(pg_query($db,"SELECT prod_name FROM product WHERE prod_id = '$pdid'"))[0];
		$pdprice = pg_fetch_row(pg_query($db,"SELECT prod_pro_price FROM product WHERE prod_id = '$pdid'"))[0]*$qty;
		$product[$t] = [$pdid,$pdname,$pdprice];
		$totalprice += $pdprice;
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
	$data['contents']['header']['contents'][0]['weight'] = 'bold';
	$data['contents']['header']['contents'][0]['align'] = 'center';
	$data['contents']['body']['type'] = 'box';
	$data['contents']['body']['layout'] = 'vertical';
	
	
	for($a=0;$a<$size;$a++)
	{
		$data['contents']['body']['contents'][$a]['type'] = 'box';
		$data['contents']['body']['contents'][$a]['layout'] = 'baseline';
		$data['contents']['body']['contents'][$a]['contents'][0]['type'] = 'text';
		$data['contents']['body']['contents'][$a]['contents'][0]['text'] = $sku_qty[$a][0];//.' '.$sku_color[$a]; //sku id
		$data['contents']['body']['contents'][$a]['contents'][0]['margin'] = 'sm';
		$data['contents']['body']['contents'][$a]['contents'][1]['type'] = 'text';
		$data['contents']['body']['contents'][$a]['contents'][1]['text'] = $sku_qty[$a][1].' ชิ้น'; // qty ordered
		$data['contents']['body']['contents'][$a]['contents'][1]['margin'] = 'lg';
		$data['contents']['body']['contents'][$a]['contents'][2]['type'] = 'text';
		$data['contents']['body']['contents'][$a]['contents'][2]['text'] = $product[$a][2].' บาท'; //total price
		$data['contents']['body']['contents'][$a]['contents'][2]['align'] = 'end';
	}
	
	$data['contents']['body']['contents'][$size]['type'] = 'box';
	$data['contents']['body']['contents'][$size]['layout'] = 'baseline';
	$data['contents']['body']['contents'][$size]['contents'][0]['type'] = 'text';
	$data['contents']['body']['contents'][$size]['contents'][0]['text'] = 'ยอดชำระสุทธิ'; //prod_name
	$data['contents']['body']['contents'][$size]['contents'][0]['margin'] = 'sm';
	$data['contents']['body']['contents'][$size]['contents'][0]['weight'] = 'regular';
	$data['contents']['body']['contents'][$size]['contents'][1]['type'] = 'text';
	$data['contents']['body']['contents'][$size]['contents'][1]['text'] = $totalprice.' บาท'; //prod_name
	$data['contents']['body']['contents'][$size]['contents'][1]['margin'] = 'sm';
	$data['contents']['body']['contents'][$size]['contents'][1]['weight'] = 'bold';
	
	
	return $data;
	
}
    
    
    
    
function add_to_order($db,$cus_id,$cart_avail)
{
	
	$order_id = substr(uniqid(),0,6);
	$query = pg_query($db,"SELECT order_id FROM orderlist");
	$dup = array();
	$q=0;
	$j=0;
	while($q < pg_num_rows($query))
	{
		$dup[$q] = pg_fetch_row($query)[0];
		$q++;
	}
	while($j <= $q)
	{ 
		if($order_id == $dup[$j] ) 
		{
			$order_id = substr(uniqid(),0,6);
			$j=0;
		}
		$j++;
	}
	
	$skuids = pg_query($db,"SELECT sku_id FROM cart_product WHERE cartp_id = '$cart_avail'");
	$total_price = 0;
	$i = 0;
	while($skuid = pg_fetch_row($skuids)[0])
	{
		$qtt = pg_fetch_row(pg_query($db,"SELECT cart_prod_qtt FROM cart_product WHERE sku_id='$skuid' AND cartp_id = '$cart_avail'"))[0];
		$prod_id = pg_fetch_row(pg_query($db,"SELECT prod_id FROM stock WHERE sku_id='$skuid'"))[0];
		$prod_price = pg_fetch_row(pg_query($db,"SELECT prod_pro_price FROM product WHERE prod_id='$prod_id'"))[0];
		$price = $prod_price*$qtt;
		$total_price += $price; 
		$i++;
	}
	date_default_timezone_set("Asia/Bangkok");
	$time = date("H:i:s");
	$date = date("Y-m-d");
	pg_query($db,"INSERT INTO orderlist (order_id,cartp_id,total_price,order_date,order_time,order_status) VALUES ('$order_id','$cart_avail','$total_price','$date','$time','waiting for payment')");
	pg_query($db,"UPDATE createcart SET cart_used = '1' WHERE cartp_id = '$cart_avail'");
	pg_query($db,"INSERT INTO createcart (cus_id,cart_used) VALUES ('$cus_id','0')");
	return $order_id;
	
}
function carousel_show_favorite($db,$cus_id)
  {
    $check = pg_query($db,"SELECT * FROM favorite WHERE favorite.cus_id = '$cus_id' LIMIT 10");	
    $i = 0;
    $prod_array = array();
    $fav = array();
    while ($list = pg_fetch_row($check))
    {
	    $prod = pg_fetch_row(pg_query($db,"SELECT * FROM product WHERE prod_id = '$list[2]'")); 
	    $prod_array[$i] = $prod; 
	    $fav[$i] = $list[0];
	    $i++;
	    file_put_contents("php://stderr", "fav id =====> ".json_encode($list[0], JSON_UNESCAPED_UNICODE)); 
    }
    
    
    $datas = [];
    $datas['type'] = 'flex';
    $datas['altText'] = 'Flex Message';
    $datas['contents']['type'] = 'carousel';
    
	
    for ($j=0; $j< pg_num_rows($check); $j++)
     {
	$datas['contents']['contents'][$j]['type'] = 'bubble';
    	$datas['contents']['contents'][$j]['direction'] = 'ltr';
	$datas['contents']['contents'][$j]['header']['type'] = 'box';
	$datas['contents']['contents'][$j]['header']['layout'] = 'vertical';
	$datas['contents']['contents'][$j]['header']['contents'][0]['type'] = 'image';
	$datas['contents']['contents'][$j]['header']['contents'][0]['url'] = $prod_array[$j][2];
	$datas['contents']['contents'][$j]['header']['contents'][0]['size'] = 'full';
	$datas['contents']['contents'][$j]['header']['contents'][0]['aspectRatio'] = '1.51:1';
	$datas['contents']['contents'][$j]['header']['contents'][0]['aspectMode'] = 'fit';             
	$datas['contents']['contents'][$j]['header']['contents'][1]['type'] = 'text';      
	$datas['contents']['contents'][$j]['header']['contents'][1]['text'] = $prod_array[$j][1];      
	$datas['contents']['contents'][$j]['header']['contents'][1]['size'] = 'xl';
	$datas['contents']['contents'][$j]['header']['contents'][1]['weight'] = 'bold';
	$datas['contents']['contents'][$j]['header']['contents'][1]['wrap'] = true;
	$datas['contents']['contents'][$j]['header']['contents'][2]['type'] = 'box';
	$datas['contents']['contents'][$j]['header']['contents'][2]['layout'] = 'baseline';     
	$datas['contents']['contents'][$j]['header']['contents'][2]['contents'][0]['type'] = 'text';       
	$datas['contents']['contents'][$j]['header']['contents'][2]['contents'][0]['text'] = '฿ '.$prod_array[$j][5];
	$datas['contents']['contents'][$j]['header']['contents'][2]['contents'][0]['margin'] = 'none';  
	    if($prod_array[$j][6] < $prod_array[$j][5]) {
	$datas['contents']['contents'][$j]['header']['contents'][2]['contents'][1]['type'] = 'text';       
	$datas['contents']['contents'][$j]['header']['contents'][2]['contents'][1]['text'] = 'Now ฿'.$prod_array[$j][6].' !!!';            
	$datas['contents']['contents'][$j]['header']['contents'][2]['contents'][1]['size'] = 'lg';
	$datas['contents']['contents'][$j]['header']['contents'][2]['contents'][1]['weight'] = 'bold';
	$datas['contents']['contents'][$j]['header']['contents'][2]['contents'][1]['color'] = '#FF0000';     }
	$datas['contents']['contents'][$j]['header']['contents'][3]['type'] = 'text';
	$datas['contents']['contents'][$j]['header']['contents'][3]['text'] = $prod_array[$j][4];     
	$datas['contents']['contents'][$j]['header']['contents'][3]['size'] = 'sm';            
	$datas['contents']['contents'][$j]['header']['contents'][3]['wrap'] = true;      
	$datas['contents']['contents'][$j]['footer']['type'] = 'box';
	$datas['contents']['contents'][$j]['footer']['layout'] = 'vertical';
	$datas['contents']['contents'][$j]['footer']['contents'][0]['type'] = 'button';
	$datas['contents']['contents'][$j]['footer']['contents'][0]['action']['type'] = 'postback';      
	$datas['contents']['contents'][$j]['footer']['contents'][0]['action']['label'] = 'เลือกสีและไซส์';
	$datas['contents']['contents'][$j]['footer']['contents'][0]['action']['text'] = 'view more';      
	$datas['contents']['contents'][$j]['footer']['contents'][0]['action']['data'] = 'View '.$prod_array[$j][0];
	$datas['contents']['contents'][$j]['footer']['contents'][0]['color'] = '#E5352E';      
	$datas['contents']['contents'][$j]['footer']['contents'][0]['style'] = 'primary';
	$datas['contents']['contents'][$j]['footer']['contents'][1]['type'] = 'button';    
	$datas['contents']['contents'][$j]['footer']['contents'][1]['action']['type'] = 'postback';      
	$datas['contents']['contents'][$j]['footer']['contents'][1]['action']['label'] = 'ลบออกจาก Favorite';
	$datas['contents']['contents'][$j]['footer']['contents'][1]['action']['text'] =  'Delete '.$fav[$j].'ออกจาก Favorite เรียบร้อย';  
	$datas['contents']['contents'][$j]['footer']['contents'][1]['action']['data'] = 'Delete_fav '.$fav[$j];
	$datas['contents']['contents'][$j]['footer']['contents'][1]['color'] = '#D1D1D1';      
	$datas['contents']['contents'][$j]['footer']['contents'][1]['style'] = 'primary';	          
	    
	file_put_contents("php://stderr", "json =====> ".json_encode($datas[$i], JSON_UNESCAPED_UNICODE));     

     }
	
    if($i == 0) { return ['type'=>'text','text' => 'ยังไม่มีรายการที่บันทึกไว้'];   }
    else    { return $datas; }
  }
  
function out_of_time($db)
  {
     date_default_timezone_set("Asia/Bangkok");
     $time = date("H:i:s");
     $date = date("Y-m-d");
     $order_list = pg_query($db,"SELECT * FROM orderlist"); 
     while($order=pg_fetch_row($order_list))
     {
	     $exp_date = date("Y-m-d", strtotime("+2 days", strtotime("$order[3]")));
	     if($date >= $exp_date )
	    {
		if($time > $order[4] AND $order[5] == 'waiting for payment') {
		pg_query($db,"DELETE FROM orderlist WHERE order_id = '$order[0]' ");
		file_put_contents("php://stderr", "delete success ");
		}
	     }
	     
     }
 
    	  
  }
	  




function move_to_history($db)
  {
     date_default_timezone_set("Asia/Bangkok");
     $time = date("H:i:s");
     $date = date("Y-m-d");
 /*
     $ordertrack_query = pg_query($db,"SELECT * FROM orderlist WHERE order_status = 'shipping' ");
     $ordertracklist = Array();
     $i=0;
     while($list = pg_fetch_row($tracking_query))
     {
	     $ordertracklist[$i] = $list;
	     $i++;
     }
     for($t=0;$t<=$i;$t++)
     {
     $tracking = new Trackingmore;
     $tracking = $tracking->getRealtimeTrackingResults('kerry-logistics','SHP4003994671',Array()); 
     $trace = $tracking['data']['items'][0]['lastEvent'];	
     if(strtoupper(explode(' ',$trace)[1])== 'SUCCESSFUL')
     {
	     
	     pg_query($db,"INSERT INTO historyorder (order_id,cartp_id,total_price,order_date,order_time,tracking_number) 
	       VALUES ('$ordertracklist[$t][0]','$ordertracklist[$t][1]','$ordertracklist[$t][2]','$ordertracklist[$t][3]','$ordertracklist[$t][4]','$ordertracklist[$t][5]')");
	     pg_query($db, "DELETE FROM orderlist WHERE order_id = '$ordertracklist[$t][0]'");
     }
     }
*/
	
     $tracking = new Trackingmore;
     $tracking = $tracking->getRealtimeTrackingResults('kerry-logistics','SHP4003994671',Array()); 
     $trace = $tracking['data']['items'][0]['lastEvent'];	
     if(strtoupper(explode(' ',$trace)[1])== 'SUCCESSFUL')
     {
	     
	     pg_query($db,"INSERT INTO historyorder (order_id,cartp_id,total_price,order_date,order_time,tracking_number) 
	       VALUES ('5c67a4','98','30','2019-02-16','12:48:48','SHP4007911074')");
	     pg_query($db, "DELETE FROM orderlist WHERE order_id = '5c67a4' ");
	     file_put_contents("php://stderr", "delete success ");
     }
	
	
	
	
	
  }
	

function timepost()
{
     date_default_timezone_set("Asia/Bangkok");
     $timee = date("H:i:s");
     $datee = date("Y-m-d");
	return ['type'=>'text','text' => [$datee,$timee] ];
}

   
?>
