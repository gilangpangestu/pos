<?php
    defined('BASEPATH') OR exit('No direct script access allowed');

    class model_barang extends CI_Model
    {
        public $table = "barang";

        public function data($number, $offset)
        {
            return $query = $this->db->get('barang', $number,$offset)->result();
        }

        public function simpan($data,$table)
        {
            $this->db->insert($table,$data);
        }

        public function get_data($table,$where)
        {
            return $this->db->get_where($table,$where);
        }

        public function edit($table,$data,$where)
        {
            $this->db->where($where);
            $this->db->update($table,$data);
        }

        public function hapus($table,$where)
        {
            $this->db->where($where);
            $this->db->delete($table);
        }

        public function jumlah_data()
        {
            return $this->db->get('barang')->num_rows();
        }

        function BarangId()
        {
            $this->db->select('RIGHT(barang.id_barang,3) as kode', FALSE);
            $this->db->order_by('kategori_id','DESC');
            $this->db->limit(1);
            $query = $this->db->get('barang');
            if($query->num_rows() <> 0)
            {
                $data =$query->row();
                $kode = intval($data->kode) + 1;
            }else
            {
                $kode = 1;
            }

            $kodemax = str_pad($kode,4, "0", STR_PAD_LEFT);
            $kodejadi = "B".$kodemax;
            return $kodejadi;
        }
    }
?>