<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Client_model extends CI_Model {
    
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function get_all_clients() {
        $this->db->select('C_Pid, C_Name, C_Shortcode, C_Email, C_Phone_No, registered_in');
        $this->db->from('client_details');
        $this->db->order_by('C_Name', 'ASC');
        return $this->db->get()->result();
    }

    public function get_client_by_id($id) {
        return $this->db->get_where('client_details', ['C_Pid' => $id])->row();
    }

    public function search_client_by_name($name) {
        $this->db->select('C_Pid, C_Name, C_Shortcode, C_Email, C_Phone_No, registered_in');
        $this->db->from('client_details');
        $this->db->like('C_Name', $name);
        $this->db->order_by('C_Name', 'ASC');
        return $this->db->get()->result();
    }

    public function get_all_registrations() {
        $this->db->select('id, registration_Name');
        $this->db->from('registered_in');
        $this->db->order_by('registration_Name', 'ASC');
        return $this->db->get()->result();
    }

    public function does_field_exist($field, $value, $exclude_id = null) {
        $this->db->where($field, $value);
        if ($exclude_id) {
            $this->db->where('C_Pid !=', $exclude_id);
        }
        return $this->db->get('client_details')->num_rows() > 0;
    }

    public function insert_client($data) {
        return $this->db->insert('client_details', $data);
    }

    public function update_client($id, $data) {
        $this->db->where('C_Pid', $id);
        return $this->db->update('client_details', $data);
    }

    public function delete_client($id) {
        $this->db->where('C_Pid', $id);
        $this->db->delete('client_details');
        return $this->db->affected_rows() > 0;
    }
}