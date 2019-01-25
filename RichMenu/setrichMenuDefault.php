<?php

$richMenuId = "richmenu-a6176f168491d2594d3b3d4d4dc0cfd9";


//set rich menu default after upload img 

$response = set_richmenu_default($richMenuId,$ACCESS_TOKEN);
file_put_contents("php://stderr", "POST JSON ===> ".$response);

function set_richmenu_default($richMenuId,$ACCESS_TOKEN)
{
	$curl = curl_init();
    	curl_setopt_array($curl, array(
      CURLOPT_URL => "https://api.line.me/v2/bot/user/all/richmenu/".$richMenuId,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_POSTFIELDS => $post_data,
      CURLOPT_HTTPHEADER => array(
        "authorization: Bearer ".$ACCESS_TOKEN,
        "cache-control: no-cache",
        "content-type: application/json; charset=UTF-8",
      ),
    ));
	
	 $result = curl_exec($curl);
	 $err = curl_error($curl);
	 curl_close($curl);
	 if ($err) {
		return $err;
	    } else {
		return $result;
	    }	
}	



?>
