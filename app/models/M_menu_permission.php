<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class M_menu_permission extends SAM_Model {
    private $table = 'menu_permission';
    private $pk = 'id_pm';

    public function __construct()
    {
        parent::__construct();

        $this->_table = $this->table;
        $this->_pk = $this->table;
    }

    public function getDataByPosition($id_roles)
    {
        $this->db->select('menu.*');
        $this->db->join('menu','menu.id_menu=menu_permission.id_menu','inner');

    	$this->_where = 'id_roles='.$id_roles.' AND menu.active=1';
    	$result = $this->_get();

        if ($result->num_rows() > 0) 
		{
			return $result->result();
		}
    }

    public function removeAll($id_position)
    {
    	$affected = false;

    	$this->_where = 'id_roles='.$id_position;
    	$this->_delete();

    	if($this->db->affected_rows()){
    		$affected = true;
    	}

    	return $affected;
    }

}


?>