<?php
/*
$richMenuId1 = "richmenu-ff58dd0a3a6e5f68cfc40afae5abe6ad"; //page1
$richMenuId2= "richmenu-717a8ebccd0d4a7e0ca2c85d77a50f10"; //page2
*/

//set rich menu default after upload img 

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
