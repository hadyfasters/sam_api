<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Close extends SAM_Controller 
{
    public function __construct()
	{
		parent::__construct();
		// Load the Model
        $this->load->model('M_lead');  
        $this->load->model('M_call');  
        $this->load->model('M_close');   
        $this->load->model('M_close');   
        // Verify Login
        $this->is_login = $this->verify_login();
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

		if($this->is_login){
			$meets = $this->M_close->getAll();
			// Set Response
			$this->response_code = 200;
			$this->response['status'] = TRUE;
			$this->response['data'] = $meets;
		}

		// Run the Application
		$this->run(SECURED);
	}

	// detail
	public function detail()
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
						'attempt' => $res->attempt,
						'issued_date' => $res->meet_date,
						'issued_time' => $res->meet_time,
						'attachment' => $res->attachment,
						'no_rekening' => $res->no_rekening,
						'realisasi_dana' => $res->realisasi_dana
					];

					// Set Response Code
					$this->response_code = 200;
					$update = $this->M_close->save($input,$id);
					if($update){
						// Set Success Response
						$this->response['status'] = TRUE;
						$this->response['message'] = 'Close data has been updated.';
						$this->response['data'] = ['update_id' => $update];
					}else{
						// Set Error Response
						$this->response['message'] = 'Error : '.$this->db->error();
					}
				}
			}
		}

		// Run the Application
		$this->run(SECURED);
	}

	// update
	public function update()
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
						'attempt' => $res->attempt,
						'issued_date' => $res->meet_date,
						'issued_time' => $res->meet_time,
						'attachment' => $res->attachment,
						'no_rekening' => $res->no_rekening,
						'realisasi_dana' => $res->realisasi_dana
					];

					// Set Response Code
					$this->response_code = 200;
					$update = $this->M_close->save($input,$id);
					if($update){
						// Set Success Response
						$this->response['status'] = TRUE;
						$this->response['message'] = 'Close data has been updated.';
						$this->response['data'] = ['update_id' => $update];
					}else{
						// Set Error Response
						$this->response['message'] = 'Error : '.$this->db->error();
					}
				}
			}
		}

		// Run the Application
		$this->run(SECURED);
	}

	// approval
	public function approval()
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
					$lead_id = $res->lead_id;
					$call_id = $res->call_id;
					$approval = $res->approval;
					$approver = $res->approval_by;
					$notes = '';
					$next_data = [];

					// Set Input
					$approval_data = [
						'approval' => $approval,
						'approval_by' => $approver,
						'approval_note' => $notes,
						'approval_date' => date('Y-m-d H:i:s')
					];

					// Set Response Code
					$this->response_code = 200;
					$update = $this->M_close->save($approval_data,$id);
					if($update){
						if(!empty($next_data)){
							$insert_next = $this->M_close->save($next_data);
						}

						// Set Success Response
						$this->response['status'] = TRUE;
						$this->response['message'] = 'Meet data has been updated.';
						$this->response['data'] = ['update_id' => $update];
					}else{
						// Set Error Response
						$this->response['message'] = 'Error : '.$this->db->error();
					}
				}
			}
		}

		// Run the Application
		$this->run(SECURED);
	}

}