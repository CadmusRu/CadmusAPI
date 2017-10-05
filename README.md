# CadmusAPI
CadmusApi hub client library

# Install
`composer require cadmus/api @dev`

# Example
```php
<?php
  
  use Cadmus\API\Client;
  
  include_once("vendor/autoload.php");
  
  $cadmusApi = new Client("Your API key");
  
  try
  {
    $response = $cadmusApi->call("owner/api_name", 
      ["array" => "parameters"]
    );
    
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
