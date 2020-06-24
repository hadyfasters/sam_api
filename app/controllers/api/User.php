<?php defined('BASEPATH') OR exit('No direct script access allowed');

class User extends SAM_Controller 
{
    public function __construct()
	{
		parent::__construct();
		// Load the Model
        $this->load->model('M_users');  
        $this->load->model('M_userposition');  
        // Verify Login
        $this->is_login = $this->verify_login();
	}

	// get all list include superadmin
	public function lists()
	{
		$users = [];

		// Get Request
		$req = file_get_contents('php://input');
		
		// Decode Request
		$res = json_decode($req);

		if(SECURED){
			// if(!is_array($res) && empty($res)){
			if(empty($res)){
				// Get Secured Request
				$res = json_decode($this->encryption->sam_decrypt($req));
				
			}
		}

		if($this->is_login){
			if(!empty($res)) {
				if(isset($res->level) && $res->level == 99) {
					$users = $this->M_users->getAll($res);
				}else{
					$users = $this->M_users->get($res);
				}
			}
			// Set Response
			$this->response_code = 200;
			$this->response['status'] = TRUE;
			$this->response['message'] = 'User list generated.';
			$this->response['data'] = $users;
		}

		// Run the Application
		$this->run(SECURED);
	}

	// get all list include superadmin
	public function get()
	{
		$users = [];

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
			if($res != null) {
				$users = $this->M_users->getByID($res->id);
			}
			// Set Response
			$this->response_code = 200;
			$this->response['status'] = TRUE;
			$this->response['data'] = $users;
		}

		// Run the Application
		$this->run(SECURED);
	}

	// create users
	public function create()
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
					'npp' => $res->npp,
					'nama' => $res->nama,
					'position' => $res->position,
					'branch_code' => $res->branch,
					'active' => $res->status
				];

				// get userposition for default password
				$pos = $this->M_userposition->getByID($res->position);
				// Set Password Default
				$input['password'] = $this->hash($pos->up_default);

				// Set Response Code
				$this->response_code = 200;
				// Save the Data
				$create = $this->M_users->save($input);
				if($create){
					// Set Response
					$this->response['status'] = TRUE;
					$this->response['message'] = 'User has been created.';
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

	// update users
	public function update()
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

				// Set Response Code
				$this->response_code = 200;
				$exist = $this->M_users->getByID($id);
				if($exist){

					$old = [
						'npp' => $exist->npp,
						'nama' => $exist->nama,
						'position' => $exist->position,
						'branch_code' => $exist->id_branch
					];

					// Set Input
					$input = $new = [
						'npp' => $res->npp,
						'nama' => $res->nama,
						'position' => $res->position,
						'branch_code' => $res->branch
					];

					$diff = array_diff_assoc($input,$old);

					$input['is_active'] = $res->status;

					$update = $this->M_users->save($input,$id);
					if($update){
						if($diff)
						{
							// Set Update Log
							$this->setLog($old,$new,$id,$res->user);
						}

						// Set Success Response
						$this->response['status'] = TRUE;
						$this->response['message'] = 'User has been updated.';
						$this->response['data'] = ['insert_id' => $update];
					}else{
						// Set Error Response
						$this->response['status'] = FALSE;
						$this->response['message'] = 'Error : '.$this->db->error();
					}
				}else{
					// Set Failed Response
					$this->response['status'] = FALSE;
					$this->response['message'] = 'User not found.';
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

		if(SECURED){
			if($req && !isset($res->auth_token)){
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
					'is_active' => 3
				];
				// Set Response Code
				$this->response_code = 200;
				$exist = $this->M_users->getByID($id);
				if($exist){
					$update = $this->M_users->save($input,$id);
					if($update){
						// Set Success Response
						$this->response['status'] = TRUE;
						$this->response['message'] = 'User has been removed.';
						$this->response['data'] = ['insert_id' => $update];
					}else{
						// Set Error Response
						$this->response['message'] = 'Error : '.$this->db->error();
					}
				}else{
					// Set Failed Response
					$this->response['status'] = FALSE;
					$this->response['message'] = 'User not found.';
				}
			}
		}

		// Run the Application
		$this->run(SECURED);
	}

	// activation users
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
					$exist = $this->M_users->getByID($id);
					if($exist){
						$update = $this->M_users->save($input,$id);
						if($update){
							// Set Success Response
							$this->response['status'] = TRUE;
							$this->response['message'] = 'User has been updated.';
							$this->response['data'] = ['insert_id' => $update];
						}else{
							// Set Error Response
							$this->response['message'] = 'Error : '.$this->db->error();
						}
					}else{
						// Set Failed Response
						$this->response['status'] = FALSE;
						$this->response['message'] = 'User not found.';
					}
				}
				$this->destroy_token('auth_'.$res->auth_token);
			}
		}

		// Run the Application
		$this->run(SECURED);
	}


	public function setLog($old,$new,$id,$user)
	{
        $this->load->model('M_user_history');  
		$arr = [
			'user' => $id,
			'previous_data' => json_encode($old),
			'latest_data' => json_encode($new),
			'updated_date' => date('Y-m-d H:i:s'),
			'updated_by' => $user
		];

		$this->M_user_history->save($arr);
	}

	// get all list include superadmin
	public function history_lists()
	{
        $this->load->model('M_user_history');  
		$users = [];

		if($this->is_login){
			$users = $this->M_user_history->getAll();

			// Set Response
			$this->response_code = 200;
			$this->response['status'] = TRUE;
			$this->response['message'] = 'User History list generated.';
			$this->response['data'] = $users;
		}

		// Run the Application
		$this->run(SECURED);
	}

}