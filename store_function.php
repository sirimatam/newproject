<?php
require 'track.class.php';

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
   $skuarray = array();
   $run = 0;
   while($promo_list_single = pg_fetch_row($promo_top))
   {
	$promo_list[$run] = $promo_list_single;
	$run++;
   }
   $running = 0;
   
        $datas = [];
    	$datas['type'] = 'flex';
    	$datas['altText'] = 'Flex Message';
    	$datas['contents']['type'] = 'carousel';
      for ($i=0; $i<$run;$i++)
     {
        $datas['contents']['contents'][$i]['type'] = 'bubble';
    	$datas['contents']['contents'][$i]['direction'] = 'ltr';
	$datas['contents']['contents'][$i]['header']['type'] = 'box';
	$datas['contents']['contents'][$i]['header']['layout'] = 'vertical';
	$datas['contents']['contents'][$i]['header']['contents'][0]['type'] = 'image';
	$datas['contents']['contents'][$i]['header']['contents'][0]['url'] = $promo_list[$i][2];    
	$datas['contents']['contents'][$i]['header']['contents'][0]['size'] = 'full';
	$datas['contents']['contents'][$i]['header']['contents'][0]['aspectRatio'] = '1.51:1';
	$datas['contents']['contents'][$i]['header']['contents'][0]['aspectMode'] = 'fit';             
	$datas['contents']['contents'][$i]['header']['contents'][1]['type'] = 'text';      
	$datas['contents']['contents'][$i]['header']['contents'][1]['text'] = $promo_list[$i][1];      
        $datas['contents']['contents'][$i]['header']['contents'][1]['weight'] = 'bold';
	$datas['contents']['contents'][$i]['header']['contents'][1]['size'] = 'xl';
	$datas['contents']['contents'][$i]['header']['contents'][1]['wrap'] = true;
	$datas['contents']['contents'][$i]['header']['contents'][2]['type'] = 'box';
	$datas['contents']['contents'][$i]['header']['contents'][2]['layout'] = 'baseline';     
	$datas['contents']['contents'][$i]['header']['contents'][2]['contents'][0]['type'] = 'text';       
	$datas['contents']['contents'][$i]['header']['contents'][2]['contents'][0]['text'] = '฿ '.$promo_list[$i][5];      
	$datas['contents']['contents'][$i]['header']['contents'][2]['contents'][0]['margin'] = 'none';  
	$datas['contents']['contents'][$i]['header']['contents'][2]['contents'][1]['type'] = 'text';       
	$datas['contents']['contents'][$i]['header']['contents'][2]['contents'][1]['text'] = 'Now ฿'.$promo_list[$i][6].' !!!';               
	$datas['contents']['contents'][$i]['header']['contents'][2]['contents'][1]['weight'] = 'bold';
	$datas['contents']['contents'][$i]['header']['contents'][2]['contents'][1]['color'] = '#FF0000'; 
	$datas['contents']['contents'][$i]['header']['contents'][3]['type'] = 'box';
	$datas['contents']['contents'][$i]['header']['contents'][3]['layout'] = 'vertical';
	$datas['contents']['contents'][$i]['header']['contents'][3]['contents'][0]['type'] = 'text';
	$datas['contents']['contents'][$i]['header']['contents'][3]['contents'][0]['text'] = $promo_list[$i][4];    
	$datas['contents']['contents'][$i]['header']['contents'][3]['contents'][0]['wrap'] = true;
	$datas['contents']['contents'][$i]['footer']['type'] = 'box';
	$datas['contents']['contents'][$i]['footer']['layout'] = 'vertical';
	$datas['contents']['contents'][$i]['footer']['contents'][0]['type'] = 'button';
	$datas['contents']['contents'][$i]['footer']['contents'][0]['action']['type'] = 'postback';      
	$datas['contents']['contents'][$i]['footer']['contents'][0]['action']['label'] = 'เลือกสีและไซส์';
	$datas['contents']['contents'][$i]['footer']['contents'][0]['action']['text'] = 'view more';      
	$datas['contents']['contents'][$i]['footer']['contents'][0]['action']['data'] = 'View '.$promo_list[$i][0];
	$datas['contents']['contents'][$i]['footer']['contents'][0]['color'] = '#E5352E';      
	$datas['contents']['contents'][$i]['footer']['contents'][0]['style'] = 'primary';
	$datas['contents']['contents'][$i]['footer']['contents'][1]['type'] = 'button';
	$datas['contents']['contents'][$i]['footer']['contents'][1]['action']['type'] = 'postback';      
	$datas['contents']['contents'][$i]['footer']['contents'][1]['action']['label'] = 'Favorite';
	$datas['contents']['contents'][$i]['footer']['contents'][1]['action']['text'] = 'บันทึกเป็น Favorite';      
	$datas['contents']['contents'][$i]['footer']['contents'][1]['action']['data'] = 'Favorite '.$promo_list[$i][0];
	$datas['contents']['contents'][$i]['footer']['contents'][1]['color'] = '#D1D1D1';      
	$datas['contents']['contents'][$i]['footer']['contents'][1]['style'] = 'primary';
	
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
    
	  
    $pddata = array();
    $run2 = 0;
    
    for($i=0; $i<pg_num_rows($skuid);$i++)
    {
	 $prod_id = $skuarray[$i][1];
	 $arraypd = pg_fetch_row(pg_query($db,"SELECT * FROM product WHERE prod_id = '$prod_id'"));
	 $pddata[$run2] = $arraypd;
	 $run2++;
    }
    
    if(pg_num_rows($skuid) == 0)
    {
	 $data = ['type' => 'text', 'text' => 'ไม่พบสินค้าในตะกร้า กรุณาเลือกสินค้าลงตะกร้า']; 
	 return $data;
    }
    else{
	
	    
        $datas = [];
    	$datas['type'] = 'flex';
    	$datas['altText'] = 'Flex Message';
    	$datas['contents']['type'] = 'carousel';    
    for ($i=0; $i<pg_num_rows($skuid);$i++)
     {	
	$datas['contents']['contents'][$i]['type'] = 'bubble';
    	$datas['contents']['contents'][$i]['direction'] = 'ltr';
	$datas['contents']['contents'][$i]['header']['type'] = 'box';
	$datas['contents']['contents'][$i]['header']['layout'] = 'vertical';
	$datas['contents']['contents'][$i]['header']['contents'][0]['type'] = 'image';
	$datas['contents']['contents'][$i]['header']['contents'][0]['url'] = $skuarray[$i][5];    
	$datas['contents']['contents'][$i]['header']['contents'][0]['size'] = 'full';
	$datas['contents']['contents'][$i]['header']['contents'][0]['aspectRatio'] = '1.51:1';
	$datas['contents']['contents'][$i]['header']['contents'][0]['aspectMode'] = 'fit';             
	$datas['contents']['contents'][$i]['header']['contents'][1]['type'] = 'text';      
	$datas['contents']['contents'][$i]['header']['contents'][1]['text'] = 'รหัสสินค้า '.$skuarray[$i][0].' '.$pddata[$i][1];      
        $datas['contents']['contents'][$i]['header']['contents'][1]['weight'] = 'bold';
	$datas['contents']['contents'][$i]['header']['contents'][1]['size'] = 'xl';
	$datas['contents']['contents'][$i]['header']['contents'][1]['wrap'] = true;
	$datas['contents']['contents'][$i]['header']['contents'][2]['type'] = 'box';
	$datas['contents']['contents'][$i]['header']['contents'][2]['layout'] = 'baseline';     
	$datas['contents']['contents'][$i]['header']['contents'][2]['contents'][0]['type'] = 'text';       
	$datas['contents']['contents'][$i]['header']['contents'][2]['contents'][0]['text'] = '฿ '.$pddata[$i][5];      
	$datas['contents']['contents'][$i]['header']['contents'][2]['contents'][0]['margin'] = 'none';  
	    if($pddata[$i][6] < $pddata[$i][5]) {
	$datas['contents']['contents'][$i]['header']['contents'][2]['contents'][1]['type'] = 'text';       
	$datas['contents']['contents'][$i]['header']['contents'][2]['contents'][1]['text'] = 'Now ฿'.$pddata[$i][6].' !!!';               
	$datas['contents']['contents'][$i]['header']['contents'][2]['contents'][1]['weight'] = 'bold';
	$datas['contents']['contents'][$i]['header']['contents'][2]['contents'][1]['color'] = '#FF0000'; }
	$datas['contents']['contents'][$i]['header']['contents'][3]['type'] = 'box';
	$datas['contents']['contents'][$i]['header']['contents'][3]['layout'] = 'vertical';
	$datas['contents']['contents'][$i]['header']['contents'][3]['contents'][0]['type'] = 'text';
	$datas['contents']['contents'][$i]['header']['contents'][3]['contents'][0]['text'] = $pddata[$i][4];     
	$datas['contents']['contents'][$i]['header']['contents'][3]['contents'][0]['wrap'] = true;   
	$datas['contents']['contents'][$i]['header']['contents'][3]['contents'][1]['type'] = 'text';
	$datas['contents']['contents'][$i]['header']['contents'][3]['contents'][1]['text'] = 'size: '.$skuarray[$i][4];     
	$datas['contents']['contents'][$i]['header']['contents'][3]['contents'][2]['type'] = 'text';
	$datas['contents']['contents'][$i]['header']['contents'][3]['contents'][2]['text'] = $skuarray[$i][3]; //สี
	$datas['contents']['contents'][$i]['header']['contents'][3]['contents'][3]['type'] = 'text';
	$datas['contents']['contents'][$i]['header']['contents'][3]['contents'][3]['text'] = 'stock: '.$skuarray[$i][2];
	$datas['contents']['contents'][$i]['header']['contents'][3]['contents'][4]['type'] = 'text';
	$datas['contents']['contents'][$i]['header']['contents'][3]['contents'][4]['text'] = 'จำนวนที่สั่ง: '.$cart_qtt[$i];
	$datas['contents']['contents'][$i]['header']['contents'][3]['contents'][4]['weight'] = 'bold';
	$datas['contents']['contents'][$i]['footer']['type'] = 'box';
	$datas['contents']['contents'][$i]['footer']['layout'] = 'vertical';
	$datas['contents']['contents'][$i]['footer']['contents'][0]['type'] = 'button';      
	$datas['contents']['contents'][$i]['footer']['contents'][0]['action']['type'] = 'postback';      
	$datas['contents']['contents'][$i]['footer']['contents'][0]['action']['label'] = 'ลบออกจากตะกร้า';
	$datas['contents']['contents'][$i]['footer']['contents'][0]['action']['text'] = 'ลบสินค้ารหัส'.$skuarray[$i][0].'ออกจากตะกร้า';    
	$datas['contents']['contents'][$i]['footer']['contents'][0]['action']['data'] = 'Delete '.$skuarray[$i][0];
	$datas['contents']['contents'][$i]['footer']['contents'][0]['color'] = '#E5352E';       
	
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
	$data['contents']['footer']['contents'][1]['type'] = 'box';
	$data['contents']['footer']['contents'][1]['layout'] = 'horizontal';
	$data['contents']['footer']['contents'][1]['contents'][0]['type'] = 'button';
	$data['contents']['footer']['contents'][1]['contents'][0]['action']['type'] = 'postback';
	$data['contents']['footer']['contents'][1]['contents'][0]['action']['label'] = 'ล้างตะกร้า';
	$data['contents']['footer']['contents'][1]['contents'][0]['action']['text'] = 'ล้างตะกร้า';
	$data['contents']['footer']['contents'][1]['contents'][0]['action']['data'] = 'Clear '.$cartp_id;
	$data['contents']['footer']['contents'][1]['contents'][0]['color'] = '#E5352E';
	$data['contents']['footer']['contents'][1]['contents'][1]['type'] = 'button';
	$data['contents']['footer']['contents'][1]['contents'][1]['action']['type'] = 'message';
	$data['contents']['footer']['contents'][1]['contents'][1]['action']['label'] = 'เลือกสินค้าเพิ่ม';
	$data['contents']['footer']['contents'][1]['contents'][1]['action']['text'] = 'ค้นหาสินค้า';
	$data['contents']['footer']['contents'][1]['contents'][1]['color'] = '#4B4848';
		
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
    	$datas['type'] = 'flex';
    	$datas['altText'] = 'Flex Message';
    	$datas['contents']['type'] = 'carousel';
	
      for ($i=0; $i<$num_carousel;$i++)
     {
	$datas['contents']['contents'][$i]['type'] = 'bubble';
    	$datas['contents']['contents'][$i]['direction'] = 'ltr';
	$datas['contents']['contents'][$i]['header']['type'] = 'box';
	$datas['contents']['contents'][$i]['header']['layout'] = 'vertical';
	$datas['contents']['contents'][$i]['header']['contents'][0]['type'] = 'image';
	$datas['contents']['contents'][$i]['header']['contents'][0]['url'] = $prod[$i][2];     
	$datas['contents']['contents'][$i]['header']['contents'][0]['size'] = 'full';
	$datas['contents']['contents'][$i]['header']['contents'][0]['aspectRatio'] = '1.51:1';
	$datas['contents']['contents'][$i]['header']['contents'][0]['aspectMode'] = 'fit';             
	$datas['contents']['contents'][$i]['header']['contents'][1]['type'] = 'text';      
	$datas['contents']['contents'][$i]['header']['contents'][1]['text'] = $prod[$i][1];      
        $datas['contents']['contents'][$i]['header']['contents'][1]['weight'] = 'bold';
	$datas['contents']['contents'][$i]['header']['contents'][1]['size'] = 'xl';
	$datas['contents']['contents'][$i]['header']['contents'][1]['wrap'] = true;
	$datas['contents']['contents'][$i]['header']['contents'][2]['type'] = 'box';
	$datas['contents']['contents'][$i]['header']['contents'][2]['layout'] = 'baseline';     
	$datas['contents']['contents'][$i]['header']['contents'][2]['contents'][0]['type'] = 'text';       
	$datas['contents']['contents'][$i]['header']['contents'][2]['contents'][0]['text'] = '฿ '.$prod[$i][5];      
	$datas['contents']['contents'][$i]['header']['contents'][2]['contents'][0]['margin'] = 'none';  
	      if($prod[$i][6]<$prod[$i][5]) {
	$datas['contents']['contents'][$i]['header']['contents'][2]['contents'][1]['type'] = 'text';       
	$datas['contents']['contents'][$i]['header']['contents'][2]['contents'][1]['text'] = 'Now ฿'.$prod[$i][6].' !!!';               
	$datas['contents']['contents'][$i]['header']['contents'][2]['contents'][1]['size'] = 'lg';
	$datas['contents']['contents'][$i]['header']['contents'][2]['contents'][1]['weight'] = 'bold';
	$datas['contents']['contents'][$i]['header']['contents'][2]['contents'][1]['color'] = '#FF0000';    }
	$datas['contents']['contents'][$i]['header']['contents'][3]['type'] = 'text';
	$datas['contents']['contents'][$i]['header']['contents'][3]['text'] = $prod[$i][4];     
	$datas['contents']['contents'][$i]['header']['contents'][3]['size'] = 'sm';            
	$datas['contents']['contents'][$i]['header']['contents'][3]['wrap'] = true;
	$datas['contents']['contents'][$i]['header']['contents'][4]['type'] = 'spacer';      
	$datas['contents']['contents'][$i]['footer']['type'] = 'box';
	$datas['contents']['contents'][$i]['footer']['layout'] = 'vertical';
	$datas['contents']['contents'][$i]['footer']['contents'][0]['type'] = 'button';
	$datas['contents']['contents'][$i]['footer']['contents'][0]['action']['type'] = 'postback';      
	$datas['contents']['contents'][$i]['footer']['contents'][0]['action']['label'] = 'เลือกสีและไซส์';
	$datas['contents']['contents'][$i]['footer']['contents'][0]['action']['text'] = 'view more';      
	$datas['contents']['contents'][$i]['footer']['contents'][0]['action']['data'] = 'View '.$prod[$i][0];
	$datas['contents']['contents'][$i]['footer']['contents'][0]['color'] = '#E5352E';      
	$datas['contents']['contents'][$i]['footer']['contents'][0]['style'] = 'primary';
	$datas['contents']['contents'][$i]['footer']['contents'][1]['type'] = 'button';
	$datas['contents']['contents'][$i]['footer']['contents'][1]['action']['type'] = 'postback';      
	$datas['contents']['contents'][$i]['footer']['contents'][1]['action']['label'] = 'Favorite';
	$datas['contents']['contents'][$i]['footer']['contents'][1]['action']['text'] = 'บันทึกเป็น Favorite';      
	$datas['contents']['contents'][$i]['footer']['contents'][1]['action']['data'] = 'Favorite '.$prod[$i][0];
	$datas['contents']['contents'][$i]['footer']['contents'][1]['color'] = '#D1D1D1';      
	$datas['contents']['contents'][$i]['footer']['contents'][1]['style'] = 'primary';	      
	      
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
    	$datas[$j]['type'] = 'flex';
    	$datas[$j]['altText'] = 'Flex Message';
    	$datas[$j]['contents']['type'] = 'carousel';
	   
	
	for ($i=0; $i<10;$i++)
     {
	$datas[$j]['contents']['contents'][$i]['type'] = 'bubble';
    	$datas[$j]['contents']['contents'][$i]['direction'] = 'ltr';
	$datas[$j]['contents']['contents'][$i]['header']['type'] = 'box';
	$datas[$j]['contents']['contents'][$i]['header']['layout'] = 'vertical';
	$datas[$j]['contents']['contents'][$i]['header']['contents'][0]['type'] = 'image';
	$datas[$j]['contents']['contents'][$i]['header']['contents'][0]['url'] = $prod[($j*10)+$i][2];    
	$datas[$j]['contents']['contents'][$i]['header']['contents'][0]['size'] = 'full';
	$datas[$j]['contents']['contents'][$i]['header']['contents'][0]['aspectRatio'] = '1.51:1';
	$datas[$j]['contents']['contents'][$i]['header']['contents'][0]['aspectMode'] = 'fit';            
	$datas[$j]['contents']['contents'][$i]['header']['contents'][1]['type'] = 'text';      
	$datas[$j]['contents']['contents'][$i]['header']['contents'][1]['text'] = $prod[($j*10)+$i][1];      
        $datas[$j]['contents']['contents'][$i]['header']['contents'][1]['weight'] = 'bold';
	$datas[$j]['contents']['contents'][$i]['header']['contents'][1]['size'] = 'xl';
	$datas[$j]['contents']['contents'][$i]['header']['contents'][1]['wrap'] = true;
	$datas[$j]['contents']['contents'][$i]['header']['contents'][2]['type'] = 'box';
	$datas[$j]['contents']['contents'][$i]['header']['contents'][2]['layout'] = 'baseline';     
	$datas[$j]['contents']['contents'][$i]['header']['contents'][2]['contents'][0]['type'] = 'text';       
	$datas[$j]['contents']['contents'][$i]['header']['contents'][2]['contents'][0]['text'] = '฿ '.$prod[($j*10)+$i][5];      
	$datas[$j]['contents']['contents'][$i]['header']['contents'][2]['contents'][0]['margin'] = 'none';  
		 if($prod[($j*10)+$i][6]<$prod[($j*10)+$i][5]) {
	$datas[$j]['contents']['contents'][$i]['header']['contents'][2]['contents'][1]['type'] = 'text';       
	$datas[$j]['contents']['contents'][$i]['header']['contents'][2]['contents'][1]['text'] = 'Now ฿'.$prod[($j*10)+$i][6].' !!!';               
	$datas[$j]['contents']['contents'][$i]['header']['contents'][2]['contents'][1]['weight'] = 'bold';
	$datas[$j]['contents']['contents'][$i]['header']['contents'][2]['contents'][1]['color'] = '#FF0000';  }	
	$datas[$j]['contents']['contents'][$i]['header']['contents'][3]['type'] = 'box';
	$datas[$j]['contents']['contents'][$i]['header']['contents'][3]['layout'] = 'vertical';
	$datas[$j]['contents']['contents'][$i]['header']['contents'][3]['contents'][0]['type'] = 'text';
	$datas[$j]['contents']['contents'][$i]['header']['contents'][3]['contents'][0]['text'] = $prod[($j*10)+$i][4];    
	$datas[$j]['contents']['contents'][$i]['header']['contents'][3]['contents'][0]['wrap'] = true;
	$datas[$j]['contents']['contents'][$i]['footer']['type'] = 'box';
	$datas[$j]['contents']['contents'][$i]['footer']['layout'] = 'vertical';
	$datas[$j]['contents']['contents'][$i]['footer']['contents'][0]['type'] = 'button';
	$datas[$j]['contents']['contents'][$i]['footer']['contents'][0]['action']['type'] = 'postback';      
	$datas[$j]['contents']['contents'][$i]['footer']['contents'][0]['action']['label'] = 'เลือกสีและไซส์';
	$datas[$j]['contents']['contents'][$i]['footer']['contents'][0]['action']['text'] = 'view more';      
	$datas[$j]['contents']['contents'][$i]['footer']['contents'][0]['action']['data'] = 'View '.$prod[($j*10)+$i][0];
	$datas[$j]['contents']['contents'][$i]['footer']['contents'][0]['color'] = '#E5352E';      
	$datas[$j]['contents']['contents'][$i]['footer']['contents'][0]['style'] = 'primary';
	$datas[$j]['contents']['contents'][$i]['footer']['contents'][1]['type'] = 'button';
	$datas[$j]['contents']['contents'][$i]['footer']['contents'][1]['action']['type'] = 'postback';      
	$datas[$j]['contents']['contents'][$i]['footer']['contents'][1]['action']['label'] = 'Favorite';
	$datas[$j]['contents']['contents'][$i]['footer']['contents'][1]['action']['text'] = 'บันทึกเป็น Favorite';      
	$datas[$j]['contents']['contents'][$i]['footer']['contents'][1]['action']['data'] = 'Favorite '.$prod[($j*10)+$i][0];
	$datas[$j]['contents']['contents'][$i]['footer']['contents'][1]['color'] = '#D1D1D1';      
	$datas[$j]['contents']['contents'][$i]['footer']['contents'][1]['style'] = 'primary';	

     }
   }
	$last_carousel = $num_carousel-($num_set*10);
	   
	$datas[$num_set]['type'] = 'flex';
    	$datas[$num_set]['altText'] = 'Flex Message';
    	$datas[$num_set]['contents']['type'] = 'carousel';   
	   
	for ($i=0; $i<$last_carousel;$i++)
     {
	$datas[$num_set]['contents']['contents'][$i]['type'] = 'bubble';
    	$datas[$num_set]['contents']['contents'][$i]['direction'] = 'ltr';
	$datas[$num_set]['contents']['contents'][$i]['header']['type'] = 'box';
	$datas[$num_set]['contents']['contents'][$i]['header']['layout'] = 'vertical';
	$datas[$num_set]['contents']['contents'][$i]['header']['contents'][0]['type'] = 'image';
	$datas[$num_set]['contents']['contents'][$i]['header']['contents'][0]['url'] = $prod[($num_set*10)+$i][2];    
	$datas[$num_set]['contents']['contents'][$i]['header']['contents'][0]['size'] = 'full';
	$datas[$num_set]['contents']['contents'][$i]['header']['contents'][0]['aspectRatio'] = '1.51:1';
	$datas[$num_set]['contents']['contents'][$i]['header']['contents'][0]['aspectMode'] = 'fit';            
	$datas[$num_set]['contents']['contents'][$i]['header']['contents'][1]['type'] = 'text';      
	$datas[$num_set]['contents']['contents'][$i]['header']['contents'][1]['text'] = $prod[($num_set*10)+$i][1];      
        $datas[$num_set]['contents']['contents'][$i]['header']['contents'][1]['weight'] = 'bold';
	$datas[$num_set]['contents']['contents'][$i]['header']['contents'][1]['size'] = 'xl';
	$datas[$num_set]['contents']['contents'][$i]['header']['contents'][1]['wrap'] = true;
	$datas[$num_set]['contents']['contents'][$i]['header']['contents'][2]['type'] = 'box';
	$datas[$num_set]['contents']['contents'][$i]['header']['contents'][2]['layout'] = 'baseline';     
	$datas[$num_set]['contents']['contents'][$i]['header']['contents'][2]['contents'][0]['type'] = 'text';       
	$datas[$num_set]['contents']['contents'][$i]['header']['contents'][2]['contents'][0]['text'] = '฿ '.$prod[($num_set*10)+$i][5];      
	$datas[$num_set]['contents']['contents'][$i]['header']['contents'][2]['contents'][0]['margin'] = 'none';  
		 if($prod[($num_set*10)+$i][6]<$prod[($num_set*10)+$i][5]) {
	$datas[$num_set]['contents']['contents'][$i]['header']['contents'][2]['contents'][1]['type'] = 'text';       
	$datas[$num_set]['contents']['contents'][$i]['header']['contents'][2]['contents'][1]['text'] = 'Now ฿'.$prod[($num_set*10)+$i][6].' !!!';               
	$datas[$num_set]['contents']['contents'][$i]['header']['contents'][2]['contents'][1]['weight'] = 'bold';
	$datas[$num_set]['contents']['contents'][$i]['header']['contents'][2]['contents'][1]['color'] = '#FF0000'; }	
	$datas[$num_set]['contents']['contents'][$i]['header']['contents'][3]['type'] = 'box';
	$datas[$num_set]['contents']['contents'][$i]['header']['contents'][3]['layout'] = 'vertical';
	$datas[$num_set]['contents']['contents'][$i]['header']['contents'][3]['contents'][0]['type'] = 'text';
	$datas[$num_set]['contents']['contents'][$i]['header']['contents'][3]['contents'][0]['text'] = $prod[($num_set*10)+$i][4];    
	$datas[$num_set]['contents']['contents'][$i]['header']['contents'][3]['contents'][0]['wrap'] = true;
	$datas[$num_set]['contents']['contents'][$i]['footer']['type'] = 'box';
	$datas[$num_set]['contents']['contents'][$i]['footer']['layout'] = 'vertical';
	$datas[$num_set]['contents']['contents'][$i]['footer']['contents'][0]['type'] = 'button';
	$datas[$num_set]['contents']['contents'][$i]['footer']['contents'][0]['action']['type'] = 'postback';      
	$datas[$num_set]['contents']['contents'][$i]['footer']['contents'][0]['action']['label'] = 'เลือกสีและไซส์';
	$datas[$num_set]['contents']['contents'][$i]['footer']['contents'][0]['action']['text'] = 'view more';      
	$datas[$num_set]['contents']['contents'][$i]['footer']['contents'][0]['action']['data'] = 'View '.$prod[($num_set*10)+$i][0];
	$datas[$num_set]['contents']['contents'][$i]['footer']['contents'][0]['color'] = '#E5352E';      
	$datas[$num_set]['contents']['contents'][$i]['footer']['contents'][0]['style'] = 'primary';
	$datas[$num_set]['contents']['contents'][$i]['footer']['contents'][1]['type'] = 'button';
	$datas[$num_set]['contents']['contents'][$i]['footer']['contents'][1]['action']['type'] = 'postback';      
	$datas[$num_set]['contents']['contents'][$i]['footer']['contents'][1]['action']['label'] = 'Favorite';
	$datas[$num_set]['contents']['contents'][$i]['footer']['contents'][1]['action']['text'] = 'บันทึกเป็น Favorite';      
	$datas[$num_set]['contents']['contents'][$i]['footer']['contents'][1]['action']['data'] = 'Favorite '.$prod[($num_set*10)+$i][0];
	$datas[$num_set]['contents']['contents'][$i]['footer']['contents'][1]['color'] = '#D1D1D1';      
	$datas[$num_set]['contents']['contents'][$i]['footer']['contents'][1]['style'] = 'primary';		

     }
   return $datas;
   }
}


function carousel_view_more($db,$prod_id) 
{
  
  $pd_name = pg_fetch_row(pg_query($db,"SELECT prod_name FROM product WHERE prod_id = '$prod_id'"))[0];
  $pd_des = pg_fetch_row(pg_query($db,"SELECT prod_description FROM product WHERE prod_id = '$prod_id'"))[0];
  $pd_pro_price = pg_fetch_row(pg_query($db,"SELECT prod_pro_price FROM product WHERE prod_id = '$prod_id'"))[0];
  $pd_price = pg_fetch_row(pg_query($db,"SELECT prod_price FROM product WHERE prod_id = '$prod_id'"))[0];
  $pd_sku = pg_query($db,"SELECT * FROM stock WHERE stock.prod_id = '$prod_id'");
  //$list = pg_fetch_row($pd_sku);
  $num_carousel = pg_num_rows($pd_sku);
  $sku = array();
  $sku_num =0;
  //$times = $num_carousel/10;
   $running = 0;
   $carousel = array();
   while($list = pg_fetch_row($pd_sku))
	{/*
	   	if($list[2] == 0)
		{
			$msg = 'out of stock'; 
			$sku[$sku_num] = [$list[0],$list[1],$msg,$list[3],$list[4],$list[5]];
		}
	   	else { */ $sku[$sku_num] = $list; //}
		$sku_num++;
	}
  if($num_carousel <=10)
   {
	$datas = [];
    	$datas['type'] = 'flex';
    	$datas['altText'] = 'Flex Message';
    	$datas['contents']['type'] = 'carousel';

      for ($i=0; $i<$num_carousel;$i++)
     {
        $datas['contents']['contents'][$i]['type'] = 'bubble';
    	$datas['contents']['contents'][$i]['direction'] = 'ltr';
	$datas['contents']['contents'][$i]['header']['type'] = 'box';
	$datas['contents']['contents'][$i]['header']['layout'] = 'vertical';
	$datas['contents']['contents'][$i]['header']['contents'][0]['type'] = 'image';
	$datas['contents']['contents'][$i]['header']['contents'][0]['url'] = $sku[$i][5];     
	$datas['contents']['contents'][$i]['header']['contents'][0]['size'] = 'full';
	$datas['contents']['contents'][$i]['header']['contents'][0]['aspectRatio'] = '1.51:1';
	$datas['contents']['contents'][$i]['header']['contents'][0]['aspectMode'] = 'fit';             
	$datas['contents']['contents'][$i]['header']['contents'][1]['type'] = 'text';      
	$datas['contents']['contents'][$i]['header']['contents'][1]['text'] = 'รหัสสินค้า '.$sku[$i][0].' '.$pd_name;      
        $datas['contents']['contents'][$i]['header']['contents'][1]['weight'] = 'bold';
	$datas['contents']['contents'][$i]['header']['contents'][1]['size'] = 'xl';
	$datas['contents']['contents'][$i]['header']['contents'][1]['wrap'] = true;
	$datas['contents']['contents'][$i]['header']['contents'][2]['type'] = 'box';
	$datas['contents']['contents'][$i]['header']['contents'][2]['layout'] = 'baseline';     
	$datas['contents']['contents'][$i]['header']['contents'][2]['contents'][0]['type'] = 'text';       
	$datas['contents']['contents'][$i]['header']['contents'][2]['contents'][0]['text'] = '฿ '.$pd_price;      
	$datas['contents']['contents'][$i]['header']['contents'][2]['contents'][0]['margin'] = 'none';  
	      if($pd_pro_price<$pd_price) {
	$datas['contents']['contents'][$i]['header']['contents'][2]['contents'][1]['type'] = 'text';       
	$datas['contents']['contents'][$i]['header']['contents'][2]['contents'][1]['text'] = 'Now ฿'.$pd_pro_price.' !!!';               
	$datas['contents']['contents'][$i]['header']['contents'][2]['contents'][1]['weight'] = 'bold';
	$datas['contents']['contents'][$i]['header']['contents'][2]['contents'][1]['color'] = '#FF0000'; }
	$datas['contents']['contents'][$i]['header']['contents'][3]['type'] = 'box';
	$datas['contents']['contents'][$i]['header']['contents'][3]['layout'] = 'vertical';
	$datas['contents']['contents'][$i]['header']['contents'][3]['contents'][0]['type'] = 'text';
	$datas['contents']['contents'][$i]['header']['contents'][3]['contents'][0]['text'] = $pd_des;     
	$datas['contents']['contents'][$i]['header']['contents'][3]['contents'][0]['wrap'] = true;   
	$datas['contents']['contents'][$i]['header']['contents'][3]['contents'][1]['type'] = 'text';
	$datas['contents']['contents'][$i]['header']['contents'][3]['contents'][1]['text'] = 'size: '.$sku[$i][4];     
	$datas['contents']['contents'][$i]['header']['contents'][3]['contents'][2]['type'] = 'text';
	$datas['contents']['contents'][$i]['header']['contents'][3]['contents'][2]['text'] = $sku[$i][3]; //สี
	$datas['contents']['contents'][$i]['header']['contents'][3]['contents'][3]['type'] = 'text';
	$datas['contents']['contents'][$i]['header']['contents'][3]['contents'][3]['text'] = 'stock: '.$sku[$i][2];
	$datas['contents']['contents'][$i]['footer']['type'] = 'box';
	$datas['contents']['contents'][$i]['footer']['layout'] = 'vertical';
	$datas['contents']['contents'][$i]['footer']['contents'][0]['type'] = 'button';      
	$datas['contents']['contents'][$i]['footer']['contents'][0]['action']['type'] = 'postback';      
	$datas['contents']['contents'][$i]['footer']['contents'][0]['action']['label'] = 'สั่งลงตะกร้า';
	$datas['contents']['contents'][$i]['footer']['contents'][0]['action']['text'] = 'บันทึก'.$pd_name.' '.$sku[$i][3].'size '.$sku[$i][4].' ลงตะกร้า 1 ชิ้น';     
	$datas['contents']['contents'][$i]['footer']['contents'][0]['action']['data'] = 'Cart '.$sku[$i][0];
	$datas['contents']['contents'][$i]['footer']['contents'][0]['color'] = '#E5352E';      
	$datas['contents']['contents'][$i]['footer']['contents'][0]['style'] = 'primary';
	$datas['contents']['contents'][$i]['footer']['contents'][1]['type'] = 'box';
	$datas['contents']['contents'][$i]['footer']['contents'][1]['layout'] = 'horizontal';  
	$datas['contents']['contents'][$i]['footer']['contents'][1]['contents'][0]['type'] = 'button';      
	$datas['contents']['contents'][$i]['footer']['contents'][1]['contents'][0]['action']['type'] = 'message';
	$datas['contents']['contents'][$i]['footer']['contents'][1]['contents'][0]['action']['label'] = 'สั่งเกิน 1 ชิ้น';      
	$datas['contents']['contents'][$i]['footer']['contents'][1]['contents'][0]['action']['text'] = 'กรุณาพิมพ์ "=รหัสสินค้า เว้นวรรค ตามด้วยจำนวนสินค้าที่ต้องการ" เช่น =A1 4';            
	$datas['contents']['contents'][$i]['footer']['contents'][1]['contents'][0]['color'] = '#E5352E';    
	$datas['contents']['contents'][$i]['footer']['contents'][1]['contents'][1]['type'] = 'button';      
	$datas['contents']['contents'][$i]['footer']['contents'][1]['contents'][1]['action']['type'] = 'message';
	$datas['contents']['contents'][$i]['footer']['contents'][1]['contents'][1]['action']['label'] = 'ดูสินค้าอื่น';      
	$datas['contents']['contents'][$i]['footer']['contents'][1]['contents'][1]['action']['text'] = 'ค้นหาสินค้า';           
	$datas['contents']['contents'][$i]['footer']['contents'][1]['contents'][1]['color'] = '#4B4848';      
	 
	  
     }
    
     return $datas;
   }
   else
   {
   $num_set = floor($num_carousel/10);
   $datas = [];
   for($j=0;$j<$num_set;$j++)
   {
        $datas[$j]['type'] = 'flex';
    	$datas[$j]['altText'] = 'Flex Message';
    	$datas[$j]['contents']['type'] = 'carousel';
	
	for ($i=0; $i<10;$i++)
     {
		
	$datas[$j]['contents']['contents'][$i]['type'] = 'bubble';
    	$datas[$j]['contents']['contents'][$i]['direction'] = 'ltr';
	$datas[$j]['contents']['contents'][$i]['header']['type'] = 'box';
	$datas[$j]['contents']['contents'][$i]['header']['layout'] = 'vertical';
	$datas[$j]['contents']['contents'][$i]['header']['contents'][0]['type'] = 'image';
	$datas[$j]['contents']['contents'][$i]['header']['contents'][0]['url'] = $sku[($j*10)+$i][5];    
	$datas[$j]['contents']['contents'][$i]['header']['contents'][0]['size'] = 'full';
	$datas[$j]['contents']['contents'][$i]['header']['contents'][0]['aspectRatio'] = '1.51:1';
	$datas[$j]['contents']['contents'][$i]['header']['contents'][0]['aspectMode'] = 'fit';            	
	$datas[$j]['contents']['contents'][$i]['header']['contents'][1]['type'] = 'text';      
	$datas[$j]['contents']['contents'][$i]['header']['contents'][1]['text'] = 'รหัสสินค้า '.$sku[($j*10)+$i][0].' '.$pd_name;      
        $datas[$j]['contents']['contents'][$i]['header']['contents'][1]['weight'] = 'bold';
	$datas[$j]['contents']['contents'][$i]['header']['contents'][1]['size'] = 'xl';
	$datas[$j]['contents']['contents'][$i]['header']['contents'][1]['wrap'] = true;
	$datas[$j]['contents']['contents'][$i]['header']['contents'][2]['type'] = 'box';
	$datas[$j]['contents']['contents'][$i]['header']['contents'][2]['layout'] = 'baseline';     
	$datas[$j]['contents']['contents'][$i]['header']['contents'][2]['contents'][0]['type'] = 'text';       
	$datas[$j]['contents']['contents'][$i]['header']['contents'][2]['contents'][0]['text'] = '฿ '.$pd_price;      
	$datas[$j]['contents']['contents'][$i]['header']['contents'][2]['contents'][0]['margin'] = 'none';  
		if($pd_pro_price<$pd_price) {
	$datas[$j]['contents']['contents'][$i]['header']['contents'][2]['contents'][1]['type'] = 'text';       
	$datas[$j]['contents']['contents'][$i]['header']['contents'][2]['contents'][1]['text'] = 'Now ฿'.$pd_pro_price.' !!!';               
	$datas[$j]['contents']['contents'][$i]['header']['contents'][2]['contents'][1]['weight'] = 'bold';
	$datas[$j]['contents']['contents'][$i]['header']['contents'][2]['contents'][1]['color'] = '#FF0000'; 	}
	$datas[$j]['contents']['contents'][$i]['header']['contents'][3]['type'] = 'box';
	$datas[$j]['contents']['contents'][$i]['header']['contents'][3]['layout'] = 'vertical';
	$datas[$j]['contents']['contents'][$i]['header']['contents'][3]['contents'][0]['type'] = 'text';
	$datas[$j]['contents']['contents'][$i]['header']['contents'][3]['contents'][0]['text'] = $pd_des;     
	$datas[$j]['contents']['contents'][$i]['header']['contents'][3]['contents'][0]['wrap'] = true;   
	$datas[$j]['contents']['contents'][$i]['header']['contents'][3]['contents'][1]['type'] = 'text';
	$datas[$j]['contents']['contents'][$i]['header']['contents'][3]['contents'][1]['text'] = 'size: '.$sku[($j*10)+$i][4];     
	$datas[$j]['contents']['contents'][$i]['header']['contents'][3]['contents'][2]['type'] = 'text';
	$datas[$j]['contents']['contents'][$i]['header']['contents'][3]['contents'][2]['text'] = $sku[($j*10)+$i][3]; //สี
	$datas[$j]['contents']['contents'][$i]['header']['contents'][3]['contents'][3]['type'] = 'text';
	$datas[$j]['contents']['contents'][$i]['header']['contents'][3]['contents'][3]['text'] = 'stock: '.$sku[($j*10)+$i][2];	
	$datas[$j]['contents']['contents'][$i]['footer']['type'] = 'box';
	$datas[$j]['contents']['contents'][$i]['footer']['layout'] = 'vertical';
	$datas[$j]['contents']['contents'][$i]['footer']['contents'][0]['type'] = 'button';      
	$datas[$j]['contents']['contents'][$i]['footer']['contents'][0]['action']['type'] = 'postback';      
	$datas[$j]['contents']['contents'][$i]['footer']['contents'][0]['action']['label'] = 'สั่งลงตะกร้า';
	$datas[$j]['contents']['contents'][$i]['footer']['contents'][0]['action']['text'] = 'บันทึก'.$pd_name.' '.$sku[($j*10)+$i][3].'size '.$sku[($j*10)+$i][4].' ลงตะกร้า 1 ชิ้น';     
	$datas[$j]['contents']['contents'][$i]['footer']['contents'][0]['action']['data'] = 'Cart '.$sku[($j*10)+$i][0];
	$datas[$j]['contents']['contents'][$i]['footer']['contents'][0]['color'] = '#E5352E';      
	$datas[$j]['contents']['contents'][$i]['footer']['contents'][0]['style'] = 'primary';
	$datas[$j]['contents']['contents'][$i]['footer']['contents'][1]['type'] = 'box';
	$datas[$j]['contents']['contents'][$i]['footer']['contents'][1]['layout'] = 'horizontal';      
	$datas[$j]['contents']['contents'][$i]['footer']['contents'][1]['contents'][0]['type'] = 'button';      
	$datas[$j]['contents']['contents'][$i]['footer']['contents'][1]['contents'][0]['action']['type'] = 'message';
	$datas[$j]['contents']['contents'][$i]['footer']['contents'][1]['contents'][0]['action']['label'] = 'สั่งเกิน 1 ชิ้น';      
	$datas[$j]['contents']['contents'][$i]['footer']['contents'][1]['contents'][0]['action']['text'] = 'กรุณาพิมพ์ "=รหัสสินค้า เว้นวรรค ตามด้วยจำนวนสินค้าที่ต้องการ" เช่น =A1 4';            
	$datas[$j]['contents']['contents'][$i]['footer']['contents'][1]['contents'][0]['color'] = '#E5352E';    
	$datas[$j]['contents']['contents'][$i]['footer']['contents'][1]['contents'][1]['type'] = 'button';      
	$datas[$j]['contents']['contents'][$i]['footer']['contents'][1]['contents'][1]['action']['type'] = 'message';
	$datas[$j]['contents']['contents'][$i]['footer']['contents'][1]['contents'][1]['action']['label'] = 'ดูสินค้าอื่น';      
	$datas[$j]['contents']['contents'][$i]['footer']['contents'][1]['contents'][1]['action']['text'] = 'ค้นหาสินค้า';           
	$datas[$j]['contents']['contents'][$i]['footer']['contents'][1]['contents'][1]['color'] = '#4B4848';	
        
		/*
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
        $datas[$j]['template']['columns'][$i]['actions'][2]['text'] = 'ดูและสั่งซื้อสินค้า'; */
     }
   }
	$last_carousel = $num_carousel-($num_set*10);
	   
	$datas[$num_set]['type'] = 'flex';
    	$datas[$num_set]['altText'] = 'Flex Message';
    	$datas[$num_set]['contents']['type'] = 'carousel';   
    
	for ($i=0; $i<$last_carousel;$i++)
     {
	$datas[$num_set]['contents']['contents'][$i]['type'] = 'bubble';
    	$datas[$num_set]['contents']['contents'][$i]['direction'] = 'ltr';
	$datas[$num_set]['contents']['contents'][$i]['header']['type'] = 'box';
	$datas[$num_set]['contents']['contents'][$i]['header']['layout'] = 'vertical';
	$datas[$num_set]['contents']['contents'][$i]['header']['contents'][0]['type'] = 'image';
	$datas[$num_set]['contents']['contents'][$i]['header']['contents'][0]['url'] = $sku[($num_set*10)+$i][5];    
	$datas[$num_set]['contents']['contents'][$i]['header']['contents'][0]['size'] = 'full';
	$datas[$num_set]['contents']['contents'][$i]['header']['contents'][0]['aspectRatio'] = '1.51:1';
	$datas[$num_set]['contents']['contents'][$i]['header']['contents'][0]['aspectMode'] = 'fit';            
	$datas[$num_set]['contents']['contents'][$i]['header']['contents'][1]['type'] = 'text';      
	$datas[$num_set]['contents']['contents'][$i]['header']['contents'][1]['text'] = 'รหัสสินค้า '.$sku[($num_set*10)+$i][0].' '.$pd_name;      
        $datas[$num_set]['contents']['contents'][$i]['header']['contents'][1]['weight'] = 'bold';
	$datas[$num_set]['contents']['contents'][$i]['header']['contents'][1]['size'] = 'xl';
	$datas[$num_set]['contents']['contents'][$i]['header']['contents'][1]['wrap'] = true;
	$datas[$num_set]['contents']['contents'][$i]['header']['contents'][2]['type'] = 'box';
	$datas[$num_set]['contents']['contents'][$i]['header']['contents'][2]['layout'] = 'baseline';     
	$datas[$num_set]['contents']['contents'][$i]['header']['contents'][2]['contents'][0]['type'] = 'text';       
	$datas[$num_set]['contents']['contents'][$i]['header']['contents'][2]['contents'][0]['text'] = '฿ '.$pd_price;      
	$datas[$num_set]['contents']['contents'][$i]['header']['contents'][2]['contents'][0]['margin'] = 'none';  
		if($pd_pro_price<$pd_price) {
	$datas[$num_set]['contents']['contents'][$i]['header']['contents'][2]['contents'][1]['type'] = 'text';       
	$datas[$num_set]['contents']['contents'][$i]['header']['contents'][2]['contents'][1]['text'] = 'Now ฿'.$pd_pro_price.' !!!';               
	$datas[$num_set]['contents']['contents'][$i]['header']['contents'][2]['contents'][1]['weight'] = 'bold';
	$datas[$num_set]['contents']['contents'][$i]['header']['contents'][2]['contents'][1]['color'] = '#FF0000'; 	}
	$datas[$num_set]['contents']['contents'][$i]['header']['contents'][3]['type'] = 'box';
	$datas[$num_set]['contents']['contents'][$i]['header']['contents'][3]['layout'] = 'vertical';
	$datas[$num_set]['contents']['contents'][$i]['header']['contents'][3]['contents'][0]['type'] = 'text';
	$datas[$num_set]['contents']['contents'][$i]['header']['contents'][3]['contents'][0]['text'] = $pd_des;    
	$datas[$num_set]['contents']['contents'][$i]['header']['contents'][3]['contents'][0]['wrap'] = true;
	$datas[$num_set]['contents']['contents'][$i]['header']['contents'][3]['contents'][1]['type'] = 'text';
	$datas[$num_set]['contents']['contents'][$i]['header']['contents'][3]['contents'][1]['text'] = 'size: '.$sku[($num_set*10)+$i][4];     
	$datas[$num_set]['contents']['contents'][$i]['header']['contents'][3]['contents'][2]['type'] = 'text';
	$datas[$num_set]['contents']['contents'][$i]['header']['contents'][3]['contents'][2]['text'] = $sku[($num_set*10)+$i][3]; //สี
	$datas[$num_set]['contents']['contents'][$i]['header']['contents'][3]['contents'][3]['type'] = 'text';
	$datas[$num_set]['contents']['contents'][$i]['header']['contents'][3]['contents'][3]['text'] = 'stock: '.$sku[($num_set*10)+$i][2];
	$datas[$num_set]['contents']['contents'][$i]['footer']['type'] = 'box';
	$datas[$num_set]['contents']['contents'][$i]['footer']['layout'] = 'vertical';
	$datas[$num_set]['contents']['contents'][$i]['footer']['contents'][0]['type'] = 'button';      
	$datas[$num_set]['contents']['contents'][$i]['footer']['contents'][0]['action']['type'] = 'postback';      
	$datas[$num_set]['contents']['contents'][$i]['footer']['contents'][0]['action']['label'] = 'สั่งลงตะกร้า';
	$datas[$num_set]['contents']['contents'][$i]['footer']['contents'][0]['action']['text'] = 'บันทึก'.$pd_name.' '.$sku[($num_set*10)+$i][3].'size '.$sku[($num_set*10)+$i][4].' ลงตะกร้า 1 ชิ้น';     
	$datas[$num_set]['contents']['contents'][$i]['footer']['contents'][0]['action']['data'] = 'Cart '.$sku[($num_set*10)+$i][0];
	$datas[$num_set]['contents']['contents'][$i]['footer']['contents'][0]['color'] = '#E5352E';      
	$datas[$num_set]['contents']['contents'][$i]['footer']['contents'][0]['style'] = 'primary';
	$datas[$num_set]['contents']['contents'][$i]['footer']['contents'][1]['type'] = 'box';
	$datas[$num_set]['contents']['contents'][$i]['footer']['contents'][1]['layout'] = 'horizontal';      
	$datas[$num_set]['contents']['contents'][$i]['footer']['contents'][1]['contents'][0]['type'] = 'button';      
	$datas[$num_set]['contents']['contents'][$i]['footer']['contents'][1]['contents'][0]['action']['type'] = 'message';
	$datas[$num_set]['contents']['contents'][$i]['footer']['contents'][1]['contents'][0]['action']['label'] = 'สั่งเกิน 1 ชิ้น';      
	$datas[$num_set]['contents']['contents'][$i]['footer']['contents'][1]['contents'][0]['action']['text'] = 'กรุณาพิมพ์ "=รหัสสินค้า เว้นวรรค ตามด้วยจำนวนสินค้าที่ต้องการ" เช่น =A1 4';            
	$datas[$num_set]['contents']['contents'][$i]['footer']['contents'][1]['contents'][0]['color'] = '#E5352E';    
	$datas[$num_set]['contents']['contents'][$i]['footer']['contents'][1]['contents'][1]['type'] = 'button';      
	$datas[$num_set]['contents']['contents'][$i]['footer']['contents'][1]['contents'][1]['action']['type'] = 'message';
	$datas[$num_set]['contents']['contents'][$i]['footer']['contents'][1]['contents'][1]['action']['label'] = 'ดูสินค้าอื่น';      
	$datas[$num_set]['contents']['contents'][$i]['footer']['contents'][1]['contents'][1]['action']['text'] = 'ค้นหาสินค้า';           
	$datas[$num_set]['contents']['contents'][$i]['footer']['contents'][1]['contents'][1]['color'] = '#4B4848';		
	
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
	
    
    if($count>=10)
    { 
	$reply_msg = ['type' => 'text', 'text' => 'คุณสามารถเพิ่มสินค้าลงตะกร้า ได้ 10 รายการเท่านั้น'];    
	file_put_contents("php://stderr", "สั่งเกิน  ===> ".json_encode($reply_msg));    
	    
      return $reply_msg;
      //end of function 	    
    }  
    
    elseif($sku_qtt_now < $cart_qtt)
    {
	$reply_msg = ['type' => 'text', 'text' => 'สินค้าในสต็อกมีจำนวน '.$sku_qtt_now.' ชิ้น กรุณาสั่งใหม่' ];
	file_put_contents("php://stderr", "ของไม่พอ  ===> ".json_encode($reply_msg));
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
        $reply_msg = ['type' => 'text', 'text' => 'เพิ่มสินค้ารหัส '.$sku_id.' จำนวน '.$cart_qtt.' ชิ้น ลงตะกร้า'];
	file_put_contents("php://stderr", "สั่งของสำเร็จ  ===> ".json_encode($reply_msg));
	return $reply_msg;
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
