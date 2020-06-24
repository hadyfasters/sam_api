<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Menu extends SAM_Controller 
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_menu');

        $this->is_login = $this->verify_login();
	}

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
			$lists = [];
			// Get List
			$data_menu = $this->M_menu->getMain();
			// Set Menu and Submenu
			foreach ($data_menu as $main) {
				$lists[(int)$main->sequence]['parent'] = $main;

				$data_subs = $this->M_menu->getSubs($main->id_menu);
				$lists[(int)$main->sequence]['sub'] = $data_subs;

				// if($value->parent == 0)
				// {
				// 	$lists[(int)$value->sequence]['parent'] = $value;
				// }else{
				// 	$lists[(int)$value->sequence]['sub'][] = $value;
				// }
			}
			
			// Set the Response
			$this->response_code = 200;
			$this->response['status'] = TRUE;
			$this->response['message'] = 'List menu has been generated.';
			$this->response['data'] = $lists;
		}

		// Run the Application
		$this->run(SECURED);
	}

	// create menu
	public function create()
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
					// Set Input
					$input = [
						'nama_menu' => $res->name,
						'link' => $res->link,
						'icon' => $res->icon,
						'parent' => $res->parent,
						'sequence' => $res->sequence,
						'active' => $res->is_active
					];
					// Set Response Code
					$this->response_code = 200;
					// Save the Data
					$create = $this->M_Menu->save($input);
					if($create){
						// Set Response
						$this->response['status'] = TRUE;
						$this->response['message'] = 'Menu has been created.';
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

	// update menu
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
						'nama_menu' => $res->name,
						'link' => $res->link,
						'icon' => $res->icon,
						'parent' => $res->parent,
						'sequence' => $res->sequence,
						'active' => $res->is_active
					];
					// Set Response Code
					$this->response_code = 200;
					$exist = $this->M_Menu->getByID($id);
					if($exist){
						$update = $this->M_Menu->save($input,$id);
						if($update){
							// Set Success Response
							$this->response['status'] = TRUE;
							$this->response['message'] = 'Menu has been updated.';
							$this->response['data'] = ['insert_id' => $update];
						}else{
							// Set Error Response
							$this->response['message'] = 'Error : '.$this->db->error();
						}
					}else{
						// Set Failed Response
						$this->response['status'] = FALSE;
						$this->response['message'] = 'Product not found.';
					}
				}
				$this->destroy_token('auth_'.$res->auth_token);
			}
		}

		// Run the Application
		$this->run(SECURED);
	}

}