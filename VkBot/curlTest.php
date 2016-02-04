<?php
$url = "https://api.vk.com/method/messages.send?user_id=16309784&message=hello&access_token=ede799b8c223c783602774d256acb5388e5bf817f47689cb76a71bdf7fbd8f12b52ade533c30d371c8b8f&v=5.44";
$res = file_get_contents($url);
echo $res;