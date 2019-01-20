<?php


$request = file_get_contents('php://input');   // Get request content
$request_array = json_decode($request, true);   // Decode JSON to Array

if ( sizeof($request_array['events']) > 0 )
{
  $reply_message = '';
  $reply_token = $event['replyToken'];
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
   $pd_type = pg_query("SELECT * FROM Product WHERE prod_type = $type");  
   $num_carousel = pg_num_rows($pd_type);
   $list = pg_fetch_row($pd_type);
   $times = $num_carousel/10;
   $running = 0;
   $carousel = array();
   if($num_carousel <=10)
   {
      for ($i=0, $i<10,$i++)
     {
        $datas = [];
        $datas['columns'][$i]['thumbnailImageUrl'] = list[$i][$prod_img]; 
        $datas['columns'][$i]['title'] = $list[$i][$prod_name];
        $datas['columns'][$i]['text'] = $list[$i][$prod_description];
        $datas['columns'][$i]['actions'][0]['type'] = 'message';
        $datas['columns'][$i]['actions'][0]['label'] = 'รายละเอียดเพิ่มเติม';
        $datas['columns'][$i]['actions'][0]['text'] = 'ดูเพิ่มเติม';
        $datas['columns'][$i]['actions'][1]['type'] = 'message';
        $datas['columns'][$i]['actions'][1]['label'] = 'บันทึกเป็น Favorite';
        $datas['columns'][$i]['actions'][1]['text'] = 'Favorite <3';  
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
        $datas['columns'][$i]['thumbnailImageUrl'] = list[$i][$prod_img]; 
        $datas['columns'][$i]['title'] = $list[$i][$prod_name];
        $datas['columns'][$i]['text'] = $list[$i][$prod_description];
        $datas['columns'][$i]['actions'][0]['type'] = 'message';
        $datas['columns'][$i]['actions'][0]['label'] = 'รายละเอียดเพิ่มเติม';
        $datas['columns'][$i]['actions'][0]['text'] = 'ดูเพิ่มเติม';
        $datas['columns'][$i]['actions'][1]['type'] = 'message';
        $datas['columns'][$i]['actions'][1]['label'] = 'บันทึกเป็น Favorite';
        $datas['columns'][$i]['actions'][1]['text'] = 'Favorite <3';  
        $running++;
     }
     $carousel[ceil($running-10)/10] = $datas;
   }
    

   }





}


?>
