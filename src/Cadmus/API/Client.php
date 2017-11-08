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
     * @param array  $request_array
     * @param array  $headers
     *
     * @return \Cadmus\API\Response
     */
    public function call($api, $request_array, $headers = [])
    {
      return $this->post($api, $request_array, $headers);
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
      
      if (!isset($request_array['get_a_file']))
      {
        $request_array['get_a_file'] = 1;
      }
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
     * @param array  $arguments
     *
     * @return \Cadmus\API\Response
     */
    public function get($api, $arguments)
    {
      $arguments["token"] = $this->token;
      if (!isset($arguments["get_a_file"]))
      {
        $arguments["get_a_file"] = 1;
      }
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
  }





