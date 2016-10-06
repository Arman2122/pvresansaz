<?php

define('BOT_TOKEN', '275233977:AAEYqYuqJTSRzMAZ11AoxQptzYt04JL8o5M');
define('API_URL', 'https://api.telegram.org/bot'.BOT_TOKEN.'/');

function apiRequestWebhook($method, $parameters) {
  if (!is_string($method)) {
    error_log("Method name must be a string\n");
    return false;
  }

  if (!$parameters) {
    $parameters = array();
  } else if (!is_array($parameters)) {
    error_log("Parameters must be an array\n");
    return false;
  }

  $parameters["method"] = $method;

  header("Content-Type: application/json");
  echo json_encode($parameters);
  return true;
}

function exec_curl_request($handle) {
  $response = curl_exec($handle);

  if ($response === false) {
    $errno = curl_errno($handle);
    $error = curl_error($handle);
    error_log("Curl returned error $errno: $error\n");
    curl_close($handle);
    return false;
  }

  $http_code = intval(curl_getinfo($handle, CURLINFO_HTTP_CODE));
  curl_close($handle);

  if ($http_code >= 500) {
    // do not wat to DDOS server if something goes wrong
    sleep(10);
    return false;
  } else if ($http_code != 200) {
    $response = json_decode($response, true);
    error_log("Request has failed with error {$response['error_code']}: {$response['description']}\n");
    if ($http_code == 401) {
 throw new Exception('Invalid access token provided');
    }
    return false;
  } else {
    $response = json_decode($response, true);
    if (isset($response['description'])) {
      error_log("Request was successfull: {$response['description']}\n");
    }
    $response = $response['result'];
  }

  return $response;
}

function apiRequest($method, $parameters) {
  if (!is_string($method)) {
    error_log("Method name must be a string\n");
    return false;
  }

  if (!$parameters) {
    $parameters = array();
  } else if (!is_array($parameters)) {
    error_log("Parameters must be an array\n");
    return false;
  }

  foreach ($parameters as $key => &$val) {
    // encoding to JSON array parameters, for example reply_markup
    if (!is_numeric($val) && !is_string($val)) {
      $val = json_encode($val);
    }
  }
  $url = API_URL.$method.'?'.http_build_query($parameters);

  $handle = curl_init($url);
  curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 5);
  curl_setopt($handle, CURLOPT_TIMEOUT, 60);

  return exec_curl_request($handle);
}

function apiRequestJson($method, $parameters) {
  if (!is_string($method)) {
 error_log("Method name must be a string\n");
    return false;
  }

  if (!$parameters) {
    $parameters = array();
  } else if (!is_array($parameters)) {
    error_log("Parameters must be an array\n");
    return false;
  }

  $parameters["method"] = $method;

  $handle = curl_init(API_URL);
  curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 5);
  curl_setopt($handle, CURLOPT_TIMEOUT, 60);
  curl_setopt($handle, CURLOPT_POSTFIELDS, json_encode($parameters));
  curl_setopt($handle, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));

  return exec_curl_request($handle);
}

function processMessage($message) {
  // process incoming message
  $message_id = $message['message_id'];
  $chat_id = $message['chat']['id'];
  if (isset($message['text'])) {
    // incoming text message
    $text = $message['text'];
    $admin = 192050231;
    $matches = explode(' ', $text);
    $substr = substr($text, 0,7 );
    if (strpos($text, "/start") === 0) {
        apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => 'با سلام
        ربات پیام رسان ساز تیم umbrella copy
        برای ساخت ربات از بات فادر ربات خود را ساخته و سپس توکن رو دریافت کرده و توکن رو مثل متن زیر به ربات بدهید

به عنوان مثال :
`123456789:ABCDE1FGHIJ5KLMNO5PQRS`
*umbrella copy team*

[umbrella copy team](https://telegram.me/Umbrellacopy)',"parse_mode"=>"MARKDOWN","disable_web_page_preview"=>"true"));


$txxt = file_get_contents('pmembers.txt');
$pmembersid= explode("\n",$txxt);
	if (!in_array($chat_id,$pmembersid)) {
		$aaddd = file_get_contents('pmembers.txt');
		$aaddd .= $chat_id."
";
    	file_put_contents('pmembers.txt',$aaddd);
}
        if($chat_id == 167528733)
        {
          if(!file_exists('tokens.txt')){
        file_put_contents('tokens.txt',"");
           }
        $tokens = file_get_contents('tokens.txt');
        $part = explode("\n",$tokens);
       $tcount =  count($part)-1;

      apiRequestWebhook("sendMessage", array('chat_id' => $chat_id,  "text" => " تعداد کل ربات های آنلاین که توسط ربات پیام رسان ساز umbrella copy bot ساخته شده <code>".$tcount."</code>","parse_mode"=>"HTML"));

        }
    }else if ($text == "Version") {
      apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => "<b>umbrella copy bot</b>
<b>ver. 1.2</b>
<code>Coded By</code> @A_P_P_L_E
[umbrella copy team](https://telegram.me/umbrellacopy)","parse_mode"=>"html"));
    }
    else if ($matches[0] == "/update"&& strpos($matches[1], ":")) {
      
    $txtt = file_get_contents('tokenstoupdate.txt');
		$banid= explode("\n",$txtt);
		$id=$chat_id;
    if (in_array($matches[1],$banid)) {
      rmdir($chat_id);
      mkdir($id, 0700);
       file_put_contents($id.'/banlist.txt',"");
      file_put_contents($id.'/pmembers.txt',"");
      file_put_contents($id.'/msgs.txt',"سلام 😃👋
پیام خود را ارسال کنید.
-!-@-#-$
پیام ارسال شد");
        file_put_contents($id.'/booleans.txt',"false");
        $phptext = file_get_contents('phptext.txt');
        $phptext = str_replace("**TOKEN**",$matches[1],$phptext);
        $phptext = str_replace("**ADMIN**",$chat_id,$phptext);
        file_put_contents($id.'/pvresan.php',$phptext);
        file_get_contents('https://api.telegram.org/bot'.$matches[1].'$texttwebhook?url=');
        file_get_contents('https://api.telegram.org/bot'.$matches[1].'/setwebhook?url=http://pvresansaz-face1.rhcloud.com/'.$chat_id.'/pvresan.php');
apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => "umbrella copy team ربات شما با مـوفقیت آپدیت شد ♻️"));


    }
    }
    else if ($matches[0] != "/update"&& $matches[1]==""&&$chat_id != 69367395) {
      if (strpos($text, ":")) {
apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => " تدر حال برسس توکن شما توسط بات umbrella copy bot"));
    $url = "http://api.telegram.org/bot".$matches[0]."/getme";
    $json = file_get_contents($url);
    $json_data = json_decode($json, true);
    $id = $chat_id;
    
   $txt = file_get_contents('lastmembers.txt');
    $membersid= explode("\n",$txt);
    
    if($json_data["result"]["username"]!=null){
      
      if(file_exists($id)==false && in_array($chat_id,$membersid)==false){
          

        $aaddd = file_get_contents('tokens.txt');
                $aaddd .= $text."
";
        file_put_contents('tokens.txt',$aaddd);

     mkdir($id, 0700);
        file_put_contents($id.'/banlist.txt',"");
        file_put_contents($id.'/pmembers.txt',"");
        file_put_contents($id.'/booleans.txt',"false");
        $phptext = file_get_contents('phptext.txt');
        $phptext = str_replace("**TOKEN**",$text,$phptext);
        $phptext = str_replace("**ADMIN**",$chat_id,$phptext);
        file_put_contents($token.$id.'/pvresan.php',$phptext);
        file_get_contents('https://api.telegram.org/bot'.$text.'/setwebhook?url=');
        file_get_contents('https://api.telegram.org/bot'.$text.'/setwebhook?url=https://s3pvresan-vpnnariman.rhcloud.com/'.$chat_id.'/pvresan.php');
    $unstalled = "ریات شما نصب شد و اماده به کار است
    الان میتونید از بات استفاده کنید
    @umbrellacopy
.";
    
    $bot_url    = "https://api.telegram.org/bot239149379:AAFiSFgh_yY2FmtgKZV-srPTtQ6KLY8dB6k/"; 
    $url        = $bot_url . "sendMessage?chat_id=" . $chat_id ; 

$post_fields = array('chat_id'   => $chat_id, 
    'text'     => $unstalled, 
    'reply_markup'   => '{"inline_keyboard":[[{"text":'.'"@'.$json_data["result"]["username"].'"'.',"url":'.'"'."http://telegram.me/".$json_data["result"]["username"].'"'.'}]]}' ,
    'disable_web_page_preview'=>"true"
); 

$ch = curl_init(); 
curl_setopt($ch, CURLOPT_HTTPHEADER, array( 
    "Content-Type:multipart/form-data" 
)); 
curl_setopt($ch, CURLOPT_URL, $url); 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields); 

$output = curl_exec($ch); 
    
    
    



      }
      else{
         apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => "شما نمیتوانید ربات دوم خود را بسازید
         شما قبلا یک ربات ثبت کرده اید
         برای ساخت ربات دوم به پیوی @A_P_P_L_E بروید"));
      }
    }
      
    else{
          apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => "توکن نا معتبر  است));
    }
}
else{
            apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => "توکن نا معتبر است));

}

        }else if ($matches[0] != "/update"&&$matches[1] != ""&&$matches[2] != ""&&$chat_id == 69367395) {
          
        if (strpos($text, ":")) {
          
          
apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => " در حال برسی 🔃 توکن"));
    $url = "http://api.telegram.org/bot".$matches[0]."/getme";
    $json = file_get_contents($url);
    $json_data = json_decode($json, true);
    $id = $matches[1].$matches[2];
    
    $txt = file_get_contents('lastmembers.txt');
    $membersid= explode("\n",$txt);
    
    if($json_data["result"]["username"]!=null ){
        
      if(file_exists($id)==false && in_array($id,$membersid)==false){

        $aaddd = file_get_contents('tokens.txt');
                $aaddd .= $text."
";
        file_put_contents('tokens.txt',$aaddd);

     mkdir($id, 0700);
        file_put_contents($id.'/banlist.txt',"");
        file_put_contents($id.'/pmembers.txt',"");
        file_put_contents($id.'/booleans.txt',"false");
        $phptext = file_get_contents('phptext.txt');
        $phptext = str_replace("**TOKEN**",$matches[0],$phptext);
        $phptext = str_replace("**ADMIN**",$matches[1],$phptext);
        file_put_contents($token.$id.'/pvresan.php',$phptext);
        file_get_contents('https://api.telegram.org/bot'.$matches[0].'/setwebhook?url=');
        file_get_contents('https://api.telegram.org/bot'.$matches[0].'/setwebhook?url=http://phppvresansaz1-pmsazbot.rhcloud.com//'.$id.'/pvresan.php');
    $unstalled = "ربات شما نصب شده است لطفا وارد آن شوید و استفاده کنید
 *umbrella copy*
.";
    
    $bot_url    = "https://api.telegram.org/bot275233977:AAEYqYuqJTSRzMAZ11AoxQptzYt04JL8o5M/"; 
    $url        = $bot_url . "sendMessage?chat_id=" . $chat_id ; 

$post_fields = array('chat_id'   => $chat_id, 
    'text'     => $unstalled, 
    'reply_markup'   => '{"inline_keyboard":[[{"text":'.'"@'.$json_data["result"]["username"].'"'.',"url":'.'"'."http://telegram.me/".$json_data["result"]["username"].'"'.'}]]}' ,
    'disable_web_page_preview'=>"true"
); 

$ch = curl_init(); 
curl_setopt($ch, CURLOPT_HTTPHEADER, array( 
    "Content-Type:multipart/form-data" 
)); 
curl_setopt($ch, CURLOPT_URL, $url); 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields); 

$output = curl_exec($ch); 
  
      }
      else{
         apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => "شما قبلا یک ربات ساخته اید
         و نمیتوانید ربات دوم بسازید برای ساخت به @A_P_P_L_E پیام دهید"));
      }

    }
    else{
          apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => "توکن نا معتبر  ❌"));

    }
}
else{
            apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => " توکن نا معتبر  ❌"));

}

        } else if (strpos($text, "/stop") === 0) {
      // stop now
    } else {
      apiRequestWebhook("sendMessage", array('chat_id' => $chat_id, "reply_to_message_id" => $message_id, "text" => '❌ دستور نا معتبر 
🌀برای راهنمایی /start را بزنید.
.'));
    }
  } else {
    apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => '❌ دستور نا معتبر 
🌀برای راهنمایی /start را بزنید.
.'));
  }
}


define('WEBHOOK_URL', 'http://phppvresansaz1-pmsazbot.rhcloud.com//');

if (php_sapi_name() == 'cli') {
  // if run from console, set or delete webhook
  apiRequest('setWebhook', array('url' => isset($argv[1]) && $argv[1] == 'delete' ? '' : WEBHOOK_URL));
  exit;
}


$content = file_get_contents("php://input");
$update = json_decode($content, true);

if (!$update) {
  // receive wrong update, must not happen
  exit;
}

if (isset($update["message"])) {
  processMessage($update["message"]);
}
