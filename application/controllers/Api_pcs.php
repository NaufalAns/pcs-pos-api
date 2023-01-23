<?php

use Firebase\JWT\JWT;
use Restserver\Libraries\REST_Controller;

defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Firebase/JWT/JWT.php';

class Api_pcs extends REST_Controller
{
	private $secret_key = 'asd8ahd89qnex89sazs098ha0nbzmnz';

	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_admin');
		$this->load->model('M_produk');
		$this->load->model('M_transaksi');
		$this->load->model('M_item_transaksi');
	}

	// Admin Start

	public function admin_get()
	{
		$this->cekToken();

		$data['admin'] = $this->M_admin->getAdmin();

		$data_json = array(
			'success' => true,
			'message' => 'Data found',
			'data' => array(
				'admin' => $data['admin']
			)
		);

		$this->response($data_json, REST_Controller::HTTP_OK);
	}

	public function admin_post()
	{
		$this->cekToken();

		// Validasi
		$validation_message = array();

		if ($this->post('email') == '') {
			$validation_message['email'] = 'Email tidak boleh kosong!';
		}

		if ($this->post('email') != '' && !filter_var($this->post('email'), FILTER_VALIDATE_EMAIL)) {
			$validation_message['email'] = 'Email tidak valid!';
		}

		if ($this->post('nama') == '') {
			$validation_message['nama'] = 'Nama tidak boleh kosong!';
		}

		if (count($validation_message) > 0) {
			$data_json = array(
				'success' => false,
				'message' => 'Data tidak valid',
				'data' => $validation_message
			);

			$this->response($data_json, REST_Controller::HTTP_BAD_REQUEST);
			$this->output->_display();
			exit;
		}

		$data = array(
			'email' => $this->input->post('email'),
			'password' => md5($this->input->post('password')),
			'nama' => $this->input->post('nama'),
		);

		$result = $this->M_admin->insertAdmin($data);

		$data_json = array(
			'success' => true,
			'message' => 'Insert Berhasil',
			'data' => array(
				'admin' => $result
			)
		);

		$this->response($data_json, REST_Controller::HTTP_OK);
	}

	public function admin_put()
	{
		$this->cekToken();

		// Validasi
		$validation_message = array();

		if ($this->put('email') == '') {
			$validation_message['email'] = 'Email tidak boleh kosong!';
		}

		if ($this->put('email') != '' && !filter_var($this->put('email'), FILTER_VALIDATE_EMAIL)) {
			$validation_message['email'] = 'Email tidak valid!';
		}

		if ($this->put('nama') == '') {
			$validation_message['nama'] = 'Nama tidak boleh kosong!';
		}

		if (count($validation_message) > 0) {
			$data_json = array(
				'success' => false,
				'message' => 'Data tidak valid',
				'data' => $validation_message
			);

			$this->response($data_json, REST_Controller::HTTP_BAD_REQUEST);
			$this->output->_display();
			exit;
		}

		$data = array(
			'email' => $this->put('email'),
			'password' => md5($this->put('password')),
			'nama' => $this->put('nama'),
		);

		$id = $this->put('id');

		$result = $this->M_admin->updateAdmin($id, $data);

		$data_json = array(
			'success' => true,
			'message' => 'Update Berhasil',
			'data' => array(
				'admin' => $result
			)
		);

		$this->response($data_json, REST_Controller::HTTP_OK);
	}

	public function admin_delete()
	{
		$this->cekToken();

		$id = $this->delete('id');

		$result = $this->M_admin->deleteAdmin($id);

		if (empty($result)) {
			$data_json = array(
				'success' => false,
				'message' => 'Id tidak valid',
				'data' => null
			);

			$this->response($data_json, REST_Controller::HTTP_BAD_REQUEST);
			$this->output->_display();
			exit;
		}

		$data_json = array(
			'success' => true,
			'message' => 'Delete Berhasil',
			'data' => array(
				'admin' => $result
			)
		);

		$this->response($data_json, REST_Controller::HTTP_OK);
	}

	public function login_post()
	{
		$data = array(
			'email' => $this->input->post('email'),
			'password' => md5($this->input->post('password')),
		);

		$result = $this->M_admin->cekLoginAdmin($data);

		if (empty($result)) {
			$data_json = array(
				'success' => false,
				'message' => 'Email atau password tidak valid!',
				'error_code' => 1308,
				'data' => null
			);

			$this->response($data_json, REST_Controller::HTTP_BAD_REQUEST);
			$this->output->_display();
			exit;
		}

		$date = new DateTime();

		$payload = array(
			'id' => $result['id'],
			'email' => $result['email'],
			'iat' => $date->getTimestamp(),
			'exp' => $date->getTimestamp() + 3600
		);

		$data_json = array(
			'success' => true,
			'message' => 'Otentikasi Berhasil',
			'data' => array(
				'admin' => $result,
				'token' => JWT::encode($payload, $this->secret_key)
			)
		);

		$this->response($data_json, REST_Controller::HTTP_OK);
	}

	// Admin End

	// Produk Start

	public function produk_get()
	{
		$this->cekToken();

		$data['produk'] = $this->M_produk->getProduk();

		$data_json = array(
			'success' => true,
			'message' => 'Data found',
			'data' => array(
				'produk' => $data['produk']
			)
		);

		$this->response($data_json, REST_Controller::HTTP_OK);
	}

	public function produk_supplier_get()
	{
		$this->cekToken();

		$data['produk'] = $this->M_produk->getProdukBySupplier();

		$data_json = array(
			'success' => true,
			'message' => 'Data found',
			'data' => array(
				'produk' => $data['produk']
			)
		);

		$this->response($data_json, REST_Controller::HTTP_OK);
	}

	public function produk_post()
	{
		$this->cekToken();

		// Validasi
		$validation_message = array();

		if ($this->post('admin_id') == '') {
			$validation_message['admin_id'] = 'Admin Id tidak boleh kosong!';
		}

		if ($this->post('admin_id') != '' && !$this->M_admin->cekAdminExist($this->post('admin_id'))) {
			$validation_message['admin_id'] = 'Admin Id tidak valid!';
		}

		if ($this->post('nama') == '') {
			$validation_message['nama'] = 'Nama tidak boleh kosong!';
		}

		if ($this->post('harga') == '') {
			$validation_message['harga'] = 'Harga tidak boleh kosong!';
		}

		if ($this->post('harga') != '' && !is_numeric($this->post('harga'))) {
			$validation_message['harga'] = 'Harga tidak boleh selain angka!';
		}

		if ($this->post('stok') == '') {
			$validation_message['stok'] = 'Stok tidak boleh kosong!';
		}

		if ($this->post('stok') != '' && !is_numeric($this->post('stok'))) {
			$validation_message['stok'] = 'Stok tidak boleh selain angka!';
		}

		if (count($validation_message) > 0) {
			$data_json = array(
				'success' => false,
				'message' => 'Data tidak valid',
				'data' => $validation_message
			);

			$this->response($data_json, REST_Controller::HTTP_BAD_REQUEST);
			$this->output->_display();
			exit;
		}

		$data = array(
			'admin_id' => $this->input->post('admin_id'),
			'nama' => $this->input->post('nama'),
			'harga' => $this->input->post('harga'),
			'stok' => $this->input->post('stok'),
		);

		$result = $this->M_produk->insertProduk($data);

		$data_json = array(
			'success' => true,
			'message' => 'Insert Berhasil',
			'data' => array(
				'produk' => $result
			)
		);

		$this->response($data_json, REST_Controller::HTTP_OK);
	}

	public function produk_put()
	{
		$this->cekToken();

		// Validasi
		$validation_message = array();

		if ($this->put('admin_id') == '') {
			$validation_message['admin_id'] = 'Admin Id tidak boleh kosong!';
		}

		if ($this->post('admin_id') != '' && !$this->M_admin->cekAdminExist($this->post('admin_id'))) {
			$validation_message['admin_id'] = 'Admin Id tidak valid!';
		}

		if ($this->put('nama') == '') {
			$validation_message['nama'] = 'Nama tidak boleh kosong!';
		}

		if ($this->put('harga') == '') {
			$validation_message['harga'] = 'Harga tidak boleh kosong!';
		}

		if ($this->put('harga') != '' && !is_numeric($this->put('harga'))) {
			$validation_message['harga'] = 'Harga tidak boleh selain angka!';
		}

		if ($this->put('stok') == '') {
			$validation_message['stok'] = 'Stok tidak boleh kosong!';
		}

		if ($this->put('stok') != '' && !is_numeric($this->put('stok'))) {
			$validation_message['stok'] = 'Stok tidak boleh selain angka!';
		}

		if (count($validation_message) > 0) {
			$data_json = array(
				'success' => false,
				'message' => 'Data tidak valid',
				'data' => $validation_message
			);

			$this->response($data_json, REST_Controller::HTTP_BAD_REQUEST);
			$this->output->_display();
			exit;
		}

		$data = array(
			'admin_id' => $this->put('admin_id'),
			'nama' => $this->put('nama'),
			'harga' => $this->put('harga'),
			'stok' => $this->put('stok'),
		);

		$id = $this->put('id');

		$result = $this->M_produk->updateProduk($id, $data);

		$data_json = array(
			'success' => true,
			'message' => 'Update Berhasil',
			'data' => array(
				'produk' => $result
			)
		);

		$this->response($data_json, REST_Controller::HTTP_OK);
	}

	public function produk_delete()
	{
		$this->cekToken();

		$id = $this->delete('id');

		$result = $this->M_produk->deleteProduk($id);

		if (empty($result)) {
			$data_json = array(
				'success' => false,
				'message' => 'Id tidak valid',
				'data' => null
			);

			$this->response($data_json, REST_Controller::HTTP_BAD_REQUEST);
			$this->output->_display();
			exit;
		}

		$data_json = array(
			'success' => true,
			'message' => 'Delete Berhasil',
			'data' => array(
				'produk' => $result
			)
		);

		$this->response($data_json, REST_Controller::HTTP_OK);
	}

	// Produk End

	// Transaksi Start

	public function transaksi_get()
	{
		$this->cekToken();

		$data['transaksi'] = $this->M_transaksi->getTransaksi();

		$data_json = array(
			'success' => true,
			'message' => 'Data found',
			'data' => array(
				'transaksi' => $data['transaksi']
			)
		);

		$this->response($data_json, REST_Controller::HTTP_OK);
	}

	public function transaksi_bulan_ini_get()
	{
		$this->cekToken();

		$result = $this->M_transaksi->getTransaksiBulanIni();

		$data_json = array(
			'success' => true,
			'message' => 'Data found',
			'data' => $result
		);

		$this->response($data_json, REST_Controller::HTTP_OK);
	}

	public function total_pendapatan_bulan_ini_get()
	{
		$this->cekToken();

		$result = $this->M_transaksi->getTotalPendapatanBulanIni();

		$data_json = array(
			'success' => true,
			'message' => 'Data found',
			'data' => $result
		);

		$this->response($data_json, REST_Controller::HTTP_OK);
	}
	public function total_pembelian_bulan_ini_get()
	{
		$this->cekToken();

		$result = $this->M_transaksi->getTotalPembelianBulanIni();

		$data_json = array(
			'success' => true,
			'message' => 'Data found',
			'data' => $result
		);

		$this->response($data_json, REST_Controller::HTTP_OK);
	}
	public function total_pendapatan_bulan_ini_bersih_get()
	{
		$this->cekToken();

		$result = $this->M_transaksi->getTotalTransaksiBulanIniBersih();

		$data_json = array(
			'success' => true,
			'message' => 'Data found',
			'data' => $result
		);

		$this->response($data_json, REST_Controller::HTTP_OK);
	}

	public function transaksi_post()
	{
		$this->cekToken();

		// Validasi
		$validation_message = array();

		if ($this->post('admin_id') == '') {
			$validation_message['admin_id'] = 'Admin Id tidak boleh kosong!';
		}

		if ($this->post('admin_id') != '' && !$this->M_admin->cekAdminExist($this->post('admin_id'))) {
			$validation_message['admin_id'] = 'Admin Id tidak valid!';
		}

		if ($this->post('total') == '') {
			$validation_message['total'] = 'Total tidak boleh kosong!';
		}

		if ($this->post('total') != '' && !is_numeric($this->post('total'))) {
			$validation_message['total'] = 'Total tidak boleh selain angka!';
		}

		if (count($validation_message) > 0) {
			$data_json = array(
				'success' => false,
				'message' => 'Data tidak valid',
				'data' => $validation_message
			);

			$this->response($data_json, REST_Controller::HTTP_BAD_REQUEST);
			$this->output->_display();
			exit;
		}

		$data = array(
			'admin_id' => $this->input->post('admin_id'),
			'total' => $this->input->post('total'),
			'tanggal' => date('Y-m-d H:i:s'),
		);

		$result = $this->M_transaksi->insertTransaksi($data);

		$data_json = array(
			'success' => true,
			'message' => 'Insert Berhasil',
			'data' => array(
				'transaksi' => $result
			)
		);

		$this->response($data_json, REST_Controller::HTTP_OK);
	}

	public function transaksi_put()
	{
		$this->cekToken();

		// Validasi
		$validation_message = array();

		if ($this->put('admin_id') == '') {
			$validation_message['admin_id'] = 'Admin Id tidak boleh kosong!';
		}

		if ($this->post('admin_id') != '' && !$this->M_admin->cekAdminExist($this->post('admin_id'))) {
			$validation_message['admin_id'] = 'Admin Id tidak valid!';
		}

		if ($this->put('total') == '') {
			$validation_message['total'] = 'Total tidak boleh kosong!';
		}

		if ($this->put('total') != '' && !is_numeric($this->put('total'))) {
			$validation_message['total'] = 'Total tidak boleh selain angka!';
		}

		if (count($validation_message) > 0) {
			$data_json = array(
				'success' => false,
				'message' => 'Data tidak valid',
				'data' => $validation_message
			);

			$this->response($data_json, REST_Controller::HTTP_BAD_REQUEST);
			$this->output->_display();
			exit;
		}

		$data = array(
			'admin_id' => $this->put('admin_id'),
			'total' => $this->put('total'),
			'tanggal' => date('Y-m-d H:i:s'),
		);

		$id = $this->put('id');

		$result = $this->M_transaksi->updateTransaksi($id, $data);

		$data_json = array(
			'success' => true,
			'message' => 'Update Berhasil',
			'data' => array(
				'transaksi' => $result
			)
		);

		$this->response($data_json, REST_Controller::HTTP_OK);
	}

	public function transaksi_delete()
	{
		$this->cekToken();

		$id = $this->delete('id');

		$result = $this->M_transaksi->deleteTransaksi($id);

		if (empty($result)) {
			$data_json = array(
				'success' => false,
				'message' => 'Id tidak valid',
				'data' => null
			);

			$this->response($data_json, REST_Controller::HTTP_BAD_REQUEST);
			$this->output->_display();
			exit;
		}

		$data_json = array(
			'success' => true,
			'message' => 'Delete Berhasil',
			'data' => array(
				'transaksi' => $result
			)
		);

		$this->response($data_json, REST_Controller::HTTP_OK);
	}

	// Transaksi End

	// Transaksi Item Start

	public function item_transaksi_get()
	{
		$this->cekToken();

		$data['item_transaksi'] = $this->M_item_transaksi->getItemTransaksi();

		$data_json = array(
			'success' => true,
			'message' => 'Data found',
			'data' => array(
				'item_transaksi' => $data['item_transaksi']
			)
		);

		$this->response($data_json, REST_Controller::HTTP_OK);
	}

	public function item_transaksi_by_transaksi_id_get()
	{
		$this->cekToken();

		$result = $this->M_item_transaksi->getItemTransaksiByTransaksiId($this->input->get('transaksi_id'));

		$data_json = array(
			'success' => true,
			'message' => 'Data found',
			'data' => array(
				'item_transaksi' => $result
			)
		);

		$this->response($data_json, REST_Controller::HTTP_OK);
	}

	public function item_transaksi_post()
	{
		$this->cekToken();

		// Validasi
		$validation_message = array();

		if ($this->post('transaksi_id') == '') {
			$validation_message['transaksi_id'] = 'Transaksi Id tidak boleh kosong!';
		}

		if ($this->post('transaksi_id') != '' && !$this->M_transaksi->cekTransaksiExist($this->post('transaksi_id'))) {
			$validation_message['transaksi_id'] = 'Transaksi Id tidak valid!';
		}

		if ($this->post('produk_id') == '') {
			$validation_message['produk_id'] = 'Produk Id tidak boleh kosong!';
		}

		if ($this->post('produk_id') != '' && !$this->M_produk->cekProdukExist($this->post('produk_id'))) {
			$validation_message['produk_id'] = 'Produk Id tidak valid!';
		}

		if ($this->post('qty') == '') {
			$validation_message['qty'] = 'Qty tidak boleh kosong!';
		}

		if ($this->post('qty') != '' && !is_numeric($this->post('qty'))) {
			$validation_message['qty'] = 'Qty tidak boleh selain angka!';
		}

		if ($this->post('harga_saat_transaksi') == '') {
			$validation_message['harga_saat_transaksi'] = 'Harga tidak boleh kosong!';
		}

		if ($this->post('harga_saat_transaksi') != '' && !is_numeric($this->post('harga_saat_transaksi'))) {
			$validation_message['harga_saat_transaksi'] = 'Harga tidak boleh selain angka!';
		}

		if (count($validation_message) > 0) {
			$data_json = array(
				'success' => false,
				'message' => 'Data tidak valid',
				'data' => $validation_message
			);

			$this->response($data_json, REST_Controller::HTTP_BAD_REQUEST);
			$this->output->_display();
			exit;
		}

		$data = array(
			'transaksi_id' => $this->input->post('transaksi_id'),
			'produk_id' => $this->input->post('produk_id'),
			'qty' => $this->input->post('qty'),
			'harga_saat_transaksi' => $this->input->post('harga_saat_transaksi'),
			'sub_total' => $this->input->post('qty') * $this->input->post('harga_saat_transaksi'),
		);

		$result = $this->M_item_transaksi->insertItemTransaksi($data);

		$data_json = array(
			'success' => true,
			'message' => 'Insert Berhasil',
			'data' => array(
				'item_transaksi' => $result
			)
		);

		$this->response($data_json, REST_Controller::HTTP_OK);
	}

	public function item_transaksi_put()
	{
		$this->cekToken();

		// Validasi
		$validation_message = array();

		if ($this->put('transaksi_id') == '') {
			$validation_message['transaksi_id'] = 'Transaksi Id tidak boleh kosong!';
		}

		if ($this->put('transaksi_id') != '' && !$this->M_transaksi->cekTransaksiExist($this->put('transaksi_id'))) {
			$validation_message['transaksi_id'] = 'Transaksi Id tidak valid!';
		}

		if ($this->put('produk_id') == '') {
			$validation_message['produk_id'] = 'Produk Id tidak boleh kosong!';
		}

		if ($this->put('produk_id') != '' && !$this->M_produk->cekProdukExist($this->put('produk_id'))) {
			$validation_message['produk_id'] = 'Produk Id tidak valid!';
		}

		if ($this->put('qty') == '') {
			$validation_message['qty'] = 'Qty tidak boleh kosong!';
		}

		if ($this->put('qty') != '' && !is_numeric($this->put('qty'))) {
			$validation_message['qty'] = 'Qty tidak boleh selain angka!';
		}

		if ($this->put('harga_saat_transaksi') == '') {
			$validation_message['harga_saat_transaksi'] = 'Harga tidak boleh kosong!';
		}

		if ($this->put('harga_saat_transaksi') != '' && !is_numeric($this->put('harga_saat_transaksi'))) {
			$validation_message['harga_saat_transaksi'] = 'Harga tidak boleh selain angka!';
		}

		if (count($validation_message) > 0) {
			$data_json = array(
				'success' => false,
				'message' => 'Data tidak valid',
				'data' => $validation_message
			);

			$this->response($data_json, REST_Controller::HTTP_BAD_REQUEST);
			$this->output->_display();
			exit;
		}

		$data = array(
			'transaksi_id' => $this->put('transaksi_id'),
			'produk_id' => $this->put('produk_id'),
			'qty' => $this->put('qty'),
			'harga_saat_transaksi' => $this->put('harga_saat_transaksi'),
			'sub_total' => $this->put('qty') * $this->put('harga_saat_transaksi'),
		);

		$id = $this->put('id');

		$result = $this->M_item_transaksi->updateItemTransaksi($id, $data);

		$data_json = array(
			'success' => true,
			'message' => 'Update Berhasil',
			'data' => array(
				'item_transaksi' => $result
			)
		);

		$this->response($data_json, REST_Controller::HTTP_OK);
	}

	public function item_transaksi_delete()
	{
		$this->cekToken();

		$id = $this->delete('id');

		$result = $this->M_item_transaksi->deleteItemTransaksi($id);

		if (empty($result)) {
			$data_json = array(
				'success' => false,
				'message' => 'Id tidak valid',
				'data' => null
			);

			$this->response($data_json, REST_Controller::HTTP_BAD_REQUEST);
			$this->output->_display();
			exit;
		}

		$data_json = array(
			'success' => true,
			'message' => 'Delete Berhasil',
			'data' => array(
				'item_transaksi' => $result
			)
		);

		$this->response($data_json, REST_Controller::HTTP_OK);
	}

	public function item_transaksi_by_transaksi_id_delete()
	{
		$this->cekToken();

		$transaksi_id = $this->delete('transaksi_id');

		$result = $this->M_item_transaksi->deleteItemTransaksiByTransaksiId($transaksi_id);

		if (empty($result)) {
			$data_json = array(
				'success' => false,
				'message' => 'Id tidak valid',
				'data' => null
			);

			$this->response($data_json, REST_Controller::HTTP_BAD_REQUEST);
			$this->output->_display();
			exit;
		}

		$data_json = array(
			'success' => true,
			'message' => 'Delete Berhasil',
			'data' => array(
				'item_transaksi' => $result
			)
		);

		$this->response($data_json, REST_Controller::HTTP_OK);
	}

	// Transaksi Item End

	public function cekToken()
	{
		try {
			$token = $this->input->get_request_header('Authorization');

			if (!empty($token)) {
				$token = explode(' ', $token)[1];
			}

			$token_decode = JWT::decode($token, $this->secret_key, array('HS256'));
		} catch (Exception $e) {
			$data_json = array(
				'success' => false,
				'message' => 'Token tidak valid',
				'error_code' => 1204,
				'data' => null
			);

			$this->response($data_json, REST_Controller::HTTP_BAD_REQUEST);
			$this->output->_display();
			exit;
		}
	}
}
