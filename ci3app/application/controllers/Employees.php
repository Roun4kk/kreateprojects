<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Employees extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Employee_model');
        $this->load->helper(array('form', 'url'));
        $this->load->library(array('form_validation', 'session'));
    }

    
    public function index() {
            
        $data['employees'] = $this->Employee_model->getAll();
        $data['departments'] = $this->Employee_model->getDepartments();
        $data['editId'] = $this->input->get('edit_id');
        $data['page_title'] = 'Employee Management System';

        $this->load->view('header', $data);
        $this->load->view('employees_list', $data);
        $this->load->view('footer');
    }

    public function add() {

        $validation = $this->validate_employee_form();
        if (!$validation['valid']) {
            $this->session->set_flashdata('error', $validation['errors']);
            $data['departments'] = $this->Employee_model->getDepartments();
            $data['page_title'] = 'Add New Employee';

            $this->load->view('header', $data);
            $this->load->view('employees_form', $data);
            $this->load->view('footer');
        } else {
            $data = array(
                'name'          => $this->input->post('name'),
                'email'         => $this->input->post('email'),
                'phone'         => $this->input->post('phone'),
                'hire_date'     => $this->input->post('hire_date'),
                'department_id' => $this->input->post('department_id')
            );
            $this->Employee_model->insert($data);
            $this->session->set_flashdata('success', 'Employee added successfully!');
            redirect('employees');
        }
    }

    public function edit($id) {
        $employee = $this->Employee_model->getById($id);
        if (!$employee) {
            $this->session->set_flashdata('error', 'Employee not found!');
            redirect('employees');
        }

        $validation = $this->validate_employee_form($id);
        if (!$validation['valid']) {
            $this->session->set_flashdata('error', $validation['errors']);

            $data['employee'] = $employee;
            $data['departments'] = $this->Employee_model->getDepartments();
            $data['employees'] = $this->Employee_model->getAll();
            $data['editId'] = $employee->id;
            $data['page_title'] = 'Employee Management System';

            $deptId = $this->input->post('department_id') ?: $employee->department_id;
            $dept = $this->Employee_model->getDepartmentById($deptId);
            $data['location'] = $dept ? $dept->location : '';

            $this->load->view('header', $data);
            $this->load->view('employees_list', $data);
            $this->load->view('footer');
        } else {
            $update_data = array(
                'name'          => $this->input->post('name'),
                'email'         => $this->input->post('email'),
                'phone'         => $this->input->post('phone'),
                'hire_date'     => $this->input->post('hire_date'),
                'department_id' => $this->input->post('department_id')
            );

            if ($this->Employee_model->update($id, $update_data)) {
                $this->session->set_flashdata('success', 'Employee updated successfully!');
            } else {
                $this->session->set_flashdata('error', 'Failed to update employee!');
            }
            redirect('employees');
        }
    }

    public function delete($id) {
        $employee = $this->Employee_model->getById($id);
        if (!$employee) {
            $this->session->set_flashdata('error', 'Employee not found!');
            redirect('employees');
        }

        if ($this->Employee_model->delete($id)) {
            $this->session->set_flashdata('success', 'Employee deleted successfully!');
        } else {
            $this->session->set_flashdata('error', 'Failed to delete employee!');
        }
        redirect('employees');
    }

    private function validate_employee_form($exclude_id = null) {
        $this->form_validation->set_rules('name', 'Name', 'required|regex_match[/^[A-Za-z ]+$/]', [
            'regex_match' => 'The name should contain only alphabets and spaces.'
        ]);

        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');

        $this->form_validation->set_rules('phone', 'Phone', 'required|regex_match[/^[7-9][0-9]{9}$/]', [
            'regex_match' => 'The phone number must be exactly 10 digits and start with 7, 8, or 9.'
        ]);

        $email = $this->input->post('email');
        $phone = $this->input->post('phone');

        $email_exists = false;
        $this->db->where('email', $email);
        if ($exclude_id) $this->db->where('id !=', $exclude_id);
        if ($this->db->get('employees')->num_rows() > 0) {
            $email_exists = true;
        }

        $phone_exists = false;
        $this->db->where('phone', $phone);
        if ($exclude_id) $this->db->where('id !=', $exclude_id);
        if ($this->db->get('employees')->num_rows() > 0) {
            $phone_exists = true;
        }

        if ($this->form_validation->run() === FALSE || $email_exists || $phone_exists) {
            $errors = validation_errors();
            if ($email_exists) {
                $errors .= '<p>Email already exists for another employee.</p>';
            }
            if ($phone_exists) {
                $errors .= '<p>Phone number already exists for another employee.</p>';
            }
            return ['valid' => false, 'errors' => $errors];
        }

        return ['valid' => true, 'errors' => ''];
    }
    // public function ajax_edit() {
    //     $id = $this->input->post('id');

    //     if (!$id) {
    //         return $this->output
    //             ->set_status_header(400)
    //             ->set_output(json_encode(['status' => false, 'message' => 'Missing employee ID']));
    //     }

    //     $validation = $this->validate_employee_form($id);
    //     if (!$validation['valid']) {
    //         return $this->output
    //             ->set_content_type('application/json')
    //             ->set_output(json_encode(['status' => false, 'message' => $validation['errors']]));
    //     }

    //     $update_data = array(
    //         'name'          => $this->input->post('name'),
    //         'email'         => $this->input->post('email'),
    //         'phone'         => $this->input->post('phone'),
    //         'hire_date'     => $this->input->post('hire_date'),
    //         'department_id' => $this->input->post('department_id')
    //     );

    //     if ($this->Employee_model->update($id, $update_data)) {
    //         return $this->output
    //             ->set_content_type('application/json')
    //             ->set_output(json_encode(['status' => true, 'message' => 'Employee updated successfully']));
    //     } else {
    //         return $this->output
    //             ->set_content_type('application/json')
    //             ->set_output(json_encode(['status' => false, 'message' => 'Failed to update employee']));
    //     }
    // }

}
