<?php  

    define('BOT_TOKEN', '*');
    define('API_URL', 'https://api.telegram.org/bot'.BOT_TOKEN.'/');
    define('PROXY_USE', true);
    define('PROXY_USER', "*");
    define('PROXY_PASSWORD', "*");
    define('PROXY_SERVER', "*");
    define('PROXY_PORT', 1080);    
    
    // ------------------ My Custom Functions -------------------
    
    // Создать команду
    function createCommand($command){
        include(__DIR__."/../commands/".$command.".php");
        $class = "Command_".$command;
        $cmd = new $class();
        return $cmd;
    }
    
    // Создать команду с декоратором, рекомендуется 
    function createCommandWD($command){
        $c = createCommand($command);
        $cd = new cDecorator($c);
        return $cd;
    }
    
    // ---------------------- Telegram API ----------------------
    
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
        if (!is_numeric($val) && !is_string($val)) {
          $val = json_encode($val);
        }
      }
      $url = API_URL.$method.'?'.http_build_query($parameters);      

      $handle = curl_init($url);
      curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 5);
      curl_setopt($handle, CURLOPT_TIMEOUT, 60);
      checkProxy($handle); 
      $ret = exec_curl_request($handle);
      return $ret;
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
      checkProxy($handle); 
      $ret = exec_curl_request($handle);
      return $ret;
    }
    
   
    
    function getFileData($path){
        $ch = curl_init();
        $url = "https://api.telegram.org/file/bot".BOT_TOKEN."/".$path;
        curl_setopt($ch, CURLOPT_URL, $url);        
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        checkProxy($ch);
        return curl_exec($ch);
    }
    
    function getFileDataRaw($url){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
        curl_setopt($ch, CURLOPT_ENCODING, "UTF-8");
        checkProxy($ch);
        $output = curl_exec($ch); 
        curl_close($ch);      
        return $output;
    }
    
    function getFileDataRawWithOutUTF($url){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
        curl_setopt($ch, CURLOPT_ENCODING, "UTF-8"); 
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: text/html; charset=CP-1252"));
        checkProxy($ch);
        $output = curl_exec($ch); 
        curl_close($ch);      
        return $output;
    }
    
    function getFile($id){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api.telegram.org/bot".BOT_TOKEN."/getFile?file_id=".$id);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
        curl_setopt($ch, CURLOPT_ENCODING, "UTF-8");
        checkProxy($ch);
        return json_decode(curl_exec($ch), true);
    }
        
        
    function grab_image($url, $saveto){
        $ch = curl_init ($url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER,1);
        checkProxy($ch);
        $raw=curl_exec($ch);
        curl_close ($ch);
        if(file_exists($saveto)){
            unlink($saveto);
        }
        $fp = fopen($saveto,'x');
        fwrite($fp, $raw);
        fclose($fp);
    }
    
    function checkProxy($curl){
        
        if (PROXY_USE){
            curl_setopt($curl, CURLOPT_PROXYTYPE, 7);
            curl_setopt($curl, CURLOPT_PROXY, PROXY_USER.':'.PROXY_PASSWORD.'@'.PROXY_SERVER.':'.PROXY_PORT);
        }      
                
    }
    
?>