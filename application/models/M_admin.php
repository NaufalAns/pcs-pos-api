<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_admin extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	public function getAdmin()
	{
		$query = $this->db->get('admin');
		return $query->result_array();
	}

	public function getAdminById($id)
	{
		$query = $this->db->get_where('admin', array('id' => $id));
		return $query->row_array();
	}

	public function insertAdmin($data)
	{
		$this->db->insert('admin', $data);

		$insert_id = $this->db->insert_id();

		return $this->getAdminById($insert_id);
	}

	public function updateAdmin($id, $data)
	{
		$this->db->where('id', $id);
		$this->db->update('admin', $data);

		return $this->getAdminById($id);
	}

	public function deleteAdmin($id)
	{
		$result = $this->getAdminById($id);

		$this->db->where('id', $id);
		$this->db->delete('admin');

		return $result;
	}

	public function cekLoginAdmin($data)
	{
		$this->db->where($data);
		$result = $this->db->get('admin');

		return $result->row_array();
	}

	public function cekAdminExist($id)
	{
		$admin = $this->getAdminById($id);

		if (empty($admin)) {
			return false;
		}

		return true;
	}
}
