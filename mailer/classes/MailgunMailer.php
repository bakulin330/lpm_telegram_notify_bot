<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 19.01.2016
 * Time: 16:39
 */
class MailgunMailer
{
    protected $message_data;
    protected $url;
    protected $key;
    protected $layout_dir;
    protected $template_dir;

    public function __construct($url, $key)
    {
        $this->url = $url;
        $this->key = $key;
        $this->layout_dir = DIR_LAYOUTS ;
        $this->template_dir = DIR_TEMPLATE;
    }

    public function sendRequest(array $message, $is_preview)
    {

//        $this->message_data = $message;

//        if(isset($layout)){
//            $layout_data = file_get_contents($this->layout_dir . $layout . '.html');
//            $message['html'] = $layout_data;
//        }

//        if(isset($layout)){
//            $layout_data = $this->getLayout($layout);
//            $this->message_data['html'] = $layout_data;
//        }


//        if(isset($template)){
//            $template_data = include $this->template_dir.$template.'.php';
//            for ($i = 0; $i < count($template_data); $i++ ){
//                $message['html'] = str_replace( "%message$i%", $template_data[$i], $message['html'] );
//            }
//        }

//        if(isset($template)){
//            $template_data = $this->getTemplate($template);
//            $this->includeTemplate($template_data);
//        }

//        if(!empty($variables)){
//            for ($i = 0; $i < count($variables); $i++){
//                $message['html'] = str_replace ( "%var$i%", $variables[$i], $message['html'] );
//            }
//        }

//        if(!empty($variables)){
//            $this->includeVariables($variables);
//        }


        if($is_preview === true){
            $replace_bracket = ["<", ">"];
            echo "<pre>";
            echo "<b> От кого: </b>".str_replace($replace_bracket,"", $message["from"]) . "<br>";
            echo "<b> Кому: </b>".str_replace($replace_bracket, "", $message["to"]) . "<br>";
            echo "<b> Тема сообщения: </b>" . $message["subject"] . "<br>";
            echo "<b> Текст сообщения: </b>" . (array_key_exists('text', $message) ? $message['text'] : $message['html']) . "<br>";
            echo "</pre>";

            return;
        }


        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
        curl_setopt($ch, CURLOPT_USERPWD, 'api:key-b5ac1e31bdde25cdc418f44d17dd2887');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT,15);
        curl_setopt($ch, CURLOPT_POST,1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_URL,
            'https://api.mailgun.net/v3/sandbox6ab3b5b2e70c4cec9b2749264474a34b.mailgun.org/messages');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $message);
        $result = curl_exec($ch);
        if (false === $result) {
            $errno = curl_errno($ch);
            $error = curl_error($ch);
            $this->logError("Curl returned error $errno: $error\n");
        }
        curl_close($ch);
        echo $this->message_data['html'];
        return $result;


    }

    public function logError ($handler)
    {
        file_put_contents(DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'mail.errors.log', "----\n".date('Y-m-d H:i:s')."\n".$handler."\n", FILE_APPEND);
    }

    public function create()
    {
        return new MailgunLetter($this);
    }

    public function getLayout($layout)
    {
        return file_get_contents($this->layout_dir . $layout . '.html');
    }

    public function getTemplate($template)
    {
        return include $this->template_dir.$template.'.php';
    }

    public function includeTemplate($template_data)
    {
        for ($i = 0; $i < count($template_data); $i++ ){
            $this->message_data['html'] = str_replace( "%message$i%", $template_data[$i], $this->message_data['html'] );
        }
    }

    public function includeVariables($variables)
    {
        for ($i = 0; $i < count($variables); $i++){
            $this->message_data['html'] = str_replace ( "%var$i%", $variables[$i], $this->message_data['html'] );
        }
    }
}