<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class model_transaksi extends CI_Model
	{
		public function data()
		{
			$query = "SELECT transaksi_detail.*,operator.nm_lengkap FROM transaksi_detail INNER JOIN operator ON transaksi_detail.operator=operator.operator_id";
			return $this->db->query($query)->result();
		}

		public function get_barang()
		{
			$query = "SELECT id_barang,nm_barang,harga FROM barang ORDER BY id_barang";
			return $this->db->query($query)->result();
		}

		public function temp_trans($table,$data)
		{
			$this->db->insert($table,$data);
		}

		public function data_co()
		{
			$query = "SELECT * FROM temp_transaksi ORDER BY id_barang";
			return $this->db->query($query)->result();
		}

		public function total_co()
		{
			$query = "SELECT SUM(sub_total) AS total FROM temp_transaksi";
			return $this->db->query($query)->result();
		}

		public function hapus_co($table, $where)
		{
			$this->db->where('id_barang', $where);
			$this->db->delete($table);
		}

		function id_transaksi_detail($table)
		{
			$this->db->select('RIGHT(transaksi_detail.t_detail_id,3) as kode', FALSE);
			$this->db->order_by('t_detail_id','DESC');
			$this->db->limit(1);
			$query = $this->db->get($table);
			if($query->num_rows() <> 0)
			{
				$data =$query->row();
				$kode = intval($data->kode) + 1;
			}else
			{
				$kode = 1;
			}

			$kodemax = str_pad($kode,3, "0", STR_PAD_LEFT);
			$kodejadi = date('md').$kodemax;
			return $kodejadi;
		}

		function id_transaksi($table)
		{
			$this->db->select('RIGHT(transaksi.id_transaksi,4) as kode', FALSE);
			$this->db->order_by('id_transaksi','DESC');
			$this->db->limit(1);
			$query = $this->db->get($table);
			if($query->num_rows() <> 0)
			{
				$data =$query->row();
				$kode = intval($data->kode) + 1;
			}else
			{
				$kode = 1;
			}

			$kodemax = str_pad($kode, 4, "0", STR_PAD_LEFT);
			$kodejadi = date('d').$kodemax;
			return $kodejadi;
		}

		public function selesai($table,$data)
		{
			$this->db->insert($table,$data);
		}

		public function hasil($table)
		{
			return $this->db->get_where('t_detail_id', $where);
		}

		public function total_trans()
		{
			$query = "SELECT SUM(total) AS total FROM transaksi_detail";
			return $this->db->query($query)->result();
		}

		public function cariTanggal($mulai, $akhir)
		{
			$query = "SELECT transaksi_detail.*,operator.nm_lengkap FROM transaksi_detail INNER JOIN operator ON transaksi_detail.operator=operator.operator_id AND transaksi_detail.tgl BETWEEN '$mulai' AND '$akhir'";
			return $this->db->query($query)->result();
		}

		public function totalCari($mulai,$akhir)
		{
			$query = "SELECT SUM(total) AS total FROM transaksi_detail WHERE tgl BETWEEN '$mulai' AND '$akhir'";
			return $this->db->query($query)->result();
		}
	}
?>