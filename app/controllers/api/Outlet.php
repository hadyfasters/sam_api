<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Outlet extends SAM_Controller 
{
    public function __construct()
	{
		parent::__construct();
		// Load the Model
        $this->load->model('M_region');  
        $this->load->model('M_branch');   
        // Verify Login
        $this->is_login = $this->verify_login();
	}

	// get all list include superadmin
	public function list_wilayah()
	{
		if($this->is_login){
			$products = $this->M_region->getAll();
			// Set Response
			$this->response_code = 200;
			$this->response['status'] = TRUE;
			$this->response['data'] = $products;
		}

		// Run the Application
		$this->run(SECURED);
	}

	// get all list include superadmin
	public function list_cabang()
	{
		if($this->is_login){
			$branches = $this->M_branch->getAll();
			// Set Response
			$this->response_code = 200;
			$this->response['status'] = TRUE;
			$this->response['data'] = $branches;
		}

		// Run the Application
		$this->run(SECURED);
	}

	// get wilayah
	public function get_wilayah()
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
			$region = $this->M_region->getByID($res->id);
			// Set Response
			$this->response_code = 200;
			$this->response['status'] = TRUE;
			$this->response['data'] = $region;
		}

		// Run the Application
		$this->run(SECURED);
	}

	// get wilayah
	public function get_cabang()
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
			$region = $this->M_branch->getByID($res->id);
			// Set Response
			$this->response_code = 200;
			$this->response['status'] = TRUE;
			$this->response['data'] = $region;
		}

		// Run the Application
		$this->run(SECURED);
	}

	// create wilayah
	public function create_wilayah()
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

		if($res !== null) {
			// is Logged In
			if($this->is_login){
				// Set Input
				$input = [
					'code' => $res->code,
					'name' => $res->name,
					'start_date' => $res->start_date,
					'status' => $res->status
				];
				// Set Response Code
				$this->response_code = 200;
				// Save the Data
				$create = $this->M_region->save($input);
				if($create){
					// Set Response
					$this->response['status'] = TRUE;
					$this->response['message'] = 'Region has been created.';
					$this->response['data'] = ['insert_id' => $create];
				}else{
					// Set Error Message
					$this->response['message'] = 'Error : '.$this->db->error();
				}
			}
		}

		// Run the Application
		$this->run(SECURED);
	}

	// create cabang
	public function create_cabang()
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

		if($res !== null) {
			// is Logged In
			if($this->is_login){
				// Set Input
				$input = [
					'id_region' => $res->region,
					'code' => $res->code,
					'name' => $res->name,
					'start_date' => $res->start_date,
					'end_date' => $res->end_date,
					'status' => 0
				];
				// Set Response Code
				$this->response_code = 200;
				// Save the Data
				$create = $this->M_branch->save($input);
				if($create){
					// Set Response
					$this->response['status'] = TRUE;
					$this->response['message'] = 'Branch has been created.';
					$this->response['data'] = ['insert_id' => $create];
				}else{
					// Set Error Message
					$this->response['message'] = 'Error : '.$this->db->error();
				}
			}
		}

		// Run the Application
		$this->run(SECURED);
	}

	// update wilayah
	public function update_wilayah()
	{
		// Get Request
		$req = file_get_contents('php://input');
		
		// Decode Request
		$res = json_decode($req);

		if(SECURED){
			if(!is_array($req)){
				// Get Secured Request
				$res = json_decode($this->encryption->sam_decrypt($req));
			}
		}

		if($res !== null) {
			// is Logged In
			if($this->is_login){
				// Set ID
				$id = $res->id;
				// Set Input
				$input = [
					'code' => $res->code,
					'name' => $res->name,
					'start_date' => $res->start_date,
					'status' => $res->status
				];

				// Set Response Code
				$this->response_code = 200;
				$create = $this->M_region->save($input,$id);
				if($create){
					// Set Success Response
					$this->response['status'] = TRUE;
					$this->response['message'] = 'Region has been updated.';
					$this->response['data'] = ['insert_id' => $create];
				}else{
					// Set Error Response
					$this->response['message'] = 'Error : '.$this->db->error();
				}
			}
		}

		// Run the Application
		$this->run(SECURED);
	}

	// update cabang
	public function update_cabang()
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

		if($res !== null) {
			// is Logged In
			if($this->is_login){
				// Set ID
				$id = $res->id;
				// Set Input
				$input = [
					'id_region' => $res->region,
					'code' =>$res->code,
					'name' => $res->name,
					'start_date' => $res->start_date,
					'end_date' => $res->end_date,
					// 'status' => $res->status
				];

				if($res->end_date){
					$input['end_date'] = $res->end_date;
				}
				
				// Set Response Code
				$this->response_code = 200;
				$create = $this->M_branch->save($input,$id);
				if($create){
					// Set Success Response
					$this->response['status'] = TRUE;
					$this->response['message'] = 'Branch has been updated.';
					$this->response['data'] = ['insert_id' => $create];
				}else{
					// Set Error Response
					$this->response['message'] = 'Error : '.$this->db->error();
				}
			}
		}

		// Run the Application
		$this->run(SECURED);
	}

}