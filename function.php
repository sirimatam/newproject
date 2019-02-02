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
        $datas['template']['columns'][$i]['actions'][1]['text'] = 'บันทึกเป็น Favorite';   
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
  $pd_sku = pg_query($db,"SELECT * FROM STOCK WHERE stock.prod_id = '$prod_id'");
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
        $datas['template']['columns'][$i]['text'] = $pd_des."\n".$sku[$i][3]."\n"."จำนวน : ".$sku[$i][2];
        $datas['template']['columns'][$i]['actions'][0]['type'] = 'postback';
        $datas['template']['columns'][$i]['actions'][0]['label'] = 'สั่งลงตะกร้า';
        $datas['template']['columns'][$i]['actions'][0]['text'] = 'บันทึก'.$pd_name.' '.$sku[$i][3].' ลงตะกร้าเรียบร้อยแล้ว';
        $datas['template']['columns'][$i]['actions'][0]['data'] = 'Cart '.$sku[$i][0];
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




/*
  
//if message['text'] == 'Favorite'.$prod_id
  
function add_favorite($prod_id,$cus_id)
  {
    /* check fav cannot more than 10 */
   /* $check = pg_query($db,'SELECT * FROM Favorite WHERE Favorite.cus_id = $cus_id');
    $count = pg_num_rows($check);
    if($count>=10){ return $reply_msg = 'คุณสามารถ Favorite ได้ 10 รายการเท่านั้น'}  
    //end of function
    else{
    $fave_id++;
    pg_query($db,'INSERT INTO Favorite VALUES ($fave_id,$prod_id,$cus_id)');
    }
  }  


  /*
  function carousel_show_favorite($cus_id)
  {
    $check = pg_query($db,'SELECT * FROM Favorite WHERE Favorite.cus_id = $cus_id'); 
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
  }*/
  
  /* if message['text'] == delete.$fav_id' */
  /*function delete_favorite($fav_id)
  {
    pg_query('DELETE FROM Favorite WHERE fav_id = $fav_id');
  }

  function delete_from_cart($sku_id,$cus_id)
  {
    $cart_avail = pg_fetch_row(pg_query($db,"SELECT cartp_id FROM Createcart WHERE cus_id = $cus_id AND cart_used = '0'"))[0];
    pg_query('DELETE FROM Cart_product WHERE sku_id = $sku_id AND cartp_id = $cart_avail');
  }
  */
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
function add_to_cart($sku_id,$cus_id,$cart_qtt)
  {
    /* check cart cannot more than 10 */
  /*  $cartp_id = pg_fetch_row(pg_query($db,'SELECT cartp_id FROM Createcart WHERE cart_used = '0' AND cus_id = $cus_id'));
    $check = pg_query($db,'SELECT * FROM Cart_product WHERE cartp_id = $cartp_id');
    $count = pg_num_rows($check);
    if($count>=10){ return $reply_msg = 'คุณสามารถเพิ่มสินค้าลงตะกร้า ได้ 10 รายการเท่านั้น';}  
    //end of function
    else{
    pg_query($db,"INSERT INTO Cart_product (cartp_id,sku_id,cart_prod_qtt) VALUES ($cartp_id,$sku_id,$cart_qtt)"); //ยังไม่ได้ใส่กรณีซื้อSKUเดียวกันสองตัว
    }*/
  }    
  
//ยังแก้ไม่เสร็จ  
/*function carousel_cart($cus_id)
{
    $cartid = pg_fetch_row(pg_query($db,"SELECT cartp_id FROM Createcart WHERE Createcart.cus_id = $cus_id AND Createcart.cart_used = '0'"))[0];
    $skuid = pg_query($db,"SELECT sku_id FROM Cart_product WHERE Cart_product.cartp_id = $cartid");
    $skuarray = array();
    $run1 = 0;
    $skurow = pg_fetch_row(pg_query($db,"SELECT sku_id FROM Cart_product WHERE Cart_product.cartp_id = $cartid"));
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
	 $x = pg_fetch_row(pg_query($db,"SELECT (prod_id,prod_name,prod_description) FROM Product WHERE Stock.sku_id = $skuarray[$i] AND Stock.prod_id = Product.prod_id"));
	 $namearray[$run2] = array($x[0],$x[1],$x[2]) ;
	 $run2++;
    }
    //$pd = pg_fetch_result(pg_query($db,'SELECT (prod_id,prod_name,prod_description) FROM Product WHERE Stock.prod_id = Product.prod_id AND Cart_product.cartp_id = $cartid AND '));
    
    $cartitems = pg_query($db,'SELECT * FROM Cart_product WHERE Cart_product.cartp_id = $cartid');
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
    
    

function flex_order($order_id)
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
	
	$order_array = pg_fetch_row($db,"SELECT * From Order WHERE order_id = $order_id");
	$cartp_id = $order_array[1];
	$cartp_array = pg_query($db,"SELECT (sku_id,cart_prod_id) From Cart_product WHERE Cart_product.cartp_id = $cartp_id");
	$sku_tuple = array();
	for($i=0;$i<pg_num_rows($cartp_array);$i++)
	{
		$sku_tuple[$i] = ($cartp_array[0] => $cartp_array[1]);
	}
	$pd_id = array();
	$run =0;
	foreach( $sku_tuple as $sku_id => $order_qty)
	{
		$pd_id[$run] = pg_fetch_row(pg_query($db,"SELECT prod_id FROM Stock WHERE Stock.sku_id = $sku_id"))[0];
		$run++;
	}
	$running = 0;
	$pd = [];
	foreach ( $pd_id as $pdt_id )
	{
		$pd[$running] = pg_fetch_row(pg_query($db,"SELECT (prod_id,prod_name,prod_pro_price) FROM Product WHERE Product.prod_id = $pdt_id"))[0];
		$running++;
	}
	for($i=0;$i<sizeof($pd);$i++)
	{
		$data['contents']['header']['contents'][$i]['type'] = 'box';
		$data['contents']['header']['contents'][$i]['layout'] = 'baseline';
		$data['contents']['header']['contents'][$i]['layout']['contents'][0]['type'] = 'text';
		$data['contents']['header']['contents'][$i]['layout']['contents'][0]['text'] = $pd[$i][1]; //prod_name
		$data['contents']['header']['contents'][$i]['layout']['contents'][0]['flex'] = 0;
		$data['contents']['header']['contents'][$i]['layout']['contents'][0]['margin'] = 'sm';
		$data['contents']['header']['contents'][$i]['layout']['contents'][0]['weight'] = 'regular';
		$data['contents']['header']['contents'][$i]['layout']['contents'][1]['type'] = 'text';
		$data['contents']['header']['contents'][$i]['layout']['contents'][1]['text'] = $pd[$i][2]; //prod_name
		$data['contents']['header']['contents'][$i]['layout']['contents'][1]['flex'] = 0;
		$data['contents']['header']['contents'][$i]['layout']['contents'][1]['margin'] = 'sm';
		$data['contents']['header']['contents'][$i]['layout']['contents'][1]['weight'] = 'regular';
		
	}
	
	

	
}


    
    
    
    
function add_to_order($cus_id)
{
	
	$order_id = uniqid();
	$cart_avail = pg_fetch_row(pg_query($db,"SELECT cartp_id FROM Createcart WHERE cus_id = $cus_id AND cart_used = '0'"))[0];
	$skuids = pg_query($db,"SELECT sku_id FROM Cart_product WHERE cart_id = $cart_avail");
	$total_price = 0;
	while($skuid = pg_fetch_row($skuids))
	{
		$prod_id = pg_fetch_row(pg_query($db,"SELECT prod_id FROM Stock WHERE sku_id=$skuid"))[0];
		$prod_price = pg_fetch_row(pg_query($db,"SELECT prod_pro_price FROM Product WHERE Stock.sku_id=$skuid AND Product.prod_id=$prod_id"));
		$total_price = $prod_price; 
	}
	date_default_timezone_set("Asia/Bangkok");
	$time = date("H:i:sa");
	$date = date("Y/m/d") ;
	pg_query($db,"INSERT INTO Order VALUES ($order_id,$cart_avail,$total_price,$date,$time,'waiting for payment')");
	pg_query($db,"UPDATE Createcart SET cart_used = '1' WHERE cartp_id = $cart_avail");
	pg_query($db,"INSERT INTO Createcart (cus_id,cart_used) VALUES ($cus_id,'0')");
	return $order_id;
	
}
  
  
  
  */
  
  
  
  
  
  
?>
