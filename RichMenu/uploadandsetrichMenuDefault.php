<?php

$richMenuId1 = "richmenu-ff58dd0a3a6e5f68cfc40afae5abe6ad";  // page1
$richMenuId2 = "richmenu-b5605d39250019a4ad9734dffc7d23ef"; //page2 old
$richMenuId2 = "richmenu-cc0063317dd8d062a9be46ef81a8e717"; // new
/*
$file1 = fopen('image/firstpage.png', 'r');
$size1 = filesize('image/firstpage.png');


$fildata1 = fread($file1,$size1);
$upload_pic1 = upload_richmenu($richMenuId1,$ACCESS_TOKEN,$fildata1,$file1);
file_put_contents("php://stderr", "POST JSON1 ===> ".$upload_pic1);

$file2 = fopen('image/secondpage2.png', 'r');
$size2 = filesize('image/secondpage2.png');


$fildata2 = fread($file2,$size2);
$upload_pic2 = upload_richmenu($richMenuId2,$ACCESS_TOKEN,$fildata2,$file2);
file_put_contents("php://stderr", "POST JSON2 ===> ".$upload_pic2);
*/	


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


//set rich menu default after upload img 


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
      //CURLOPT_POST => 1,
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

function unlink_richmenu($userid,$ACCESS_TOKEN)
{
	$curl = curl_init();
    	curl_setopt_array($curl, array(
      CURLOPT_URL => "https://api.line.me/v2/bot/user/".$userid."/richmenu",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "DELETE",
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
