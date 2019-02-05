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
	curl_setopt($ch, CURLOPT_TIMEOUT, 30); 
	curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, "");
	$response = curl_exec($ch);
	$err = curl_error($ch);
	curl_close($ch);
	if ($err) {
  	 return $msg = "cURL Error #:" . $err;
	} 
	else {
		define('UPLOAD_DIR', 'image/');
		$img=base64_encode($response);
		$data = base64_decode($img);
		$file = UPLOAD_DIR . $msgid . '.png';
		$success = file_put_contents($file, $data);
		return $success;
	     }

}
	   
function show_promotion_product($db) 
{ 
   $promo = pg_query($db,"SELECT * FROM product WHERE prod_price>prod_pro_price"); 
   $num = pg_num_rows($promo);
   
   if($num>10)
   {
	$promo_top = pg_query($db,"SELECT TOP 10 * FROM product ORDER BY (prod_price-prod_pro_price)/prod_price DESC WHERE prod_price>prod_pro_price");  
   	$promo_num = pg_num_rows($promo_top);
   }
   else
   {
   	$promo_top = pg_query($db,"SELECT * FROM product ORDER BY (prod_price-prod_pro_price)/prod_price DESC WHERE prod_price>prod_pro_price");  
   	$promo_num = pg_num_rows($promo_top);
   }
	
   $promo_list = array();	
   $run = 0;
   while($promo_list_single = pg_fetch_row($promo_top))
   {
	$promo_list[$run] = $promo_list_single;
	$run++;
   }
   $running = 0;
   $carousel = array();
   
      for ($i=0; $i<=$promo_num;$i++)
     {
        $datas = [];
	$datas['type'] = 'template';
        $datas['altText'] = 'this is a carousel template';
        $datas['template']['type'] = 'carousel';
        $datas['template']['columns'][$i]['thumbnailImageUrl'] = $promo_list[$i][$prod_img]; 
        $datas['template']['columns'][$i]['title'] = $promo_list[$i][$prod_name];
        $datas['template']['columns'][$i]['text'] = $promo_list[$i][$prod_description];
	$datas['template']['columns'][$i]['actions'][0]['type'] = 'postback';
        $datas['template']['columns'][$i]['actions'][0]['label'] = 'รายละเอียดเพิ่มเติม';
        $datas['template']['columns'][$i]['actions'][0]['text'] = 'view more';
        $datas['template']['columns'][$i]['actions'][0]['data'] =  'View '.$list[$i][0];
        $datas['template']['columns'][$i]['actions'][1]['type'] = 'postback';
        $datas['template']['columns'][$i]['actions'][1]['label'] = 'บันทึกเป็น Favorite';
        $datas['template']['columns'][$i]['actions'][1]['text'] = 'บันทึกเป็น Favorite';   
        $datas['template']['columns'][$i]['actions'][1]['data'] = 'Favorite '.$promo_list[$i][0];
     }
     $carousel[$i] = $datas;
     return $carousel;
   
}   
	   
function customer_address($db,$cusid)
{
	pg_query($db,"UPDATE customer SET cus_name = 'C001', cus_address = '', cus_tel = '' WHERE cus_id = $cusid ");
}
	   

  
  
  
function button_all_type()
  {
    $data = [
  "type" => "flex",
  "altText" => "Flex Message",
  "contents" => [
    "type" => "bubble",
    "direction" => "ltr",
    "header" => [
      "type" => "box",
      "layout" => "vertical",
      "contents" => [
        [
          "type" => "text",
          "text" => "เลือกประเภทสินค้า",
          "align" => "center",
          "weight" => "bold"
        ]
      ]
    ],
    "body" => [
      "type" => "box",
      "layout" => "vertical",
      "contents" => [
        [
          "type" => "button",
          "action" => [
            "type" => "message",
            "label" => "สายเดี่ยว/แขนกุด",
            "text" => "เสื้อสายเดี่ยว/แขนกุด"
          ]
        ],
        [
          "type" => "button",
          "action" => [
            "type" => "message",
            "label" => "เสื้อมีแขน",
            "text" => "เสื้อมีแขน"
          ]
        ],
        [
          "type" => "button",
          "action" => [
            "type" => "message",
            "label" => "เดรส",
            "text" => "เดรส"
          ]
        ],
        [
          "type" => "button",
          "action" => [
            "type" => "message",
            "label" => "กางเกงขาสั้น",
            "text" => "กางเกงขาสั้น"
          ]
        ],
        [
          "type" => "button",
          "action" => [
            "type" => "message",
            "label" => "กางเกงขายาว",
            "text" => "กางเกงขายาว"
          ]
        ]
      ]
    ]
  ]
];
   return $data;
  }  
function show_address($db,$cusid)
{
	$query = pg_query($db,"SELECT cus_description FROM customer WHERE cus_id = '$cusid' AND cus_default = '1'");
	$address = pg_fetch_row($query)[0];
	
	if ($address == '')
	{ $address = 'กรุณาเพิ่ม ชื่อ นามสกุล และที่อยู่จัดส่ง';}
	
	$data = [];
	$data['type'] = 'template';
	$data['altText'] = 'this is a buttons template';
	$data['template']['type'] = 'buttons';
	$data['template']['actions'][0]['type'] = 'message';
	$data['template']['actions'][0]['label'] = 'แก้ไขชื่อและที่อยู่จัดส่ง';
	$data['template']['actions'][0]['text'] = 'แก้ไขชื่อและที่อยู่';
	$data['template']['title'] = 'ชื่อและที่อยู่จัดส่งปัจจุบัน';
	$data['template']['text'] = $address;
	
	return $data;
}
/* ข้อ 2 */

function carousel_product_type($db,$type) // $type = Prod_type FROM Product
{ 
  // how to check whether prod_qtt > 0
   $pd_type = pg_query($db,"SELECT * FROM product WHERE prod_type = $type");  
   $num_carousel = pg_num_rows($pd_type);
   $list = pg_fetch_row($pd_type);
   //$times = $num_carousel/10;
   $running = 0;
   $carousel = array();
   if($num_carousel <=10)
   {
      for ($i=0; $i<10;$i++)
     {
        $datas = [];
        $datas['type'] = 'template';
        $datas['altText'] = 'this is a carousel template';
        $datas['template']['type'] = 'carousel';
        $datas['template']['columns'][$i]['thumbnailImageUrl'] = $list[$i][$prod_img]; 
        $datas['template']['columns'][$i]['title'] = $list[$i][$prod_name];
        $datas['template']['columns'][$i]['text'] = $list[$i][$prod_description];
        $datas['template']['columns'][$i]['actions'][0]['type'] = 'postback';
        $datas['template']['columns'][$i]['actions'][0]['label'] = 'รายละเอียดเพิ่มเติม';
        $datas['template']['columns'][$i]['actions'][0]['text'] = 'view more';
        $datas['template']['columns'][$i]['actions'][0]['data'] =  'View '.$list[$i][$prod_id];
        $datas['template']['columns'][$i]['actions'][1]['type'] = 'postback';
        $datas['template']['columns'][$i]['actions'][1]['label'] = 'บันทึกเป็น Favorite';
        $datas['template']['columns'][$i]['actions'][1]['text'] = 'บันทึกเป็น Favorite';   
        $datas['template']['columns'][$i]['actions'][1]['data'] = 'Favorite '.$list[$i][$prod_id];
     }
     $carousel[0] = $datas;
     return $carousel;
   }
   else
   {
   while( $running < $num_carousel)  
   {
     for ($i=0;$i<10;$i++)
     {
        $datas = [];
        $datas['type'] = 'template';
        $datas['altText'] = 'this is a carousel template';
        $datas['template']['type'] = 'carousel';
        $datas['template']['columns'][$i]['thumbnailImageUrl'] = $list[$i][$prod_img]; 
        $datas['template']['columns'][$i]['title'] = $list[$i][$prod_name];
        $datas['template']['columns'][$i]['text'] = $list[$i][$prod_description];
        $datas['template']['columns'][$i]['actions'][0]['type'] = 'postback';
        $datas['template']['columns'][$i]['actions'][0]['label'] = 'รายละเอียดเพิ่มเติม';
        $datas['template']['columns'][$i]['actions'][0]['text'] = 'view more';
        $datas['template']['columns'][$i]['actions'][0]['data'] =  'View '.$list[$i][$prod_id];
        $datas['template']['columns'][$i]['actions'][1]['type'] = 'postback';
        $datas['template']['columns'][$i]['actions'][1]['label'] = 'บันทึกเป็น Favorite';
        $datas['template']['columns'][$i]['actions'][1]['text'] = 'บันทึกเป็น Favorite';   
        $datas['template']['columns'][$i]['actions'][1]['data'] = 'Favorite '.$list[$i][$prod_id];
        $running++;
     }
     $carousel[ceil($running-10)/10] = $datas;
   }
    return $carousel;

   }


}
  
  
  
function carousel_view_more($db,$prod_id) 
{
  $pd_name = pg_fetch_row(pg_query($db,'SELECT prod_name FROM product WHERE prod_id = $prod_id'))[0];
  $pd_des = pg_fetch_row(pg_query($db,'SELECT prod_description FROM product WHERE prod_id = $prod_id'))[0];
  $pd_sku = pg_query($db,'SELECT sku_id FROM stock WHERE stock.prod_id = $prod_id');
  $list = pg_fetch_row($pd_sku);
  $num_carousel = pg_num_rows($pd_sku);
  //$times = $num_carousel/10;
   $running = 0;
   $carousel = array();
  if($num_carousel <=10)
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
        $datas['template']['columns'][$i]['actions'][0]['label'] = 'สั่งลงตะกร้า';
        $datas['template']['columns'][$i]['actions'][0]['text'] = 'บันทึก'.$pd_name.' '.$list[$i][$sku_color].' ลงตะกร้าเรียบร้อยแล้ว';
        $datas['template']['columns'][$i]['actions'][0]['data'] = 'Cart '.$list[$i][$sku_id];
        $datas['template']['columns'][$i]['actions'][1]['type'] = 'message';
        $datas['template']['columns'][$i]['actions'][1]['label'] = 'ดูสินค้าอื่น';
        $datas['template']['columns'][$i]['actions'][1]['text'] = 'ดูและสั่งซื้อสินค้า';  
     }
     $carousel[0] = $datas;
     return $carousel;
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


  
//if message['text'] == 'Favorite'.$prod_id
  
function add_favorite($db,$prod_id,$cus_id)
  {
    /* check fav cannot more than 10 */
    $check = pg_query($db,'SELECT * FROM favorite WHERE favorite.cus_id = $cus_id');
    $count = pg_num_rows($check);
    if($count>=10){ return $reply_msg = 'คุณสามารถ Favorite ได้ 10 รายการเท่านั้น';}  
    //end of function
    else{
    $fave_id++;
    pg_query($db,'INSERT INTO favorite VALUES ($fave_id,$prod_id,$cus_id)');
    }
  }  


  
  function carousel_show_favorite($db,$cus_id)
  {
    $check = pg_query($db,"SELECT * FROM favorite WHERE favorite.cus_id = '$cus_id'"); 
    $list = pg_fetch_row($check);
    for ($i=0; $i<10;$i++)
     {
        $datas = [];
        $datas['type'] = 'template';
        $datas['altText'] = 'this is a carousel template';
        $datas['template']['type'] = 'carousel';    
        $datas['template']['columns'][$i]['thumbnailImageUrl'] = $list[$i][$prod_img]; 
        $datas['template']['columns'][$i]['title'] = $list[$prod_name]; //check prod_name ว่าต้องมี [$i] มั้ย
        $datas['template']['columns'][$i]['text'] = $list[$i][$prod_description];
        $datas['template']['columns'][$i]['actions'][0]['type'] = 'postback';
        $datas['template']['columns'][$i]['actions'][0]['label'] = 'รายละเอียดเพิ่มเติม';
        $datas['template']['columns'][$i]['actions'][0]['text'] = 'view more';
        $datas['template']['columns'][$i]['actions'][0]['data'] =  'View '.$list[$i][$prod_id];
        $datas['template']['columns'][$i]['actions'][1]['type'] = 'postback';
        $datas['template']['columns'][$i]['actions'][1]['label'] = 'ลบออกจาก Favorite';
        $datas['template']['columns'][$i]['actions'][1]['text'] = 'Delete '.$list[$i][$fav_id].'ออกจาก Favorite เรียบร้อย';  
        $datas['template']['columns'][$i]['actions'][1]['data'] =  'Delete '.$list[$i][$fav_id];
     }
    return $datas;
  }
  
  /* if message['text'] == delete.$fav_id' */
  function delete_favorite($db,$fav_id)
  {
    pg_query('DELETE FROM favorite WHERE fav_id = $fav_id');
  }

  function delete_from_cart($db,$sku_id,$cus_id)
  {
    $cart_avail = pg_fetch_row(pg_query($db,"SELECT cartp_id FROM createcart WHERE cus_id = $cus_id AND cart_used = '0'"))[0];
    pg_query('DELETE FROM cart_product WHERE sku_id = $sku_id AND cartp_id = $cart_avail');
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

  

  
  
 function create_cart($cus_id)
  {
    //check จ่ายตังก่อน ++ ยังแก้ไม่เสด
    pg_query($db,'INSERT INTO Favorite VALUES ($fave_id,$prod_id,$cus_id)');
  }
  
  
 
  
  
  
  
  
  
  
  
  
  
//if message['text'] == 'Cart'.$sku_id

function add_to_cart($sku_id,$cus_id,$cart_qtt)

  {
    /* check cart cannot more than 10 */
    $cartp_id = pg_fetch_row(pg_query($db,"SELECT cartp_id FROM createcart WHERE cart_used = '0' AND cus_id = '$cus_id' "));
    $check = pg_query($db,"SELECT * FROM cart_product WHERE cartp_id = '$cartp_id' ");
    $count = pg_num_rows($check);
    if($count>=10){ return $reply_msg = 'คุณสามารถเพิ่มสินค้าลงตะกร้า ได้ 10 รายการเท่านั้น';}  
    //end of function
    else{

  
    pg_query($db,"INSERT INTO Cart_product (cartp_id,sku_id,cart_prod_qtt) VALUES ('$cartp_id','$sku_id','$cart_qtt')"); //ยังไม่ได้ใส่กรณีซื้อSKUเดียวกันสองตัว

    }
  }    
  
 
function carousel_cart($db,$cus_id)
{
    $cartid = pg_fetch_row(pg_query($db,"SELECT cartp_id FROM createcart WHERE createcart.cus_id = '$cus_id' AND createcart.cart_used = '0'"))[0];
    $skuid = pg_query($db,"SELECT sku_id FROM cart_product WHERE cart_product.cartp_id = '$cartid'");
    $skuarray = array();
    $run1 = 0;
    $skurow = pg_fetch_row($skuid);
    while($aaa = $skurow)
    {
	    $skuarray[$run1] = $aaa;
	    $run1++;
    }
	  //$pdid = pg_fetch_row(pg_query($db,"SELECT (prod_id,prod_name,prod_description) FROM Product WHERE Stock"));
    $namearray = array(); 
    $run2 = 0;
    for($i=0; $i<=pg_num_rows($skuid);$i++)
    {
	 $x = pg_fetch_row(pg_query($db,"SELECT (prod_id,prod_name,prod_description) FROM product WHERE stock.sku_id = '$skuarray[$i]' AND Stock.prod_id = Product.prod_id"));
	 $namearray[$run2] = array($x[0],$x[1],$x[2]) ;
	 $run2++;
    }
    //$pd = pg_fetch_result(pg_query($db,'SELECT (prod_id,prod_name,prod_description) FROM Product WHERE Stock.prod_id = Product.prod_id AND Cart_product.cartp_id = $cartid AND '));
    
    $cartitems = pg_query($db,"SELECT * FROM cart_product WHERE cart_product.cartp_id = '$cartid'");
    $list = pg_fetch_row($cartitems);
    for ($i=0; $i<pg_num_rows($skuid);$i++)
     {	
        $datas = [];
	$datas['type'] = 'template';
        $datas['altText'] = 'this is a carousel template';
        $datas['template']['type'] = 'carousel';        
        $datas['template']['columns'][$i]['thumbnailImageUrl'] = $list[$i][$sku_id]; 
        $datas['template']['columns'][$i]['title'] = $namearray[$i][1];
        $datas['template']['columns'][$i]['text'] = $namearray[$i][2];
        $datas['template']['columns'][$i]['actions'][0]['type'] = 'postback';
        $datas['template']['columns'][$i]['actions'][0]['label'] = 'ลบออกจาก ตะกร้า';
        $datas['template']['columns'][$i]['actions'][0]['text'] = 'Delete'.$namearray[$i][0].'ออกจาก Favorite เรียบร้อย';  
        $datas['template']['columns'][$i]['actions'][0]['data'] =  'Delete '.$skuarray[$i];
     }
    return $datas;
  }
    
    

function flex_order($db,$order_id)
{
	$data = [];
	$data['type'] = 'flex';
	$data['altText'] = 'Flex Message';
	$data['contents']['type'] = 'bubble';
	$data['contents']['header']['type'] = 'box';
	$data['contents']['header']['layout'] = 'vertical';
	$data['contents']['header']['flex'] = 0;
	$data['contents']['header']['contents']['type'] = 'text';
	$data['contents']['header']['contents']['text'] = 'รหัสใบสั่งซื้อที่ '.$order_id;
	$data['contents']['header']['contents']['size'] = 'xl';
	$data['contents']['header']['contents']['align'] = 'center';
	$data['contents']['header']['contents']['weight'] = 'bold';
	$data['contents']['body']['type'] = 'box';
	$data['contents']['body']['layout'] = 'vertical';
	$data['contents']['body']['spacing'] = 'md';
	
	
	$show = array();
	$order_array = pg_fetch_row($db,"SELECT * From order WHERE order_id = '$order_id'");
	$cartp_id = $order_array[1];
	$total_price = $order_array[2];
	$cartp_array = pg_query($db,"SELECT (sku_id,cart_prod_id,cart_prod_qtt) From cart_product WHERE cart_product.cartp_id = '$cartp_id' ");
	for($i=0;$i<pg_num_rows($cartp_array);$i++)
	{
		$show[$i] = [$cartp_array[$i][0],'prodid','prodname','skucolor','skusize',$cartp_array[$i][2],'prod_pro_price']; 	
	}
	
	for($i=0;$i<pg_num_rows($cartp_array);$i++)
	{
		$skudatas = pg_fetch_row(pg_query($db,"SELECT (prod_id,sku_color,sku_size) FROM stock WHERE stock.sku_id = '$show[$i][0]'"));
		$show[$i] = [$show[$i][0],$skudatas[0],'prodname',$skudatas[1],$skudatas[2],$show[$i][5],'prod_pro_price'];
	}
	for($i=0;$i<pg_num_rows($cartp_array);$i++)
	{
		$pd = pg_fetch_row(pg_query($db,"SELECT (prod_name,prod_pro_price) FROM product WHERE product.prod_id = '$show[$i][1]'"));
		$show[$i] = [$show[$i][0],$show[$i][1],$pd[0],$show[$i][3],$show[$i][4],$show[$i][5],$pd[1]];
	}
	
	
	for($i=0;$i<sizeof($show);$i++)
	{
		$data['contents']['body']['contents'][$i]['type'] = 'box';
		$data['contents']['body']['contents'][$i]['layout'] = 'baseline';
		$data['contents']['body']['contents'][$i]['layout']['contents'][0]['type'] = 'text';
		$data['contents']['body']['contents'][$i]['layout']['contents'][0]['text'] = $show[$i][2]; //prod_name
		$data['contents']['body']['contents'][$i]['layout']['contents'][0]['flex'] = 0;
		$data['contents']['body']['contents'][$i]['layout']['contents'][0]['margin'] = 'sm';
		$data['contents']['body']['contents'][$i]['layout']['contents'][0]['weight'] = 'regular';
		$data['contents']['body']['contents'][$i]['layout']['contents'][1]['type'] = 'text';
		$data['contents']['body']['contents'][$i]['layout']['contents'][1]['text'] = $pd[$i][3]; //sku color
		$data['contents']['body']['contents'][$i]['layout']['contents'][1]['flex'] = 0;
		$data['contents']['body']['contents'][$i]['layout']['contents'][1]['margin'] = 'sm';
		$data['contents']['body']['contents'][$i]['layout']['contents'][1]['weight'] = 'regular';
		$data['contents']['body']['contents'][$i]['layout']['contents'][2]['type'] = 'text';
		$data['contents']['body']['contents'][$i]['layout']['contents'][2]['text'] = $pd[$i][4]; //sku size
		$data['contents']['body']['contents'][$i]['layout']['contents'][2]['flex'] = 0;
		$data['contents']['body']['contents'][$i]['layout']['contents'][2]['margin'] = 'sm';
		$data['contents']['body']['contents'][$i]['layout']['contents'][2]['weight'] = 'regular';
		$data['contents']['body']['contents'][$i]['layout']['contents'][3]['text'] = $pd[$i][5]; // qtt
		$data['contents']['body']['contents'][$i]['layout']['contents'][3]['flex'] = 0;
		$data['contents']['body']['contents'][$i]['layout']['contents'][3]['margin'] = 'sm';
		$data['contents']['body']['contents'][$i]['layout']['contents'][3]['weight'] = 'regular';
		$data['contents']['body']['contents'][$i]['layout']['contents'][4]['text'] = $pd[$i][5]; // price
		$data['contents']['body']['contents'][$i]['layout']['contents'][4]['flex'] = 0;
		$data['contents']['body']['contents'][$i]['layout']['contents'][4]['margin'] = 'sm';
		$data['contents']['body']['contents'][$i]['layout']['contents'][4]['weight'] = 'regular';
		$data['contents']['body']['contents'][$i]['layout']['contents'][4]['align'] = 'end';
		
	}
	
	$data['contents']['footer']['type'] = 'box';
	$data['contents']['footer']['layout'] = 'vertical';
	$data['contents']['footer']['contents'][0]['type'] = 'seperator';
	$data['contents']['footer']['contents'][0]['color'] = '#000000';
	$data['contents']['footer']['contents'][1]['type'] = 'text';
	$data['contents']['footer']['contents'][1]['text'] = 'ราคาทั้งสิ้น '.$total_price.' บาท';
	$data['contents']['footer']['contents'][2]['type'] = 'text';
	$data['contents']['footer']['contents'][2]['text'] = 'กรุณาชำระเงินภายใน 2 วัน';
	$data['contents']['footer']['contents'][2]['align'] = 'center';
	
		
}


    
    
    
    
function add_to_order($db,$cus_id)
{
	
	$order_id = uniqid();
	$cart_avail = pg_fetch_row(pg_query($db,"SELECT cartp_id FROM createcart WHERE cus_id = '$cus_id' AND cart_used = '0'"))[0];
	$skuids = pg_query($db,"SELECT sku_id FROM cart_product WHERE cart_id = $cart_avail");
	$total_price = 0;
	while($skuid = pg_fetch_row($skuids))
	{
		$prod_id = pg_fetch_row(pg_query($db,"SELECT prod_id FROM stock WHERE sku_id='$skuid'"))[0];
		$prod_price = pg_fetch_row(pg_query($db,"SELECT prod_pro_price FROM product WHERE stock.sku_id='$skuid' AND product.prod_id='$prod_id'"));
		$total_price = $prod_price; 
	}
	date_default_timezone_set("Asia/Bangkok");
	$time = date("H:i:sa");
	$date = date("Y/m/d") ;
	pg_query($db,"INSERT INTO order VALUES ($order_id,$cart_avail,$total_price,$date,$time,'waiting for payment')");
	pg_query($db,"UPDATE createcart SET cart_used = '1' WHERE cartp_id = '$cart_avail'");
	pg_query($db,"INSERT INTO createcart (cus_id,cart_used) VALUES ($cus_id,'0')");
	return $order_id;
	
}
  
  
  
  
  
  
  
  
  
  
?>
