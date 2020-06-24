<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SAM_Controller extends CI_Controller{
    protected $is_login = FALSE;
    protected $is_verified = TRUE;
    protected $response_code = 401;
    protected $response = [
        'status' => FALSE,
        'message' => 'Please login to use this source.'
    ];

    public function __construct(){
        parent::__construct();
        $this->load->driver('cache');
        $this->load->library('encryption');
    }

    public function index(){
        $this->run();
    }

    public function run($crypt=false)
    {
        if($crypt && $this->response['status']){
            unset($this->response['message']);
            $this->response = $this->secure($this->response);
        } 

        $this->output
            ->set_status_header($this->response_code)
            ->set_content_type('application/json')
            ->set_output(json_encode($this->response));
    }

    public function secure($response)
    {
        $response = $this->encryption->sam_encrypt($response);

        return $response;
    }

    public function recure($input)
    {
        $this->response = $this->encryption->sam_decrypt($input);

        return $this->response;
    }

    protected function generate_token($payload=null,$length=32)
    {
        $token = substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, $length);
        $this->set_token('auth_'.$token,$token);
        return $token;
    }

    protected function set_token($tokenID,$payload=null,$time=60)
    {
        $this->cache->file->save($tokenID,$payload,$time);
    }

    protected function verify_token($tokenID)
    {
        $tokenCache = $this->cache->file->get($tokenID);
        if(!$tokenCache) {
            $this->is_verified = FALSE;
            $this->response_code = 403;
            $this->response = [
                'status' => FALSE,
                'message' => 'The action you have requested is not allowed.'
            ];
        }
        return $this->is_verified;
    }

    protected function destroy_token($tokenID)
    {
        $destroy = $this->cache->file->delete($tokenID);
        return $destroy;
    }

    public function verify_login()
    {
        $headers = getallheaders();
        if(isset($headers['Authorization']))
        {
            $headers = $headers['Authorization'];
            $headers = explode(' ',$headers);
            $tokenID = json_decode($this->encryption->sam_decrypt($headers[1]));
            $this->is_login = $this->cache->file->get($tokenID);
        }

        return $this->is_login;
    }

    public function hash($password)
    {
        $password = password_hash($password, PASSWORD_BCRYPT);
        return $password;
    }
}