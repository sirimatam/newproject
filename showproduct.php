<?php

	   

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
	
   $promo_list = pg_fetch_row($promo_top);
   $running = 0;
   $carousel = array();
   
      for ($i=0, $i<=$promo_num,$i++)
     {
        $datas = [];
        $datas['columns'][$i]['thumbnailImageUrl'] = $promo_list[$i][$prod_img]; 
        $datas['columns'][$i]['title'] = $promo_list[$i][$prod_name];
        $datas['columns'][$i]['text'] = $promo_list[$i][$prod_description];
        $datas['columns'][$i]['actions'][0]['type'] = 'message';
        $datas['columns'][$i]['actions'][0]['label'] = 'รายละเอียดเพิ่มเติม';
        $datas['columns'][$i]['actions'][0]['text'] = $list[$i][$prod_id];
        $datas['columns'][$i]['actions'][1]['type'] = 'message';
        $datas['columns'][$i]['actions'][1]['label'] = 'บันทึกเป็น Favorite';
        $datas['columns'][$i]['actions'][1]['text'] = 'Favorite'.$promo_list[$i][$prod_id];   
     }
     $carousel[$i] = $datas;
     return $carousel;
   
}   
	   
	   
	   
?>
