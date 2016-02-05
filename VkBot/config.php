<?php

if (!defined('CONFIG_LOADED')) {
    defined('DS') or define('DS', DIRECTORY_SEPARATOR);
    defined('DIR_ROOT') or define('DIR_ROOT', dirname(__FILE__) . DS);
    defined('DIR_TMP') or define('DIR_TMP', DIR_ROOT . 'tmp' . DS);
    defined('DIR_TESTS') or define('DIR_TESTS', DIR_ROOT . 'tests' . DS);
    defined('DIR_CLASSES') or define('DIR_CLASSES', DIR_ROOT . 'classes' . DS);
    defined('DIR_VENDOR') or define('DIR_VENDOR', DIR_ROOT . 'vendor' . DS);
    defined('DIR_IMG') or define('DIR_IMG', DIR_ROOT . 'imgFolder' . DS);
//    define('BASE_URL', 'http://localhost/VKapi/');
    define('BASE_URL', 'http://wp.12qw.ru/telegram/VkBot');

    $settings = [
        'url' => 'https://api.vk.com/method/',
        'access_token' => 'ede799b8c223c783602774d256acb5388e5bf817f47689cb76a71bdf7fbd8f12b52ade533c30d371c8b8f',
        'v' => '5.44',
        'code_length' => 4,
    ];
    $user_id = 89370;

    define('CONFIG_LOADED', true);
}