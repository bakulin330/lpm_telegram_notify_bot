<form method="post" action="">
    <label>URL: <input type="text" name="url" > </label>
    <label>Название картинки: <input type="text" name="file_name"> </label> <br/>
    <label>browser width: <input type="text" name="browser_width"></label>
    <label>browser height: <input type="text" name="browser_height"></label> <br />
<!--    <label>image width: <input type="text" name="image_width"></label>-->
<!--    <label>image height: <input type="text" name="image_height"></label>-->
    <br />
    <input type="submit" name="screenshot" value="Сделать снимок">
</form>






<?php
require_once 'config.php';
require_once DIR_CLASSES.'WebShot.php';

if(isset($_POST["screenshot"]) && isset($_POST["url"])){
    $obj = new WebShot();
    $obj->url = $_POST["url"];
    $obj->file_name = $_POST["file_name"];
    ($_POST["browser_width"]) ? $obj->browser_width = $_POST["browser_width"] : "";
    ($_POST["browser_height"]) ? $obj->browser_height = $_POST["browser_height"] : "";
//    ($_POST["image_width"]) ? $obj->image_width = $_POST["image_width"] : "";
//    ($_POST["image_height"]) ? $obj->image_height = $_POST["image_height"] : "";

    $result = $obj->screenshot();

    echo $result;

}



?>