<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Close extends SAM_Controller 
{
    public function __construct()
	{
		parent::__construct();
		// Load the Model
        $this->load->model('M_lead');  
        $this->load->model('M_call');  
        $this->load->model('M_meet');   
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

		if(!is_array($res) && empty($res) && !empty($req)){
			// Get Secured Request
			$res = json_decode($this->encryption->sam_decrypt($req));
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

	// get all list include superadmin
	public function get()
	{
		// Get Request
		$req = file_get_contents('php://input');
		
		// Decode Request
		$res = json_decode($req);

		if(!is_array($res) && empty($res) && !empty($req)){
			// Get Secured Request
			$res = json_decode($this->encryption->sam_decrypt($req));
		}

		if($this->is_login){
			$calls = $this->M_close->getSearch($res);
			// Set Response
			$this->response_code = 200;
			$this->response['status'] = TRUE;
			$this->response['data'] = $calls;
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

		if(!is_array($res) && empty($res) && !empty($req)){
			// Get Secured Request
			$res = json_decode($this->encryption->sam_decrypt($req));
		}

		// is Logged In
		if($this->is_login){
			// Set ID
			$id = $res->id;
			
			// Set Response Code
			$this->response_code = 200;
			$rows = $this->M_close->getByID($id);
			if($rows){
				// Set Success Response
				$this->response['status'] = TRUE;
				$this->response['data'] = $rows;
			}else{
				// Set Error Response
				$this->response['message'] = 'Error : '.$this->db->error();
			}
		}

		// Run the Application
		$this->run(SECURED);
	}

	// create
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

		// is Logged In
		if($this->is_login){
			// Set Input
			$input = [
				'lead_id' => $res->lead_id,
				'call_id' => $res->call_id,
				'meet_id' => $res->meet_id,
				'issued_date' => $res->issued_date,
				'issued_time' => $res->issued_time,
				'no_rekening' => $res->no_rekening,
				'realisasi_dana' => $res->realisasi_dana,
				'attachment' => (isset($res->attachment)?$res->attachment:''),
				'additional_info' => $res->additional_info,
				'created_date' => date('Y-m-d H:i:s'),
				'created_by' => $res->user,
				'status' => $res->status
			];

			// Set Response Code
			$this->response_code = 200;

			$exist = $this->M_close->getByLeadID($res->lead_id);
			if($exist){
				$saving = $this->M_close->save($input,$exist->close_id);
			}else{
				$saving = $this->M_close->save($input);
			}
			if($saving){
				$files = [
					'close_id' => $saving,
					'lead_id' => $res->lead_id,
					'call_id' => $res->call_id,
					'meet_id' => $res->meet_id,
					'file_name' => (isset($res->attachment)?$res->attachment:''),
					'file_type' => (isset($res->file_type)?$res->file_type:''),
					'file_size' => (isset($res->file_size)?$res->file_size:''),
					'file_path' => (isset($res->file_path)?$res->file_path:''),
					'close_date' => $res->issued_date,
					'close_time' => $res->issued_time,
					'additional_info' => $res->additional_info,
					'created_date' => date('Y-m-d H:i:s'),
					'creator' => $res->user
				];

				$this->M_close->saveTrx($files);

				// Set Success Response
				$this->response['status'] = TRUE;
				$this->response['message'] = 'Close data has been created.';
				$this->response['data'] = ['insert_id' => $saving];
			}else{
				// Set Error Response
				$this->response['message'] = 'Error : '.$this->db->error();
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

		if(!is_array($res) && empty($res) && !empty($req)){
			// Get Secured Request
			$res = json_decode($this->encryption->sam_decrypt($req));
		}

		// is Logged In
		if($this->is_login){
			// Set ID
			$id = $res->id;
			// Set Input
			$input = [
				'lead_id' => $res->lead_id,
				'call_id' => $res->call_id,
				'meet_id' => $res->meet_id,
				'issued_date' => $res->issued_date,
				'issued_time' => $res->issued_time,
				'no_rekening' => $res->no_rekening,
				'realisasi_dana' => $res->realisasi_dana,
				'additional_info' => $res->additional_info,
				'status' => $res->status
			];

			$tx_data = [
				'close_id' => $res->id,
				'lead_id' => $res->lead_id,
				'call_id' => $res->call_id,
				'meet_id' => $res->meet_id,
				'close_date' => $res->issued_date,
				'close_time' => $res->issued_time,
				'created_date' => date('Y-m-d H:i:s'),
				'additional_info' => $res->additional_info
			];

			if(isset($res->attachment)){
				$input['attachment'] = $res->attachment;
				$tx_data['file_name'] = $res->attachment;
				$tx_data['file_type'] = $res->file_type;
				$tx_data['file_size'] = $res->file_size;
				$tx_data['file_path'] = $res->file_path;
			}

			// Set Response Code
			$this->response_code = 200;
			$update = $this->M_close->save($input,$id);
			if($update){
				if(isset($res->tx_id) && $res->tx_id<>''){
					$this->M_close->saveTrx($tx_data,$res->tx_id);
				}else{
					$this->M_close->saveTrx($tx_data);
				}
				// Set Success Response
				$this->response['status'] = TRUE;
				$this->response['message'] = 'Close data has been updated.';
				$this->response['data'] = ['update_id' => $update];
			}else{
				// Set Error Response
				$this->response['message'] = 'Error : '.$this->db->error();
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

		if(!is_array($res) && empty($res) && !empty($req)){
			// Get Secured Request
			$res = json_decode($this->encryption->sam_decrypt($req));
		}
		
		// is Logged In
		if($this->is_login){
			// Set ID
			$id = $res->id;
			$approval = $res->approval;
			$approver = $res->approval_by;
			$notes = '';

			if(!$approval){
				$notes = $res->approval_note;
			}

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
				// Set Success Response
				$this->response['status'] = TRUE;
				$this->response['message'] = 'Close data has been updated.';
				$this->response['data'] = ['update_id' => $update];
			}else{
				// Set Error Response
				$this->response['message'] = 'Error : '.$this->db->error();
			}
		}

		// Run the Application
		$this->run(SECURED);
	}

	// remove
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
		
		// is Logged In
		if($this->is_login){
			// Set ID
			$id = $res->id;

			try{
				$this->response_code = 200;
				
				$deleted = $this->M_close->removeThis($id);

				if($deleted){
					// Set Success Response
					$this->response['status'] = TRUE;
					$this->response['message'] = 'Close data has been deleted.';
				}
			} catch(Exception $e){
				$this->response['message'] = 'Error : '.$e->getMessage();
			}
		}

		// Run the Application
		$this->run(SECURED);
	}

	public function trx_lists()
	{
		// Get Request
		$req = file_get_contents('php://input');
		
		// Decode Request
		$res = json_decode($req);

		if(!is_array($res) && empty($res) && !empty($req)){
			// Get Secured Request
			$res = json_decode($this->encryption->sam_decrypt($req));
		}

		if($this->is_login){
			// Set ID
			$id = $res->lead_id;

			$closes = $this->M_close->getTrx($id);

			// Set Response
			$this->response_code = 200;
			$this->response['status'] = TRUE;
			$this->response['data'] = $closes;
		}

		// Run the Application
		$this->run(SECURED);
	}

}