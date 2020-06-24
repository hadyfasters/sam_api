<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class M_user_history extends SAM_Model {
    private $table = 'log_users';
    private $pk = 'user_history_id';

    public function __construct(){
        parent::__construct();

        $this->_table = $this->table;
        $this->_pk = $this->pk;
    }

    public function getAll()
    {
        $this->db->select("previous_data,latest_data,cast(updated_date as date) date,cast(updated_date as time) time,updated_by");
        $result = $this->_get();

        if ($result->num_rows() > 0) 
		{
			return $result->result();
		}
    }

}


?>