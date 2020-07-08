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
        $this->db->select('dt_lead.*,ms_product.product_name,dt_call.issued_date as call_date,dt_meet.issued_date as meet_date, dt_close.issued_date as close_date');
        $this->db->join('ms_product','ms_product.product_id=dt_lead.produk','left');
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
                    if($key=='created_by'){
                        $where['dt_lead.created_by'] = $value;
                    }elseif($key=='status'){
                        $where['dt_lead.status'] = $value;
                    }else{
                        $where[$key] = $value;
                    }
                }
            }
        }

        if($where){
            $this->_where = $where;
        }

        if(isset($data->nama_prospek)){
            $this->db->like('nama_prospek',$data->nama_prospek);
        }

        $this->db->select('dt_lead.*,ms_product.product_name,dt_call.issued_date as call_date,dt_meet.issued_date as meet_date, dt_close.issued_date as close_date, dt_user.branch_code');
        $this->db->join('ms_product','ms_product.product_id=dt_lead.produk','left');
        $this->db->join('dt_call','dt_call.lead_id=dt_lead.lead_id','left');
        $this->db->join('dt_meet','dt_meet.lead_id=dt_lead.lead_id','left');
        $this->db->join('dt_close','dt_close.lead_id=dt_lead.lead_id','left');
        $this->db->join('dt_user','dt_user.id_user=dt_lead.created_by','left');
        $this->db->order_by('lead_id DESC');
        $result = $this->_get();

        if ($result->num_rows() > 0) 
        {
            return $result->result();
        }
    }

    public function getByID($id)
    {
        $this->db->select('dt_lead.lead_id,dt_lead.kategori_nasabah,dt_lead.nama_prospek,dt_lead.jenis_nasabah,dt_lead.alamat,
        dt_lead.provinsi as provinsi_id,dt_lead.kota_kabupaten as kota_kabupaten_id,dt_lead.kecamatan as kecamatan_id,
        dt_lead.kelurahan as kelurahan_id,dt_lead.produk,dt_lead.kontak_person,dt_lead.no_kontak,dt_lead.additional_info,dt_lead.potensi_dana,
        dt_lead.created_date,dt_lead.created_by,ms_product.product_name,ms_provinces.name as provinsi,
        ms_regencies.name as kota_kabupaten, ms_districts.name as kecamatan, 
        ms_villages.name as kelurahan,dt_lead.approval,dt_lead.approval_date,dt_lead.status');
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

    public function removeThis($where)
    {
        $this->_where = $where;
        return $this->_delete();
    }

    public function getProspect($data)
    {
        $this->_where = [
            'dt_lead.kategori_nasabah' => $data->kategori_nasabah,
            'dt_lead.approval' => 1,
            'dt_lead.status' => 1,
            'dt_lead.created_by' => $data->user
        ];
        $this->db->order_by('nama_prospek');
        $result = $this->_get();
   
        if ($result->num_rows() > 0) 
        {
            return $result->result();
        }
    }

}


?>