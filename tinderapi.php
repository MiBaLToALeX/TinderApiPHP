<?php
modoDebug(true);
//API
define('TINDER_API', 'https://api.gotinder.com');
define('TINDER_TOKEN', '');
define('TINDER_USER_AGENT', 'okhttp/3.3.1'); //Actual USER-AGENT de Tinder

class TinderApi{

    static public function http_params_GET(){
        $http_params_GET = isset($_GET['data']) ? $_GET['data'] : '';
        $http_params_GET = preg_replace('/[^a-zA-Z0-9_\-\{\}]/', '', $http_params_GET);
        return $http_params_GET;
    }

    static public function http_params_POST() {
        $http_params_POST = isset($_GET['data']) ? $_GET['data'] : '';
        $http_params_POST = preg_replace('/[^a-zA-Z0-9_\-\{\}]/', '', $http_params_POST);
        return $http_params_POST;
    }

    //Endpoints
    static public function GET_PROFILE(){
        return '/profile';
    }
}
if(empty(TinderApi::http_params_GET())){
    printf("<strong>Options: </strong> \r\nprofile");
}else{
    switch(TinderApi::http_params_GET()){
        case 'profile':
            get_profile();
          break;
    }
}
function get_profile()
{
  $curl = curl_init();
  curl_setopt_array($curl, array(
      CURLOPT_RETURNTRANSFER => 1,
      CURLOPT_URL => TINDER_API . TinderApi::GET_PROFILE(),
      CURLOPT_USERAGENT => TINDER_USER_AGENT,
      CURLOPT_HTTPHEADER => array(
       'Content-type: application/json',
       sprintf('X-Auth-Token: %s', TINDER_TOKEN)
      )
  ));
  $resp = curl_exec($curl);
  echo $resp;
  curl_close($curl);
}
function modoDebug($modoDebug = false){
	if(is_bool($modoDebug) && $modoDebug) {
		error_reporting(E_ALL);
		ini_set('display_errors', 'On');
	}
}
?>
