<?php
	/*   
function show_promotion_product() 
{ 
   $promo = pg_query($db,"SELECT * FROM Product WHERE prod_price>prod_pro_price"); 
   $num = pg_num_rows($promo);
   
   if($num>10)
   {
	$promo_top = pg_query($db,"SELECT TOP 10 * FROM Product ORDER BY (prod_price-prod_pro_price)/prod_price DESC WHERE prod_price>prod_pro_price");  
   	$promo_num = pg_num_rows($promo_top);
   }
   else
   {
   	$promo_top = pg_query($db,"SELECT * FROM Product ORDER BY (prod_price-prod_pro_price)/prod_price DESC WHERE prod_price>prod_pro_price");  
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
	   
function customer_address($cusid)
{
	pg_query($db,"UPDATE Customer SET cus_name = 'C001', cus_address = '', cus_tel = '' WHERE cus_id = $cusid ");
}
	   
  
  */
  
function button_all_type()
  {
	  $data = 
    [
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
/*
function show_address($cusid)
{
	$query = pg_query($db,"SELECT cus_description FROM Customer WHERE Customer.cus_id = $cusid");
	$address = pg_fetch_row($query)[0];
	
	$data = [];
	$data['type'] = 'template';
	$data['altText'] = 'this is a buttons template';
	$data['template']['type'] = 'buttons';
	$data['template']['actions']['type'] = 'message';
	$data['template']['actions']['label'] = 'แก้ไขที่อยู่จัดส่ง';
	$data['template']['actions']['text'] = 'แก้ไขที่อยู่';
	$data['template']['title'] = 'ที่อยู่จัดส่งปัจจุบัน';
	$data['template']['text'] = $address;
	
	return $data;
}*/
/* ข้อ 2 */
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
  
  $pd_name = pg_fetch_row(pg_query($db,"SELECT prod_name FROM product WHERE prod_id = '$prod_id'"))[0];
  $pd_des = pg_fetch_row(pg_query($db,"SELECT prod_description FROM product WHERE prod_id = '$prod_id'"))[0];
  $pd_price = pg_fetch_row(pg_query($db,"SELECT prod_pro_price FROM product WHERE prod_id = '$prod_id'"))[0];
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
        $datas['template']['columns'][$i]['title'] = 'รหัสสินค้า '.$sku[$i][0].' '.$pd_name;
        $datas['template']['columns'][$i]['text'] = $pd_des."\n".$sku[$i][3]."  Stock : ".$sku[$i][2];
        $datas['template']['columns'][$i]['actions'][0]['type'] = 'postback';
        $datas['template']['columns'][$i]['actions'][0]['label'] = 'สั่งลงตะกร้า';
        $datas['template']['columns'][$i]['actions'][0]['text'] = 'บันทึก'.$pd_name.' '.$sku[$i][3].' ลงตะกร้าเรียบร้อยแล้ว';
        $datas['template']['columns'][$i]['actions'][0]['data'] = 'Cart '.$sku[$i][0];
	$datas['template']['columns'][$i]['actions'][1]['type'] = 'message';
        $datas['template']['columns'][$i]['actions'][1]['label'] = 'สั่งสินค้ามากกว่า 1 ชิ้น';
        $datas['template']['columns'][$i]['actions'][1]['text'] = "กรุณาพิมพ์รหัสสินค้า เว้นวรรค ตามด้วยจำนวนสินค้าที่ต้องการ เช่น A001 4";
       $datas['template']['columns'][$i]['actions'][2]['type'] = 'message';
        $datas['template']['columns'][$i]['actions'][2]['label'] = 'ดูสินค้าอื่น';
        $datas['template']['columns'][$i]['actions'][2]['text'] = 'ดูและสั่งซื้อสินค้า';  
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
	$datas['template']['columns'][$i]['actions'][1]['type'] = 'postback';
        $datas['template']['columns'][$i]['actions'][1]['label'] = 'สั่งสินค้ามากกว่า 1 ชิ้น';
        $datas['template']['columns'][$i]['actions'][1]['text'] = "กรุณาพิมพ์รหัสสินค้า เว้นวรรค ตามด้วยจำนวนสินค้าที่ต้องการ เช่น A001 4";
	$datas['template']['columns'][$i]['actions'][1]['data'] = 'สั่งสินค้ามากว่า 1 ชิ้น';
        $datas['template']['columns'][$i]['actions'][2]['type'] = 'message';
        $datas['template']['columns'][$i]['actions'][2]['label'] = 'ดูสินค้าอื่น';
        $datas['template']['columns'][$i]['actions'][2]['text'] = 'ดูและสั่งซื้อสินค้า';    
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
  function delete_from_cart($db,$sku_id,$cus_id)
  {
    $cart_avail = pg_fetch_row(pg_query($db,"SELECT cartp_id FROM createcart WHERE cus_id = '$cus_id' AND cart_used = '0'"))[0];
    $cart_qtt = pg_fetch_row(pg_query($db,"SELECT cart_prod_qtt FROM cart_product WHERE sku_id = '$sku_id' AND cartp_id = '$cart_avail'"))[0];
    pg_query("DELETE FROM cart_product WHERE sku_id = '$sku_id' AND cartp_id = '$cart_avail'");
    $sku_qtt_now = pg_fetch_row(pg_query($db,"SELECT sku_qtt FROM stock WHERE sku_id = '$sku_id'"))[0];
    $sku_qtt_new = $sku_qtt_now+$cart_qtt;
    pg_query($db,"UPDATE stock SET sku_qtt = '$sku_qtt_new' WHERE sku_id = '$sku_id'");
  }

  function out_of_time($db)
  {
     date_default_timezone_set("Asia/Bangkok");
     $time = date("H:i:s");
     $date = date("Y-m-d");
     $order_list = pg_query($db,"SELECT * FROM orderlist"); 
     $order_array=array();
     while($order=pg_fetch_row($order_list))
     {
	     $exp_date = date("Y-m-d", strtotime($order[3]."+2 days"));
	     if($date >= $exp_date AND $time >= $order[4] AND $order[5] == 'waiting for payment')
	     {
		     pg_query("DELETE FROM orderlist WHERE order_id = '$order[0]'");
		     
	     }
	     
     }
	  
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
  /*
  
  
 function create_cart($cus_id)
  {
    //check จ่ายตังก่อน ++ ยังแก้ไม่เสด
    pg_query($db,'INSERT INTO Favorite VALUES ($fave_id,$prod_id,$cus_id)');
  }
  
  
  */
  
  
  
  
  
  
  
  
  
  
//if message['text'] == 'Cart'.$sku_id
function add_to_cart($db,$sku_id,$cus_id,$cart_qtt)
  {
    /* check cart cannot more than 10 */
    $cartp_id = pg_fetch_row(pg_query($db,"SELECT cartp_id FROM createcart WHERE cart_used = '0' AND cus_id = '$cus_id'"))[0];
    $check = pg_query($db,"SELECT * FROM cart_product WHERE cartp_id = '$cartp_id'");
    $count = pg_num_rows($check);
    $sku_qtt_now = pg_fetch_row(pg_query($db,"SELECT sku_qtt FROM stock WHERE sku_id = '$sku_id'"))[0];
    $cart_sku = pg_query($db,"SELECT sku_id FROM cart_product WHERE cartp_id = '$cartp_id'");
	
    
    if($count>=10){ return $reply_msg = 'คุณสามารถเพิ่มสินค้าลงตะกร้า ได้ 10 รายการเท่านั้น';}  
    //end of function
    elseif($sku_qtt_now < $cart_qtt)
    {
	$reply_msg = ['type' => 'text', 'text' => 'สินค้าในสต็อกไม่เพียงพอ'];
	return $reply_msg;
    }
    else{
    $sku_qtt_new = $sku_qtt_now-$cart_qtt;
    $have_sku_check = 0;
    if (pg_num_rows($cart_sku)>0)
    {
    	while($sku_now = pg_fetch_row($cart_sku)[0])
    	{
	    if($sku_now==$sku_id)
	    {
		    $cart_qtt_now = pg_fetch_row(pg_query($db,"SELECT cart_prod_qtt FROM cart_product WHERE sku_id = '$sku_id' AND cartp_id = '$cartp_id'"))[0];
		    $cart_qtt_new = $cart_qtt_now+$cart_qtt;
		    pg_query($db,"UPDATE cart_product SET cart_prod_qtt = '$cart_qtt_new' WHERE sku_id = '$sku_id' AND cartp_id = '$cartp_id'"); //ยังไม่ได้ใส่กรณีซื้อSKUเดียวกันสองตัว
    		    pg_query($db,"UPDATE stock SET sku_qtt = '$sku_qtt_new' WHERE sku_id = '$sku_id'"); //ยังไม่ได้ใส่กรณีซื้อSKUเดียวกันสองตัว
		    $have_sku_check=1;
	    }
    	}
	if($have_sku_check == 0)
	{
		pg_query($db,"INSERT INTO cart_product (cartp_id,sku_id,cart_prod_qtt) VALUES ('$cartp_id','$sku_id','$cart_qtt')");
	        pg_query($db,"UPDATE stock SET sku_qtt = '$sku_qtt_new' WHERE sku_id = '$sku_id'");	
	}
    
    }
    else
    {
	    pg_query($db,"INSERT INTO cart_product (cartp_id,sku_id,cart_prod_qtt) VALUES ('$cartp_id','$sku_id','$cart_qtt')");
	    pg_query($db,"UPDATE stock SET sku_qtt = '$sku_qtt_new' WHERE sku_id = '$sku_id'"); //ยังไม่ได้ใส่กรณีซื้อSKUเดียวกันสองตัว
    }
    //pg_query($db,"UPDATE stock SET sku_qtt = '$sku_qtt_new' WHERE sku_id = '$sku_id'"); //ยังไม่ได้ใส่กรณีซื้อSKUเดียวกันสองตัว
    }
  }    
  
//ยังแก้ไม่เสร็จ  
function carousel_cart($db,$cus_id)
{
    $cartid = pg_fetch_row(pg_query($db,"SELECT cartp_id FROM createcart WHERE cus_id = '$cus_id' AND cart_used = '0'"))[0];
    $skuid = pg_query($db,"SELECT sku_id FROM cart_product WHERE cart_product.cartp_id = '$cartid'");
    $cartqtts = pg_query($db,"SELECT cart_prod_qtt FROM cart_product WHERE cart_product.cartp_id = '$cartid'");
    $run = 0;
    $cart_qtt = array();
    $total = 0;
     while($ccc = pg_fetch_row($cartqtts)[0])
    {
	    $cart_qtt[$run] = $ccc;
	    $run++;
	    $total +=$ccc;
    }
    $skuarray = array();
    $run1 = 0;
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
        $datas['template']['columns'][$i]['text'] = $namearray[$i][2]."\n".$skuarray[$i][3]." จำนวน ".$cart_qtt[$i]." ชิ้น";
        $datas['template']['columns'][$i]['actions'][0]['type'] = 'postback';
        $datas['template']['columns'][$i]['actions'][0]['label'] = 'ลบออกจาก ตะกร้า';
        $datas['template']['columns'][$i]['actions'][0]['text'] = 'ลบสินค้ารหัส'.$skuarray[$i][0].'ออกจากตะกร้า';  
        $datas['template']['columns'][$i]['actions'][0]['data'] =  'Delete '.$skuarray[$i][0];
     }
	
     
	$datas2 = [];
	$datas2['type'] = 'template';
        $datas2['altText'] = 'this is a confirm template';
        $datas2['template']['type'] = 'confirm';
        $datas2['template']['actions'][0]['type'] = 'postback';
        $datas2['template']['actions'][0]['label'] = 'สั่งซื้อ';
        $datas2['template']['actions'][0]['text'] = 'สั่งซื้อ';  
        $datas2['template']['actions'][0]['data'] =  'Order '.$cartid;
	$datas2['template']['actions'][1]['type'] = 'postback';
        $datas2['template']['actions'][1]['label'] = 'ล้างตะกร้า';
        $datas2['template']['actions'][1]['text'] = 'ล้างตะกร้า';  
        $datas2['template']['actions'][1]['data'] =  'Clear '.$cartid;
	$datas2['template']['text'] = 'สินค้า'.$total.' ชิ้น';
     
    $push_array[0] = $datas;
    $push_array[1] = $datas2;	
    return $push_array;
    }
	
  }

function carousel_flex_order($db,$userid)
{
	$cartp_id_array = pg_query($db,"SELECT cartp_id FROM createcart WHERE createcart.cus_id = '$userid' AND createcart.cart_used = '1'");
	$run2 = 0;
	$run1 = 0;
	$order = array();
	$sku_color = array();
	$pd = [];
	while($cartp_id = pg_fetch_row($cartp_id_array)[0])
	{
		$a = pg_query($db,"SELECT (order_id,cartp_id,total_price) FROM orderlist WHERE cartp_id = '$cartp_id' AND order_status != 'complete'");
		if(pg_num_rows($a)>0)
		{
			$order[$run1] = pg_fetch_row($a)[0];
			$run1++;
		}
	}
	for($k=0;$k<sizeof($order);$k++)
	{
	$cartp_array = pg_query($db,"SELECT sku_id FROM cart_product WHERE cartp_id = $order[$k][1]");
	$skuid_array = array();
	$i = 0;
	while($cartp = pg_fetch_row($cartp_array)[0])
	{
		$skuid_array[$i] = $cartp;
		$i++;
	}
	$pdid_array = array();
	//$sku_color = array();
	$run =0;
	foreach( $skuid_array as $skuid)
	{
		$pdid_array[$run] = pg_fetch_row(pg_query($db,"SELECT prod_id FROM stock WHERE sku_id = '$skuid'"))[0];
		$sku_color[$k][$run] = pg_fetch_row(pg_query($db,"SELECT sku_color FROM stock WHERE sku_id = '$skuid'"))[0];
		$run++;
	}
	$running = 0;
	
	//$pd = [];
	$total = 0;
	foreach ( $pdid_array as $pdid )
	{
		$x = pg_fetch_row(pg_query($db,"SELECT prod_id FROM product WHERE prod_id = '$pdid'"))[0];
		$y = pg_fetch_row(pg_query($db,"SELECT prod_name FROM product WHERE prod_id = '$pdid'"))[0];
		$z = pg_fetch_row(pg_query($db,"SELECT prod_pro_price FROM product WHERE prod_id = '$pdid'"))[0];
		$pd[$k][$running][0] = $x;
		$pd[$k][$running][1] = $y;
		$pd[$k][$running][2] = $z;
		$total += $z;
		$running++;
	}
	
	}
	
	$data = [];
	$data['type'] = 'flex';
	$data['altText'] = 'Flex Message';
	$data['contents']['type'] = 'carousel';
	
	for($j=0;$j<pg_num_rows($order);$j++)
	{
	$data['contents']['contents'][$j]['type'] = 'bubble';
	$data['contents']['contents'][$j]['header']['type'] = 'box';
	$data['contents']['contents'][$j]['header']['layout'] = 'vertical';
	$data['contents']['contents'][$j]['header']['contents'][0]['type'] = 'text';
	$data['contents']['contents'][$j]['header']['contents'][0]['text'] = 'รหัสใบสั่งซื้อที่ '.$order[$j][0];
	$data['contents']['contents'][$j]['header']['contents'][0]['size'] = 'lg';
	$data['contents']['contents'][$j]['header']['contents'][0]['align'] = 'center';
	$data['contents']['contents'][$j]['header']['contents'][0]['weight'] = 'bold';
	//$data['contents']['body']['type'] = 'box';
	//$data['contents']['body']['layout'] = 'vertical';
	//$data['contents']['body']['spacing'] = 'md';
	$data['contents']['contents'][$j]['body']['type'] = 'box';
	$data['contents']['contents'][$j]['body']['layout'] = 'vertical';
	$data['contents']['contents'][$j]['body']['contents'][0]['type'] = 'box';
	$data['contents']['contents'][$j]['body']['contents'][0]['layout'] = 'baseline';
	$data['contents']['contents'][$j]['body']['contents'][0]['flex'] = 0;
	$data['contents']['contents'][$j]['body']['contents'][0]['contents'][0]['type'] = 'text';
	$data['contents']['contents'][$j]['body']['contents'][0]['contents'][0]['text'] = 'รวม'; //prod_name
	$data['contents']['contents'][$j]['contents'][$j]['body']['contents'][0]['contents'][0]['margin'] = 'sm';
	$data['contents']['contents'][$j]['body']['contents'][0]['contents'][0]['weight'] = 'regular';
	$data['contents']['contents'][$j]['body']['contents'][0]['contents'][1]['type'] = 'text';
	$data['contents']['contents'][$j]['body']['contents'][0]['contents'][1]['text'] = $order[$j][1].' บาท'; //prod_name
	$data['contents']['contents'][$j]['body']['contents'][0]['contents'][1]['margin'] = 'sm';
	$data['contents']['contents'][$j]['body']['contents'][0]['contents'][1]['weight'] = 'regular';
	$data['contents']['contents'][$j]['body']['contents'][0]['contents'][1]['align'] = 'end';
	
	for($i=0;$i<pg_num_rows($pd[$j]);$i++)
	{
		$data['contents']['contents'][$j]['header']['contents'][$i+1]['type'] = 'box';
		$data['contents']['contents'][$j]['header']['contents'][$i+1]['layout'] = 'baseline';
		$data['contents']['contents'][$j]['header']['contents'][$i+1]['flex'] = 0;
		$data['contents']['contents'][$j]['header']['contents'][$i+1]['contents'][0]['type'] = 'text';
		$data['contents']['contents'][$j]['header']['contents'][$i+1]['contents'][0]['text'] = $pd[$j][$i][1].' '.$sku_color[$j][$i]; //prod_name
		$data['contents']['contents'][$j]['header']['contents'][$i+1]['contents'][0]['margin'] = 'sm';
		$data['contents']['contents'][$j]['header']['contents'][$i+1]['contents'][0]['weight'] = 'regular';
		$data['contents']['contents'][$j]['header']['contents'][$i+1]['contents'][1]['type'] = 'text';
		$data['contents']['contents'][$j]['header']['contents'][$i+1]['contents'][1]['text'] = $pd[$j][$i][2].' บาท'; //prod_name
		$data['contents']['contents'][$j]['header']['contents'][$i+1]['contents'][1]['margin'] = 'sm';
		$data['contents']['contents'][$j]['header']['contents'][$i+1]['contents'][1]['weight'] = 'regular';
		$data['contents']['contents'][$j]['header']['contents'][$i+1]['contents'][1]['align'] = 'end';
	}
	}
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
	//$data['contents']['body']['type'] = 'box';
	//$data['contents']['body']['layout'] = 'vertical';
	//$data['contents']['body']['spacing'] = 'md';
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
	return $data;
	
}
    
    
    
    
function add_to_order($db,$cus_id,$cart_avail)
{
	
	$order_id = uniqid();
	//$cart_avail = pg_fetch_row(pg_query($db,"SELECT cartp_id FROM createcart WHERE cus_id = '$cus_id' AND cart_used = '0'"))[0];
	$skuids = pg_query($db,"SELECT (sku_id,cart_prod_qtt) FROM cart_product WHERE cartp_id = '$cart_avail'");
	$total_price = 0;
	$i = 0;
	while($skuid = pg_fetch_row($skuids))
	{
		$prod_id = pg_fetch_row(pg_query($db,"SELECT prod_id FROM stock WHERE sku_id='$skuid[0]'"))[0];
		$prod_price = pg_fetch_row(pg_query($db,"SELECT prod_pro_price FROM product WHERE product.prod_id='$prod_id'"))[0];
		$total_price += ($prod_price*$skuid[1]); 
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
function clear_cart($db,$cart_avail)
{
	$sku_array = pg_query($db,"SELECT sku_id FROM cart_product WHERE cartp_id = '$cart_avail'");
	while($sku_id = pg_fetch_row($sku_array)[0])
	{
		$cart_qtt = pg_fetch_row(pg_query($db,"SELECT cart_prod_qtt FROM cart_product WHERE sku_id = '$sku_id' AND cartp_id = '$cart_avail'"))[0];
		$sku_qtt_now = pg_fetch_row(pg_query($db,"SELECT sku_qtt FROM stock WHERE sku_id = '$sku_id'"))[0];
    		$sku_qtt_new = $sku_qtt_now+$cart_qtt;
   		pg_query($db,"UPDATE stock SET sku_qtt = '$sku_qtt_new' WHERE sku_id = '$sku_id'");
	}
	pg_query("DELETE FROM cart_product WHERE cartp_id = '$cart_avail'");
	$data = ['type' => 'text', 'text' => 'ล้างตะกร้าเรียบร้อยแล้ว'];

	return $data;
}
  
  
  
  
  
  
  
  
  
  
?>
