<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class M_users extends SAM_Model {
    private $table = 'dt_user';
    private $pk = 'id_user';

    public function __construct()
    {
        parent::__construct();

        $this->_table = $this->table;
        $this->_pk = $this->pk;
    }

    public function getAll()
    {
        $this->db->select('dt_user.id_user,dt_user.npp,dt_user.nama,dt_user.password,ms_branch.id_branch,ms_branch.code as branch_code,ms_region.id_region,ms_region.code as region_code,dt_user.roles,ms_roles.role_name,ms_roles.is_sa,ms_roles.acl_view,ms_roles.acl_input,ms_roles.acl_edit,ms_roles.acl_delete,ms_roles.acl_approve,dt_user.position,dt_position.position_name,dt_user.is_active');
        $this->db->join('dt_position','dt_position.position_id=dt_user.position','left');
        $this->db->join('ms_roles','ms_roles.role_id=dt_user.roles','left');
        $this->db->join('ms_branch','ms_branch.id_branch=dt_user.branch_code','left');
        $this->db->join('ms_region','ms_region.id_region=ms_branch.id_region','left');
        $query = $this->db->get($this->_table);

        if ($query->num_rows() > 0) 
        {
            return $query->result();
        }

        return FALSE;
    }

    public function getDataUser($npp)
    {
        $data = array(
            'dt_user.npp' => $npp,
            'dt_user.is_active' => 1
        );

        $this->db->select('dt_user.id_user,dt_user.npp,dt_user.nama,dt_user.password,ms_branch.id_branch,ms_branch.code as branch_code,ms_region.id_region,ms_region.code as region_code,dt_user.roles,ms_roles.role_name,ms_roles.is_sa,ms_roles.acl_view,ms_roles.acl_input,ms_roles.acl_edit,ms_roles.acl_delete,ms_roles.acl_approve,dt_user.position,dt_position.position_name,dt_user.is_active');
        $this->db->join('dt_position','dt_position.position_id=dt_user.position','inner');
        $this->db->join('ms_roles','ms_roles.role_id=dt_user.roles','inner');
        $this->db->join('ms_branch','ms_branch.id_branch=dt_user.branch_code','inner');
        $this->db->join('ms_region','ms_region.id_region=ms_branch.id_region','inner');
        $this->db->where($data);
		$query = $this->db->get($this->_table);

        if ($query->num_rows() > 0) 
		{
			return $query->row();
		}

		return FALSE;
    }

    public function getByID($id)
    {
        $data = array(
            'id_user' => $id
        );

        $this->db->select('dt_user.id_user,dt_user.npp,dt_user.nama,dt_user.password,ms_branch.id_branch,ms_branch.code as branch_code,ms_region.id_region,ms_region.code as region_code,dt_user.roles,ms_roles.role_name,ms_roles.is_sa,ms_roles.acl_view,ms_roles.acl_input,ms_roles.acl_edit,ms_roles.acl_delete,ms_roles.acl_approve,dt_user.position,dt_position.position_name,dt_user.is_active');
        $this->db->join('dt_position','dt_position.position_id=dt_user.position','inner');
        $this->db->join('ms_roles','ms_roles.role_id=dt_user.roles','inner');
        $this->db->join('ms_branch','ms_branch.id_branch=dt_user.branch_code','inner');
        $this->db->join('ms_region','ms_region.id_region=ms_branch.id_region','inner');
        $this->db->where($data);
        $query = $this->db->get($this->_table);

        if ($query->num_rows() > 0) 
        {
            return $query->row();
        }

        return FALSE;
    }

}


?>