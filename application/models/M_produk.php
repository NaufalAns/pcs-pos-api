<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_produk extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	public function getProduk()
	{
		$this->db->select('produk.*, admin.nama as nama_admin');
		$this->db->from('produk');
		$this->db->join('admin', 'admin.id = produk.admin_id');
		$this->db->where('produk.is_supplier', 0);
		$query = $this->db->get();
		return $query->result_array();
	}

	public function getProdukBySupplier()
	{
		$this->db->select('produk.*');
		$this->db->from('produk');
		$this->db->where('produk.is_supplier', 1);
		$query = $this->db->get();
		return $query->result_array();
	}

	public function getProdukById($id)
	{
		$query = $this->db->get_where('produk', array('id' => $id));
		return $query->row_array();
	}

	public function insertProduk($data)
	{
		$this->db->insert('produk', $data);

		$insert_id = $this->db->insert_id();

		return $this->getProdukById($insert_id);
	}

	public function updateProduk($id, $data)
	{
		$this->db->where('id', $id);
		$this->db->update('produk', $data);

		return $this->getProdukById($id);
	}

	public function deleteProduk($id)
	{
		$result = $this->getProdukById($id);

		$this->db->where('id', $id);
		$this->db->delete('produk');

		return $result;
	}

	public function cekProdukExist($id)
	{
		$produk = $this->getProdukById($id);

		if (empty($produk)) {
			return false;
		}

		return true;
	}
}
