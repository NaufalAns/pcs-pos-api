<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_transaksi extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	public function getTransaksi()
	{
		$this->db->select('transaksi.*, admin.nama as nama_admin');
		$this->db->from('transaksi');
		$this->db->join('admin', 'admin.id = transaksi.admin_id');
		$query = $this->db->get();
		return $query->result_array();
	}

	public function getTransaksiBulanIni()
	{
		$this->db->select('transaksi.*, admin.nama as nama_admin');
		$this->db->from('transaksi');
		$this->db->join('admin', 'admin.id = transaksi.admin_id');
		$this->db->where('MONTH(transaksi.tanggal)', date('m'));
		$this->db->where('YEAR(transaksi.tanggal)', date('Y'));
		$query = $this->db->get();
		return $query->result_array();
	}

	public function getTransaksiById($id)
	{
		$query = $this->db->get_where('transaksi', array('id' => $id));
		return $query->row_array();
	}

	public function insertTransaksi($data)
	{
		$this->db->insert('transaksi', $data);

		$insert_id = $this->db->insert_id();

		return $this->getTransaksiById($insert_id);
	}

	public function updateTransaksi($id, $data)
	{
		$this->db->where('id', $id);
		$this->db->update('transaksi', $data);

		return $this->getTransaksiById($id);
	}

	public function deleteTransaksi($id)
	{
		$result = $this->getTransaksiById($id);

		$this->db->where('id', $id);
		$this->db->delete('transaksi');

		return $result;
	}

	public function cekTransaksiExist($id)
	{
		$transaksi = $this->getTransaksiById($id);

		if (empty($transaksi)) {
			return false;
		}

		return true;
	}
	public function getTotalPendapatanBulanIni()
	{
		$this->db->select('SUM(total) as total');
		$this->db->from('transaksi');
		$this->db->where('MONTH(transaksi.tanggal)', date('m'));
		$this->db->where('YEAR(transaksi.tanggal)', date('Y'));
		$this->db->where('type', 'penjualan');
		$query = $this->db->get();
		return $query->row_array();
	}
	public function getTotalPembelianBulanIni()
	{
		$this->db->select('SUM(total) as total');
		$this->db->from('transaksi');
		$this->db->where('MONTH(transaksi.tanggal)', date('m'));
		$this->db->where('YEAR(transaksi.tanggal)', date('Y'));
		$this->db->where('type', 'pembelian');
		$query = $this->db->get();
		return $query->row_array();
	}
	public function getTotalTransaksiBulanIniBersih()
	{
		$pendapatan = $this->db->select('SUM(total) as total');
		$pendapatan = $this->db->from('transaksi');
		$pendapatan = $this->db->where('MONTH(transaksi.tanggal)', date('m'));
		$pendapatan = $this->db->where('YEAR(transaksi.tanggal)', date('Y'));
		$pendapatan = $this->db->where('type', 'penjualan');
		$pendapatan = $this->db->get();

		$pengeluaran = $this->db->select('SUM(total) as total');
		$pengeluaran = $this->db->from('transaksi');
		$pengeluaran = $this->db->where('MONTH(transaksi.tanggal)', date('m'));
		$pengeluaran = $this->db->where('YEAR(transaksi.tanggal)', date('Y'));
		$pengeluaran = $this->db->where('type', 'pembelian');
		$pengeluaran = $this->db->get();

		$pendapatanbersih = $pendapatan->row_array();
		$pengeluaranbersih = $pengeluaran->row_array();

		$hasil = $pendapatanbersih['total'] - $pengeluaranbersih['total'];

		return $hasil;
	}
}
