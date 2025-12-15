<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Noc extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Noc_model');
        $this->load->model('Client_contact_model');
        $this->load->helper(array('form', 'url')); 
        $this->load->library('upload');
    }

    public function index() {
        $data['title'] = 'NOC Management';
        $data['nocs'] = []; 
        $data['base_url'] = base_url(); 
        log_message('debug', 'Loading NOC index view');
        $this->load->view('noc_header', $data);
        $this->load->view('noc_table', $data);
        $this->load->view('noc_footer');
    }

    public function search_client_noc() {
        header('Content-Type: application/json');
        
        $query = trim($this->input->post('query'));
        log_message('debug', 'Search client query: ' . $query);
        
        if (empty($query)) {
            echo json_encode([
                'success' => false, 
                'message' => 'Please enter a search term (C_Pid, Client Name)',
            ]);
            exit();
        }
        
        if (strlen($query) > 100) {
            echo json_encode([
                'success' => false, 
                'message' => 'Search term is too long (max 100 characters)',
            ]);
            exit();
        }
        
        try {
            $clients = $this->Noc_model->search_client_by_pid_or_name($query);

            if ($clients && count($clients) > 0) {
                echo json_encode([
                    'success' => true,
                    'clients' => $clients,
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'No matching clients found.',
                ]);
            }

        } catch (Exception $e) {
            log_message('error', 'Search client error: ' . $e->getMessage());
            echo json_encode([
                'success' => false, 
                'message' => 'An error occurred while searching: ' . $e->getMessage(),
                 
            ]);
        }
        exit();
    }

    public function get_client_nocs() {
        header('Content-Type: application/json');

        $c_pid = $this->input->post('cpid');
        log_message('debug', 'Fetching all NOCs for C_Pid: ' . $c_pid);

        if (empty($c_pid)) {
            echo json_encode([
                'success' => false,
                'message' => 'Client ID (C_Pid) is required',
            ]);
            exit();
        }

        try {
            $nocs = $this->Noc_model->get_nocs_by_cpid($c_pid);
            if ($nocs && count($nocs) > 0) {
                echo json_encode([
                    'success' => true,
                    'nocs' => $nocs,
                    'cpid' => $c_pid,
                    'client_name' => $nocs[0]->C_Name,
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'No NOCs found for this client.',
                ]);
            }
        } catch (Exception $e) {
            log_message('error', 'Get client NOCs error: ' . $e->getMessage());
            echo json_encode([
                'success' => false,
                'message' => 'An error occurred while fetching NOC data: ' . $e->getMessage(),
            ]);
        }

        exit();
    }

    public function get_quantums() {
        header('Content-Type: application/json');
        $noc_sno = $this->input->post('noc_sno');
        log_message('debug', 'Fetching quantums for NOC ID: ' . $noc_sno);
        
        if (empty($noc_sno)) {
            echo json_encode([
                'success' => false, 
                'message' => 'NOC ID is required',
                 
            ]);
            exit();
        }
        
        try {
            $quantums = $this->Noc_model->get_quantums_by_noc($noc_sno);
            echo json_encode([
                'success' => true,
                'quantums' => $quantums ? $quantums : [],
            ]);
        } catch (Exception $e) {
            log_message('error', 'Get quantums error: ' . $e->getMessage());
            echo json_encode([
                'success' => false,
                'message' => 'An error occurred while fetching quantum data: ' . $e->getMessage(),
                 
            ]);
        }
        exit();
    }

    public function get_noc_by_id() {
        header('Content-Type: application/json');
        $noc_sno = $this->input->post('noc_sno');
        log_message('debug', 'Fetching NOC by ID: ' . $noc_sno);
        
        if (empty($noc_sno)) {
            echo json_encode([
                'success' => false,
                'message' => 'NOC ID is required',
                 
            ]);
            exit();
        }
        
        try {
            $noc = $this->Noc_model->get_noc_by_id($noc_sno);
            if ($noc) {
                echo json_encode([
                    'success' => true,
                    'noc' => $noc,
                     
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'NOC not found',
                     
                ]);
            }
        } catch (Exception $e) {
            log_message('error', 'Get NOC error: ' . $e->getMessage());
            echo json_encode([
                'success' => false,
                'message' => 'An error occurred while fetching NOC data: ' . $e->getMessage(),
                 
            ]);
        }
        exit();
    }

    // AJAX: Update NOC
    public function update_noc() {
        header('Content-Type: application/json');
        
        $noc_sno = $this->input->post('sno');
        log_message('debug', 'Updating NOC ID: ' . $noc_sno);
        if (empty($noc_sno)) {
            echo json_encode([
                'success' => false,
                'message' => 'NOC ID is required',
                 
            ]);
            exit();
        }
        
        $data = [
            'noc_type' => $this->input->post('noc_type'),
            'buy_sell' => $this->input->post('buy_sell'),
            'exchange' => $this->input->post('exchange'),
            'delivery_start' => $this->input->post('delivery_start'),
            'delivery_end' => $this->input->post('delivery_end'),
            'periphery' => $this->input->post('periphery'),
            'status' => $this->input->post('status'),
        ];
        
        try {
            if ($this->Noc_model->update_noc($noc_sno, $data)) {
                // Handle quantum updates
                $from_times = $this->input->post('from_time');
                $to_times = $this->input->post('to_time');
                $quantities = $this->input->post('quantity');
                
                log_message('debug', 'Quantum data for update: ' . json_encode([
                    'from_times' => $from_times,
                    'to_times' => $to_times,
                    'quantities' => $quantities
                ]));
                
                if ($from_times && $to_times && $quantities && count($from_times) === count($to_times) && count($to_times) === count($quantities)) {
                    $quantums = [];
                    for ($i = 0; $i < count($from_times); $i++) {
                        if (!empty($from_times[$i]) && !empty($to_times[$i]) && !empty($quantities[$i]) && floatval($quantities[$i]) > 0) {
                            $quantums[] = [
                                'from_time' => $from_times[$i],
                                'to_time' => $to_times[$i],
                                'quantity' => floatval($quantities[$i])
                            ];
                        }
                    }
                    
                    if (!empty($quantums)) {
                        $this->Noc_model->update_quantums($quantums, $noc_sno);
                        log_message('debug', 'Quantums updated for NOC ID ' . $noc_sno . ': ' . json_encode($quantums));
                    } else {
                        log_message('debug', 'No valid quantum entries provided for update');
                    }
                } else {
                    log_message('error', 'Invalid quantum data for update: ' . json_encode([
                        'from_times' => $from_times,
                        'to_times' => $to_times,
                        'quantities' => $quantities
                    ]));
                }
                
                echo json_encode([
                    'success' => true,
                    'message' => 'NOC updated successfully',
                     
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Failed to update NOC',
                     
                ]);
            }
        } catch (Exception $e) {
            log_message('error', 'Update NOC error: ' . $e->getMessage());
            echo json_encode([
                'success' => false,
                'message' => 'An error occurred while updating the NOC: ' . $e->getMessage(),
                 
            ]);
        }
        exit();
    }

    public function delete_noc() {
        header('Content-Type: application/json');
        
        $noc_sno = $this->input->post('sno');
        log_message('debug', 'Attempting to delete NOC ID: ' . $noc_sno);
        
        if (empty($noc_sno)) {
            echo json_encode([
                'success' => false,
                'message' => 'NOC ID is required',
                 
            ]);
            exit();
        }
        
        try {
            $this->Noc_model->delete_quantums_by_noc($noc_sno);
            $result = $this->Noc_model->delete_noc($noc_sno);
            log_message('debug', 'Delete NOC result: ' . ($result ? 'Success' : 'Failed'));
            
            if ($result) {
                echo json_encode([
                    'success' => true,
                    'message' => 'NOC deleted successfully',
                     
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Failed to delete NOC',
                     
                ]);
            }
        } catch (Exception $e) {
            log_message('error', 'Delete NOC error: ' . $e->getMessage());
            echo json_encode([
                'success' => false,
                'message' => 'An error occurred while deleting the NOC: ' . $e->getMessage(),
            ]);
        }
        exit();
    }

    public function add_noc() {
        header('Content-Type: application/json');

        $c_pid = $this->input->post('C_Pid');
        log_message('debug', 'Adding NOC for C_Pid: ' . $c_pid);

        if (empty($c_pid)) {
            echo json_encode(['success' => false, 'message' => 'Client ID is required']);
            exit();
        }

        if (!$this->Noc_model->client_exists($c_pid)) {
            echo json_encode(['success' => false, 'message' => 'Invalid client ID']);
            exit();
        }

        $data = [
            'C_Pid' => $c_pid,
            'noc_type' => $this->input->post('noc_type'),
            'buy_sell' => $this->input->post('buy_sell'),
            'exchange' => $this->input->post('exchange'),
            'delivery_start' => $this->input->post('delivery_start'),
            'delivery_end' => $this->input->post('delivery_end'),
            'periphery' => $this->input->post('periphery'),
            'status' => $this->input->post('status'),
        ];

        try {
            $noc_id = $this->Noc_model->insert_noc($data);
            log_message('debug', 'NOC inserted with ID: ' . $noc_id);

            if ($noc_id) {
                // 🔁 Handle Quantum
                $from_times = $this->input->post('from_time');
                $to_times = $this->input->post('to_time');
                $quantities = $this->input->post('quantity');

                $quantums = [];
                if (is_array($from_times) && is_array($to_times) && is_array($quantities) &&
                    count($from_times) === count($to_times) && count($to_times) === count($quantities)) {
                    for ($i = 0; $i < count($from_times); $i++) {
                        if (!empty($from_times[$i]) && !empty($to_times[$i]) && !empty($quantities[$i]) && floatval($quantities[$i]) > 0) {
                            $quantums[] = [
                                'from_time' => $from_times[$i],
                                'to_time' => $to_times[$i],
                                'quantity' => floatval($quantities[$i])
                            ];
                        }
                    }
                }

                if (!empty($quantums)) {
                    $this->Noc_model->insert_quantums($quantums, $noc_id);
                }

                // ✅ Upload file *after* NOC insert
                if (!empty($_FILES['attachment']['name'])) {
                    $uploadPath = './uploads/noc_attachments/';
                    $config['upload_path']   = $uploadPath;
                    $config['allowed_types'] = 'pdf|jpg|jpeg|png|doc|docx';
                    $config['max_size']      = 10240; // 10MB
                    $config['encrypt_name']  = true;

                    $this->upload->initialize($config);

                    if ($this->upload->do_upload('attachment')) {
                        $uploadData = $this->upload->data();

                        $attachmentData = [
                            'noc_id'     => $noc_id,
                            'file_name'  => $uploadData['client_name'],  // Original filename
                            'file_path'  => 'uploads/noc_attachments/' . $uploadData['file_name'], // Actual filename
                            'mime_type'  => $uploadData['file_type'],
                            'file_size'  => $uploadData['file_size'],
                            'uploaded_at' => date('Y-m-d H:i:s')
                        ];

                        $this->Noc_model->insert_attachment($attachmentData);
                        log_message('debug', 'Attachment uploaded: ' . json_encode($attachmentData));
                    } else {
                        log_message('error', 'File upload failed: ' . $this->upload->display_errors('', ''));
                    }
                }

                echo json_encode(['success' => true, 'message' => 'NOC added successfully', 'noc_id' => $noc_id]);
            } else {
                log_message('error', 'NOC insert failed');
                echo json_encode(['success' => false, 'message' => 'Failed to add NOC to database']);
            }
        } catch (Exception $e) {
            log_message('error', 'Add NOC error: ' . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'An error occurred while adding the NOC: ' . $e->getMessage()]);
        }
        exit();
    }
}
?>