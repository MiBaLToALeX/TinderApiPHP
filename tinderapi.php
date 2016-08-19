<?php
modoDebug(true);
//API
define('TINDER_API', 'https://api.gotinder.com');
define('TINDER_TOKEN', '');
define('TINDER_USER_AGENT', 'okhttp/3.3.1'); //Actual USER-AGENT de Tinder
define('ECHO_PRETTY_JSON', false);
define('JSON_PRINT_MODE', false);

class TinderApi{

    static public function http_params_GET(){
        $http_params_GET = isset($_GET['data']) ? $_GET['data'] : '';
        $http_params_GET = preg_replace('/[^a-zA-Z0-9_.,\-\{\}]/', '', $http_params_GET);
        return $http_params_GET;
    }

    static public function http_params_POST() {
        $http_params_POST = isset($_GET['data']) ? $_GET['data'] : '';
        $http_params_POST = preg_replace('/[^a-zA-Z0-9_.,\-\{\}]/', '', $http_params_POST);
        return $http_params_POST;
    }

    //Endpoints
    static public function GET_PROFILE(){
        return '/profile';
    }
}
if(empty(TinderApi::http_params_GET())){
  showInfo();
}else{
    $option = TinderApi::http_params_GET();
    $param1 = '';
    $param2 = '';
    switch($option){
        case 'profile':
            get_profile();
          break;
        default:
          if(preg_match('/place{1}\{-?(?:\d*\.)?\d+\,-?(?:\d*\.)?\d+\}/', $option))
          {
            $param1 = substr(after("{", $option), 0, -1);
            printf("<a href='https://www.google.es/maps/place/%s' target='_blank'>Ver pos</a>", $param1);
          }else{
            showInfo();
          }
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
  printResponse($resp);
  curl_close($curl);
}

function testPOST(){
$curl = curl_init();
curl_setopt_array($curl, array(
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_URL => TINDER_API . TinderApi::http_params_POST(),
    CURLOPT_USERAGENT => TINDER_USER_AGENT,
    CURLOPT_HTTPHEADER => array(
     'Content-type: application/json',
     sprintf('X-Auth-Token: %s', TINDER_TOKEN)
    ),
    CURLOPT_POST => 1,
    CURLOPT_POSTFIELDS => array(
        item1 => 'value',
        item2 => 'value2'
    )
));
$resp = curl_exec($curl);
printResponse($resp);
curl_close($curl);
}

/**
 * Muestra la respuesta en formato json / html.
 * @param $resp La respuesta.
 */
function printResponse($resp){
  if(ECHO_PRETTY_JSON){
    $resp = json_encode($resp, JSON_PRETTY_PRINT);
  }
  if (JSON_PRINT_MODE) {
     header('Content-Type: application/json');
  }
  echo $resp;
}
function modoDebug($modoDebug = false){
	if(is_bool($modoDebug) && $modoDebug) {
		error_reporting(E_ALL);
		ini_set('display_errors', 'On');
	}

  function showInfo(){
    $info = "<p>http://{$_SERVER['SERVER_NAME']}/?data=option</p>";
    $info .= "<strong>Options: </strong> <br/><p style='color: #27ae60;'>";
    //Opciones
    $info .= "profile<br/>";
    $info .= "place{1234567.1234567,-1234567.1234567}<br/>";
    $info .= "</p>";
    printf($info);
  }

  /**
   * Helper
   * http://php.net/manual/es/function.substr.php#112707
   */
  function after ($this, $inthat)
    {
        if (!is_bool(strpos($inthat, $this)))
        return substr($inthat, strpos($inthat,$this)+strlen($this));
    }

    function after_last ($this, $inthat)
    {
        if (!is_bool(strrevpos($inthat, $this)))
        return substr($inthat, strrevpos($inthat, $this)+strlen($this));
    }

    function before ($this, $inthat)
    {
        return substr($inthat, 0, strpos($inthat, $this));
    }

    function before_last ($this, $inthat)
    {
        return substr($inthat, 0, strrevpos($inthat, $this));
    }

    function between ($this, $that, $inthat)
    {
        return before ($that, after($this, $inthat));
    }

    function between_last ($this, $that, $inthat)
    {
     return after_last($this, before_last($that, $inthat));
    }

    if (!function_exists('strrevpos')) {
      function strrevpos($instr, $needle)
      {
          $rev_pos = strpos (strrev($instr), strrev($needle));
          if ($rev_pos===false) return false;
          else return strlen($instr) - $rev_pos - strlen($needle);
      }
    }

}
?>
