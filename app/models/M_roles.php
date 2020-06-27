<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class M_roles extends SAM_Model {
    private $table = 'ms_roles';
    private $pk = 'role_id';

    public function __construct(){
        parent::__construct();

        $this->_table = $this->table;
        $this->_pk = $this->pk;
    }

    public function getAll($data = null)
    {
        if(!empty($data) && isset($data->status)){
            $this->_where = 'is_active='.$data->status;
        }

        $result = $this->_get();

        if ($result->num_rows() > 0) 
		{
			return $result->result();
		}
    }

    public function get($data = null)
    {
        $where = '';
        if(!empty($data) && isset($data->status)){
            $where = ' AND is_active='.$data->status;
        }
        $this->_where = 'is_sa <> 1 AND role_id <> '.$data->roles.$where;

        $result = $this->_get();

        if ($result->num_rows() > 0) 
        {
            return $result->result();
        }
    }

    public function delete()
    {
        $this->_where = 'role_id = {$id}';

        $result = $this->_delete();

        if ($result->num_rows() > 0) 
        {
            return $result->result();
        }
    }

    public function getByID($id)
    {
        $this->_where = "role_id = {$id}";

        $result = $this->_get();

        if ($result->num_rows() > 0) 
        {
            return $result->row();
        }
    }

}


?>