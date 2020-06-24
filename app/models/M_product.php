<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class M_product extends SAM_Model {
    private $table = 'ms_product';
    private $pk = 'product_id';

    public function __construct(){
        parent::__construct();

        $this->_table = $this->table;
        $this->_pk = $this->pk;
    }

    public function getAll()
    {
        $result = $this->_get();

        if ($result->num_rows() > 0) 
		{
			return $result->result();
		}
    }

    public function get()
    {
        $this->_where = array(
            'is_active' => 1
        );

        $result = $this->_get();

        if ($result->num_rows() > 0) 
        {
            return $result->result();
        }
    }

    public function getByID($id)
    {
        $this->_where = "product_id = {$id}";

        $result = $this->_get();

        if ($result->num_rows() > 0) 
        {
            return $result->row();
        }
    }

}


?>