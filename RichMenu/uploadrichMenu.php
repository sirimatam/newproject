<?php

$richMenuId:"richmenu-a6176f168491d2594d3b3d4d4dc0cfd9;

$rich_img_url = 'https://api.line.me/v2/bot/richmenu/'.$richMenuId.'/content';

$ACCESS_TOKEN = getTokenData();
$file = fopen('richmenu.jpg', 'r');
$size = filesize('richmenu.jpg');
$fildata = fread($file,$size);
$curl = curl_init();
	curl_setopt_array($curl, array(
	    CURLOPT_URL => "https://api.line.me/v2/bot/richmenu/".$richMenuId."/content",
	    CURLOPT_RETURNTRANSFER => true,
	    CURLOPT_BINARYTRANSFER => true,
	    CURLOPT_ENCODING => "",
	    CURLOPT_MAXREDIRS => 10,
	    CURLOPT_TIMEOUT => 30,
	    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	    CURLOPT_CUSTOMREQUEST => "POST",
	    CURLOPT_POSTFIELDS => $fildata,
	    CURLOPT_INFILE => $file,
	    CURLOPT_HTTPHEADER => array(
	      "authorization: Bearer ".$ACCESS_TOKEN,
	      "Cache-Control: no-cache",
	      "Content-Type: image/png",
	    ),
	));
  
	$response = curl_exec($curl);
	$err = curl_error($curl);
	curl_close($curl);
  
  
	if ($err) {
        var_dump($err);
    } else {
    	var_dump($response);
    }


?>
