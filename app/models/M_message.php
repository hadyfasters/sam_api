<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class M_message extends SAM_Model {
    private $table = 'messages';
    private $pk = 'message_id';

    public function __construct(){
        parent::__construct();

        $this->_table = $this->table;
        $this->_pk = $this->pk;
    }

    public function getAll()
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

}


?>