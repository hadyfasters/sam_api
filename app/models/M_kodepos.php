<?php defined('BASEPATH') OR exit('No direct script access allowed');

class M_kodepos extends SAM_Model {
    private $table = 'ms_kodepos';
    private $pk = 'id';

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

    public function getByID($id)
    {
        $this->_where = "id = {$id}";

        $result = $this->_get();

        if ($result->num_rows() > 0) 
        {
            return $result->row();
        }
    }

}


?>