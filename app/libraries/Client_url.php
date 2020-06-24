<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Client_url {
    
    private $_url = SAM_API_URL;

    private $option = array('Content-Type: application/json');
    
    public function __construct()
    {
        $this->_CI = &get_instance();
    }

    public function postCURL($func, $postData, $token=null)
    {
        $this->option[1] = 'Content-Length: ' . strlen($postData);

        if($token){
            $this->option[2] = 'Authorization: Bearer '.$token;
        }

        $ch = curl_init($this->_url . $func);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                           
        curl_setopt($ch, CURLOPT_HTTPHEADER, $this->option);

        $output = curl_exec($ch);

        curl_close($ch);
        return $output;
    }

    public function getCURL($func, $params=null)
    {
        $ch = curl_init($this->_url . $func . $params);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);   
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);                
        curl_setopt($ch, CURLOPT_HTTPHEADER, $this->option);

        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }
    
    public function checkServicesStatus($func=null) {
        $curlInit = curl_init($this->_url . $func);
        curl_setopt($curlInit,CURLOPT_CONNECTTIMEOUT,10);
        curl_setopt($curlInit,CURLOPT_HEADER,true);
        curl_setopt($curlInit,CURLOPT_NOBODY,true);
        curl_setopt($curlInit,CURLOPT_RETURNTRANSFER,true);
     
        $response = curl_exec($curlInit);
     
        curl_close($curlInit);
        if ($response) return true;
        return false;                
     }
}