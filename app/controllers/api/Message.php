<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Message extends SAM_Controller 
{
    public function __construct()
	{
		parent::__construct();
		// Load the Model
        $this->load->model('M_message');    
	}

	// get all list include superadmin
	public function lists()
	{
		// Get Request
		$req = file_get_contents('php://input');
		
		// Decode Request
		$res = json_decode($req);

		if(SECURED){
			if($req && !isset($res->auth_token)){
				// Get Secured Request
				$res = json_decode($this->encryption->sam_decrypt($req));
			}
		}

		$messages = $this->M_message->getAll();
		// Set Response
		$this->response_code = 200;
		$this->response['status'] = TRUE;
		$this->response['data'] = $messages;

		// Run the Application
		$this->run(SECURED);
	}

	// create message
	public function create()
	{
        // Verify Login
        $this->is_login = $this->verify_login();

		// Get Request
		$req = file_get_contents('php://input');
		
		// Decode Request
		$res = json_decode($req);

		if(SECURED){
			if($req && !isset($res->auth_token)){
				// Get Secured Request
				$res = json_decode($this->encryption->sam_decrypt($req));
			}
		}

		if($res !== null) {
			// Verify Auth Token
			$this->is_verified = $this->verify_token('auth_'.$res->auth_token);
			// Verified
			if($this->is_verified){
				// is Logged In
				if($this->is_login){
					// Set Input
					$input = [
						'description' => $res->description,
						'message' => $res->message,
						'code' => $res->code,
						'is_active' => $res->is_active,
						'created_date' => date('Y-m-d H:i:s'),
						'created_by' => $res->user
					];
					// Set Response Code
					$this->response_code = 200;
					// Save the Data
					$create = $this->M_message->save($input);
					if($create){
						// Set Response
						$this->response['status'] = TRUE;
						$this->response['message'] = 'Message has been created.';
						$this->response['data'] = ['insert_id' => $create];
					}else{
						// Set Error Message
						$this->response['message'] = 'Error : '.$this->db->error();
					}
				}
				$this->destroy_token('auth_'.$res->auth_token);
			}
		}

		// Run the Application
		$this->run(SECURED);
	}

	// update message
	public function update()
	{
        // Verify Login
        $this->is_login = $this->verify_login();

		// Get Request
		$req = file_get_contents('php://input');
		
		// Decode Request
		$res = json_decode($req);

		if(SECURED){
			if($req && !isset($res->auth_token)){
				// Get Secured Request
				$res = json_decode($this->encryption->sam_decrypt($req));
			}
		}

		if($res !== null) {
			// Verify Auth Token
			$this->is_verified = $this->verify_token('auth_'.$res->auth_token); 
			// Verified
			if($this->is_verified){
				// is Logged In
				if($this->is_login){
					// Set ID
					$id = $res->id;
					// Set Input
					$input = [
						'description' => $res->description,
						'message' => $res->message,
						'code' => $res->code,
						'is_active' => $res->is_active,
						'created_date' => date('Y-m-d H:i:s'),
						'created_by' => $res->user
					];
					// Set Response Code
					$this->response_code = 200;
					$create = $this->M_message->save($input,$id);
					if($create){
						// Set Success Response
						$this->response['status'] = TRUE;
						$this->response['message'] = 'Message has been updated.';
						$this->response['data'] = ['insert_id' => $create];
					}else{
						// Set Error Response
						$this->response['message'] = 'Error : '.$this->db->error();
					}
				}
				$this->destroy_token('auth_'.$res->auth_token);
			}
		}

		// Run the Application
		$this->run(SECURED);
	}

	// activation message
	public function activation()
	{
		// Get Request
		$req = file_get_contents('php://input');
		
		// Decode Request
		$res = json_decode($req);

		if(SECURED){
			if($req && !isset($res->auth_token)){
				// Get Secured Request
				$res = json_decode($this->encryption->sam_decrypt($req));
			}
		}

		if($res !== null) {
			// Verify Auth Token
			$this->is_verified = $this->verify_token('auth_'.$res->auth_token); 
			// Verified
			if($this->is_verified){
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
					$create = $this->M_message->save($input,$id);
					if($create){
						// Set Success Response
						$this->response['status'] = TRUE;
						$this->response['message'] = 'Message has been updated.';
						$this->response['data'] = ['insert_id' => $create];
					}else{
						// Set Error Response
						$this->response['message'] = 'Error : '.$this->db->error();
					}
				}
				$this->destroy_token('auth_'.$res->auth_token);
			}
		}

		// Run the Application
		$this->run(SECURED);
	}

}