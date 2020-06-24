<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class M_userposition extends SAM_Model {
    private $table = 'dt_user_position';
    private $pk = 'up_id';

    public function __construct(){
        parent::__construct();

        $this->_table = $this->table;
        $this->_pk = $this->pk;
    }

    public function getAll($data = null)
    {
        $where = '';
        if(!empty($data) && isset($data->status)){
            $where = ' AND is_active='.$data->status;
        }
        $this->_where = 'is_active <> 3'.$where;

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
        $this->_where = 'up_level <> 99 AND is_active <> 3'.$where;

        $result = $this->_get();

        if ($result->num_rows() > 0) 
        {
            return $result->result();
        }
    }

    public function delete()
    {
        $this->_where = 'up_level <> 99 AND up_id = {$id}';

        $result = $this->_delete();

        if ($result->num_rows() > 0) 
        {
            return $result->result();
        }
    }

    public function getByID($id)
    {
        $this->_where = "up_id = {$id}";

        $result = $this->_get();

        if ($result->num_rows() > 0) 
        {
            return $result->row();
        }
    }

}


?>