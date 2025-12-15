<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Client_contact_model extends CI_Model {
    
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function get_all_client_contacts() {
        $this->db->select('ccd.Contact_ID, ccd.C_Pid, ccd.Contact_Name, ccd.Contact_Email, ccd.Contact_Phone, 
                          ccd.Designation, ccd.Department, d.Designation_Name, dept.name as Department_Name,
                          cd.C_Name, ccd.email, ccd.sms'); // Fetched email and sms columns
        $this->db->from('client_contact_details ccd');
        $this->db->join('designations d', 'ccd.Designation = d.id', 'left');
        $this->db->join('departments dept', 'ccd.Department = dept.id', 'left');
        $this->db->join('client_details cd', 'ccd.C_Pid = cd.C_Pid', 'left');
        $this->db->order_by('ccd.Contact_Name', 'ASC');
        return $this->db->get()->result();
    }

    public function get_contacts_by_cpid($c_pid) {
        $this->db->select('ccd.Contact_ID, ccd.C_Pid, ccd.Contact_Name, ccd.Contact_Email, ccd.Contact_Phone, 
                          ccd.Designation, ccd.Department, d.Designation_Name, dept.name as Department_Name,
                          cd.C_Name, ccd.email, ccd.sms'); // Fetched email and sms columns
        $this->db->from('client_contact_details ccd');
        $this->db->join('designations d', 'ccd.Designation = d.id', 'left');
        $this->db->join('departments dept', 'ccd.Department = dept.id', 'left');
        $this->db->join('client_details cd', 'ccd.C_Pid = cd.C_Pid', 'left');
        $this->db->where('ccd.C_Pid', $c_pid);
        $this->db->order_by('ccd.Contact_Name', 'ASC');
        return $this->db->get()->result();
    }

    public function get_client_contact_by_id($id) {
        return $this->db->get_where('client_contact_details', ['Contact_ID' => $id])->row();
    }

    public function get_all_designations() {
        $this->db->select('id, Designation_Name');
        $this->db->from('designations');
        $this->db->order_by('Designation_Name', 'ASC');
        return $this->db->get()->result();
    }
    
    public function get_all_departments() {
        $this->db->select('id, name');
        $this->db->from('departments');
        $this->db->order_by('name', 'ASC');
        return $this->db->get()->result();
    }

    public function does_field_exist($field, $value, $exclude_id = null) {
        $this->db->where($field, $value);
        if ($exclude_id) {
            $this->db->where('Contact_ID !=', $exclude_id);
        }
        return $this->db->get('client_contact_details')->num_rows() > 0;
    }

    public function insert_client_contact($data) {
        return $this->db->insert('client_contact_details', $data);
    }

    public function update_client_contact($id, $data) {
        $this->db->where('Contact_ID', $id);
        return $this->db->update('client_contact_details', $data);
    }

    public function delete_client_contact($id) {
        $this->db->where('Contact_ID', $id);
        $this->db->delete('client_contact_details');
        return $this->db->affected_rows() > 0;
    }
}