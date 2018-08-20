<?php 
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Dashboard extends CI_Controller
	{
		function __construct()
		{
			parent::__construct();
			sesiOperator();
			$this->load->model('model_barang');
			$this->load->model('model_kategori');
			$this->load->model('model_operator');
			$this->load->model('model_transaksi');

		}

		public function index()
		{
			$this->load->view('header');
			$this->load->view('footer');
		}

		public function kategori()
		{
			redirect('kategori');
		}

		public function barang()
		{
			redirect('barang');
		}

		public function transaksi()
		{
			redirect('transaksi');
		}

		public function operator()
		{
			redirect('operator');
		}
	}
?>