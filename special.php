<?php

function quickreplytest()
{
  $data = array();
  $data['type'] = 'text';
  $data['text'] = 'Hello Quick Reply!';
  $data['quickReply']['items'][0]['type'] = 'action';
  $data['quickReply']['items'][0]['action']['type'] = 'camera';
  $data['quickReply']['items'][0]['action']['label'] = 'Open camera';
  $data['quickReply']['items'][1]['type'] = 'action';
  $data['quickReply']['items'][1]['action']['type'] = 'cameraRoll';
  $data['quickReply']['items'][1]['action']['label'] = 'Select Photo';
  $data['quickReply']['items'][2]['action']['type'] = 'location';
  $data['quickReply']['items'][2]['action']['label'] = 'Location';
  $data['quickReply']['items'][3]['action']['type'] = 'datetimepicker';
  $data['quickReply']['items'][3]['action']['label'] = 'Select date and time';
  $data['quickReply']['items'][3]['action']['data'] = '12345';
  $data['quickReply']['items'][3]['action']['mode'] = 'datetime'; 
  
  return $data;

  
  /*
  https://cloudinary.com/documentation/upload_images#uploading_images_to_the_cloud
  https://cloudinary.com/documentation/upload_images#how_to_set_read_access_on_a_private_amazon_s3_bucket
  
  */
  
}

function upload_quickreply()
{
  $data = array();
  $data['type'] = 'text';
  $data['text'] = 'อัพโหลด หรือ เปิดกล้องเพื่อถ่ายรูปสลิป';
  $data['quickReply']['items'][0]['type'] = 'action';
  $data['quickReply']['items'][0]['action']['type'] = 'cameraRoll';
  $data['quickReply']['items'][0]['action']['label'] = 'Select Photo';
  $data['quickReply']['items'][1]['type'] = 'action';
  $data['quickReply']['items'][1]['action']['type'] = 'camera';
  $data['quickReply']['items'][1]['action']['label'] = 'Open camera';


  
  return $data;






?>
