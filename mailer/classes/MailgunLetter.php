<?php

/**
 * Created by PhpStorm.
 * User: admin
 * Date: 19.01.2016
 * Time: 10:11
 */
class MailgunLetter
{
    protected $from = 'Bakulin A. <bakulin.work@yandex.ru>';

    protected $to = '';
    protected $subject = '';
    protected $plain_message = '';
    protected $html_message = '';
    protected $is_html = false;
    protected $is_plain = false;
    protected $layout;
    protected $template;
    protected $variables = [];
    protected $is_preview = false;
    protected $images = [];

    /**
     * @var MailgunMailer
     */
    protected $mailer;


    public function __construct($mailer)
    {
        $this->mailer = $mailer;
    }

    public function getTo()
    {
        return $this->to;
    }

    public function &to($to)
    {
        $this->to = $to;
        return $this;
    }

    public function getSubject()
    {
        return $this->subject;
    }

    public function &subject($subject)
    {
        $this->subject = $subject;
        return $this;
    }

    public function getPlain_message()
    {
        return $this->plain_message;
    }

    public function &plain_message($message)
    {
        $this->plain_message = htmlspecialchars($message);
        return $this;
    }

    public function getHtml_message()
    {
        return $this->html_message;
    }

    public function &html_message($message)
    {
        $this->html_message = $message;
        return $this;
    }

    public function getIsHtml()
    {
        return $this->is_html;
    }

    public function &setIsHtml()
    {
        $this->is_html = true;
        return $this;
    }

    public function setIsPlain()
    {
        $this->is_plain = true;
        return $this;
    }

    public function getLayout()
    {
        return $this->layout;
    }

    public function &setLayout($layout)
    {
        $this->layout = $layout;
        return $this;
    }

    public function getTemplate()
    {
        return $this->template;
    }

    public function &setTemplate($template)
    {
        $this->template = $template;
        return $this;
    }

    public function &setVariables(array $var)
    {
        $this->variables = $var;
        return $this;
    }

    public function getVariables()
    {
        return $this->variables;
    }



//    public function addLayoutImages(array $images)
//    {
//        $this->layout_images = $images;
//        return $this;
//    }

    public function addImages($images)
    {
        $this->images = $images;
        return $this;
    }

    public function drawLayout($tpl_path, array $vars = [])
    {
        if (count($vars)>0){
            extract($vars);
        }
        ob_start();
        include $tpl_path;
        return ob_get_clean();
    }



    public function drawTemplate($tpl_path, array $vars = [])
    {
        if (count($vars)>0){
            extract($vars);
        }
        ob_start();
        include $tpl_path;
        return ob_get_clean();
    }

//    public function drawLayout($tpl_path, array $vars = [])
//    {
//        if($this->is_preview === true){
//            $images = [
//                'img1' => DIR_IMG_SRC.$this->layout.DS.'img1.png',
//                'img2' => DIR_IMG_SRC.$this->layout.DS.'img2.png',
//                'img3' => DIR_IMG_SRC.$this->layout.DS.'img3.png',
//            ];
//        } else {
//            $images = [
//                'img1' => 'cid:img1.png',
//                'img2' => 'cid:img2.png',
//                'img3' => 'cid:img3.png',
//            ];
//        }
//        if (count($vars)>0){
//            extract($vars);
//        }
//        extract($images);
//        ob_start();
//        include $tpl_path;
//        return ob_get_clean();
//    }

    public function send()
    {
        $this->mailer->sendRequest($this->draw(), $this->is_preview);
    }

    public function draw()
    {
        $template_data = "";

        $post = [
            'from'=>$this->from,
            'to'=>$this->to,
            'subject'=>$this->subject,
            'o:tracking'=>'yes',
            'o:tracking-clicks'=>'yes',
            'o:tracking-opens'=>'yes',
        ];

        if(!empty($this->plain_message)){
            $post['text'] = $this->plain_message;
            return $post;
        }

        if(!empty($this->html_message)){
            $post['html'] = $this->html_message;
            return $post;
        }

        if(!empty($this->template) && !empty($this->layout)){
            if ($this->is_plain) {
                $content = $this->drawTemplate(DIR_TEMPLATE . $this->template . "_text.php", $this->variables);
                $post['text'] = $this->drawTemplate(DIR_LAYOUTS . $this->layout . "_text.php", array_merge($this->variables, ['content'=>$content]));
            }
            if ($this->is_html) {
                $content = $this->drawTemplate(DIR_TEMPLATE . $this->template . "_html.php", $this->variables);
//                $post['html'] = $this->drawLayout(DIR_LAYOUTS . $this->layout . "_html.php", array_merge($this->variables, ['content'=>$content]));
                $post['html'] = $this->drawLayout(DIR_LAYOUTS . $this->layout . "_html.php", array_merge($this->variables, ['content'=>$content]));

                $post = $this->inlineImages($post);
                $post = $this->attachImages($post);
            }
            return $post;
        }

        throw new Exception('error');
    }
    

    public function attachImages($post)
    {
        //$images = array_merge($this->layout_images,$this->template_images);
        for ($i = 0; $i < count($this->images); $i++){
            $image = pathinfo($this->images[$i]);
            $post+= [
                "attachment[$i]" => "@".DIR_ROOT.substr($image['dirname'], 0).DS.$image['basename'],
            ];
        }
        return $post;
    }

    public function inlineImages($post)
    {
        //$images = array_merge($this->layout_images,$this->template_images);
        for ($i = 0; $i < count($this->images); $i++){
            $image = pathinfo($this->images[$i]);
            $post+= [
                "inline[$i]" => "@".DIR_ROOT.substr($image['dirname'], 0).DS.$image['basename'],
            ];
        }
        return $post;
    }

//    public function getLayoutImages($layout)
//    {
//        switch($layout){
//            case "test":
//                return [
//                    'inline[0]' => '@'.DIR_IMG.$layout.DS.'img1.png',
//                    'inline[1]' => '@'.DIR_IMG.$layout.DS.'img2.png',
//                ];
//
//            case "test2":
//                return [
//                    'inline[0]' => '@'.DIR_IMG.$layout.DS.'img1.png',
//                    'inline[1]' => '@'.DIR_IMG.$layout.DS.'img2.png',
//                ];
//            default: throw new Exception('Has no such layout');
//        }
//    }


    public function is_preview ()
    {
        $this->is_preview = true;
        $message = $this->draw();
//        $images = array_merge($this->layout_images,$this->template_images);

        for ( $i = 0; $i < count($this->images); $i++){

            $replace = pathinfo($this->images[$i]);
            $message['html'] = str_replace("cid:".$replace['basename'], $replace['dirname'].DS.$replace['basename'], $message['html']);
//            $message['html'] = $this->images[$i];
        }



        $replace_bracket = ["<", ">"];
        echo "<pre>";
        echo "<b> От кого: </b>".str_replace($replace_bracket,"", $message["from"]) . "<br>";
        echo "<b> Кому: </b>".str_replace($replace_bracket, "", $message["to"]) . "<br>";
        echo "<b> Тема сообщения: </b>" . $message["subject"] . "<br>";
        echo "<b> Текст сообщения: </b>" . (array_key_exists('text', $message) ? $message['text'] : $message['html']) . "<br>";
        echo "</pre>";

        return;
    }

}