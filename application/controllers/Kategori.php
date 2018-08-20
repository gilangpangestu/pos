<?php
    defined('BASEPATH') OR exit('No direct script access allowed');

    class Kategori extends CI_Controller
    {
        public function __construct()
        {
            parent::__construct();
            sesiOperator();
            $this->load->model('_kategori');
        }

        public function index()
        {
            $this->load->library('pagination');
            $total_data = $this->model_kategori->jumlah_data();
            $config['base_url'] = base_url().'Kategori/index';
            $config['total_rows'] = $total_data;
            $config['per_page'] = 5;
            $from = $this->uri->segment(3);
            $this->pagination->initialize($config);
            $data['ktg'] = $this->model_kategori->data($config['per_page'],$from);
            $this->load->view('header');
            $this->load->view('kategori/list_katbar', $data);
            $this->load->view('footer');
        }

        public function tambah_kategori()
        {
            $data['auto'] = $this->model_kategori->kategoriId();
            $this->load->view('header');
            $this->load->view('kategori/add_katbar', $data);
            $this->load->view('footer');
        }

        public function simpan()
        {
            $kategori_id = $this->input->post('id_kategori');
            $nm_kategori = $this->input->post('nama_kategori');
            $data = array (
                'id_kategori' => $id_kategori,
                'nama_kategori' => $nama_kategori
            );

            $this->model_kategori->input_data($data,'kategori_barang');
            redirect('kategori/index');
        }

        public function editData($id_kategori)
        {
            $where = array (
                'id_kategori' => $id_kategori
            );

            $data['kategori_barang'] = $this->model_kategori->get_data('kategori_barang', $where)->result();
            $this->load->view('header');
            $this->load->view('kategori/edit_katbar', $data);
            $this->load->view('footer');
        }

        public function update()
        {
            $idkat = $this->input->post('id_kategori');
            $namkat = $this->input->post('nama_kategori');

            $data = array (
                'id_kategori'=> $idkat,
                'nama_kategori' => $namkat
            );

            $where = array (
                'id_kategori' => $idkat
            );

            $this->model_kategori->update_data('kategori_barang',$data,$where);
            redirect('kategori/index');
        }

        public function hapus($id_kategori)
        {
            $where = array (
                'id_kategori' => $id_kategori
            );

            $this->model_kategori->hapus_data('kategori_barang',$where);
            redirect('kategori/index');
        }
    }
?>