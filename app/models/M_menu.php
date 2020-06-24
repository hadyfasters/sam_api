<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class M_menu extends SAM_Model {
    private $table = 'menu';
    private $pk = 'id_menu';

    public function __construct()
    {
        parent::__construct();

        $this->_table = $this->table;
        $this->_pk = $this->table;
    }

    public function getAll()
    {
        $data = array(
            'active' => 1
        );

        $this->db->where($data);
        $this->db->order_by('parent ASC,sequence ASC');
		$query = $this->db->get($this->_table);

        if ($query->num_rows() > 0) 
		{
			return $query->result();
		}
    }

    public function getMain()
    {
        $data = array(
            'parent' => 0,
            'active' => 1
        );

        $this->db->where($data);
        $this->db->order_by('sequence ASC');
        $query = $this->db->get($this->_table);

        if ($query->num_rows() > 0) 
        {
            return $query->result();
        }
    }

    public function getSubs($parent)
    {
        $data = array(
            'parent' => $parent,
            'active' => 1
        );

        $this->db->where($data);
        $this->db->order_by('sequence ASC');
        $query = $this->db->get($this->_table);

        if ($query->num_rows() > 0) 
        {
            return $query->result();
        }
    }

}


?>