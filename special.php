<?php

function quickreply()
{
  $data = array();
  $data['messages']['type'] = 'text';
	$data['messages']['text'] = 'Hello Quick Reply!';
  $data['messages']['quickReply']['items'][0]['type'] = 'action';
  $data['messages']['quickReply']['items'][0]['action']['type'] = 'camera';
  $data['messages']['quickReply']['items'][0]['action']['label'] = 'Open camera';
  $data['messages']['quickReply']['items'][1]['type'] = 'action';
  $data['messages']['quickReply']['items'][1]['action']['type'] = 'cameraRoll';
  $data['messages']['quickReply']['items'][1]['action']['label'] = 'Send Photo';
  $data['messages']['quickReply']['items'][2]['action']['type'] = 'location';
  $data['messages']['quickReply']['items'][2]['action']['label'] = 'Location';
  $data['messages']['quickReply']['items'][3]['action']['type'] = 'datetimepicker';
  $data['messages']['quickReply']['items'][3]['action']['label'] = 'Select date and time';
  $data['messages']['quickReply']['items'][3]['action']['data'] = '12345';
  $data['messages']['quickReply']['items'][3]['action']['mode'] = 'datetime';
  
  return $data;
}






?>
