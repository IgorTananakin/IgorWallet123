<?php
//echo "файл существует";
//echo $_POST['value'];
$input = json_decode(file_get_contents("php://input"), true);//парсит из json
echo $input['value'];