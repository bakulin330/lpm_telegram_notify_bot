<form action="" method="post">
    <label for="to">Кому: </label><input id="to" type="email">
    <br>
    <label for="subject">Тема: </label><input id="subject" type="text">
    <br>
    <label for="text" id="plain_message">Текст письма: </label>
    <input type="checkbox" id="is_html"> Использовать html-теги
    <br>
    <textarea id="text" rows="7" cols="30"></textarea>
    <br>
    <input type="checkbox" id="is_layout"> Использовать шаблон
    <br>
    <select size="1" id="layout" name="layout">
        <option value="1" > 1 шаблон </option>
        <option value="2" > 2 шаблон </option>
        <option value="3" > 3 шаблон </option>
    </select>

    <select size="1" id="template" name="template">
        <option selected> Содержание письма </option>
        <option value="1" > Приветствие </option>
        <option value="2" > Оповещание </option>
        <option value="3" > Предупреждение </option>
    </select>
    <br>
    <label for="var1"> opt1: </label><input id="var1" type="text">
    <br>
    <label for="var2"> opt2: </label><input id="var2" type="text">
    <br>
    <label for="var3"> opt3: </label><input id="var3" type="text">
    <br>
    <input id="preveiw" type="submit" name="preview" value="Предпросмотр">
    <input type="submit" name="send" value="Отправить">
</form>

<!--<script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>-->
<!--<script>-->
<!--    $(document).ready(function(){-->
<!--        $("#preview").click(function{-->
<!--            $.ajax({-->
<!--                url:"index.php",-->
<!--                type:"POST",-->
<!--                data:({-->
<!--                    to:$("#to").val(),-->
<!--                    subject:$("#subject").val(),-->
<!--                    plain_message:$("#plain_message").val(),-->
<!--                    is_html:$("#is_html").prop("checked"),-->
<!--                    is_layout:$("#is_layout").prop("checked"),-->
<!--                    layout:$("#layout :selected").val(),-->
<!--                        template:$("#template :selected").val(),-->
<!--                        var1:$("#var1").val(),-->
<!--                        var2:$("#var2").val(),-->
<!--                        var3:$("#var3").val()-->
<!--                })-->
<!--            })-->
<!--        })-->
<!--    })-->
<!--</script>-->


<?php
require_once 'config.php';
require DIR_CLASSES.'MailgunLetter.php';
require DIR_CLASSES.'MailgunMailer.php';


$mailer = new MailgunMailer('url','key');
//$mailer->create()
//    ->to('me <arte.mas@mail.ru>')
//    ->subject('test')
//    ->plain_message('test message')
//    ->html_message('<html> <body> <h1>h1 header</h1> <br> <hr> <hr> </body> </html>')
//    ->setIsHtml()
//    ->setLayout('action')
//    ->setTemplate('hello')
//    ->setVariables(['first', 'second', 'third'])
//    ->send();

if(isset($_POST['preview'])){
    $mailer->create()
        ->to('me <arte.mas@mail.ru>')
        ->subject('test')
        //->plain_message('<b>Hello</b>')
        //->html_message('<html> <body> <h1>h1 header</h1> <br> <hr> <hr> </body> </html>')
        ->setIsHtml()
        //->setIsPlain()
        ->setLayout('test2')
        ->setTemplate('test2')
        ->setVariables(['arr' => 'first', 'arr2' => 'second'])
        ->is_preview()
        ->send();

}