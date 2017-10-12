<?php
  
  use Buzz\Message\Form\FormUpload;
  use Cadmus\API\Client;
  
  include_once("vendor/autoload.php");
  
  $cadmusApi = new Client("01904164d2e2db20400612e9d70d24b3dc2db17567ec92cb93c51b87697793c1");  // Ключ к api
  
  $imageFilePath = 'qr.png'; // Путь к картинке
  $image = new FormUpload($imageFilePath); // Создаём объект файла для загрузки
  
  $response = $cadmusApi->post("pg/qr.decode", ['image' => $image]); // Загружаем файл в апи
  
  var_dump($response->getResponse());
  