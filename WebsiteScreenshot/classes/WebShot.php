<?php



class WebShot
{
    protected $url;
    protected $file_name;
    protected $browser_width = 1280;
    protected $browser_height = 900;
//    protected $image_width = 1280;
//    protected $image_height = 900;

    public function __set($var, $value)
    {
        $this->$var = $value;
    }

    public function screenshot()
    {
        if(!$this->url) return false;

//        preg_match("#(www\.)([a-z0-9-_]{0,30})#i", $this->url, $matches );
//        $dir_name = $matches[2];
//        if (!is_dir(DIR_TMP)){
//            mkdir(DIR_TMP,0755);
//        }

        $command = "phantomjs ".DIR_SCRIPT."getScreenshot.js $this->url ".DIR_TMP.$this->file_name.".png $this->browser_width $this->browser_height";
        $command = "phantomjs script/getScreenshot.js $this->url ".DIR_TMP.$this->file_name.".png $this->browser_width $this->browser_height";
        exec($command, $out, $ret);
        $link = DIR_TMP.$this->file_name.".png";
        echo 'cmd:'.$command."<br/>ret:".print_r($ret,true)."<br/>out:".print_r($out,true);

        if(file_exists($link)){
            echo "Скриншот сделан . Посмотреть можно пройдя по <a href='".BASE_URL."tmp".DS.$this->file_name.".png' target='_blank'>этой ссылке</a>";
            return;
        }else {
            echo 'не могу найти снимок.';
            return;
        }


    }
}