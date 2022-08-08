<?php


$API_URL = 'https://api.line.me/v2/bot/message';
$ACCESS_TOKEN = '9OyE6AL14J0JAlGFPmaZwBot2ieTmTQ7sehJ4VAuUyVeCR/Rb3goghXxVMsOwCsvQjXXNu6Q8fx7LYiCq+CO2rvTVmHvX0M4oA5U8eJs10TSnHj6YQQUmyk9XtvEGdAw1bzrBLboQBdT3OvmMjrZBwdB04t89/1O/w1cDnyilFU='; 
$channelSecret = '9cc369a589e4c8b1ad0e6f8cfc09b7b2';


$POST_HEADER = array('Content-Type: application/json', 'Authorization: Bearer ' . $ACCESS_TOKEN);

$request = file_get_contents('php://input');   // Get request content
$request_array = json_decode($request, true);   // Decode JSON to Array
var_export($request_array);

$nowtimespc = date("H:i:s");
  $nowdatespc = date("Y-m-d");
  $datetimemixspc = strtotime($nowdatespc.' '.$nowtimespc);
  $dtmixspcinsert = date('Y-m-d H:i:s', $datetimemixspc);
  $LINEData = file_get_contents('php://input');
  $jsonData = json_decode($LINEData,true);
  $replyToken = $jsonData["events"][0]["replyToken"];
  $text = $jsonData["events"][0]["message"]["text"];
  $userId = $jsonData["events"][0]["source"]['userId'];
  $timestamp = $jsonData["events"][0]["timestamp"];
  $type = $jsonData["events"][0]["message"]['type'];
  $beacon_type = $jsonData["events"][0]["beacon"]['type'];
  $beacon_hwid = $jsonData["events"][0]["beacon"]['hwid'];
  
  $curl = curl_init();


  function sendMessage($replyJson, $token){
          $ch = curl_init($token["URL"]);
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
          curl_setopt($ch, CURLINFO_HEADER_OUT, true);
          curl_setopt($ch, CURLOPT_POST, true);
          curl_setopt($ch, CURLOPT_HTTPHEADER, array(
              'Content-Type: application/json',
              'Authorization: Bearer ' . $token["AccessToken"])
              );
          curl_setopt($ch, CURLOPT_POSTFIELDS, $replyJson);
          $result = curl_exec($ch);
          curl_close($ch);
    return $result;
  }
  
  if($type="beacon"){
      if($beacon_type=="enter"){
        
          
          $sqlmember = "SELECT `member_userid`, `member_name` FROM `data_member` WHERE `member_userid` = '$userId'";
          $ressqlmember = $connectdb->query($sqlmember);
          $row = mysqli_fetch_assoc($ressqlmember);

          $member_name = $row['member_name'];
          $sql = "INSERT INTO `savedata`(`beacon_userid`,`beacon_hwid`,beacon_time) VALUES('$userId','$beacon_hwid','$dtmixspcinsert')";
          $res = $connectdb->query($sql);
          
          $message = '{
            "type": "text",
            "text": "สวัสดีคุณ '.$member_name.'"
            }';
          $replymessage = json_decode($message);
      }
     
  }



  $lineData['URL'] = "https://api.line.me/v2/bot/message/reply";
  $lineData['AccessToken'] = "9OyE6AL14J0JAlGFPmaZwBot2ieTmTQ7sehJ4VAuUyVeCR/Rb3goghXxVMsOwCsvQjXXNu6Q8fx7LYiCq+CO2rvTVmHvX0M4oA5U8eJs10TSnHj6YQQUmyk9XtvEGdAw1bzrBLboQBdT3OvmMjrZBwdB04t89/1O/w1cDnyilFU=";
  $replyJson["replyToken"] = $replyToken;
  $replyJson["messages"][0] = $replymessage;

  $encodeJson = json_encode($replyJson);
  $results = sendMessage($encodeJson,$lineData);

  http_response_code(200);
?>
