<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Product extends SAM_Controller 
{
    public function __construct()
	{
		parent::__construct();
		// Load the Model
        $this->load->model('M_product');    
        // Verify Login
        $this->is_login = $this->verify_login();
	}

	// get all list include superadmin
	public function lists()
	{
		if($this->is_login){
			$products = $this->M_product->getAll();
			// Set Response
			$this->response_code = 200;
			$this->response['status'] = TRUE;
			$this->response['data'] = $products;
		}

		// Run the Application
		$this->run(SECURED);
	}

	// get product
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
			$product = $this->M_product->getByID($res->id);
			// Set Response
			$this->response_code = 200;
			$this->response['status'] = TRUE;
			$this->response['data'] = $product;
		}

		// Run the Application
		$this->run(SECURED);
	}

	// create product
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
				// Set Input
				$input = [
					'product_name' => $res->product_name,
					'product_desc' => $res->product_desc,
					'is_active' => $res->status,
					'created_date' => date('Y-m-d H:i:s'),
					'created_by' => $res->user
				];
				// Set Response Code
				$this->response_code = 200;
				// Save the Data
				$create = $this->M_product->save($input);
				if($create){
					// Set Response
					$this->response['status'] = TRUE;
					$this->response['message'] = 'Product has been created.';
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

	// update product
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
					'product_name' => $res->product_name,
					'product_desc' => $res->product_desc,
					'is_active' => $res->status
				];
				// Set Response Code
				$this->response_code = 200;
				$exist = $this->M_product->getByID($id);
				if($exist){
					$update = $this->M_product->save($input,$id);
					if($update){
						// Set Success Response
						$this->response['status'] = TRUE;
						$this->response['message'] = 'Product has been updated.';
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
		}

		// Run the Application
		$this->run(SECURED);
	}

}