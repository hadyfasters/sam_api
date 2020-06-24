<?php defined('BASEPATH') OR exit('No direct script access allowed');

class M_lead extends SAM_Model {
    private $table = 'dt_lead';
    private $pk = 'lead_id';

    public function __construct(){
        parent::__construct();

        $this->_table = $this->table;
        $this->_pk = $this->pk;
    }

    public function getAll()
    {
        $this->db->select('dt_lead.*,dt_call.issued_date as call_date,dt_meet.issued_date as meet_date, dt_close.issued_date as close_date');
        $this->db->join('dt_call','dt_call.lead_id=dt_lead.lead_id','left');
        $this->db->join('dt_meet','dt_meet.lead_id=dt_lead.lead_id','left');
        $this->db->join('dt_close','dt_close.lead_id=dt_lead.lead_id','left');
        $this->db->order_by('dt_lead.lead_id DESC');
        $result = $this->_get();

        if ($result->num_rows() > 0) 
		{
			return $result->result();
		}
    }

    public function getSearch($data)
    {
        $where = [];
        if(!empty($data)){
            foreach ($data as $key => $value) {
                if(!empty($value) && $key!='nama_prospek'){
                    $where[$key] = $value;
                }
            }
        }

        if($where){
            $this->_where = $where;
        }

        if($data->nama_prospek){
            $this->db->like('nama_prospek',$data->nama_prospek);
        }

        $this->db->order_by('lead_id DESC');
        $result = $this->_get();

        if ($result->num_rows() > 0) 
        {
            return $result->result();
        }
    }

    public function getByID($id)
    {
        $this->db->select('dt_lead.*,ms_product.product_name,ms_provinces.name as provinsi,ms_regencies.name as kota_kabupaten, ms_districts.name as kecamatan, ms_villages.name as kelurahan');
        $this->db->join('ms_product','ms_product.product_id=dt_lead.produk','left');
        $this->db->join('ms_provinces','ms_provinces.id=dt_lead.provinsi','left');
        $this->db->join('ms_regencies','ms_regencies.id=dt_lead.kota_kabupaten','left');
        $this->db->join('ms_districts','ms_districts.id=dt_lead.kecamatan','left');
        $this->db->join('ms_villages','ms_villages.id=dt_lead.kelurahan','left');

        $this->_where = "dt_lead.lead_id = {$id}";

        $result = $this->_get();

        if ($result->num_rows() > 0) 
        {
            return $result->row();
        }
    }

}


?>