<?php
/*$params = $_REQUEST;
print_r($params);exit;*/
if (!function_exists('getallheaders')) {
    function getallheaders() {
        foreach ($_SERVER as $name => $value) {
            echo $value.'<br>';
            if (substr($name, 0, 5) == 'HTTP_') {
                $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
            }
        }
        return $headers;
    }
}
$headers = getallheaders(); //Version

if(isset($headers['Version'])){
    $Version = $headers['Version'];
    $Version = 'v'.str_replace(".","",$Version);
}else{
    $Version = 'v10';
}

return $Version;