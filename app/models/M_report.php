<?php defined('BASEPATH') OR exit('No direct script access allowed');

class M_report extends SAM_Model {

    public function __construct(){
        parent::__construct();
    }

    public function getLeadReport()
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

    public function getCallReport()
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

    public function getMeetReport()
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

    public function getCloseReport()
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

    public function getLeadMainReport()
    {
        $_stringQuery = "";
        $_stringQuery = $_stringQuery . "f.npp, f.nama, g.code as branch_code, g.name as branch_name, h.code as region_code, ";
        $_stringQuery = $_stringQuery . "a.lead_id, a.nama_prospek , a.alamat, kontak_person, a.no_kontak, a.jenis_nasabah, ";
        $_stringQuery = $_stringQuery . "sum(a.potensi_dana) as potensi_dana_product, b.product_name, a.kategori_nasabah, count(a.lead_id) as total_lead, ";
        $_stringQuery = $_stringQuery . "sum(c.attempt) as total_call, "; 
        $_stringQuery = $_stringQuery . "round(sum(c.attempt) / count(a.lead_id),2) as perc_total_call, "; 
        $_stringQuery = $_stringQuery . "sum(d.attempts) as total_meet, "; 
        $_stringQuery = $_stringQuery . "round(sum(d.attempts) / count(a.lead_id),2) as perc_total_meet, "; 
        $_stringQuery = $_stringQuery . "count(close_id) as total_close, ";         
        $_stringQuery = $_stringQuery . "round(count(close_id) / count(a.lead_id),2) as perc_total_close, ";
        $_stringQuery = $_stringQuery . "sum(e.realisasi_dana) as realisasi_dana, "; 
        $_stringQuery = $_stringQuery . "round(sum(e.realisasi_dana) / sum(a.potensi_dana) * 100, 2) as perc_realisasi_dana ";
        $_stringQuery = $_stringQuery . "from dt_lead a ";
        $_stringQuery = $_stringQuery . "inner join ms_product b on b.product_id = a.produk ";
        $_stringQuery = $_stringQuery . "left outer join dt_call c on c.lead_id = a.lead_id ";
        $_stringQuery = $_stringQuery . "left outer join dt_meet d on d.lead_id = a.lead_id ";
        $_stringQuery = $_stringQuery . "left outer join dt_close e on e.lead_id = a.lead_id ";
        $_stringQuery = $_stringQuery . "inner join dt_user f on f.id_user = a.created_by ";
        $_stringQuery = $_stringQuery . "inner join ms_branch g on g.id_branch = f.branch_code ";
        $_stringQuery = $_stringQuery . "inner join ms_region h on h.id_region = g.id_region ";
        $_stringQuery = $_stringQuery . "where e.approval = 1 and a.created_by = 1 ";
        $_stringQuery = $_stringQuery . "group by f.npp, f.nama, g.code, g.name, h.code, ";
        $_stringQuery = $_stringQuery . "a.lead_id, a.nama_prospek , a.alamat, kontak_person, a.no_kontak, a.jenis_nasabah, "; 
        $_stringQuery = $_stringQuery . "a.potensi_dana, b.product_name, a.kategori_nasabah ";

        // if($data->data_summary){
        //     $this->db->and('data_summary',$data->data_summary);
        // }
        $this->db->select($_stringQuery);
        $result = $this->_get();
        if ($result->num_rows() > 0) 
        {
            return $result->result();
        }
    }
}

?>