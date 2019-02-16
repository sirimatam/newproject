<?php



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
	// check can be 1,2,3
	
	$cartp_id_array = pg_query($db,"SELECT cartp_id FROM createcart WHERE createcart.cus_id = '$userid' AND createcart.cart_used = '1'");
	$run2 = 0;
	$run1 = 0;
	$order = array();
	$order_id = array();
	$order_price = array();
	$sku_color = array();
	$pd = array();
	$trackinglist = array();

	while($cartp_id = pg_fetch_row($cartp_id_array)[0])
	{
		if($check=='1') // ที่รอชำระเงิน
		{
			$a = pg_query($db,"SELECT cartp_id FROM orderlist WHERE cartp_id = '$cartp_id' AND order_status = 'waiting for payment'");
			$b = pg_query($db,"SELECT total_price FROM orderlist WHERE cartp_id = '$cartp_id' AND order_status = 'waiting for payment'");
			$c = pg_query($db,"SELECT order_id FROM orderlist WHERE cartp_id = '$cartp_id' AND order_status = 'waiting for payment'");
		}
		elseif($check=='2')//ที่ต้องจัดส่ง
		{
			$a = pg_query($db,"SELECT cartp_id FROM orderlist WHERE cartp_id = '$cartp_id' AND  order_status = 'waiting for packing'");
			$b = pg_query($db,"SELECT total_price FROM orderlist WHERE cartp_id = '$cartp_id' AND  order_status = 'waiting for packing'");
			$c = pg_query($db,"SELECT order_id FROM orderlist WHERE cartp_id = '$cartp_id' AND order_status = 'waiting for packing'");
			
		}
		elseif($check == '3')//ที่ต้องได้รับ
		{
			$a = pg_query($db,"SELECT cartp_id FROM orderlist WHERE cartp_id = '$cartp_id' AND order_status != 'waiting for payment' AND order_status != 'waiting for packing' ");
			$b = pg_query($db,"SELECT total_price FROM orderlist WHERE cartp_id = '$cartp_id' AND order_status != 'waiting for payment' AND order_status != 'waiting for packing' ");
			$c = pg_query($db,"SELECT order_id FROM orderlist WHERE cartp_id = '$cartp_id' AND order_status != 'waiting for payment' AND order_status != 'waiting for packing' ");
			$tracking = pg_query($db,"SELECT order_status FROM orderlist WHERE order_id = '$c' ");
			
		}
				
		if(pg_num_rows($a)>0)
			{
				$order[$run1] = pg_fetch_row($a)[0];
				$order_price[$run1] = pg_fetch_row($b)[0];
				$order_id[$run1] = pg_fetch_row($c)[0];
				$trackinglist[$run1] = pg_fetch_row($tracking)[0];
				$run1++;
			}
	}
	for($k=0;$k<sizeof($order);$k++)
	{

		$cartp_array = pg_query($db,"SELECT sku_id FROM cart_product WHERE cartp_id = '$order[$k]'");
		$skuid_array = array();
		$i = 0;
		
		
		while($cartp = pg_fetch_row($cartp_array)[0])
		{
			$skuid_array[$i] = $cartp;
			$cartp_qtt[$i] = pg_fetch_row(pg_query($db,"SELECT cart_prod_qtt FROM cart_product WHERE cartp_id = '$order[$k]' AND sku_id = '$cartp'"))[0];

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
	/*	
	$data = [];
	$data['type'] = 'flex';
	$data['altText'] = 'Flex Message';
	$data['contents']['type'] = 'carousel';
	
	for($j=0;$j<sizeof($order);$j++) // j = วนใบสั่งซื้อ
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
	$data['contents']['contents'][$j]['body']['contents'][$n]['contents'][0]['margin'] = 'sm';
	$data['contents']['contents'][$j]['body']['contents'][$n]['contents'][0]['weight'] = 'regular';
	$data['contents']['contents'][$j]['body']['contents'][$n]['contents'][1]['type'] = 'text';
	$data['contents']['contents'][$j]['body']['contents'][$n]['contents'][1]['text'] = $order_price[$j].' บาท'; //prod_name
	$data['contents']['contents'][$j]['body']['contents'][$n]['contents'][1]['margin'] = 'sm';
	$data['contents']['contents'][$j]['body']['contents'][$n]['contents'][1]['weight'] = 'regular';
	$data['contents']['contents'][$j]['body']['contents'][$n]['contents'][1]['align'] = 'end';			
		    
	
	if(sizeof($trackinglist)>0)
		{
		   $track = new Trackingmore;
		   $track = $track->getRealtimeTrackingResults('kerry-logistics',$trackinglist[$j],Array());
		   $trace = $track['data']['items'][0]['lastEvent'];

		   $data['contents']['contents'][$j]['footer']['type'] = 'box';
		   $data['contents']['contents'][$j]['footer']['layout'] = 'vertical';    
		   $data['contents']['contents'][$j]['footer']['contents'][0]['type'] = 'text';
		   $data['contents']['contents'][$j]['footer']['contents'][0]['text'] = $trace; //prod_name
		   $data['contents']['contents'][$j]['footer']['contents'][0]['color'] = '#FF0000';		
   
		} 
	}
	
	
	if(sizeof($order) == 0)
	{
		return ['type'=>'text','text' => 'ยังไม่มีใบออเดอร์ในขั้นตอนนี้'];
	} 
	else {	return $data; } 
	*/
	
	if(sizeof($trackinglist)>0)
		{
		   $track = new Trackingmore;
		   $track = $track->getRealtimeTrackingResults('kerry-logistics',$trackinglist[0],Array());
		   $trace = $track['data']['items'][0]['lastEvent'];
		  return ['type'=>'text','text' => $trace ];
	}
	
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
		//$pdid = pg_fetch_row(pg_query($db,"SELECT prod_id FROM product WHERE prod_id = '$id'"))[0];
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
	$dup = pg_fetch_all($query);
	$q=0;
	while($q < pg_num_rows($query))
	{
		if($order_id == $dup[$q]) 
		{
			$order_id = substr(uniqid(),0,6);
			$q= -1;
		}
		
		$q++;
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
	$date = date("Y-m-d") ;
	pg_query($db,"INSERT INTO orderlist (order_id,cartp_id,total_price,order_date,order_time,order_status) VALUES ('$order_id','$cart_avail','$total_price','$date','$time','waiting for payment')");
	pg_query($db,"UPDATE createcart SET cart_used = '1' WHERE cartp_id = '$cart_avail'");
	pg_query($db,"INSERT INTO createcart (cus_id,cart_used) VALUES ('$cus_id','0')");
	return $order_id;
	
}




  
  
  
  
  
?>
