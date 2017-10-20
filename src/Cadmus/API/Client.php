<?php
  
  namespace Cadmus\API;
  
  use Buzz\Browser;
  use Buzz\Message\Form\FormRequest;
  use Buzz\Message\Request;
  use Exception;
  
  class Client
  {
    private $token;
    private $connect_timeout;
    private $request_timeout;
    
    
    const ASYNC_ON = 0;
    const ASYNC_OFF = 1;
    
    /**
     * Client constructor.
     *
     * @param string $token
     * @param int    $connect_timeout
     * @param int    $request_timeout
     *
     * @throws \Exception
     */
    function __construct($token, $connect_timeout = 3, $request_timeout = 10)
    {
      if (strlen($token) != 64)
      {
        throw new Exception("Wrong token length.");
      }
      
      $this->token = $token;
      $this->connect_timeout = $connect_timeout;
      $this->request_timeout = $request_timeout;
    }
    
    
    /**
     * @param string $api
     * @param array  $arguments
     *
     * @return \Cadmus\API\Response
     */
    public function call($api, $arguments = [])
    {
      $arguments["token"] = $this->token;
      
      $url = "http://apis.cadmus.ru/" . $api;
      
      $handle = curl_init($url);
      
      curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, $this->connect_timeout);
      curl_setopt($handle, CURLOPT_TIMEOUT, $this->request_timeout);
      curl_setopt($handle, CURLOPT_CERTINFO, 1);
      curl_setopt($handle, CURLOPT_POST, true);
      curl_setopt($handle, CURLOPT_POSTFIELDS, $arguments);
      
      $response = curl_exec($handle);
      
      $httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
      $osErrNo = curl_getinfo($handle, CURLINFO_OS_ERRNO);
      $certInfo = curl_getinfo($handle, CURLINFO_CERTINFO);
      
      $r = new Response();
      $r->setResponse($response);
      
      return $r;
    }
    
    /**
     * @param string $api
     * @param array  $request_array
     * @param array  $headers
     *
     * @return \Cadmus\API\Response
     */
    public function post($api, $request_array, $headers = [])
    {
      $headers[] = 'X-Version: 0.1a';
      $buzz = new Browser();
      $buzz->getClient()->setTimeout($this->request_timeout + $this->connect_timeout);
      
      $request = new FormRequest();
      $request->setMethod('POST');
      // Заполняем массив из полученных данных
      $request->setField('get_a_file', 0); // Перед циклом, что бы можно было затереть
      foreach ($request_array as $item => $value)
      {
        $request->setField($item, $value);
      }
      // После цикла, token зарезервировано
      $request->setField("token", $this->token);
      
      $request->setHeaders($headers);
      $request->setHost('http://apis.cadmus.ru/');
      $request->setResource($api);
      
      $response = $buzz->send($request, null);
      
      $ret = new Response($response);
      
      return $ret;
    }
  
    /**
     * @param string $api
     * @param array  $request_array
     * @param array  $headers
     *
     * @return mixed
     */
    public function postFile($api, $request_array, $headers = [])
    {
      $headers[] = 'X-Version: 0.1a';
      $buzz = new Browser();
      $buzz->getClient()->setTimeout($this->request_timeout + $this->connect_timeout);
    
      $request = new FormRequest();
      $request->setMethod('POST');
      // Заполняем массив из полученных данных
      $request->setField('get_a_file', true); // Перед циклом, что бы можно было затереть
      foreach ($request_array as $item => $value)
      {
        $request->setField($item, $value);
      }
      // После цикла, token зарезервировано
      $request->setField("token", $this->token);
    
      $request->setHeaders($headers);
      $request->setHost('http://apis.cadmus.ru/');
      $request->setResource($api);
    
      $response = $buzz->send($request, null);
    
      return $response;
    }
    
    /**
     * @param string $api
     * @param array  $arguments
     *
     * @return \Cadmus\API\Response
     */
    public function get($api, $arguments)
    {
      $arguments["token"] = $this->token;
      
      $query = [];
      foreach ($arguments as $argument => $value)
      {
        $query[] = $argument . "=" . urlencode($value);
      }
      $query = implode("&", $query);
      $url = "http://apis.cadmus.ru/" . $api . "?" . $query;
      
      $buzz = new Browser();
      $buzz->getClient()->setTimeout($this->request_timeout + $this->connect_timeout);
      
      $response = $buzz->get($url);
      
      $ret = new Response();
      $ret->setResponse($response->getContent());
      
      return $ret;
    }
    
    /**
     * @param string $api
     * @param array  $arguments
     *
     * @return mixed FileContent
     */
    public function getFile($api, $arguments)
    {
      $arguments["token"] = $this->token;
      $arguments["get_a_file"] = true;
      $query = [];
      
      foreach ($arguments as $argument => $value)
      {
        $query[] = $argument . "=" . urlencode($value);
      }
      $query = implode("&", $query);
      $url = "http://apis.cadmus.ru/" . $api . "?" . $query;
      $buzz = new Browser();
      $buzz->getClient()->setTimeout($this->request_timeout + $this->connect_timeout);
      
      $response = $buzz->get($url);
      
      $ret = new Response();
      
      return $response->getContent();
    }
  }





