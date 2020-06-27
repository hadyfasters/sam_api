<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class M_userposition extends SAM_Model {
    private $table = 'dt_position';
    private $pk = 'position_id';

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

    public function delete()
    {
        $this->_where = 'position_id = {$id}';

        $result = $this->_delete();

        if ($result->num_rows() > 0) 
        {
            return $result->result();
        }
    }

    public function getByID($id)
    {
        $this->_where = "position_id = {$id}";

        $result = $this->_get();

        if ($result->num_rows() > 0) 
        {
            return $result->row();
        }
    }

}


?>