<?php
$token = '156771533:AAFtGPT_o3MFuPRBnuYwOZGfNHWt_FivTy4';
$site = 'https://api.telegram.org/bot'.$token;

//$update = file_get_contents($site."/getupdates");

//print_r($update);

$curl = curl_init();
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_URL, $site."/getupdates");
$result = curl_exec($curl);

$update = file_get_contents($site."/getupdates");

print_r($update);


?>