<?php
  
  use Cadmus\API\Client;
  
  include_once("vendor/autoload.php");
  
  $cadmusApi = new Client("01904164d2e2db20400612e9d70d24b3dc2db17567ec92cb93c51b87697793c1");  // Ключ к api
  
  try
  {
    $call = $cadmusApi->post("pg/demo", [
      "get_a_file" => 0,
      "assoc"      => "array",
      "of"         => "parameters",
    ]);
    
    $response = $call->getResponse();
    
    if (isset($response['message']))
    {
      echo "Post Result: " . $response['message'] . "\n";
    }
    else
    {
      echo "Post Error: " . $response['error'] . "\n";
    }
    
    $call = $cadmusApi->get("pg/demo", [
      "get_a_file" => 0,
      "assoc"      => "array",
      "of"         => "parameters",
    ]);
    
    $response = $call->getResponse();
    
    if (isset($response['message']))
    {
      echo "Get Result: " . $response['message'] . "\n";
    }
    else
    {
      echo "Get Error: " . $response['error'] . "\n";
    }
  }
  catch (Exception $e)
  {
    echo json_encode([
        'status'  => 500,
        'message' => $e->getMessage(),
      ],
        JSON_PRETTY_PRINT) . "\n";
  }