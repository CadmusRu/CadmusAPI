<?php
  
  namespace Cadmus\API;
  
  use Buzz\Browser;
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
     * @param string $token Base58 encoded
     * @param int    $connect_timeout
     * @param int    $request_timeout
     *
     * @throws \Exception
     */
    function __construct($token, $connect_timeout = 3, $request_timeout = 10)
    {
//      $base58 = new \StephenHill\Base58();
//      $token = $base58->decode($token);
      
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
      
      // $url = "http://apis.cadmus.ru/" . $api . "?token=" . $this->token;
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
      $r->setHttpCode($httpCode);
      $r->setOsErrNo($osErrNo);
      $r->setCertinfo($certInfo);
      
      return $r;
    }
  
    /**
     * @param string $api
     * @param array $request_array
     *
     * @return \Cadmus\API\Response
     */
    public function postBuzz($api, $request_array)
    {
      $buzz = new Browser();
      $buzz->getClient()->setTimeout($this->request_timeout + $this->connect_timeout);
      $url = "http://apis.cadmus.ru/" . $api;
      
      $request_array["token"] = $this->token;
      $request=http_build_query($request_array);
      $response = $buzz->post($url, [], $request);
      
      $ret = new Response();
      $ret->setResponse($response->getContent());
      return $ret;
    }
  
    /**
     * @param string $api
     * @param array $arguments
     *
     * @return \Cadmus\API\Response
     */
    public function getBuzz($api, $arguments)
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
     * @return \Cadmus\API\Response
     */
    public function get($api, $arguments = [])
    {
      $arguments["token"] = $this->token;
      
      // $url = "http://apis.cadmus.ru/" . $api . "?token=" . $this->token;
      $query = [];
      foreach ($arguments as $argument => $value)
      {
        $query[] = $argument . "=" . urlencode($value);
      }
      $query = implode("&", $query);
      $url = "http://apis.cadmus.ru/" . $api . "?" . $query;
      
      $handle = curl_init($url);
      
      curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, $this->connect_timeout);
      curl_setopt($handle, CURLOPT_TIMEOUT, $this->request_timeout);
      curl_setopt($handle, CURLOPT_CERTINFO, 1);
      
      $response = curl_exec($handle);
      
      $httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
      $osErrNo = curl_getinfo($handle, CURLINFO_OS_ERRNO);
      $certInfo = curl_getinfo($handle, CURLINFO_CERTINFO);
      
      $r = new Response();
      $r->setResponse($response);
      $r->setHttpCode($httpCode);
      $r->setOsErrNo($osErrNo);
      $r->setCertinfo($certInfo);
      
      return $r;
    }
    
    static function file($file)
    {
      if (!file_exists($file))
      {
        throw new Exception("File '$file' is not exists.");
      }
      
      return curl_file_create($file);
    }
  }





