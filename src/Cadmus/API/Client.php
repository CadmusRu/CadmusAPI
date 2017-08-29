<?php
  
  namespace Cadmus\API;
  
  use Exception;
  
  class Client
  {
    private $token;
    private $connect_timeout = 3;
    private $request_timeout = 10;
    private $response;
    private $httpCode;
    private $osErrNo;
    private $certinfo;
    
    const ASYNC_ON = 0;
    const ASYNC_OFF = 1;
    
    
    function __construct($token)
    {
      if (strlen($token) != 64)
      {
        throw new Exception("Wrong token length.");
      }
      
      $this->token = $token;
      
    }
    
    
    /**
     * @param       $api
     * @param array $arguments
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
      
      $this->httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
      $this->osErrNo = curl_getinfo($handle, CURLINFO_OS_ERRNO);
      $this->certinfo = curl_getinfo($handle, CURLINFO_CERTINFO);
      
      $this->response = $response;
    }
    
    static function file($file)
    {
      if (!file_exists($file))
      {
        throw new Exception("File '$file' is not exists.");
      }
      
      return curl_file_create($file);
    }
    
    /**
     * @return mixed
     */
    public function getResponse()
    {
      return $this->response;
    }
    
    /**
     * @param mixed $response
     */
    public function setResponse($response)
    {
      $this->response = $response;
    }
  
    /**
     * @return mixed
     */
    public function getHttpCode()
    {
      return $this->httpCode;
    }
  
    /**
     * @param mixed $httpCode
     */
    public function setHttpCode($httpCode)
    {
      $this->httpCode = $httpCode;
    }
  
    /**
     * @return mixed
     */
    public function getOsErrNo()
    {
      return $this->osErrNo;
    }
  
    /**
     * @param mixed $osErrNo
     */
    public function setOsErrNo($osErrNo)
    {
      $this->osErrNo = $osErrNo;
    }
  
    /**
     * @return mixed
     */
    public function getCertinfo()
    {
      return $this->certinfo;
    }
  
    /**
     * @param mixed $certinfo
     */
    public function setCertinfo($certinfo)
    {
      $this->certinfo = $certinfo;
    }
  }





