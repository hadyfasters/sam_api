<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Roles extends SAM_Controller 
{
    public function __construct()
	{
		parent::__construct();
		// Load the Model
        $this->load->model('M_roles');    
        // Verify Login
        $this->is_login = $this->verify_login();
	}

	// get all list include superadmin
	public function lists()
	{
		$roles = [];

		// Get Request
		$req = file_get_contents('php://input');
		
		// Decode Request
		$res = json_decode($req);

		if(!is_array($res) && empty($res) && !empty($req)){
			// Get Secured Request
			$res = json_decode($this->encryption->sam_decrypt($req));
		}

		if($this->is_login){
			if($res != null) {
				if(isset($res->is_sa) && $res->is_sa == 1) {
					$roles = $this->M_roles->getAll($res);
				}else{
					$roles = $this->M_roles->get($res);
				}
			}
			// Set Response
			$this->response_code = 200;
			$this->response['status'] = TRUE;
			$this->response['data'] = $roles;
		}

		// Run the Application
		$this->run(SECURED);
	}

	// get all list include superadmin
	public function get()
	{
		$userposition = [];

		// Get Request
		$req = file_get_contents('php://input');
		
		// Decode Request
		$res = json_decode($req);

		if(!is_array($res) && empty($res) && !empty($req)){
			// Get Secured Request
			$res = json_decode($this->encryption->sam_decrypt($req));
		}

		if($this->is_login){
			if($res != null) {
				$roles = $this->M_roles->getByID($res->id);
			}
			// Set Response
			$this->response_code = 200;
			$this->response['status'] = TRUE;
			$this->response['data'] = $roles;
		}

		// Run the Application
		$this->run(SECURED);
	}

	// create userposition
	public function create()
	{
		// Get Request
		$req = file_get_contents('php://input');
		
		// Decode Request
		$res = json_decode($req);

		if(!is_array($res) && empty($res) && !empty($req)){
			// Get Secured Request
			$res = json_decode($this->encryption->sam_decrypt($req));
		}

		if($res !== null) {
			// is Logged In
			if($this->is_login){
				$res->level = isset($res->level) ? $res->level:0;
				// Set Input
				$input = [
					'role_name' => $res->role_name,
					'remarks' => $res->remarks,
					'acl_view' => $res->acl_view,
					'acl_input' => $res->acl_input,
					'acl_edit' => $res->acl_edit,
					'acl_delete' => $res->acl_delete,
					'acl_approve' => $res->acl_approve,
					'is_active' => $res->is_active
				];
				// Set Response Code
				$this->response_code = 200;
				// Save the Data
				$create = $this->M_roles->save($input);
				if($create){
					// Set Response
					$this->response['status'] = TRUE;
					$this->response['message'] = 'Role has been created.';
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

	// update userposition
	public function update()
	{
		// Get Request
		$req = file_get_contents('php://input');
		
		// Decode Request
		$res = json_decode($req);

		if(!is_array($res) && empty($res) && !empty($req)){
			// Get Secured Request
			$res = json_decode($this->encryption->sam_decrypt($req));
		}

		if($res !== null) {
			// is Logged In
			if($this->is_login){
				// Set ID
				$id = $res->id;
				// Set Input
				$input = [
					'role_name' => $res->role_name,
					'remarks' => $res->remarks,
					'acl_view' => $res->acl_view,
					'acl_input' => $res->acl_input,
					'acl_edit' => $res->acl_edit,
					'acl_delete' => $res->acl_delete,
					'acl_approve' => $res->acl_approve,
					'is_active' => $res->is_active
				];
				// Set Response Code
				$this->response_code = 200;
				$exist = $this->M_roles->getByID($id);
				if($exist){
					$update = $this->M_roles->save($input,$id);
					if($update){
						// Set Success Response
						$this->response['status'] = TRUE;
						$this->response['message'] = 'Role has been updated.';
						$this->response['data'] = ['insert_id' => $update];
					}else{
						// Set Error Response
						$this->response['message'] = 'Error : '.$this->db->error();
					}
				}else{
					// Set Failed Response
					$this->response['status'] = FALSE;
					$this->response['message'] = 'Role not found.';
				}
			}
		}

		// Run the Application
		$this->run(SECURED);
	}

	public function remove()
	{
		// Get Request
		$req = file_get_contents('php://input');
		
		// Decode Request
		$res = json_decode($req);

		if(!is_array($res) && empty($res) && !empty($req)){
			// Get Secured Request
			$res = json_decode($this->encryption->sam_decrypt($req));
		}

		if($res !== null) {
			// is Logged In
			if($this->is_login){
				// Set ID
				$id = $res->id;
				// Set Input
				$input = [
					'is_active' => 3
				];
				// Set Response Code
				$this->response_code = 200;
				$exist = $this->M_roles->getByID($id);
				if($exist){
					$update = $this->M_roles->save($input,$id);
					if($update){
						// Set Success Response
						$this->response['status'] = TRUE;
						$this->response['message'] = 'Role has been removed.';
						$this->response['data'] = ['insert_id' => $update];
					}else{
						// Set Error Response
						$this->response['message'] = 'Error : '.$this->db->error();
					}
				}else{
					// Set Failed Response
					$this->response['status'] = FALSE;
					$this->response['message'] = 'Role not found.';
				}
			}
		}

		// Run the Application
		$this->run(SECURED);
	}

	// activation userposition
	public function activation()
	{
		// Get Request
		$req = file_get_contents('php://input');
		
		// Decode Request
		$res = json_decode($req);

		if(!is_array($res) && empty($res) && !empty($req)){
			// Get Secured Request
			$res = json_decode($this->encryption->sam_decrypt($req));
		}

		if($res !== null) {
			// is Logged In
			if($this->is_login){
				// Set ID
				$id = $res->id;
				// Set Input
				$input = [
					'is_active' => $res->is_active
				];
				// Set Response Code
				$this->response_code = 200;
				$exist = $this->M_roles->getByID($id);
				if($exist){
					$update = $this->M_roles->save($input,$id);
					if($update){
						// Set Success Response
						$this->response['status'] = TRUE;
						$this->response['message'] = 'Role has been updated.';
						$this->response['data'] = ['insert_id' => $update];
					}else{
						// Set Error Response
						$this->response['message'] = 'Error : '.$this->db->error();
					}
				}else{
					// Set Failed Response
					$this->response['status'] = FALSE;
					$this->response['message'] = 'Role not found.';
				}
			}
		}

		// Run the Application
		$this->run(SECURED);
	}

}