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


//Get tracking results of a single tracking.
$track = new Trackingmore;
$track = $track->getSingleTrackingResult('kerry-logistics',$trackingNumber,'en');


//Get realtime tracking results of a single tracking.
$track = new Trackingmore;
$extraInfo['destination_code']          = 'US';
$extraInfo['tracking_ship_date']  = '20180226';
$extraInfo['tracking_postal_code'] = '13ES20';
$extraInfo['specialNumberDestination']       = 'US';
$extraInfo['order']       = '#123123';
$extraInfo['order_create_time']       = '2017/8/27 16:51';
$extraInfo['lang']       = 'cn';
$track = $track->getRealtimeTrackingResults('kerry-logistics',$trackingNumber,$extraInfo);





function getTracking($orderid)
{
    $trackingNumber = pg_fetch_row(pg_query($db,"SELECT order_status FROM order WHERE order_id = $orderid"))[0];
    return $trackingnumber;
}



?>
