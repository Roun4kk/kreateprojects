<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Client extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Client_model');
        $this->load->helper('url');
        $this->load->library('session');
    }

    /**
     * Displays the main client view.
     */
    public function index() {
        $registrations = $this->Client_model->get_all_registrations();
        // Change 'registrations_lookup' to 'registrations' to match the view
        $data['registrations'] = array_column($registrations, 'registration_Name', 'id');
        $data['clients'] = $this->Client_model->get_all_clients();
        
        $this->load->view('client_header');
        $this->load->view('client_details_table', $data);
        $this->load->view('client_footer');
    }

    /**
     * Handles adding a new client.
     */
    public function add() {
        $data = $this->get_input_data();
        $errors = $this->validate_client_data($data);

        if (!empty($errors)) {
            $this->send_json_response(false, $errors);
            return;
        }

        $result = $this->Client_model->insert_client($data);

        if ($result) {
            $this->send_json_response(true);
        } else {
            $this->send_json_response(false, ['Database error: Could not add the client.']);
        }
    }

    /**
     * Handles updating an existing client.
     */
    public function update($id) {
        $data = $this->get_input_data();
        $errors = $this->validate_client_data($data, $id);

        if (!empty($errors)) {
            $this->send_json_response(false, $errors);
            return;
        }
        
        $this->Client_model->update_client($id, $data);
        $this->send_json_response(true); // Assume success if no DB exception occurred
    }

    /**
     * Handles deleting a client.
     */
    public function delete($id) {
        $result = $this->Client_model->delete_client($id);
        if ($result) {
            $this->send_json_response(true);
        } else {
            $this->send_json_response(false, ['Could not find the client to delete.']);
        }
    }

    /**
     * Retrieves a single client's data for editing.
     */
    public function get($id) {
        $client = $this->Client_model->get_client_by_id($id);
        if ($client) {
            $this->output->set_content_type('application/json')->set_output(json_encode($client));
        } else {
            $this->send_json_response(false, ['Client not found']);
        }
    }

    private function validate_client_data($data, $id = null) {
        $errors = [];

        // --- Validation Rules ---
        
        // --- START: Updated C_Pid Validation ---
        if (empty($data['C_Pid']) || !preg_match('/^[A-Za-z0-9]{10}$/', $data['C_Pid'])) {
            $errors[] = 'Client PID must be 10 alphanumeric characters.';
        } 
        // If it's a new record OR if it's an update where the PID has been changed...
        elseif (!$id || ($id && $data['C_Pid'] !== $id)) {
            // ...then we must check if the new PID already exists.
            if ($this->Client_model->does_field_exist('C_Pid', $data['C_Pid'])) {
                $errors[] = "The Client PID '" . htmlspecialchars($data['C_Pid']) . "' already exists.";
            }
        }
        // --- END: Updated C_Pid Validation ---

        if (empty($data['C_Name']) || !preg_match('/^[A-Za-z\s]{2,}$/', $data['C_Name'])) {
            $errors[] = 'Client Name must be at least 2 letters.';
        } elseif ($this->Client_model->does_field_exist('C_Name', $data['C_Name'], $id)) {
            $errors[] = "The Client Name '" . htmlspecialchars($data['C_Name']) . "' already exists.";
        }

        if (empty($data['C_Shortcode']) || !preg_match('/^[A-Za-z0-9]{8}$/', $data['C_Shortcode'])) {
            $errors[] = 'Shortcode must be 8 alphanumeric characters.';
        } elseif ($this->Client_model->does_field_exist('C_Shortcode', $data['C_Shortcode'], $id)) {
            $errors[] = "The Shortcode '" . htmlspecialchars($data['C_Shortcode']) . "' already exists.";
        }
        
        // FIXED: Phone Number Validation
        if (empty($data['C_Phone_No']) || !preg_match('/^[789]\d{9}$/', $data['C_Phone_No'])) {
            $errors[] = 'Phone Number must be a valid 10-digit number starting with 7, 8, or 9.';
        } elseif ($this->Client_model->does_field_exist('C_Phone_No', $data['C_Phone_No'], $id)) {
            $errors[] = "Phone No '" . htmlspecialchars($data['C_Phone_No']) . "' already exists.";
        }

        // FIXED: Email Validation
        if (empty($data['C_Email']) || !filter_var($data['C_Email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'A valid Email is required.';
        } elseif ($this->Client_model->does_field_exist('C_Email', $data['C_Email'], $id)) {
            $errors[] = "Email '" . htmlspecialchars($data['C_Email']) . "' already exists.";
        }

        return $errors;
    }
    
    /**
     * Processes raw input data.
     */
    private function get_input_data() {
        $input = json_decode(file_get_contents("php://input"), true);
        
        // Prepare the 'registered_in' field, encoding it if it's an array
        if (isset($input['registered_in']) && is_array($input['registered_in'])) {
            $input['registered_in'] = json_encode($input['registered_in']);
        } else {
            $input['registered_in'] = NULL;
        }

        unset($input['C_Pid_old']); // Remove temporary frontend field
        return $input;
    }

    /**
     * Sends a consistent JSON response.
     */
    private function send_json_response($success, $errors = []) {
        $response = ['success' => $success, 'errors' => $errors];
        $this->output
             ->set_content_type('application/json')
             ->set_output(json_encode($response));
    }
}