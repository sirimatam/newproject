<?php

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

/*      https://www.trackingmore.com/api-class_php.html  
        https://www.trackingmore.com/api-php.html
        https://www.trackingmore.com/api-index.html
   */

/**
	* Get tracking results of a single tracking
	* @access public
	* @param string $trackingNumber  Tracking number
	* @param string $carrierCode Carrier code
	* @param string $lang language
	* @return array
	*/
	public function getSingleTrackingResult($carrierCode,$trackingNumber,$lang='en'){
        $returnData = array();
        $requestUrl = self::ROUTE_TRACKINGS.'/'.$carrierCode.'/'.$trackingNumber.'/'.$lang;
        $result = $this->_getApiData($requestUrl, 'GET');
        if ($result) {
            $returnData = json_decode($result, true);
        }
        return $returnData;
    }
?>