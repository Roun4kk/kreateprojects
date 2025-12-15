<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Client_contact extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Client_contact_model');
        $this->load->model('Client_model');
        $this->load->helper('url');
        $this->load->library('session');
    }

    /**
     * Displays the main client contact view.
     */
    public function index() {
        $data['client_contacts'] = $this->Client_contact_model->get_all_client_contacts();
        $data['designations'] = array_column($this->Client_contact_model->get_all_designations(), 'Designation_Name', 'id');
        $data['departments'] = array_column($this->Client_contact_model->get_all_departments(), 'name', 'id');
        $data['clients'] = array_column($this->Client_model->get_all_clients(), 'C_Name', 'C_Pid');
        $data['registrations'] = array_column($this->Client_model->get_all_registrations(), 'registration_Name', 'id');
        
        $this->load->view('client_header');
        $this->load->view('client_contact_table', $data);
        $this->load->view('client_footer');
    }

    /**
     * Search client contacts by Client PID or Name
     */
    public function search() {
        $search_term = $this->input->post('search_term');
        
        if (empty($search_term)) {
            $this->send_json_response(false, ['Search term is required.']);
            return;
        }

        // First try to find client by PID
        $client = $this->Client_model->get_client_by_id($search_term);
        
        // If not found by PID, search by name
        if (!$client) {
            $clients_by_name = $this->Client_model->search_client_by_name($search_term);
            if (!empty($clients_by_name)) {
                $client = $clients_by_name[0]; // Take the first match
            }
        }

        if (!$client) {
            $this->send_json_response(false, ['No client found with the given PID or name.']);
            return;
        }

        // Get contacts for this client
        $client_contacts = $this->Client_contact_model->get_contacts_by_cpid($client->C_Pid);
        
        $this->send_json_response(true, [], [
            'client_contacts' => $client_contacts,
            'client_info' => $client
        ]);
    }

    /**
     * Handles adding a new client contact.
     */
    public function add() {
        $data = $this->get_input_data();
        $errors = $this->validate_contact_data($data);

        if (!empty($errors)) {
            $this->send_json_response(false, $errors);
            return;
        }

        // Ensure email and sms are JSON encoded
        if (isset($data['email']) && is_array($data['email'])) {
            $data['email'] = json_encode($data['email']);
        } else {
            $data['email'] = json_encode([]);
        }
        if (isset($data['sms']) && is_array($data['sms'])) {
            $data['sms'] = json_encode($data['sms']);
        } else {
            $data['sms'] = json_encode([]);
        }

        $result = $this->Client_contact_model->insert_client_contact($data);

        if ($result) {
            $this->send_json_response(true);
        } else {
            $this->send_json_response(false, ['Database error: Could not add the contact.']);
        }
    }

    /**
     * Handles updating an existing client contact.
     */
    public function update($id) {
        $data = $this->get_input_data();
        $errors = $this->validate_contact_data($data, $id);

        if (!empty($errors)) {
            $this->send_json_response(false, $errors);
            return;
        }
        
        // Ensure email and sms are JSON encoded
        if (isset($data['email']) && is_array($data['email'])) {
            $data['email'] = json_encode($data['email']);
        } else {
            $data['email'] = json_encode([]);
        }
        if (isset($data['sms']) && is_array($data['sms'])) {
            $data['sms'] = json_encode($data['sms']);
        } else {
            $data['sms'] = json_encode([]);
        }

        $result = $this->Client_contact_model->update_client_contact($id, $data);
        if ($result) {
            $this->send_json_response(true);
        } else {
            $this->send_json_response(false, ['Database error: Could not update the contact.']);
        }
    }

    /**
     * Handles updating email and sms settings for a client contact.
     */
    public function update_settings($id) {
        $data = $this->get_input_data();
        
        // Validate contact ID
        $contact = $this->Client_contact_model->get_client_contact_by_id($id);
        if (!$contact) {
            $this->send_json_response(false, ['Contact not found.']);
            return;
        }

        // Ensure email and sms are JSON encoded
        $update_data = [
            'email' => isset($data['email']) && is_array($data['email']) ? json_encode($data['email']) : json_encode([]),
            'sms' => isset($data['sms']) && is_array($data['sms']) ? json_encode($data['sms']) : json_encode([])
        ];

        $result = $this->Client_contact_model->update_client_contact($id, $update_data);
        if ($result) {
            $this->send_json_response(true);
        } else {
            $this->send_json_response(false, ['Database error: Could not update settings.']);
        }
    }

    /**
     * Handles deleting a client contact.
     */
    public function delete($id) {
        $result = $this->Client_contact_model->delete_client_contact($id);
        if ($result) {
            $this->send_json_response(true);
        } else {
            $this->send_json_response(false, ['Could not find the contact to delete.']);
        }
    }

    /**
     * Retrieves a single client contact's data for editing.
     */
    public function get($id) {
        $contact = $this->Client_contact_model->get_client_contact_by_id($id);
        if ($contact) {
            $this->output->set_content_type('application/json')->set_output(json_encode($contact));
        } else {
            $this->send_json_response(false, ['Contact not found']);
        }
    }

    private function validate_contact_data($data, $id = null) {
        $errors = [];

        // Validate Contact Name
        if (empty($data['Contact_Name']) || !preg_match('/^[A-Za-z\s]{2,}$/', $data['Contact_Name'])) {
            $errors[] = 'Contact Name must have at least 2 letters and no numbers or special characters.';
        }

        // Validate Contact Email
        if (empty($data['Contact_Email']) || !filter_var($data['Contact_Email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'A valid Email is required.';
        } elseif ($this->Client_contact_model->does_field_exist('Contact_Email', $data['Contact_Email'], $id)) {
            $errors[] = "The Email '" . htmlspecialchars($data['Contact_Email']) . "' already exists.";
        }

        // Validate Contact Phone
        if (empty($data['Contact_Phone']) || !preg_match('/^[789]\d{9}$/', $data['Contact_Phone'])) {
            $errors[] = 'Phone Number must be a valid 10-digit number starting with 7, 8, or 9.';
        } elseif ($this->Client_contact_model->does_field_exist('Contact_Phone', $data['Contact_Phone'], $id)) {
            $errors[] = "The Phone '" . htmlspecialchars($data['Contact_Phone']) . "' already exists.";
        }

        // Validate Designation
        if (empty($data['Designation'])) {
            $errors[] = 'Designation is required.';
        }

        // Validate Department
        if (empty($data['Department'])) {
            $errors[] = 'Department is required.';
        }

        return $errors;
    }
    
    /**
     * Processes raw input data.
     */
    private function get_input_data() {
        $input = json_decode(file_get_contents("php://input"), true);
        return $input;
    }

    /**
     * Sends a consistent JSON response.
     */
    private function send_json_response($success, $errors = [], $data = []) {
        $response = ['success' => $success, 'errors' => $errors] + $data;
        $this->output
             ->set_content_type('application/json')
             ->set_output(json_encode($response));
    }
}