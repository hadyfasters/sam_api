<?php defined('BASEPATH') OR exit('No direct script access allowed');

class M_report extends SAM_Model {

    public function __construct(){
        parent::__construct();
    }

    public function getActivityReport()
    {
        $_stringQuery = "";
        $_stringQuery = $_stringQuery . "a.lead_id, a.nama_prospek , a.alamat, '' kontak_person, a.no_kontak,";
        $_stringQuery = $_stringQuery . "a.potensi_dana, b.product_name, a.kategori_nasabah, a.jenis_nasabah,";
        $_stringQuery = $_stringQuery . "a.additional_info, a.created_date, a.created_by, c.issued_date as fu_call_date, c.issued_time as fu_call_time, ";
        $_stringQuery = $_stringQuery . "d.issued_date as fu_meet_date, d.issued_time as fu_meet_time, e.issued_date as fu_close_date, e.issued_time as fu_close_time";
        $this->db->select($_stringQuery);        
        $this->db->join('ms_product b', 'b.product_id = a.produk', 'inner');
        $this->db->join('(select * from dt_call) c','c.lead_id = a.lead_id','left outer');
        $this->db->join('(select * from dt_meet) d','d.lead_id = a.lead_id','left outer');
        $this->db->join('(select * from dt_close) e','e.lead_id = a.lead_id','left outer');
        $query = $this->db->get('dt_lead a');
        if ($query->num_rows() > 0) 
        {
            return $query->result();
        }
        return FALSE;
    }
}

?>