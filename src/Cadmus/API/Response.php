<?php
  /**
   * Created by PhpStorm.
   * User: wrewolf
   * Date: 29.08.17
   * Time: 14:08
   */
  
  namespace Cadmus\API;
  
  
  class Response
  {
    private $response;
    private $httpCode;
    private $osErrNo;
    private $certInfo;
  
    public function setCertinfo($certInfo)
    {
      $this->certInfo = $certInfo;
    }
  
    public function setOsErrNo($osErrNo)
    {
      $this->osErrNo = $osErrNo;
    }
  
    public function setHttpCode($httpCode)
    {
      $this->httpCode = $httpCode;
    }
  
    public function setResponse($response)
    {
      $this->response = $response;
    }
    
    /**
     * @return mixed
     */
    public function getResponse()
    {
      return $this->response;
    }
    
    /**
     * @return mixed
     */
    public function getOsErrNo()
    {
      return $this->osErrNo;
    }
    
    /**
     * @return mixed
     */
    public function getHttpCode()
    {
      return $this->httpCode;
    }
    
    /**
     * @return mixed
     */
    public function getCertInfo()
    {
      return $this->certInfo;
    }
  }