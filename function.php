<?php


$request = file_get_contents('php://input');   // Get request content
$request_array = json_decode($request, true);   // Decode JSON to Array

if ( sizeof($request_array['events']) > 0 )
{
  $reply_message = '';
  $reply_token = $event['replyToken'];ฟ
  if ( $event['type'] == 'message' ) 
  {
   if ( $event['message']['type'] == 'text' )&&($text = $event['message']['text'])){
    if ($text == "ดูและสั่งซื้อสินค้า")
    {
     bubble_all_product
    }
   
   }
 
    
}

function bubble_all_product{
$data = [
 'replyToken' => $reply_token,
 'messages' =>

}

/* ข้อ 2 */

function carousel_product_type($type) // $type = Prod_type FROM Product
{ 
  // how to check whether prod_qtt > 0
   $pd_type = pg_query("SELECT * FROM Product WHERE prod_type = $type");  
   $num_carousel = pg_num_rows($pd_type);
   $list = pg_fetch_row($pd_type);
   //$times = $num_carousel/10;
   $running = 0;
   $carousel = array();
   if($num_carousel <=10)
   {
      for ($i=0, $i<10,$i++)
     {
        $datas = [];
        $datas['columns'][$i]['thumbnailImageUrl'] = $list[$i][$prod_img]; 
        $datas['columns'][$i]['title'] = $list[$i][$prod_name];
        $datas['columns'][$i]['text'] = $list[$i][$prod_description];
        $datas['columns'][$i]['actions'][0]['type'] = 'message';
        $datas['columns'][$i]['actions'][0]['label'] = 'รายละเอียดเพิ่มเติม';
        $datas['columns'][$i]['actions'][0]['text'] = $list[$i][$prod_id];
        $datas['columns'][$i]['actions'][1]['type'] = 'message';
        $datas['columns'][$i]['actions'][1]['label'] = 'บันทึกเป็น Favorite';
        $datas['columns'][$i]['actions'][1]['text'] = 'Favorite'.$list[$i][$prod_id];   
     }
     $carousel[0] = $datas;
     return $carousel;
   }
   else
   {
   while( $running < $num_carousel)  
   {
     for ($i=0, $i<10,$i++)
     {
        $datas = [];
        $datas['columns'][$i]['thumbnailImageUrl'] = $list[$i][$prod_img]; 
        $datas['columns'][$i]['title'] = $list[$i][$prod_name];
        $datas['columns'][$i]['text'] = $list[$i][$prod_description];
        $datas['columns'][$i]['actions'][0]['type'] = 'message';
        $datas['columns'][$i]['actions'][0]['label'] = 'รายละเอียดเพิ่มเติม';
        $datas['columns'][$i]['actions'][0]['text'] = $list[$i][$prod_id];
        $datas['columns'][$i]['actions'][1]['type'] = 'message';
        $datas['columns'][$i]['actions'][1]['label'] = 'บันทึกเป็น Favorite';
        $datas['columns'][$i]['actions'][1]['text'] = 'Favorite'.$list[$i][$prod_id];  
        $running++;
     }
     $carousel[ceil($running-10)/10] = $datas;
   }
    

   }


}
  
  
  
function carousel_view_more($prod_id) 
{
  $pd_name = pg_fetch_result(pg_query('SELECT prod_name FROM Product WHERE prod_id = $prod_id'));
  $pd_des = pg_fetch_result(pg_query('SELECT prod_description FROM Product WHERE prod_id = $prod_id'));
  $pd_sku = pg_query('SELECT sku_id FROM STOCK WHERE stock.prod_id = Product.prod_id');
  $list = pg_fetch_row($pd_sku);
  $num_carousel = pg_num_rows($pd_sku);
  //$times = $num_carousel/10;
   $running = 0;
   $carousel = array();
  if($num_carousel <=10)
   {
      for ($i=0, $i<10,$i++)
     {
        $datas = [];
        $datas['columns'][$i]['thumbnailImageUrl'] = $list[$i][$sku_img]; 
        $datas['columns'][$i]['title'] = $list[$prod_name];
        $datas['columns'][$i]['text'] = $list[$i][$prod_description]."</br>".$list[$i][$sku_color]."ขนาด".$list[$i][$sku_size]."</br>".$list[$i][$sku_qtt];
        $datas['columns'][$i]['actions'][0]['type'] = 'message';
        $datas['columns'][$i]['actions'][0]['label'] = 'สั่งลงตะกร้า';
        $datas['columns'][$i]['actions'][0]['text'] = 'Cart'.$list[$i][$sku_id];
        $datas['columns'][$i]['actions'][1]['type'] = 'message';
        $datas['columns'][$i]['actions'][1]['label'] = 'ดูสินค้าอื่น';
        $datas['columns'][$i]['actions'][1]['text'] = 'ดูและสั่งซื้อสินค้า';  
     }
     $carousel[0] = $datas;
     return $carousel;
   }
   else
   {
   while( $running < $num_carousel)  
   {
     for ($i=0, $i<10,$i++)
     {
        $datas = [];
        $datas['columns'][$i]['thumbnailImageUrl'] = $list[$i][$sku_img]; 
        $datas['columns'][$i]['title'] = $list[$prod_name];
        $datas['columns'][$i]['text'] = $list[$i][$prod_description]."</br>".$list[$i][$sku_color]."ขนาด".$list[$i][$sku_size]."</br>".$list[$i][$sku_qtt];
        $datas['columns'][$i]['actions'][0]['type'] = 'message';
        $datas['columns'][$i]['actions'][0]['label'] = 'สั่งลงตะกร้า';
        $datas['columns'][$i]['actions'][0]['text'] = 'Cart'.$list[$i][$sku_id];
        $datas['columns'][$i]['actions'][1]['type'] = 'message';
        $datas['columns'][$i]['actions'][1]['label'] = 'ดูสินค้าอื่น';
        $datas['columns'][$i]['actions'][1]['text'] = 'ดูและสั่งซื้อสินค้า';    
        $running++;
     }
     $carousel[ceil($running-10)/10] = $datas;
   }  

   }

  
  
}

  
//if message['text'] == 'Favorite'.$prod_id
  
function add_favorite($prod_id,$cus_id)
  {
    /* check fav cannot more than 10 */
    $check = pg_query('SELECT * FROM Favorite WHERE Favorite.cus_id = Customer.cus_id');
    $count = pg_num_rows($check);
    if($count>=10){ return $reply_msg = 'คุณสามารถ Favorite ได้ 10 รายการเท่านั้น'}  
    //end of function
    else{
    $fave_id++;
    pg_query('INSERT INTO Favorite VALUES ($fave_id,$prod_id,$cus_id)');
    }
  }  
function carousel_show_favorite($cus_id)
  {
    $check = pg_query('SELECT * FROM Favorite WHERE Favorite.cus_id = Customer.cus_id');
   
    $list = pg_fetch_row($check);
    for ($i=0, $i<10,$i++)
     {
        $datas = [];
        $datas['columns'][$i]['thumbnailImageUrl'] = $list[$i][$sku_img]; 
        $datas['columns'][$i]['title'] = $list[$prod_name];
        $datas['columns'][$i]['text'] = $list[$i][$prod_description]."</br>".$list[$i][$sku_color]."ขนาด".$list[$i][$sku_size]."</br>".$list[$i][$sku_qtt];
        $datas['columns'][$i]['actions'][0]['type'] = 'message';
        $datas['columns'][$i]['actions'][0]['label'] = 'สั่งลงตะกร้า';
        $datas['columns'][$i]['actions'][0]['text'] = 'Cart'.$list[$i][$sku_id];
        $datas['columns'][$i]['actions'][1]['type'] = 'message';
        $datas['columns'][$i]['actions'][1]['label'] = 'ลบออกจาก Favorite';
        $datas['columns'][$i]['actions'][1]['text'] = 'delete'.$list[$i][$fav_id].'ออกจาก Favorite เรียบร้อย';  
     }
    return $datas;
  }

  
  /* if message['text'] == delete.$fav_id.'ออกจาก Favorite เรียบร้อย' */
  function delete_favorite($fav_id)
  {
    pg_query('DELETE FROM Favorite WHERE fav_id = $fav_id');
  }
    
    
    
    
    
    
    
    
    
    
    
  }
  
  
  
  
  
  
  
  
  
  
?>
