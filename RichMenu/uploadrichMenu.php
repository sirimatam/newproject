<?php

$richMenuId:"richmenu-03bd2f91fb7c3f83ff305ea1812abfee";  // page1

$rich_img_url = 'https://api.line.me/v2/bot/richmenu/'.$richMenuId.'/content';


$file = fopen('/image/firstpage.png', 'r');
$size = filesize('/image/firstpage.png');
$fildata = fread($file,$size);
$upload_pic = upload_richmenu($richMenuId,$ACCESS_TOKEN,$fildata,$file);
file_put_contents("php://stderr", "POST JSON ===> ".$upload_pic);
	


function upload_richmenu($richMenuId,$ACCESS_TOKEN,$fildata,$file)
{
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
               "cache-control: no-cache",
	       "Content-Type: image/png",
	 	
	    ),
	));
  
	$response = curl_exec($curl);
	$err = curl_error($curl);
	curl_close($curl);
  
  
	if ($err) {
         return $err;
    } else {
    	return $response;
    }
}	 


?>
