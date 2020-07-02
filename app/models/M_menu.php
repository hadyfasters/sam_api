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

    public function getMain($roles=null,$is_sa=0)
    {
        $data = array(
            'menu.parent' => 0,
            'menu.sa_only' => 0,
            'menu.active' => 1
        );

        if($is_sa){
            unset($data['menu.sa_only']);
        }

        if($roles){
            $data['menu_permission.id_roles'] = $roles;
            $data['menu_permission.is_active'] = 1;

            $this->db->select('menu.*,ms_roles.role_id,ms_roles.role_name,menu_permission.is_active');
            $this->db->join('menu_permission','menu.id_menu=menu_permission.id_menu','inner');
            $this->db->join('ms_roles','menu_permission.id_roles=ms_roles.role_id','left');
        }

        $this->db->where($data);
        $this->db->order_by('sequence ASC');
        $query = $this->db->get($this->_table);

        if ($query->num_rows() > 0) 
        {
            return $query->result();
        }
    }

    public function getSubs($parent,$roles=null,$is_sa=0)
    {
        $data = array(
            'menu.parent' => $parent,
            'menu.sa_only' => 0,
            'menu.active' => 1
        );

        if($is_sa){
            unset($data['menu.sa_only']);
        }

        if($roles){
            $data['menu_permission.id_roles'] = $roles;
            $data['menu_permission.is_active'] = 1;

            $this->db->select('menu.*,ms_roles.role_id,ms_roles.role_name,menu_permission.is_active');
            $this->db->join('menu_permission','menu.id_menu=menu_permission.id_menu','inner');
            $this->db->join('ms_roles','menu_permission.id_roles=ms_roles.role_id','left');
        }
        
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