<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class M_branch extends SAM_Model {
    private $table = 'ms_branch';
    private $pk = 'id_branch';

    public function __construct(){
        parent::__construct();

        $this->_table = $this->table;
        $this->_pk = $this->pk;
    }

    public function getAll()
    {
        $this->db->select('ms_branch.*,ms_region.name as region_name,ms_region.code as region_code');
        $this->db->join('ms_region','ms_region.id_region=ms_branch.id_region','left');
        $result = $this->_get();

        if ($result->num_rows() > 0) 
		{
			return $result->result();
		}
    }

    public function getByID($id)
    {
        $this->_where = "id_branch = {$id}";

        $result = $this->_get();

        if ($result->num_rows() > 0) 
        {
            return $result->row();
        }
    }

    public function getActive()
    {
        $this->_where = array(
            'status' => 1
        );

        $result = $this->_get();

        if ($result->num_rows() > 0) 
        {
            return $result->result();
        }
    }

}


?>