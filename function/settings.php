<?php
/*==========> INFO 
 * CODE     : BY ZLAXTERT
 * SCRIPT   : CC CHECKER
 * VERSION  : V4.5
 * TELEGRAM : t.me/zlaxtert
 * BY       : DARKXCODE
 */
$settings = array(
    "mode_proxy" => "off", // on or off
    "proxy_list" => "proxy.txt", // proxy list (ex: proxy.txt) (FORMAT: proxy:port)
    "proxy_Auth" => "", // proxy password (ex: username:pass)
    "type_proxy" => "http", // http , https or socks
    "apikey"     => "apikey.txt", // apikey list (ex: apikey.txt) [DONT CHANGE THIS]
    "thisAPI"    => "API.txt", // This API (ex: API.txt) [DONT CHANGE THIS]
);

$mode_proxy = $settings["mode_proxy"];
$proxy_list = $settings["proxy_list"];
$proxy_pwd  = $settings["proxy_Auth"];
$proxy_type = $settings["type_proxy"];
$thisApikey = $settings["apikey"];
$thisApi    = $settings["thisAPI"];

