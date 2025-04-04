<?php

// DONT CHANGE THIS
/*==========> INFO 
 * CODE     : BY ZLAXTERT
 * SCRIPT   : CC CHECKER
 * VERSION  : V4.5
 * TELEGRAM : t.me/zlaxtert
 * BY       : DARKXCODE
 */

 require_once "function/function.php";
 require_once "function/settings.php";
 
 echo banner();
 echo banner2();
 
 enterlist:
 echo "$WH [$GR+$WH] Your file ($YL example.txt $WH) $GR>> $BL";
 $listname = trim(fgets(STDIN));
 if (empty($listname) || !file_exists($listname)) {
     echo PHP_EOL . PHP_EOL . "$WH [$YL!$WH]$RD FILE NOT FOUND$WH [$YL!$WH]$DEF" . PHP_EOL . PHP_EOL;
     goto enterlist;
 }
 enterGateway:
 echo "      $WH [$YL!$WH]$YL GATEWAY$WH [$YL!$WH]\n";
 echo "
 $WH [$GR 1 $WH]$YL STRIPE     $WH [$GR 2 $WH]$YL STRIPE CHARGER
 $WH [$GR 3 $WH]$YL BRAINTREE  $WH [$GR 4 $WH]$YL VBV CHECK
 $WH [$GR 99 $WH]$YL EXIT

 $WH [$BL + $WH]$GR Choose $WH>> $YL";
 $gatewayNYA = trim(fgets(STDIN));
 if(preg_match ("/[^0-9]/", $gatewayNYA)) {
    echo PHP_EOL . PHP_EOL . "$WH [$YL!$WH]$RD INPUT NUMBER ONLY$WH [$YL!$WH]$DEF" . PHP_EOL . PHP_EOL;
	goto enterGateway;
 }
 if($gatewayNYA == 1){
    $gateway = "stripe";
 } else if($gatewayNYA == 2){
    $gateway = "stripe_charger";
 } else if($gatewayNYA == 3){
    $gateway = "braintree";
 } else if($gatewayNYA == 4){
    $gateway = "vbv";
 }  else if($gatewayNYA == 99){
    echo PHP_EOL . PHP_EOL . "$WH [$YL!$WH]$RD THANKS FOR USING$WH [$YL!$WH]$DEF" . PHP_EOL . PHP_EOL;
    exit();
 } else {
    echo PHP_EOL . PHP_EOL . "$WH [$YL!$WH]$RD NUMVER NOT FOUND$WH [$YL!$WH]$DEF" . PHP_EOL . PHP_EOL;
	goto enterGateway;
 }

 $lists = array_unique(explode("\n", str_replace("\r", "", file_get_contents($listname))));

 $live    = 0;
 $cvv     = 0;
 $ccn     = 0;
 $die     = 0;
 $limit   = 0;
 $unknown = 0;
 $total = count($lists);
 echo "\n\n$WH [$YL!$WH] TOTAL $GR$total$WH LISTS [$YL!$WH]$DEF\n\n";

 foreach ($lists as $list) {
    $no++;

    $iniJam = Jam();

    // GET SETTINGS
    if (strtolower($mode_proxy) == "off") {
        $Proxies = "";
        $proxy_Auth = $proxy_pwd;
        $type_proxy = $proxy_type;
        $apikey = GetApikey($thisApikey);
        $APIs = GetApiS($thisApi);
    } else {
        $Proxies = GetProxy($proxy_list);
        $proxy_Auth = $proxy_pwd;
        $type_proxy = $proxy_type;
        $apikey = GetApikey($thisApikey);
        $APIs = GetApiS($thisApi);
    }


    $api = $APIs . "/checker/cc-checkerV4.5/?cc=$list&gate=$gateway&apikey=$apikey&proxy=$Proxies&proxyPWD=$proxy_Auth&type_proxy=type_proxy";
    // CURL
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $api);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    
    $x = curl_exec($ch);
    curl_close($ch);
    $js = json_decode($x, TRUE);
    $msg = $js['data']['info']['msg'];

    $bin = $js['data']['info']['bin'];
    $Merchant = $js['data']['info']['merchant'];
    $scheme = $js['data']['info']['scheme'];
    $country = $js['data']['info']['country'];
    $alpha2 = $js['data']['info']['alpha2'];
    $bank_name = $js['data']['info']['bank_name'];
    $bank_brand = $js['data']['info']['bank_brand'];
    $type_cc = $js['data']['info']['type'];

    if($gateway == "stripe_charger"){
        $successPay = $js['data']['info']['success_pay'];
        $payynya = "[$YL DONATION$DEF: $YL$successPay$DEF ]";
    }else{
        $payynya = "|";
    }
    

    if (strpos($x, 'APPROVED')) {
        $live++;
        save_file("result/live.txt", "$list | $scheme | $type_cc | $bank_name | $bank_brand | $country ($alpha2)");                           
        echo "[$RD$no$DEF/$GR$total$DEF][$YL$iniJam$DEF]$GR APPROVED$DEF =>$BL $list$DEF | [$YL GATE$DEF: $YL$gateway$DEF ] $payynya [$YL MSG$DEF: $MG$msg$DEF ] | BY$CY DARKXCODE$DEF (V4.5)" . PHP_EOL;
    } else if (strpos($x, 'SUCCESS')) {
        $live++;
        save_file("result/live.txt", "$list | $scheme | $type_cc | $bank_name | $bank_brand | $country ($alpha2)");                           
        echo "[$RD$no$DEF/$GR$total$DEF][$YL$iniJam$DEF]$GR APPROVED$DEF =>$BL $list$DEF | [$YL GATE$DEF: $YL$gateway$DEF ] $payynya [$YL MSG$DEF: $MG$msg$DEF ] | BY$CY DARKXCODE$DEF (V4.5)" . PHP_EOL;
    } else if (strpos($x, 'APPROV')) {
        $live++;
        save_file("result/live.txt", "$list | $scheme | $type_cc | $bank_name | $bank_brand | $country ($alpha2)");                           
        echo "[$RD$no$DEF/$GR$total$DEF][$YL$iniJam$DEF]$GR APPROVED$DEF =>$BL $list$DEF | [$YL GATE$DEF: $YL$gateway$DEF ] $payynya [$YL MSG$DEF: $MG$msg$DEF ] | BY$CY DARKXCODE$DEF (V4.5)" . PHP_EOL;
    } else if (strpos($x, 'THANK YOU')) {
        $live++;
        save_file("result/live.txt", "$list | $scheme | $type_cc | $bank_name | $bank_brand | $country ($alpha2)");                           
        echo "[$RD$no$DEF/$GR$total$DEF][$YL$iniJam$DEF]$GR APPROVED$DEF =>$BL $list$DEF | [$YL GATE$DEF: $YL$gateway$DEF ] $payynya [$YL MSG$DEF: $MG$msg$DEF ] | BY$CY DARKXCODE$DEF (V4.5)" . PHP_EOL;
    } else if (strpos($x, '"cvc_check":"pass"')) {
        $live++;
        save_file("result/live.txt", "$list | $scheme | $type_cc | $bank_name | $bank_brand | $country ($alpha2)");                           
        echo "[$RD$no$DEF/$GR$total$DEF][$YL$iniJam$DEF]$GR APPROVED$DEF =>$BL $list$DEF | [$YL GATE$DEF: $YL$gateway$DEF ] $payynya [$YL MSG$DEF: $MG$msg$DEF ] | BY$CY DARKXCODE$DEF (V4.5)" . PHP_EOL;
    } else if (strpos($x, 'cvc_check')) {
        $live++;
        save_file("result/live.txt", "$list | $scheme | $type_cc | $bank_name | $bank_brand | $country ($alpha2)");                           
        echo "[$RD$no$DEF/$GR$total$DEF][$YL$iniJam$DEF]$GR APPROVED$DEF =>$BL $list$DEF | [$YL GATE$DEF: $YL$gateway$DEF ] $payynya [$YL MSG$DEF: $MG$msg$DEF ] | BY$CY DARKXCODE$DEF (V4.5)" . PHP_EOL;
    } else if (strpos($x, '"type":"one-time"')) {
        $live++;
        save_file("result/live.txt", "$list | $scheme | $type_cc | $bank_name | $bank_brand | $country ($alpha2)");                           
        echo "[$RD$no$DEF/$GR$total$DEF][$YL$iniJam$DEF]$GR APPROVED$DEF =>$BL $list$DEF | [$YL GATE$DEF: $YL$gateway$DEF ] $payynya [$YL MSG$DEF: $MG$msg$DEF ] | BY$CY DARKXCODE$DEF (V4.5)" . PHP_EOL;
    } else if (strpos($x, 'one-time')) {
        $live++;
        save_file("result/live.txt", "$list | $scheme | $type_cc | $bank_name | $bank_brand | $country ($alpha2)");                           
        echo "[$RD$no$DEF/$GR$total$DEF][$YL$iniJam$DEF]$GR APPROVED$DEF =>$BL $list$DEF | [$YL GATE$DEF: $YL$gateway$DEF ] $payynya [$YL MSG$DEF: $MG$msg$DEF ] | BY$CY DARKXCODE$DEF (V4.5)" . PHP_EOL;
    } else if (strpos($x, 'SUCCEEDED')) {
        $live++;
        save_file("result/live.txt", "$list | $scheme | $type_cc | $bank_name | $bank_brand | $country ($alpha2)");                           
        echo "[$RD$no$DEF/$GR$total$DEF][$YL$iniJam$DEF]$GR APPROVED$DEF =>$BL $list$DEF | [$YL GATE$DEF: $YL$gateway$DEF ] $payynya [$YL MSG$DEF: $MG$msg$DEF ] | BY$CY DARKXCODE$DEF (V4.5)" . PHP_EOL;
    } else if (strpos($x, 'Authenticate Successful')) {
        $live++;
        save_file("result/vbv-live.txt", "$list | $scheme | $type_cc | $bank_name | $bank_brand | $country ($alpha2)");
        echo "[$RD$no$DEF/$GR$total$DEF][$YL$iniJam$DEF]$GR PASSED$DEF =>$BL $list$DEF | [$YL GATE$DEF: $YL$gateway$DEF ] $payynya [$YL MSG$DEF: $MG$msg$DEF ] | BY$CY DARKXCODE$DEF (V4.5)" . PHP_EOL;
    } else if (strpos($x, 'Authenticate Unable To Authenticate')) {
        $live++;
        save_file("result/vbv-live.txt", "$list | $scheme | $type_cc | $bank_name | $bank_brand | $country ($alpha2)");
        echo "[$RD$no$DEF/$GR$total$DEF][$YL$iniJam$DEF]$GR PASSED$DEF =>$BL $list$DEF | [$YL GATE$DEF: $YL$gateway$DEF ] $payynya [$YL MSG$DEF: $MG$msg$DEF ] | BY$CY DARKXCODE$DEF (V4.5)" . PHP_EOL;
    } else if (strpos($x, 'Authenticate Attempt Successful')) {
        $live++;
        save_file("result/vbv-live.txt", "$list | $scheme | $type_cc | $bank_name | $bank_brand | $country ($alpha2)");
        echo "[$RD$no$DEF/$GR$total$DEF][$YL$iniJam$DEF]$GR PASSED$DEF =>$BL $list$DEF | [$YL GATE$DEF: $YL$gateway$DEF ] $payynya [$YL MSG$DEF: $MG$msg$DEF ] | BY$CY DARKXCODE$DEF (V4.5)" . PHP_EOL;
    } else if (strpos($x, 'Authenticate Unavailable')) {
        $live++;
        save_file("result/vbv-live.txt", "$list | $scheme | $type_cc | $bank_name | $bank_brand | $country ($alpha2)");
        echo "[$RD$no$DEF/$GR$total$DEF][$YL$iniJam$DEF]$GR PASSED$DEF =>$BL $list$DEF | [$YL GATE$DEF: $YL$gateway$DEF ] $payynya [$YL MSG$DEF: $MG$msg$DEF ] | BY$CY DARKXCODE$DEF (V4.5)" . PHP_EOL;
    } else if (strpos($x, "transaction_not_allowed")) {
        $cvv++;
        save_file("result/cvv.txt", "$list | $scheme | $type_cc | $bank_name | $bank_brand | $country ($alpha2)");
        echo "[$RD$no$DEF/$GR$total$DEF][$YL$iniJam$DEF]$BL CVV$DEF =>$BL $list$DEF | [$YL GATE$DEF: $YL$gateway$DEF ] $payynya [$YL MSG$DEF: $MG$msg$DEF ] | BY$CY DARKXCODE$DEF (V4.5)" . PHP_EOL;
    } else if (strpos($x, "authentication_required")) {
        $cvv++;
        save_file("result/cvv.txt", "$list | $scheme | $type_cc | $bank_name | $bank_brand | $country ($alpha2)");
        echo "[$RD$no$DEF/$GR$total$DEF][$YL$iniJam$DEF]$BL CVV$DEF =>$BL $list$DEF | [$YL GATE$DEF: $YL$gateway$DEF ] $payynya [$YL MSG$DEF: $MG$msg$DEF ] | BY$CY DARKXCODE$DEF (V4.5)" . PHP_EOL;
    } else if (strpos($x, "Your card zip code is incorrect.")) {
        $cvv++;
        save_file("result/cvv.txt", "$list | $scheme | $type_cc | $bank_name | $bank_brand | $country ($alpha2)");
        echo "[$RD$no$DEF/$GR$total$DEF][$YL$iniJam$DEF]$BL CVV$DEF =>$BL $list$DEF | [$YL GATE$DEF: $YL$gateway$DEF ] $payynya [$YL MSG$DEF: $MG$msg$DEF ] | BY$CY DARKXCODE$DEF (V4.5)" . PHP_EOL;
    } else if (strpos($x, "incorrect_zip")) {
        $cvv++;
        save_file("result/cvv.txt", "$list | $scheme | $type_cc | $bank_name | $bank_brand | $country ($alpha2)");
        echo "[$RD$no$DEF/$GR$total$DEF][$YL$iniJam$DEF]$BL CVV$DEF =>$BL $list$DEF | [$YL GATE$DEF: $YL$gateway$DEF ] $payynya [$YL MSG$DEF: $MG$msg$DEF ] | BY$CY DARKXCODE$DEF (V4.5)" . PHP_EOL;
    } else if (strpos($x, "card_error_authentication_required")) { 
        $cvv++;
        save_file("result/cvv.txt", "$list | $scheme | $type_cc | $bank_name | $bank_brand | $country ($alpha2)");
        echo "[$RD$no$DEF/$GR$total$DEF][$YL$iniJam$DEF]$BL CVV$DEF =>$BL $list$DEF | [$YL GATE$DEF: $YL$gateway$DEF ] $payynya [$YL MSG$DEF: $MG$msg$DEF ] | BY$CY DARKXCODE$DEF (V4.5)" . PHP_EOL;
    } else if (strpos($x, "three_d_secure_redirect")) {
        $cvv++;
        save_file("result/cvv.txt", "$list | $scheme | $type_cc | $bank_name | $bank_brand | $country ($alpha2)");
        echo "[$RD$no$DEF/$GR$total$DEF][$YL$iniJam$DEF]$BL CVV$DEF =>$BL $list$DEF | [$YL GATE$DEF: $YL$gateway$DEF ] $payynya [$YL MSG$DEF: $MG$msg$DEF ] | BY$CY DARKXCODE$DEF (V4.5)" . PHP_EOL;
    } else if (strpos($x, "incorrect_cvc")) {
        $ccn++;
        save_file("result/ccn.txt", "$list | $scheme | $type_cc | $bank_name | $bank_brand | $country ($alpha2)");
        echo "[$RD$no$DEF/$GR$total$DEF][$YL$iniJam$DEF]$YL CCN$DEF =>$BL $list$DEF | [$YL GATE$DEF: $YL$gateway$DEF ] $payynya [$YL MSG$DEF: $MG$msg$DEF ] | BY$CY DARKXCODE$DEF (V4.5)" . PHP_EOL;
    } else if (strpos($x, "invalid_cvc")) {
        $ccn++;
        save_file("result/ccn.txt", "$list | $scheme | $type_cc | $bank_name | $bank_brand | $country ($alpha2)");
        echo "[$RD$no$DEF/$GR$total$DEF][$YL$iniJam$DEF]$YL CCN$DEF =>$BL $list$DEF | [$YL GATE$DEF: $YL$gateway$DEF ] $payynya [$YL MSG$DEF: $MG$msg$DEF ] | BY$CY DARKXCODE$DEF (V4.5)" . PHP_EOL;
    } else if (strpos($x, "insufficient_funds")) {
        $ccn++;
        save_file("result/ccn.txt", "$list | $scheme | $type_cc | $bank_name | $bank_brand | $country ($alpha2)");
        echo "[$RD$no$DEF/$GR$total$DEF][$YL$iniJam$DEF]$YL CCN$DEF =>$BL $list$DEF | [$YL GATE$DEF: $YL$gateway$DEF ] $payynya [$YL MSG$DEF: $MG$msg$DEF ] | BY$CY DARKXCODE$DEF (V4.5)" . PHP_EOL;
    } else if (strpos($x, '"status":"failed"')) {
        $die++;
        save_file("result/dead.txt", "$list");
        echo "[$RD$no$DEF/$GR$total$DEF][$YL$iniJam$DEF]$RD DIE$DEF =>$BL $list$DEF | [$YL GATE$DEF: $YL$gateway$DEF ] $payynya [$YL MSG$DEF: $MG$msg$DEF ] | BY$CY DARKXCODE$DEF (V4.5)" . PHP_EOL;
    } else if (strpos($msg, 'erro')) {
        $die++;
        save_file("result/dead.txt", "$list");
        echo "[$RD$no$DEF/$GR$total$DEF][$YL$iniJam$DEF]$RD DIE$DEF =>$BL $list$DEF | [$YL GATE$DEF: $YL$gateway$DEF ] $payynya [$YL MSG$DEF: $MG$msg$DEF ] | BY$CY DARKXCODE$DEF (V4.5)" . PHP_EOL;
    } else if (strpos($msg, 'TIME OUT')) {
        $die++;
        save_file("result/dead.txt", "$list");
        echo "[$RD$no$DEF/$GR$total$DEF][$YL$iniJam$DEF]$RD DIE$DEF =>$BL $list$DEF | [$YL GATE$DEF: $YL$gateway$DEF ] $payynya [$YL MSG$DEF: $MG$msg$DEF ] | BY$CY DARKXCODE$DEF (V4.5)" . PHP_EOL;
    } else if (strpos($x, 'UNKNOWN RESPONSE!')) {
        $limit++;
        save_file("result/recheck.txt", "$list");
        echo "[$RD$no$DEF/$GR$total$DEF][$YL$iniJam$DEF]$CY RECHECK$DEF =>$BL $list$DEF | [$YL GATE$DEF: $YL$gateway$DEF ] $payynya [$YL MSG$DEF: $MG$msg$DEF ] | BY$CY DARKXCODE$DEF (V4.5)" . PHP_EOL;
    } else if (strpos($x, 'Bad Request')) {
        $die++;
        save_file("result/bad-req.txt", "$list");
        echo "[$RD$no$DEF/$GR$total$DEF][$YL$iniJam$DEF]$RD DIE$DEF =>$BL $list$DEF | [$YL GATE$DEF: $YL$gateway$DEF ] $payynya [$YL MSG$DEF: $MG$msg$DEF ] | BY$CY DARKXCODE$DEF (V4.5)" . PHP_EOL;
    } else if (strpos($x, 'proxy limit or proxy not support!')) {
        $die++;
        save_file("result/bad-req.txt", "$list");
        echo "[$RD$no$DEF/$GR$total$DEF][$YL$iniJam$DEF]$RD DIE$DEF =>$BL $list$DEF | [$YL GATE$DEF: $YL$gateway$DEF ] $payynya [$YL MSG$DEF: $MG$msg$DEF ] | BY$CY DARKXCODE$DEF (V4.5)" . PHP_EOL;
    } else if (strpos($x, 'Incorrect Apikey!')) {
        $die++;
        echo "[$RD$no$DEF/$GR$total$DEF][$YL$iniJam$DEF]$RD DIE$DEF =>$BL $list$DEF | [$YL GATE$DEF: $YL$gateway$DEF ] $payynya [$YL MSG$DEF: $MG$msg$DEF ] | BY$CY DARKXCODE$DEF (V4.5)" . PHP_EOL;
    } else if ($x == '') {
        $die++;
        save_file("result/bad-req.txt", "$list");
        echo "[$RD$no$DEF/$GR$total$DEF][$YL$iniJam$DEF]$RD DIE$DEF =>$BL $list$DEF | [$YL GATE$DEF: $YL$gateway$DEF ] $payynya [$YL MSG$DEF: $MG$msg$DEF ] | BY$CY DARKXCODE$DEF (V4.5)" . PHP_EOL;
    } else if ($x == 'UNKNOWN RESPONSE!') {
        $die++;
        save_file("result/bad-req.txt", "$list");
        echo "[$RD$no$DEF/$GR$total$DEF][$YL$iniJam$DEF]$RD DIE$DEF =>$BL $list$DEF | [$YL GATE$DEF: $YL$gateway$DEF ] $payynya [$YL MSG$DEF: $MG$msg$DEF ] | BY$CY DARKXCODE$DEF (V4.5)" . PHP_EOL;
    } else {
        $unknown++;
        save_file("result/unknown.txt", "$list");
        //echo $x.PHP_EOL;
        echo "[$RD$no$DEF/$GR$total$DEF][$YL$iniJam$DEF]$WH UNKNOWN$DEF =>$BL $list$DEF | [$YL GATE$DEF: $YL$gateway$DEF ] $payynya [$YL MSG$DEF: $MG$msg$DEF ] | BY$CY DARKXCODE$DEF (V4.5)" . PHP_EOL;
    }

}

//============> END

echo PHP_EOL;
echo "================[DONE]================" . PHP_EOL;
echo " DATE          : " . $date . PHP_EOL;
echo " APPROVE       : " . $live . PHP_EOL;
echo " CVV           : " . $cvv . PHP_EOL;
echo " CCN           : " . $ccn . PHP_EOL;
echo " DIE           : " . $die . PHP_EOL;
echo " RECHECK       : " . $limit . PHP_EOL;
echo " UNKNOWN       : " . $unknown . PHP_EOL;
echo " TOTAL         : " . $total . PHP_EOL;
echo "======================================" . PHP_EOL;
echo "[+] RATIO APPROVE / PASSED => $GR" . round(RatioCheck($live, $total)) . "%$DEF" . PHP_EOL;
echo "[+] RATIO CVV              => $BL" . round(RatioCheck($cvv, $total)) . "%$DEF" . PHP_EOL;
echo "[+] RATIO CCN              => $YL" . round(RatioCheck($ccn, $total)) . "%$DEF" . PHP_EOL;
echo "[+] RATIO RECHECK          => $RD" . round(RatioCheck($limit, $total)) . "%$DEF" . PHP_EOL . PHP_EOL;
echo "[!] NOTE : CHECK AGAIN FILE 'unknown.txt' or 'bad-req.txt' [!]" . PHP_EOL;
echo "This file '" . $listname . "'" . PHP_EOL;
echo "File saved in folder 'result/' " . PHP_EOL . PHP_EOL;


// ==========> FUNCTION

function collorLine($col)
{
    $data = array(
        "GR" => "\e[32;1m",
        "RD" => "\e[31;1m",
        "BL" => "\e[34;1m",
        "YL" => "\e[33;1m",
        "CY" => "\e[36;1m",
        "MG" => "\e[35;1m",
        "WH" => "\e[37;1m",
        "DEF" => "\e[0m"
    );
    $collor = $data[$col];
    return $collor;
}
?>
