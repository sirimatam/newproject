<?php

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
  
  
function show_promotion_product($db) 
{ 
   $promo = pg_query($db,"SELECT * FROM product WHERE prod_price>prod_pro_price"); 
   $num = pg_num_rows($promo);
   
	$promo_top = pg_query($db,"SELECT * FROM product WHERE prod_price>prod_pro_price ORDER BY ((prod_price-prod_pro_price)/prod_price) DESC LIMIT 10");  
   	$promo_num = pg_num_rows($promo_top);
 
	
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
	 $data = ['type' => 'text', 'text' => 'ไม่พบสินค้าในตะกร้า กรุณาเลือกสินค้าลงตะกร้า']; 
	 return $data;
    }
    else{
	$push_array = [];
	    
        $datas = [];
	$datas['type'] = 'template';
        $datas['altText'] = 'this is a carousel template';
        $datas['template']['type'] = 'carousel';  
	$datas['template']['imageSize'] = 'contain';    
    for ($i=0; $i<pg_num_rows($skuid);$i++)
     {	
        $datas['template']['columns'][$i]['thumbnailImageUrl'] = $skuarray[$i][5]; 
        $datas['template']['columns'][$i]['title'] = $skuarray[$i][0].' '.$namearray[$i][1];
        $datas['template']['columns'][$i]['text'] = $namearray[$i][2]."\n".$skuarray[$i][3]." จำนวน ".$cart_qtt[$i]." ชิ้น";
        $datas['template']['columns'][$i]['actions'][0]['type'] = 'postback';
        $datas['template']['columns'][$i]['actions'][0]['label'] = 'ลบออกจาก ตะกร้า';
        $datas['template']['columns'][$i]['actions'][0]['text'] = 'ลบสินค้ารหัส'.$skuarray[$i][0].'ออกจากตะกร้า';  
        $datas['template']['columns'][$i]['actions'][0]['data'] =  'Delete '.$skuarray[$i][0];
     }
	
    return $datas;
    }
	
  }


function flex_cart_beforeorder($db,$userid) 
{
	$cartp_id = pg_fetch_row(pg_query($db,"SELECT cartp_id FROM createcart WHERE cart_used = '0' AND cus_id = '$userid' LIMIT 1 "))[0];
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
	$data['contents']['header']['contents'][0]['text'] = 'สรุปรายการสั่งซื้อ';
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
	
	$data['contents']['footer']['type'] = 'box';
	$data['contents']['footer']['layout'] = 'vertical';
	$data['contents']['footer']['contents'][0]['type'] = 'button';
	$data['contents']['footer']['contents'][0]['action']['type'] = 'postback';
	$data['contents']['footer']['contents'][0]['action']['label'] = 'สั่งซื้อเลย';
	$data['contents']['footer']['contents'][0]['action']['text'] = 'สั่งซื้อ';
	$data['contents']['footer']['contents'][0]['action']['data'] = 'Order '.$cartp_id;
	$data['contents']['footer']['contents'][0]['color'] = '#E5352E';
	$data['contents']['footer']['contents'][0]['style'] = 'primary';
	$data['contents']['footer']['contents'][1]['type'] = 'button';
	$data['contents']['footer']['contents'][1]['action']['type'] = 'postback';
	$data['contents']['footer']['contents'][1]['action']['label'] = 'ล้างตะกร้า';
	$data['contents']['footer']['contents'][1]['action']['text'] = 'ล้างตะกร้า';
	$data['contents']['footer']['contents'][1]['action']['data'] = 'Clear '.$cartp_id;
	$data['contents']['footer']['contents'][1]['color'] = '#D1D1D1';
	$data['contents']['footer']['contents'][1]['style'] = 'primary';
	
	return $data;
	
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
   while($list = pg_fetch_row($pd_type))
	{
		$prod[$prod_num] = $list;
		$prod_num++;
	}
   if($num_carousel <=10)
   {
	$datas = [];
        $datas['type'] = 'template';
        $datas['altText'] = 'this is a carousel template';
        $datas['template']['type'] = 'carousel';
	$datas['template']['imageSize'] = 'contain'; 
	
      for ($i=0; $i<$num_carousel;$i++)
     {
	     
        $datas['template']['columns'][$i]['thumbnailImageUrl'] = $prod[$i][2]; 
        $datas['template']['columns'][$i]['title'] = $prod[$i][1];
        $datas['template']['columns'][$i]['text'] = $prod[$i][4].'ราคา '.$prod[$i][6].' บาท'; //ราคาโปร
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
   $num_set = floor($num_carousel/10);
   $datas = [];
   for($j=0;$j<$num_set;$j++)
   {
        $datas[$j]['type'] = 'template';
        $datas[$j]['altText'] = 'this is a carousel template';
        $datas[$j]['template']['type'] = 'carousel';
	$datas[$j]['template']['imageSize'] = 'contain';
	
	for ($i=0; $i<10;$i++)
     {
        $datas[$j]['template']['columns'][$i]['thumbnailImageUrl'] = $prod[($j*10)+$i][2]; 
        $datas[$j]['template']['columns'][$i]['title'] = $prod[($j*10)+$i][1];
        $datas[$j]['template']['columns'][$i]['text'] = $prod[($j*10)+$i][4].'ราคา '.$prod[($j*10)+$i][6].' บาท'; //ราคาโปร
        $datas[$j]['template']['columns'][$i]['actions'][0]['type'] = 'postback';
        $datas[$j]['template']['columns'][$i]['actions'][0]['label'] = 'รายละเอียดเพิ่มเติม';
        $datas[$j]['template']['columns'][$i]['actions'][0]['text'] = 'view more';
        $datas[$j]['template']['columns'][$i]['actions'][0]['data'] =  'View '.$prod[($j*10)+$i][0];
        $datas[$j]['template']['columns'][$i]['actions'][1]['type'] = 'postback';
        $datas[$j]['template']['columns'][$i]['actions'][1]['label'] = 'บันทึกเป็น Favorite';
        $datas[$j]['template']['columns'][$i]['actions'][1]['text'] = 'บันทึกเป็น Favorite';   
        $datas[$j]['template']['columns'][$i]['actions'][1]['data'] = 'Favorite '.$prod[($j*10)+$i][0];
     }
   }
	$last_carousel = $num_carousel-($num_set*10);
        $datas[$num_set]['type'] = 'template';
        $datas[$num_set]['altText'] = 'this is a carousel template';
        $datas[$num_set]['template']['type'] = 'carousel'; 
	$datas[$num_set]['template']['imageSize'] = 'contain';
	for ($i=0; $i<$last_carousel;$i++)
     {
        $datas[$num_set]['template']['columns'][$i]['thumbnailImageUrl'] = $prod[($num_set*10)+$i][2]; 
        $datas[$num_set]['template']['columns'][$i]['title'] = $prod[($num_set*10)+$i][1];
        $datas[$num_set]['template']['columns'][$i]['text'] = $prod[($num_set*10)+$i][4].'ราคา '.$prod[($num_set*10)+$i][6].' บาท'; //ราคาโปร
        $datas[$num_set]['template']['columns'][$i]['actions'][0]['type'] = 'postback';
        $datas[$num_set]['template']['columns'][$i]['actions'][0]['label'] = 'รายละเอียดเพิ่มเติม';
        $datas[$num_set]['template']['columns'][$i]['actions'][0]['text'] = 'view more';
        $datas[$num_set]['template']['columns'][$i]['actions'][0]['data'] =  'View '.$prod[($num_set*10)+$i][0];
        $datas[$num_set]['template']['columns'][$i]['actions'][1]['type'] = 'postback';
        $datas[$num_set]['template']['columns'][$i]['actions'][1]['label'] = 'บันทึกเป็น Favorite';
        $datas[$num_set]['template']['columns'][$i]['actions'][1]['text'] = 'บันทึกเป็น Favorite';   
        $datas[$num_set]['template']['columns'][$i]['actions'][1]['data'] = 'Favorite '.$prod[($num_set*10)+$i][0];
     }
   return $datas;
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
   while($list = pg_fetch_row($pd_sku))
	{
		$sku[$sku_num] = $list;
		$sku_num++;
	}
  if($num_carousel <=10)
   {
	$datas = [];
        $datas['type'] = 'template';
        $datas['altText'] = 'this is a carousel template';
        $datas['template']['type'] = 'carousel';
	$datas['template']['imageSize'] = 'contain';

      for ($i=0; $i<$num_carousel;$i++)
     {
     
        $datas['template']['columns'][$i]['thumbnailImageUrl'] = $sku[$i][5]; 
        $datas['template']['columns'][$i]['title'] = 'รหัสสินค้า '.$sku[$i][0].' '.$pd_name;
        $datas['template']['columns'][$i]['text'] = $pd_des."\n".$sku[$i][3]." size: ".$sku[$i][4]."  Stock : ".$sku[$i][2];
        $datas['template']['columns'][$i]['actions'][0]['type'] = 'postback';
        $datas['template']['columns'][$i]['actions'][0]['label'] = 'สั่งลงตะกร้า';
        $datas['template']['columns'][$i]['actions'][0]['text'] = 'บันทึก'.$pd_name.' '.$sku[$i][3].' ลงตะกร้าเรียบร้อยแล้ว';
        $datas['template']['columns'][$i]['actions'][0]['data'] = 'Cart '.$sku[$i][0];
	$datas['template']['columns'][$i]['actions'][1]['type'] = 'message';
        $datas['template']['columns'][$i]['actions'][1]['label'] = 'สั่งสินค้ามากกว่า 1 ชิ้น';
        $datas['template']['columns'][$i]['actions'][1]['text'] = "กรุณาพิมพ์รหัสสินค้า เว้นวรรค ตามด้วยจำนวนสินค้าที่ต้องการ เช่น</br>B01 4";
        $datas['template']['columns'][$i]['actions'][2]['type'] = 'message';
        $datas['template']['columns'][$i]['actions'][2]['label'] = 'ดูสินค้าอื่น';
        $datas['template']['columns'][$i]['actions'][2]['text'] = 'ดูและสั่งซื้อสินค้า';  
     }
    
     return $datas;
   }
   else
   {
   $num_set = floor($num_carousel/10);
   $datas = [];
   for($j=0;$j<$num_set;$j++)
   {
        $datas[$j]['type'] = 'template';
        $datas[$j]['altText'] = 'this is a carousel template';
        $datas[$j]['template']['type'] = 'carousel';
	$datas[$j]['template']['imageSize'] = 'contain';
	
	for ($i=0; $i<10;$i++)
     {
        $datas[$j]['template']['columns'][$i]['thumbnailImageUrl'] = $sku[($j*10)+$i][5]; 
        $datas[$j]['template']['columns'][$i]['title'] = 'รหัสสินค้า '.$sku[($j*10)+$i][0].' '.$pd_name;
        $datas[$j]['template']['columns'][$i]['text'] = $pd_des."\n".$sku[($j*10)+$i][3]." size: ".$sku[($j*10)+$i][4]."  Stock : ".$sku[($j*10)+$i][2];
        $datas[$j]['template']['columns'][$i]['actions'][0]['type'] = 'postback';
        $datas[$j]['template']['columns'][$i]['actions'][0]['label'] = 'สั่งลงตะกร้า';
        $datas[$j]['template']['columns'][$i]['actions'][0]['text'] = 'บันทึก'.$pd_name.' '.$sku[($j*10)+$i][3].' ลงตะกร้าเรียบร้อยแล้ว';
        $datas[$j]['template']['columns'][$i]['actions'][0]['data'] = 'Cart '.$sku[($j*10)+$i][0];
	$datas[$j]['template']['columns'][$i]['actions'][1]['type'] = 'message';
        $datas[$j]['template']['columns'][$i]['actions'][1]['label'] = 'สั่งสินค้ามากกว่า 1 ชิ้น';
        $datas[$j]['template']['columns'][$i]['actions'][1]['text'] = "กรุณาพิมพ์รหัสสินค้า เว้นวรรค ตามด้วยจำนวนสินค้าที่ต้องการ เช่น</br>B01 4";
        $datas[$j]['template']['columns'][$i]['actions'][2]['type'] = 'message';
        $datas[$j]['template']['columns'][$i]['actions'][2]['label'] = 'ดูสินค้าอื่น';
        $datas[$j]['template']['columns'][$i]['actions'][2]['text'] = 'ดูและสั่งซื้อสินค้า';
     }
   }
	$last_carousel = $num_carousel-($num_set*10);
        $datas[$num_set]['type'] = 'template';
        $datas[$num_set]['altText'] = 'this is a carousel template';
        $datas[$num_set]['template']['type'] = 'carousel'; 
	$datas[$num_set]['template']['imageSize'] = 'contain';
	for ($i=0; $i<$last_carousel;$i++)
     {
        $datas[$num_set]['template']['columns'][$i]['thumbnailImageUrl'] = $sku[($num_set*10)+$i][5]; 
        $datas[$num_set]['template']['columns'][$i]['title'] = 'รหัสสินค้า '.$sku[($num_set*10)+$i][0].' '.$pd_name;
        $datas[$num_set]['template']['columns'][$i]['text'] = $pd_des."\n".$sku[($num_set*10)+$i][3]." size: ".$sku[($num_set*10)+$i][4]."  Stock : ".$sku[($num_set*10)+$i][2];
        $datas[$num_set]['template']['columns'][$i]['actions'][0]['type'] = 'postback';
        $datas[$num_set]['template']['columns'][$i]['actions'][0]['label'] = 'สั่งลงตะกร้า';
        $datas[$num_set]['template']['columns'][$i]['actions'][0]['text'] = 'บันทึก'.$pd_name.' '.$sku[($num_set*10)+$i][3].' ลงตะกร้าเรียบร้อยแล้ว';
        $datas[$num_set]['template']['columns'][$i]['actions'][0]['data'] = 'Cart '.$sku[($num_set*10)+$i][0];
	$datas[$num_set]['template']['columns'][$i]['actions'][1]['type'] = 'message';
        $datas[$num_set]['template']['columns'][$i]['actions'][1]['label'] = 'สั่งสินค้ามากกว่า 1 ชิ้น';
        $datas[$num_set]['template']['columns'][$i]['actions'][1]['text'] = "กรุณาพิมพ์รหัสสินค้า เว้นวรรค ตามด้วยจำนวนสินค้าที่ต้องการ เช่น</br>B01 4";
        $datas[$num_set]['template']['columns'][$i]['actions'][2]['type'] = 'message';
        $datas[$num_set]['template']['columns'][$i]['actions'][2]['label'] = 'ดูสินค้าอื่น';
        $datas[$num_set]['template']['columns'][$i]['actions'][2]['text'] = 'ดูและสั่งซื้อสินค้า';
     }
   return $datas;
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
	    pg_query($db,"UPDATE stock SET sku_qtt = '$sku_qtt_new' WHERE sku_id = '$sku_id'"); 
    }
    
    }
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
  







  
function get_datetime()
{
	date_default_timezone_set("Asia/Bangkok");
	$date = date("Y-m-d");
	$time = date("H:i:s");
	return [$date,$time];
}



?>
