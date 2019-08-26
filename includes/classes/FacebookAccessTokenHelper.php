<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 14/02/19
 * Time: 05:26 م
 */

class FacebookAccessTokenHelper
{

    private $username;
    private $password;
    private $accessToken;
    private $status;
    private $errorCode;
    private $errorMessage;

    /**
     * FacebookAccessTokenHelper constructor.
     */
    public function __construct($username,$password)
    {

        $this->username = $username;
        $this->password = $password;

    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }

    /**
     * @param mixed $accessToken
     */
    public function setAccessToken($accessToken)
    {
        $this->accessToken = $accessToken;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getErrorCode()
    {
        return $this->errorCode;
    }

    /**
     * @param mixed $errorCode
     */
    public function setErrorCode($errorCode)
    {
        $this->errorCode = $errorCode;
    }

    public function generateAccessToken(){
        $url = "https://b-graph.facebook.com/auth/login?password=$this->password&email=$this->username&access_token=350685531728|62f8ce9f74b12f84c123cc23437a4a32&method=POST";
        $data = json_decode(smartbot_curl($url,false,"get"));
        if (isset($data->error)){
            $this->status = 0;
            $this->errorCode = $data->error->code;
            $this->errorMessage = $data->error->error_user_msg;
        }
        else if (isset($data->access_token)){
            $this->status = 1;
            $this->accessToken = $data->access_token;
        }

    }

    /**
     * @return mixed
     */
    public function getErrorMessage()
    {
        return $this->errorMessage;
    }

    /**
     * @param mixed $errorMessage
     */
    public function setErrorMessage($errorMessage)
    {
        $this->errorMessage = $errorMessage;
    }
}