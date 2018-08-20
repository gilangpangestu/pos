<?php
    defined('BASEPATH') OR exit('No direct script access allowed');

    class model_operator extends CI_Model
    {

        public function data($number, $offset)
        {
            return $query = $this->db->get('operator', $number,$offset)->result();
        }

        public function jumlah_data()
        {
            return $this->db->get('operator')->num_rows();
        }

        public function simpan($table,$data)
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
    }
?>