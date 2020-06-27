<?php

class SAM_Model extends CI_Model{
	protected $_table;
	protected $_pk;
	protected $_where;

    public function __construct(){
        parent::__construct();
        $this->load->database();   
    }

    protected function _get() 
    {
    	if($this->_where) $this->db->where($this->_where);
        return $this->db->get($this->_table);
    }

    protected function _delete() 
    {
        $this->db->where($this->_where);
        $delete = $this->db->delete($this->_table);
        if(!$delete){
            $return = false;
        }else $return = true;

        return $return;
    }

    public function save($data, $id = NULL) 
    {
    	// insert 
        if ($id == NULL) {
            $this->db->set($data,FALSE);
            $insert = $this->db->insert($this->_table);
            if(!$insert){
                $return = false;
            }else $return = $this->db->insert_id($this->_table,$this->_pk);
        }
        // update
        else {
            $this->db->set($data);
    		
    		if($this->_where) $this->db->where($this->_where);

            $this->db->where($this->_pk, $id);
            $update = $this->db->update($this->_table);
            
            if(!$update){
                $return = false;
            }else $return = $id;
        }
        return $return;
    }

    public function save_bulk($data)
    {
        $insert = $this->db->insert_batch($this->_table, $data);
        if(!$insert){
                $return = false;
        }else $return = $this->db->insert_id($this->_table,$this->_pk);
        
        return $return;
    }
}