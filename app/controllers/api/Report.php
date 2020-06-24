<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Report extends SAM_Controller 
{
    public function __construct()
	{
		parent::__construct();
		// Load the Model
        $this->load->model('M_report');   
        // Verify Login
        //$this->is_login = $this->verify_login(); 
        $this->is_login = true;
	}

	// get all list include superadmin
	public function activity()
	{
		if($this->is_login){
            $activityData = $this->M_report->getActivityReport();
			// Set Response
			$this->response_code = 200;
			$this->response['status'] = TRUE;
            $this->response['data'] = $activityData;
        }
		// Run the Application
		$this->run(SECURED);
	}
}