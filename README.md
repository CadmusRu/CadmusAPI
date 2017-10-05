# CadmusAPI
CadmusApi hub client library

# Install
`composer require cadmus/api @dev`

# Example
```php
<?php
  
  use Cadmus\API\Client;
  
  include_once("vendor/autoload.php");
  
  # params for API
  $API_KEY = "01904164d2e2db20400612e9d70d24b3dc2db17567ec92cb93c51b87697793c1"; # Your API key (as example DEMO-key)
  $URL = "pg/demo"; # owner/api_name
  $PARAMETERS = ["assoc" => "array", "of" => "parameters"];
  
  
  $cadmusApi = new Client($API_KEY);
  
  try
  {
    $response = $cadmusApi->call($URL, $PARAMETERS);
    
    $response = $response->getResponse();
    if(isset($response['result'])){
	    echo "Result: " . $response['result'];
    }
    else{
	    echo "Error: " . $response['error'];
    }
  } 
  catch (Exception $e)
  {
    echo json_encode(
      [
        'status'   => 500,
        'message'  => $e->getMessage(),
        'httpCode' => $response->getHttpCode(),
        'osErrNo'  => $response->getOsErrNo(),
        'certinfo' => $response->getCertInfo(),
      ], JSON_PRETTY_PRINT);
  }
  ```
