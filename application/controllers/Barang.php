<?php
    defined('BASEPATH') OR exit('No direct script access allowed');

    class Barang extends CI_Controller
    {
        public function __construct()
        {
            parent::__construct();
            sesiOperator();
            $this->load->model('model_barang');
            $this->load->model('model_kategori');
        }

        public function index()
        {
            $this->load->library('pagination');
            $total_data = $this->model_barang->jumlah_data();
            $config['base_url'] = base_url().'Barang/index';
            $config['total_rows'] = $total_data;
            $config['per_page'] = 5;
            $from = $this->uri->segment(3);
            $this->pagination->initialize($config);
            $data['brg'] = $this->model_barang->data($config['per_page'],$from);
            $this->load->view('header');
            $this->load->view('barang/list_barang', $data);
            $this->load->view('footer');
        }

        public function tambah_barang()
        {
                $data['kategori_barang'] = $this->model_kategori->DataList(true);
                $data['auto'] = $this->model_barang->BarangId();
                $this->load->view('header');
                $this->load->view('barang/add_barang', $data);
                $this->load->view('footer');
        }

        public function simpan()
        {
                $id = $this->input->post('id');
                $ktg = $this->input->post('ktg');
                $nm = $this->input->post('nm');
                $hg = $this->input->post('hg');

                $data = array (
                    'id_barang' => $id,
                    'id_kategori' => $ktg,
                    'nama_barang' => $nm,
                    'harga' => $hg
                );

                $this->model_barang->simpan($data,'barang');
                redirect('barang/index');
        }

        public function editData($id_barang)
        {
            $where = array (
                'id_barang' => $id_barang
            );

            $data = array (
                'brg' => $this->model_barang->get_data('barang', $where)->result(),
                'ktg' => $this->model_kategori->DataList(true)
            );
            $this->load->view('header');
            $this->load->view('barang/edit_barang', $data);
            $this->load->view('footer');
        }

        public function update()
        {
            $id = $this->input->post('id');
            $ktg = $this->input->post('ktg');
            $nm = $this->input->post('nm');
            $hg = $this->input->post('hg');

            $data = array (
                    'id_barang' => $id,
                    'id_kategori' => $ktg,
                    'nama_barang' => $nm,
                    'harga' => $hg
                );

            $where = array (
                'id_barang' => $id
            );

            $this->model_barang->edit('barang', $data, $where);
            redirect('barang/index');
        }

        public function hapus($id_barang)
        {
            $where = array (
                'id_barang' => $id_barang
            );

            $this->model_barang->hapus('barang',$where);
            redirect('barang/index');
        }
    }
?>