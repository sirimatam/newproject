<?php

function send_reply_message($url, $post_header, $post_body)
{
 $ch = curl_init($url);
 curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
 curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
 curl_setopt($ch, CURLOPT_HTTPHEADER, $post_header);
 curl_setopt($ch, CURLOPT_POSTFIELDS, $post_body);
 curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
 
 $result = curl_exec($ch);
 curl_close($ch);
 return $result;
}


function show_product()
{
    $data = [
	'replyToken' => $reply_token,
	'messages' => [
[
  "type" => "flex",
  "altText" => "Flex Message",
  "contents" => [
    "type" => "bubble",
    "direction" => "ltr",
    "header" => [
      "type" => "box",
      "layout" => "vertical",
      "contents" => [
        [
          "type" => "text",
          "text" => "เลือกประเภทสินค้า",
          "align" => "center",
          "weight" => "bold"
        ]
      ]
    ],
    "body" => [
      "type" => "box",
      "layout" => "vertical",
      "contents" => [
        [
          "type" => "button",
          "action" => [
            "type" => "message",
            "label" => "สายเดี่ยว/แขนกุด",
            "text" => "เสื้อสายเดี่ยว/แขนกุด"
          ]
        ],
        [
          "type" => "button",
          "action" => [
            "type" => "message",
            "label" => "เสื้อมีแขน",
            "text" => "เสื้อมีแขน"
          ]
        ],
        [
          "type" => "button",
          "action" => [
            "type" => "message",
            "label" => "เดรส",
            "text" => "เดรส"
          ]
        ],
        [
          "type" => "button",
          "action" => [
            "type" => "message",
            "label" => "กางเกงขาสั้น",
            "text" => "กางเกงขาสั้น"
          ]
        ],
        [
          "type" => "button",
          "action" => [
            "type" => "message",
            "label" => "กางเกงขายาว",
            "text" => "กางเกงขายาว"
          ]
        ]
      ]
    ]
  ]
]
]];
$post_body = json_encode($data, JSON_UNESCAPED_UNICODE);
file_put_contents("php://stderr", "POST REQUEST =====> ".$post_body);
$send_result = send_reply_message($API_URL, $POST_HEADER, $post_body);
echo "Result: ".$send_result."\r\n";
file_put_contents("php://stderr", "POST RESULT =====> ".$send_result);
    
}


?>
