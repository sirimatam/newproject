<?php

// List all supported couriers and corresponding courier code at TrackingMore.

$track = new Trackingmore;
$track = $track->getCarrierList();

// Detect a carrier by tracking code
$track = new Trackingmore;
$trackingNumber = getTracking($orderid); /// function get track
$track = $track->detectCarrier($trackingNumber);


//Create a tracking.
$track = new Trackingmore;
$extraInfo                         = array();
$extraInfo['title']                = 'iphone6';
$extraInfo['logistics_channel']   = '4PX挂号小包';
$extraInfo['customer_name']        = 'charse chen';
$extraInfo['customer_email']       = 'chasechen@gmail.com';
$extraInfo['order_id']             = '8988787987';
$extraInfo['customer_phone']       = '86 13873399982';
$extraInfo['order_create_time']    = '2018-05-11 12:00';
$extraInfo['destination_code']     = 'US';
$extraInfo['tracking_ship_date']   = time();
$extraInfo['tracking_postal_code'] = '13ES20';
$extraInfo['lang']                 = 'en';
$track = $track->createTracking('china-post','RM121516216CN',$extraInfo);










function getTracking($orderid)
{
    $trackingNumber = pg_fetch_row(pg_query($db,"SELECT order_status FROM order WHERE order_id = $orderid"))[0];
    return $trackingnumber;
}


function track_trace($db,$orderid,$post_header)
{
    $order_status = pg_fetch_row(pg_query($db,"SELECT order_status FROM order WHERE order_id = $orderid"))[0];
    $url = 'https://api.trackingmore.com/v2';
    //https://www.trackingmore.com/api-index.html
    
    
     $apikey = 'c7f2b785-3a87-4a1d-8c10-591095e49b4e';
    $header = array('Content-Type: application/json', 'Trackingmore-Api-Key: ' .$apikey);
   
    
    $trace = '';
    
     $ch = curl_init($url);
     
     curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
     curl_setopt($ch, CURLOPT_HTTPHEADER, $post_header);
     curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post, JSON_UNESCAPED_UNICODE));
     curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
     $result = curl_exec($ch);
     curl_close($ch);
     return $result;
    
    
    
    
}

?>
