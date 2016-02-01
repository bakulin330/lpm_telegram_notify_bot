<?php
$par = [
    'count' => 1,
    'unread' => 0,
];

function test ($par) {
    $arr = [
        'access_code' => '123',
        'v' => '5',
    ];

    $arr += $par;
    var_dump($arr);
}

test($par);