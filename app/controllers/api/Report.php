<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Report extends SAM_Controller 
{
    public function __construct()
	{
		parent::__construct();
		// Load the Model
        $this->load->model('M_report');   
        // Verify Login
        $this->is_login = $this->verify_login(); 
	}

	public function lead()
	{
		if($this->is_login){
			$leadData = $this->M_report->getLeadReport();
			// Set Response
			$this->response_code = 200;
			$this->response['status'] = TRUE;
			$this->response['data'] = $leadData;
		}
		// Run the Application
		$this->run(SECURED);
	}

	public function call()
	{
		if($this->is_login){
			$callData = $this->M_report->getCallReport();
			// Set Response
			$this->response_code = 200;
			$this->response['status'] = TRUE;
			$this->response['data'] = $callData;
		}
		// Run the Application
		$this->run(SECURED);
	}
	
	public function close()
	{
		if($this->is_login){
			$closeData = $this->M_report->getCloseReport();
			// Set Response
			$this->response_code = 200;
			$this->response['status'] = TRUE;
			$this->response['data'] = $closeData;
		}
		// Run the Application
		$this->run(SECURED);
	}

	public function meet()
	{
		if($this->is_login){
			$meetData = $this->M_report->getMeetReport();
			// Set Response
			$this->response_code = 200;
			$this->response['status'] = TRUE;
			$this->response['data'] = $meetData;
		}
		// Run the Application
		$this->run(SECURED);
	}

	public function lead_main()
	{
		$req = file_get_contents('php://input');
		// Decode Request
		$res = json_decode($req);

		if(!is_array($res) && empty($res) && !empty($req)){
			// Get Secured Request
			$res = json_decode($this->encryption->sam_decrypt($req));
		}
		// Get Request
		if($this->is_login){
			$leadMainData = $this->M_report->getLeadMainReport();
			// Set Response
			$this->response_code = 200;
			$this->response['status'] = TRUE;
			$this->response['data'] = $leadMainData;
			
		}
		// Run the Application
		$this->run(SECURED);
	}
}