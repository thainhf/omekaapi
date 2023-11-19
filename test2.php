<?php
//curl -X GET "https://omeka.p-set.org/api/items?fulltext_search=คุยกับผู้อ่าน" -H "Authorization: Bearer UtDqbQ2yPkxkgmfW5ZAbHLDr2iJRBGA1"
//เว็บ Curl
//https://reqbin.com/curl
$url = "https://omeka.p-set.org/api/items?fulltext_search=คุยกับผู้อ่าน&page=1&per_page=500";
//$url = "https://omeka.p-set.org/api/items?fulltext_search=หมอชาวบ้าน&page=1&per_page=500";


$curl = curl_init($url);
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

$headers = array(
    "Authorization: Bearer UtDqbQ2yPkxkgmfW5ZAbHLDr2iJRBGA1",
);
curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
//for debug only!
curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

$resp = curl_exec($curl);
curl_close($curl);
//var_dump($resp);
print_r($resp);

