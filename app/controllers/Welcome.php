<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

    public function __construct()
	{
		parent::__construct();
        $this->load->library('Client_url');  
	}

	public function index()
	{
		$check = $this->client_url->checkServicesStatus();
		var_dump($check);
	}
}
