<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Transaksi extends CI_Controller
	{
		public function __construct()
		{
			parent::__construct();
			sesiOperator();
			$this->load->model('m_transaksi');
		}

		public function index()
		{ 
			$data = array (
				'list' => $this->model_transaksi->data(),
				'total' => $this->model_transaksi->total_trans()
			);
			$this->load->view('header');
			$this->load->view('transaksi/list_transaksi', $data);
			$this->load->view('footer');
		}

		public function checkout()
		{
			$data = array (
				'brg' => $this->model_transaksi->get_barang(true),
				'list' => $this->model_transaksi->data_co(),
				'total' => $this->model_transaksi->total_co(),
				'dt' => $this->model_transaksi->id_transaksi_detail('transaksi_detail'),
			    'it' => $this->model_transaksi->id_transaksi('transaksi')

			);
			$this->load->view('header');
			$this->load->view('transaksi/checkout_form', $data);
			$this->load->view('footer');
		}

		public function simpan_barang()
		{
			$id_barang = $this->input->post('nama_barang');
			$qty = $this->input->post('qty');
			$data_barang = $this->db->get_where('barang', array('nama_barang'=>$id_barang))->row_array();
			$data = array(
				'id_barang' => $data_barang['id_barang'],
				'nama_barang' => $id_barang,
				'harga' => $data_barang['harga'],
				'qty' => $qty,
				'sub_total' => $data_barang['harga'] * $qty
			);
			$this->m_transaksi->temp_trans('temp_transaksi', $data);
			redirect('transaksi/checkout');
		}

		public function hapus($id_barang)
		{
			$this->db->where('id_barang', $id_barang);
			$this->db->delete('temp_transaksi');
			redirect('transaksi/checkout');
		}

		public function selesai_belanja()
		{
			$id_transaksi = $this->input->post('id_transaksi');
			$transaksi_detail = $this->input->post('id_detail');
			$total = $this->input->post('total');
			$tgl = date('Y-m-d');
			$id_op = $this->session->userdata('id_operator');

			$dataDetail = array (
				't_detail_id' => $transaksi_detail,
				'id_transaksi' => $id_transaksi,
				'operator' =>  $id_op,
				'total' => $total,
				'tgl' => $tgl,
				'status' => 'Lunas'
			);

			$dataTrans = array (
				'id_transaksi' => $id_transaksi,
				'tanggal_transaksi' => $tgl,
				'operator_id' =>  $id_op
			);

			$transaksi = $this->model_transaksi->selesai('transaksi', $dataTrans);
			$transaksiDetail = $this->model_transaksi->selesai('transaksi_detail', $dataDetail);
			$this->db->truncate('temp_transaksi');
			redirect('transaksi');
		}

		public function cariData()
		{
			$awal = strtotime($this->input->post('awal'));
			$berakhir = strtotime($this->input->post('akhir'));

			$mulai = date('Y-m-d', $awal);
			$akhir = date('Y-m-d', $berakhir); 

			$data = array(
				'hasil' => $this->m_transaksi->cariTanggal($mulai,$akhir),
				'total' => $this->m_transaksi->totalCari($mulai,$akhir)
			);

			if(!empty($data))
			{
				$this->load->view('header');
				$this->load->view('transaksi/list_pencarian', $data);
				$this->load->view('footer');
			}else
			{
				redirect('transaksi/index');
			}
		}

		public function getExcel()
		{
			$this->load->library("PHPexcel");

			$excel = new PHPexcel();

			$excel->getProperties()
					->setCreator('Point Of Sale Report')
					->setLastModifiedBy('Operator')
					->setTitle("Laporan Transaksi")
					->setSubject("Laporan Data Transaksi")
					->setDescription("Laporan Transaksi")
					->setKeywords("Data Transaksi");

			$style_col = array (
					'font' => array ('bold' => true),
					'alignment' => array (
						'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
						'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
					),
					'borders' => array(
						'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
						'right' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
						'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
						'left' => array('style' => PHPExcel_Style_Border::BORDER_THIN)
					)
			) ;

			$style_row = array (
				'aligment' => array (
					'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
				),
				'borders' => array(
						'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
						'right' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
						'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
						'left' => array('style' => PHPExcel_Style_Border::BORDER_THIN)
					)
			);	

			$excel->setActiveSheetIndex(0)->setCellValue('A1', "Data Transaksi");
			$excel->getActiveSheet()->mergeCells('A1:D1');
			$excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(TRUE);
			$excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(15);

			$excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

			$excel->setActiveSheetIndex(0)->setCellValue('A3', "No");
			$excel->setActiveSheetIndex(0)->setCellValue('B3', "Tanggal Transaksi");
			$excel->setActiveSheetIndex(0)->setCellValue('C3', "Operator");
			$excel->setActiveSheetIndex(0)->setCellValue('D3', "Jumlah Transaksi");

			$excel->getActiveSheet()->getStyle('A3')->applyFromArray($style_col);
			$excel->getActiveSheet()->getStyle('B3')->applyFromArray($style_col);
			$excel->getActiveSheet()->getStyle('C3')->applyFromArray($style_col);
			$excel->getActiveSheet()->getStyle('D3')->applyFromArray($style_col);

			$data = $this->m_transaksi->data();
			$total = $this->m_transaksi->total_trans();

			$no = 1;
			$numrow = 4;
			

			foreach($data as $d)
			{
				$time = strtotime($d->tgl);
				$date = date('d-m-Y', $time);

				$excel->setActiveSheetIndex(0)->setCellValue('A'.$numrow, $no);
				$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrow, $date);
				$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrow, $d->nm_lengkap);
				$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrow, $d->total);

				$excel->getActiveSheet()->getStyle('A'.$numrow)->applyFromArray($style_row);
				$excel->getActiveSheet()->getStyle('B'.$numrow)->applyFromArray($style_row);
				$excel->getActiveSheet()->getStyle('C'.$numrow)->applyFromArray($style_row);
				$excel->getActiveSheet()->getStyle('D'.$numrow)->applyFromArray($style_row);

				$no++;
				$numrow++;
			}

			$excel->setActiveSheetIndex(0)->setCellValue('G7', "Total Transaksi");
			$excel->getActiveSheet()->getStyle('G7:I7')->applyFromArray($style_col);
			$excel->getActiveSheet()->mergeCells('G7:I7');
			$excel->getActiveSheet()->getStyle('G7')->getFont()->setBold(TRUE);
			$excel->getActiveSheet()->getStyle('G7')->getFont()->setSize(15);

			$numrow1 = 8;

			foreach($total as $t)
			{
				$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrow1, $t->total);
				$excel->getActiveSheet()->mergeCells('G8:I8');

				$excel->getActiveSheet()->getStyle('G8'.$numrow)->applyFromArray($style_col);
			}

			$excel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
			$excel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
			$excel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
			$excel->getActiveSheet()->getColumnDimension('D')->setWidth(25);

			$excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);

			$excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);

			$excel->getActiveSheet(0)->setTitle("Laporan Transaksi");
			$excel->setActiveSheetIndex(0);

			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment; filename="Lap Transaksi.xlsx"');
			header('Cache-Control: max-age=0');

			$write = PHPExcel_IOFactory::CreateWriter($excel, 'Excel2007');
			$write->save('php://output');
		}

		public function getPdf()
		{
			$this->load->library('pdf');
			$data = array (
				'list' => $this->m_transaksi->data(),
				'total' => $this->m_transaksi->total_trans()
			);

			$this->load->view('cetak/cetak_transaksi', $data);
			$html = $this->output->get_output();
			$this->pdf->load_html($html);
			$this->pdf->set_paper("A4", "landscape");
			$this->pdf->render();
			$this->pdf->stream("Laporan Transaksi.pdf");
		}
	} 
?>