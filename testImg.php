<?php

include 'config.php';

$message = [
    'from' => 'me <arte.mas@mail.ru>',
    'to' => 'test <arte.mas@mail.ru>',
    'subject' => 'test',
    'text' => 'test',
    'html' => '<html> Image: <img src="cid:unnamed.png" width="51" height="51"> </html>',
    'o:tracking' => 'yes',
    'o:tracking-clicks' => 'yes',
    'o:tracking-opens' => 'yes',
    'inline' => ['@'.DIR_ROOT.'unnamed.png']
];



//'inline' => array('@./test.jpeg')
//'inline' => array('./test.jpeg')
//'inline' => array('/test.jpeg')
//'inline' => array('test.jpeg')
//'inline' => array('@test.jpeg')
//
//['inline' => array('@./test.jpeg')]
//['inline' => array('./test.jpeg')]
//['inline' => array('/test.jpeg')]
//['inline' => array('test.jpeg')]
//['inline' => array('@test.jpeg')]


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
//    'https://api.mailgun.net/v3/sandbox6ab3b5b2e70c4cec9b2749264474a34b.mailgun.org/messages'
        'http://wp.12qw.ru/telegram/sss.php'
    );
//    curl_setopt($ch, CURLOPT_INFILESIZE, filesize(DIR_ROOT.'test.jpeg'));
    curl_setopt($ch, CURLOPT_POSTFIELDS, $message);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Content-Type: multipart/form-data",
    ]);
    curl_setopt($ch, CURLOPT_HEADER, false);
//    curl_setopt($ch, CURLOPT_HTTPHEADER, [
//        "Content-Type: multipart/form-data",
//    ]);
    $result = curl_exec($ch);
//if (false === $result) {
//    $errno = curl_errno($ch);
//    $error = curl_error($ch);
//    $this->logError("Curl returned error $errno: $error\n");
//}
    curl_close($ch);
    return $result;

}


echo '<pre>' . print_r(send($message), true) . '</pre>';