<?php defined('BASEPATH') OR exit('No direct script access allowed');

class M_meet extends SAM_Model {
    private $table = 'dt_meet';
    private $pk = 'meet_id';

    public function __construct(){
        parent::__construct();

        $this->_table = $this->table;
        $this->_pk = $this->pk;
    }

    public function getAll()
    {
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
                        $where['dt_meet.created_by'] = $value;
                    }elseif($key=='status'){
                        $where['dt_meet.status'] = $value;
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

        $this->db->distinct();
        $this->db->select('dt_meet.*, dt_lead.nama_prospek,dt_lead.jenis_nasabah,dt_lead.potensi_dana,dt_lead.created_date as lead_date,ms_product.product_name,dt_call.issued_date as call_date,dt_meet.issued_date as meet_date, dt_close.issued_date as close_date, dt_user.branch_code');
        $this->db->join('dt_lead','dt_lead.lead_id=dt_meet.lead_id','inner');
        $this->db->join('dt_call','dt_call.lead_id=dt_lead.lead_id','left');
        $this->db->join('dt_close','dt_close.lead_id=dt_lead.lead_id','left');
        $this->db->join('dt_user','dt_user.id_user=dt_meet.created_by','left');
        $this->db->join('ms_product','ms_product.product_id=dt_lead.produk','left');
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
        dt_lead.kelurahan as kelurahan_id,dt_lead.produk,dt_lead.kontak_person,dt_lead.no_kontak,dt_lead.potensi_dana,
        dt_lead.created_date,dt_lead.created_by,ms_product.product_name,ms_provinces.name as provinsi,
        ms_regencies.name as kota_kabupaten, ms_districts.name as kecamatan, 
        ms_villages.name as kelurahan,dt_meet.meet_id,dt_meet.approval,dt_meet.approval_date,dt_meet.status,dt_meet.attempt,dt_meet.issued_date,
        dt_meet.issued_time,dt_meet.additional_info,dt_meet.call_id,dt_call.attempt as call_attempt,dt_call.issued_date as call_date,dt_call.issued_time as call_time');
        $this->db->join('dt_lead','dt_lead.lead_id=dt_meet.lead_id','inner');
        $this->db->join('dt_call','dt_call.lead_id=dt_lead.lead_id','inner');
        $this->db->join('ms_product','ms_product.product_id=dt_lead.produk','left');
        $this->db->join('ms_provinces','ms_provinces.id=dt_lead.provinsi','left');
        $this->db->join('ms_regencies','ms_regencies.id=dt_lead.kota_kabupaten','left');
        $this->db->join('ms_districts','ms_districts.id=dt_lead.kecamatan','left');
        $this->db->join('ms_villages','ms_villages.id=dt_lead.kelurahan','left');

        $this->_where = "meet_id = {$id}";

        $result = $this->_get();

        if ($result->num_rows() > 0) 
        {
            return $result->row();
        }
    }

    public function getByLeadID($id)
    {
        $this->_where = "lead_id = {$id}";

        $result = $this->_get();

        if ($result->num_rows() > 0) 
        {
            return $result->row();
        }
    }

    public function getAttempt($id)
    {
        $attempt = 0;

        $this->db->join('tx_meet','tx_meet.lead_id=dt_meet.lead_id','inner');

        $this->_where = "dt_meet.lead_id = {$id}";

        $result = $this->_get();

        if($result->num_rows() > 0) $attempt = $result->num_rows();

        return $attempt;
    }

    public function saveTxMeet($data,$id=null)
    {
        $this->_table = 'tx_meet';
        $this->_pk = 'tx_id';
        $response = $this->save($data,$id);

        return $response;
    }

    public function getTxMeet($id)
    {
        $this->_table = 'tx_meet';
        $this->_where = "lead_id = {$id}";
        $response = $this->_get();

        return $response->result();
    }

    public function removeThis($id)
    {
        $tables = array('dt_meet', 'tx_meet');
        $this->db->where('lead_id', $id);
        if($this->db->delete($tables)){
            return TRUE;
        }else{
            return FALSE;
        }
    }

    public function getProspect($data)
    {
        $this->_where = [
            'dt_lead.kategori_nasabah' => $data->kategori_nasabah,
            'dt_meet.approval' => 1,
            'dt_meet.status' => 1,
            'dt_meet.created_by' => $data->user
        ];

        $this->db->join('dt_lead','dt_lead.lead_id=dt_meet.lead_id','inner');
        $this->db->order_by('dt_lead.nama_prospek');
        $result = $this->_get();
   
        if ($result->num_rows() > 0) 
        {
            return $result->result();
        }
    }

}


?>