<?php

include '../config.php';

$message = [
    'from' => 'me <arte.mas@mail.ru>',
    'to' => 'test <arte.mas@mail.ru>',
    'subject' => 'test',
    'text' => 'test',
    'html' => '<html> Image: <img src="cid:png.png"> </html>',
    'o:tracking' => 'yes',
    'o:tracking-clicks' => 'yes',
    'o:tracking-opens' => 'yes',
//    'inline[0]' => '@./png.png',
//    'inline[1]' => '@./unnamed.png',
    'inline[0]' => '@'.DIR_IMG.'png.png',
    'inline[1]' => '@'.DIR_IMG.'unnamed.png',
];

function send($message)
{

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($ch, CURLOPT_USERPWD, 'api:key-b5ac1e31bdde25cdc418f44d17dd2887');
    curl_setopt ($ch, CURLOPT_MAXREDIRS, 3);
    curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, false);
    curl_setopt ($ch, CURLOPT_VERBOSE, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 15);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_URL,
    'https://api.mailgun.net/v3/sandbox6ab3b5b2e70c4cec9b2749264474a34b.mailgun.org/messages'
    );
//    curl_setopt($ch, CURLOPT_INFILESIZE, filesize(DIR_ROOT.'test.jpeg'));
    curl_setopt($ch, CURLOPT_POSTFIELDS, $message);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Content-Type: multipart/form-data",
    ]);
    curl_setopt($ch, CURLOPT_HEADER, false);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;

}


echo '<pre>' . print_r(send($message), true) . '</pre>';