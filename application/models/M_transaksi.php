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
}
