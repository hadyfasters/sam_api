<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Lead extends SAM_Controller 
{
    public function __construct()
	{
		parent::__construct();
		// Load the Model
        $this->load->model('M_lead');  
        $this->load->model('M_call');   
        // Verify Login
        $this->is_login = $this->verify_login();
	}

	// get all list include superadmin
	public function lists()
	{
		if($this->is_login){
			$leads = $this->M_lead->getAll();
			// Set Response
			$this->response_code = 200;
			$this->response['status'] = TRUE;
			$this->response['data'] = $leads;
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

		if(SECURED){
			if(!is_array($res) && empty($res)){
				// Get Secured Request
				$res = json_decode($this->encryption->sam_decrypt($req));
			}
		}

		if($res !== null) {
			if($this->is_login){
				$leads = $this->M_lead->getSearch($res);
				// Set Response
				$this->response_code = 200;
				$this->response['status'] = TRUE;
				$this->response['data'] = $leads;
			}
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
				$rows = $this->M_lead->getByID($id);
				if($rows){
					// Set Success Response
					$this->response['status'] = TRUE;
					$this->response['data'] = $rows;
				}else{
					// Set Error Response
					$this->response['message'] = 'Error : '.$this->db->error();
				}
			}
		}

		// Run the Application
		$this->run(SECURED);
	}

	// create Lead
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
					'kategori_nasabah' => $res->kategori_nasabah,
					'nama_prospek' => $res->nama_prospek,
					'jenis_nasabah' => $res->jenis_nasabah,
					'alamat' => $res->alamat,
					'provinsi' => $res->provinsi,
					'kota_kabupaten' => $res->kota_kabupaten,
					'kecamatan' => $res->kecamatan,
					'kelurahan' => $res->kelurahan,
					'kontak_person' => $res->kontak_person,
					'no_kontak' => $res->no_kontak,
					'potensi_dana' => $res->potensi_dana,
					'produk' => $res->produk,
					'additional_info' => $res->additional_info,
					'created_date' => date('Y-m-d H:i:s'),
					'created_by' => $res->user
				];
				// Set Response Code
				$this->response_code = 200;
				// Save the Data
				$create = $this->M_lead->save($input);
				if($create){
					// Set Response
					$this->response['status'] = TRUE;
					$this->response['message'] = 'Lead has been created.';
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

	// update 
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
				// Set Input
				$input = [
					'kategori_nasabah' => $res->kategori_nasabah,
					'nama_prospek' => $res->nama_prospek,
					'jenis_nasabah' => $res->jenis_nasabah,
					'alamat' => $res->alamat,
					'provinsi' => $res->provinsi,
					'kota_kabupaten' => $res->kota_kabupaten,
					'kecamatan' => $res->kecamatan,
					'kelurahan' => $res->kelurahan,
					'kontak_person' => $res->kontak_person,
					'no_kontak' => $res->no_kontak,
					'potensi_dana' => $res->potensi_dana,
					'produk' => $res->produk,
					'additional_info' => $res->additional_info,
					'created_date' => $res->created_date,
				];

				// Set Response Code
				$this->response_code = 200;
				$update = $this->M_lead->save($input,$id);
				if($update){
					// Set Success Response
					$this->response['status'] = TRUE;
					$this->response['message'] = 'Lead has been updated.';
					$this->response['data'] = ['update_id' => $update];
				}else{
					// Set Error Response
					$this->response['message'] = 'Error : '.$this->db->error();
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
				$approval = $res->approval;
				$approver = $res->approval_by;
				$notes = '';
				$call_data = [];

				if($approval){
					$call_data = [
						'lead_id' => $id,
						'created_date' => date('Y-m-d H:i:s'),
						'created_by' => 'System'
					];
				}else{
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
				$update = $this->M_lead->save($approval_data,$id);
				if($update){
					if(!empty($call_data)){
						$insert_call = $this->M_call->save($call_data);
					}

					// Set Success Response
					$this->response['status'] = TRUE;
					$this->response['message'] = 'Lead has been updated.';
					$this->response['data'] = ['update_id' => $update];
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