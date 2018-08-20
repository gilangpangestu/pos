<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	class Secure extends CI_Controller
	{
		public function __construct()
		{
			parent::__construct();
			$this->load->model('model_secure');
		}

		public function index()
		{
			$this->load->view('view_secure');
		}

		public function aksi()
		{
			$username = $this->input->post('username');
			$password = $this->input->post('password');

			$login_data = $this->model_secure->cekData($username, $password);

			if(!empty($login_data))
			{
				$this->session->set_userdata($login_data);

				$data = array (
					'last_login' => date('Y-m-d')
				);

				$this->model_secure->last_login('operator', $data, $username);	
				redirect('dashboard');
			}else
			{
				redirect('secure');
			}
		}

		public function logout()
		{
			$this->session->sess_destroy();
			redirect('secure');
		}
	}	
?>