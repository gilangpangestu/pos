<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Operator extends CI_Controller
	{
		public function __construct()
		{
			parent::__construct();
			sesiOperator();
			$this->load->model('model_operator');
		}

		public function index()
		{
			$this->load->library('pagination');
			$total_data = $this->model_operator->jumlah_data();
			$config['base_url'] = base_url().'Operator/index';
			$config['total_rows'] = $total_data;
			$config['per_page'] = 5;
			$from = $this->uri->segment(3);
			$this->pagination->initialize($config);
			$data['operator'] = $this->model_operator->data($config['per_page'],$from);
			$this->load->view('header');
			$this->load->view('operator/list_operator', $data);
			$this->load->view('footer');	
		}

		public function v_add()
		{
			$this->load->view('header');
			$this->load->view('operator/add_operator');
			$this->load->view('footer');
		}

		public function daftar()
		{
			$id_operator = $this->input->post('id_operator');
			$nama_lengkap = $this->input->post('nama_lengkap');
			$username = $this->input->post('username');                          
			$password = $this->input->post('password');

			$data = array (
				'id_operator' => $id_operator,
				'nama_lengkap' => $nama_lengkap,
				'username' => $username,
				'password' => SHA1($password)
			);

			$this->model_operator->simpan('operator',$data);
			redirect('Operator');
		}

		public function editData($id_operator)
		{
			$where = array (
				'id_operator' => $id_operator
			);

			$data['operator'] = $this->model_operator->get_data('operator', $where)->result();

			$this->load->view('header');
			$this->load->view('operator/edit_operator', $data);
			$this->load->view('footer');
		}

		public function update()
		{
			$id = $this->input->post('id');
			$nama_lengkap = $this->input->post('nama_lengkap');
			$username = $this->input->post('username');
			$password = $this->input->post('password');

			$where = array (
				'id_operator' => $id
			);

			if(!empty($password))
			{
				$data = array (
					'id_operator' => $id,
					'nama_lengkap' => $nama_lengkap,
					'username' => $username,
					'password' => SHA1($password)
				);
			}else
			{
				$data = array (
					'id_operator' => $id,
					'nama_lengkap' => $nama_lengkap,
					'username' => $username,
				);
			}

			$this->model_operator->edit('operator', $data, $where);
			redirect('Operator');
		}

		public function hapus($id_operator)
		{
			$where = array (
				'id_operator' => $id_operator
			);

			$this->model_operator->hapus('operator', $where);
			redirect('Operator');
		}
	}
?>
