<?php defined('BASEPATH') OR exit('No direct script access allowed');

class M_captcha extends SAM_Model {
    private $table = 'captcha';
    private $pk = 'captcha_id';

    public function __construct(){
        parent::__construct();

        $this->_table = $this->table;
        $this->_pk = $this->pk;
    }

    public function removeOld()
    {
        $expiration = time() - 7200; // Two hour limit
        $this->db->where('captcha_time < ', $expiration)->delete('captcha');
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

    public function getCaptcha($data)
    {
        $expiration = time() - 7200; // Two hour limit
        $sql = 'SELECT COUNT(*) AS count FROM captcha WHERE word = ? AND ip_address = ? AND captcha_time > ?';
        $binds = array($data->captcha, $data->ip_address, $expiration);
        $query = $this->db->query($sql, $binds);
        $row = $query->row();

        if ($row->count == 0)
        {
            return FALSE;
        }
        return TRUE;
    }

}


?>