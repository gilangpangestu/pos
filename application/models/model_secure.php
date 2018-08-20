<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class model_secure extends CI_Model
	{
		public $table = "operator";

		public function cekData($username,$password)
		{
			$this->db->where('username', $username);
			$this->db->where('password', SHA1($password));
			$data = $this->db->get($this->table)->row_array();
			return $data;
		}

		public function last_login($table,$data,$username)
		{
			$this->db->where('username', $username);
			$this->db->update($table, $data);
		}
	}
?>