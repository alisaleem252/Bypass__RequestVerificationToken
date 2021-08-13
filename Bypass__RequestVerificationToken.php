<?php


$url = "SOMEURLWHEREYOUWANTTOPOSTDATA";


$curl = curl_init($url);
curl_setopt($curl, CURLOPT_URL, $url);

curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_HEADER, 1);
//for debug only!
$cookies = Array();
curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

//$resp = curl_exec($curl);

$result = curl_exec($curl);

// get cookie
// multi-cookie variant contributed by @Combuster in comments
preg_match_all('/^Set-Cookie:\s*([^;]*)/mi', $result, $matches);
$cookies = '';
foreach($matches[1] as $item) {
    parse_str($item, $cookie);
    $cookies.=$item.'; ';
}


$doc = new DOMDocument();
@$doc->loadHTML($result);


/**
 * Form ID where the field is placed.
 */
$sf_domObj = $doc->getElementById("searchForm"); // DOMNodeList Object
// Generally this is the first field, but if not change the 0 index as appropriate
$__RequestVerificationToken = $sf_domObj->getElementsByTagName("input")[0]->getAttribute("value");




$headers = array(
   "Connection: keep-alive",
   "Pragma: no-cache",
   "Cache-Control: no-cache",
   "sec-ch-ua: \"Chromium\";v=\"92\", \" Not A;Brand\";v=\"99\", \"Google Chrome\";v=\"92\"",
   "sec-ch-ua-mobile: ?0",
   "Upgrade-Insecure-Requests: 1",
   "User-Agent: Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/92.0.4515.131 Safari/537.36",
   "Content-Type: application/x-www-form-urlencoded",
   "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9",
   "Sec-Fetch-Site: same-origin",
   "Sec-Fetch-Mode: navigate",
   "Sec-Fetch-User: ?1",
   "Sec-Fetch-Dest: document",
   "Accept-Language: en-US,en;q=0.9",
   "Cookie: ".$cookies,
);

curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

// Add Additional Data URL QUERY
$data = "__RequestVerificationToken=".$__RequestVerificationToken;

curl_setopt($curl, CURLOPT_POSTFIELDS, $data);



$resp = curl_exec($curl);
curl_close($curl);