<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Address extends SAM_Controller 
{
    public function __construct()
	{
		parent::__construct();
		// Load the Model
        $this->load->model('M_provinces');  
        $this->load->model('M_regencies');   
        $this->load->model('M_districts');   
        $this->load->model('M_villages');  

        // Verify Login
        $this->is_login = $this->verify_login();
	}

	// get all list include superadmin
	public function list_province()
	{
		if($this->is_login){
			$products = $this->M_provinces->getAll();
			// Set Response
			$this->response_code = 200;
			$this->response['status'] = TRUE;
			$this->response['data'] = $products;
		}

		// Run the Application
		$this->run(SECURED);
	}

	// get all list include superadmin
	public function list_regency()
	{
		// Get Request
		$req = file_get_contents('php://input');
		
		// Decode Request
		$res = json_decode($req);

		if(SECURED){
			if(!is_array($res) && empty($res)){
				// Get Secured Request
				$res = json_decode($this->encryption->sam_decrypt($req));
			}
		}

		if($this->is_login){
			$kabkot = $this->M_regencies->getByProvince($res->province);
			// Set Response
			$this->response_code = 200;
			$this->response['status'] = TRUE;
			$this->response['data'] = $kabkot;
		}

		// Run the Application
		$this->run(SECURED);
	}

	// get all list include superadmin
	public function list_district()
	{
		// Get Request
		$req = file_get_contents('php://input');
		
		// Decode Request
		$res = json_decode($req);

		if(SECURED){
			if(!is_array($res) && empty($res)){
				// Get Secured Request
				$res = json_decode($this->encryption->sam_decrypt($req));
			}
		}

		if($this->is_login){
			$districts = $this->M_districts->getByRegency($res->regency);
			// Set Response
			$this->response_code = 200;
			$this->response['status'] = TRUE;
			$this->response['data'] = $districts;
		}

		// Run the Application
		$this->run(SECURED);
	}

	// get all list include superadmin
	public function list_village()
	{
		// Get Request
		$req = file_get_contents('php://input');
		
		// Decode Request
		$res = json_decode($req);

		if(SECURED){
			if(!is_array($res) && empty($res)){
				// Get Secured Request
				$res = json_decode($this->encryption->sam_decrypt($req));
			}
		}

		if($this->is_login){
			$villages = $this->M_villages->getByDistrict($res->district);
			// Set Response
			$this->response_code = 200;
			$this->response['status'] = TRUE;
			$this->response['data'] = $villages;
		}

		// Run the Application
		$this->run(SECURED);
	}

}