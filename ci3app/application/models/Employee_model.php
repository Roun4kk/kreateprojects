
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Employee_model extends CI_Model {
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function getAll() {
        $this->db->select('employees.*, departments.name AS department_name, departments.location AS department_location');
        $this->db->from('employees');
        $this->db->join('departments', 'employees.department_id = departments.id', 'left');
        $this->db->order_by('employees.id', 'ASC');
        return $this->db->get()->result();
    }

    public function getById($id) {
        $this->db->select('employees.*, departments.name AS department_name, departments.location AS department_location');
        $this->db->from('employees');
        $this->db->join('departments', 'employees.department_id = departments.id', 'left');
        $this->db->where('employees.id', $id);
        return $this->db->get()->row();
    }

    public function insert($data) {
        return $this->db->insert('employees', $data);
    }
    public function delete($id) {
        $this->db->where('id', $id);
        return $this->db->delete('employees');
    }

    public function update($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('employees', $data);
    }

    public function getDepartments() {
        return $this->db->get('departments')->result();
    }

    public function getDepartmentById($id) {
    return $this->db->get_where('departments', ['id' => $id])->row();
    }

}