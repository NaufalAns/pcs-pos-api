<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_item_transaksi extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	public function getItemTransaksi()
	{
		$this->db->select('item_transaksi.*, produk.nama');
		$this->db->from('item_transaksi');
		$this->db->join('produk', 'produk.id = item_transaksi.produk_id');
		$query = $this->db->get();
		return $query->result_array();
	}

	public function getItemTransaksiByTransaksiId($transaksi_id)
	{
		$this->db->select('item_transaksi.*, produk.nama');
		$this->db->from('item_transaksi');
		$this->db->join('produk', 'produk.id = item_transaksi.produk_id');
		$this->db->where('item_transaksi.transaksi_id', $transaksi_id);
		$query = $this->db->get();
		return $query->result_array();
	}

	public function getItemTransaksiById($id)
	{
		$query = $this->db->get_where('item_transaksi', array('id' => $id));
		return $query->row_array();
	}

	public function insertItemTransaksi($data)
	{
		$this->db->insert('item_transaksi', $data);
		$insert_id = $this->db->insert_id();
		$result = $this->getItemTransaksiById($insert_id);

		$result_produk = $this->M_produk->getProdukById($data['produk_id']);
		$stok_lama = $result_produk['stok'];
		$stok_baru = $stok_lama - $data['qty'];

		$this->M_produk->updateProduk($data['produk_id'], array('stok' => $stok_baru));

		return $result;
	}

	public function updateItemTransaksi($id, $data)
	{
		$this->db->where('id', $id);
		$this->db->update('item_transaksi', $data);

		return $this->getItemTransaksiById($id);
	}

	public function deleteItemTransaksi($id)
	{
		$result = $this->getItemTransaksiById($id);

		$this->db->where('id', $id);
		$this->db->delete('item_transaksi');

		return $result;
	}

	public function deleteItemTransaksiByTransaksiId($transaksi_id)
	{
		$result = $this->getItemTransaksiByTransaksiId($transaksi_id);

		$this->db->where('transaksi_id', $transaksi_id);
		$this->db->delete('item_transaksi');

		return $result;
	}
}
