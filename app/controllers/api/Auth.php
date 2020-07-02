<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends SAM_Controller {

    public function __construct()
	{
		parent::__construct();
        $this->load->model('M_users');  
        $this->load->model('M_captcha');  
	}

	public function check()
	{
		// Get Plain Request
		$request = json_decode(file_get_contents('php://input'));

		if($request==null)
			// Get Secured Request
			$request = json_decode($this->encryption->sam_decrypt(file_get_contents('php://input')));

		if($request) {
			$this->M_captcha->removeOld();

			$checkCaptcha = $this->M_captcha->getCaptcha($request);
			if($checkCaptcha){
				$user = $this->M_users->getDataUser($request->username);

				// Default Login Failed Message
				$this->response['message'] = 'Your username or password is wrong.';

				// Check if user is exist
				if($user)
				{
					$password = $request->password;
					$password_hash = $user->password;

					// Verify User Password
					if(password_verify($password, $password_hash)){
						// Set Response Code
						$this->response_code = 200;

						// Set Response Status
						$this->response['status'] = TRUE;

						// Set Response Data
						$this->response['data'] = [
							'id_user' => $user->id_user,
							'npp' => $user->npp,
							'nama' => $user->nama,
							'job' => $user->position_name,
							'job_id' => $user->position,
							'role' => $user->role_name,
							'role_id' => $user->roles,
							'is_sa' => $user->is_sa,
							'acl_view' => $user->acl_view,
							'acl_input' => $user->acl_input,
							'acl_edit' => $user->acl_edit,
							'acl_delete' => $user->acl_delete,
							'acl_approve' => $user->acl_approve,
							'branch_code' => $user->branch_code
						];

						$token_expired = time() + LOGIN_TOKEN_DURATION;

						// Set login token session on server
						$this->set_token('login_'.$user->npp,$this->response['data'],LOGIN_TOKEN_DURATION);

						// Add login token to response
						$this->response['data']['token'] = $this->encryption->sam_encrypt('login_'.$user->npp);

						// Add token expired to response
						$this->response['data']['expired'] = $token_expired;
					}
				}
			}else{
				// Set Response Status
				$this->response['status'] = FALSE;
				// Captcha Failed Message
				$this->response['message'] = 'You must submit the word that appears in the image.';
			}
		}

		// Run the Application
		$this->run(SECURED);
	}

	public function token()
	{
		// Set Response Code
		$this->response_code = 200;

		// Set Response Status
		$this->response['status'] = TRUE;

		// Generate Auth Token
		$this->response['auth_token'] = $this->generate_token();

		// Run the Application
		$this->run(SECURED);
	}

	public function captcha()
	{
		// Get Plain Request
		$request = json_decode(file_get_contents('php://input'));

		if($request==null)
			// Get Secured Request
			$request = json_decode($this->encryption->sam_decrypt(file_get_contents('php://input')));

		$input = $this->M_captcha->save($request);

		if($input){
			// Set Response Code
			$this->response_code = 200;

			// Set Response Status
			$this->response['status'] = TRUE;
		}

		// Run the Application
		$this->run(SECURED);

	}

}