<?php
    defined('BASEPATH') OR exit('No direct script access allowed');

    class model_kategori extends CI_Model
    {
        public $table = "kategori_barang";

        public function data($number, $offset)
        {
            return $query = $this->db->get('kategori_barang', $number,$offset)->result();
        }

        public function jumlah_data()
        {
            return $this->db->get('kategori_barang')->num_rows();
        }

        public function DataList()
        {
            $query= "SELECT * FROM kategori_barang ORDER BY id_kategori ASC";
            return $this->db->query($query)->result();

        }

        function input_data($data,$table)
        {
            $this->db->insert($table,$data);
        }

        function get_data($table,$where)
        {
            return $this->db->get_where($table,$where);
        }

        function update_data($table,$data,$where)
        {
            $this->db->where($where);
            $this->db->update($table,$data);
        }

        function hapus_data($table,$where)
        {
            $this->db->where($where);
            $this->db->delete($table);
        }

        function kategoriId()
        {
            $this->db->select('RIGHT(kategori_barang.id_kategori,3) as kode', FALSE);
            $this->db->order_by('id_kategori','DESC');
            $this->db->limit(1);
            $query = $this->db->get('kategori_barang');
            if($query->num_rows() <> 0)
            {
                $data =$query->row();
                $kode = intval($data->kode) + 1;
            }else
            {
                $kode = 1;
            }

            $kodemax = str_pad($kode,4, "0", STR_PAD_LEFT);
            $kodejadi = "K".$kodemax;
            return $kodejadi;
        }
    }
?>