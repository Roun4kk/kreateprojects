<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Noc_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function search_client_by_pid_or_name($query) {
        log_message('debug', 'Searching client with query: ' . $query);

        $this->db->select('*');
        $this->db->from('client_details');
        $this->db->group_start();
        $this->db->like('C_Pid', $query);
        $this->db->or_like('C_Name', $query);
        $this->db->group_end();

        $results = $this->db->get()->result(); // fetch all matches
        log_message('debug', 'Search client result: ' . json_encode($results));
        return $results;
    }

    
    public function get_nocs_by_cpid($c_pid) {
        log_message('debug', 'Fetching NOCs for C_Pid: ' . $c_pid);
        $this->db->select('n.*, cd.C_Name, a.file_name, a.file_path');
        $this->db->from('noc_table n');
        $this->db->join('client_details cd', 'n.C_Pid = cd.C_Pid', 'left');
        $this->db->join('(SELECT noc_id, file_name, file_path FROM attachments GROUP BY noc_id) a', 'n.sno = a.noc_id', 'left');
        $this->db->where('n.C_Pid', $c_pid);
        $this->db->order_by('n.sno', 'DESC');
        $result = $this->db->get()->result();
        log_message('debug', 'NOCs count for C_Pid: ' . count($result));
        return $result;
    }

    public function search_nocs($query) {
        log_message('debug', 'Searching NOCs with query: ' . $query);
        $this->db->select('n.*, cd.C_Name');
        $this->db->from('noc_table n');
        $this->db->join('client_details cd', 'n.C_Pid = cd.C_Pid', 'left');
        $this->db->group_start();
        $this->db->like('cd.C_Name', $query);
        $this->db->or_like('cd.C_Pid', $query);
        $this->db->group_end();
        $result = $this->db->get()->result();
        log_message('debug', 'Search NOCs result count: ' . count($result));
        return $result;
    }

    public function get_noc_by_id($noc_id) {
        log_message('debug', 'Fetching NOC by ID: ' . $noc_id);
        $this->db->select('n.*, cd.C_Name');
        $this->db->from('noc_table n');
        $this->db->join('client_details cd', 'n.C_Pid = cd.C_Pid', 'left');
        $this->db->where('n.sno', $noc_id);
        $result = $this->db->get()->row();
        log_message('debug', 'NOC by ID result: ' . ($result ? json_encode($result) : 'No result'));
        return $result;
    }

    public function insert_noc($data) {
        log_message('debug', 'Inserting NOC: ' . json_encode($data));
        $this->db->insert('noc_table', $data);
        $insert_id = $this->db->insert_id();
        log_message('debug', 'NOC insert ID: ' . $insert_id);
        return $insert_id;
    }

    public function insert_attachment($data) {
        return $this->db->insert('attachments', $data);
    }

    public function update_noc($noc_id, $data) {
        log_message('debug', 'Updating NOC ID ' . $noc_id . ': ' . json_encode($data));
        $this->db->where('sno', $noc_id);
        $result = $this->db->update('noc_table', $data);
        log_message('debug', 'NOC update result: ' . ($result ? 'Success' : 'Failed'));
        return $result;
    }

    public function delete_noc($noc_id) {
        log_message('debug', 'Deleting NOC ID: ' . $noc_id);
        $this->db->where('sno', $noc_id);
        $result = $this->db->delete('noc_table');
        log_message('debug', 'NOC delete result: ' . ($result ? 'Success' : 'Failed'));
        return $result;
    }

    public function insert_quantums($quantums, $noc_sno) {
        log_message('debug', 'Inserting quantums for NOC ID: ' . $noc_sno . ' - ' . json_encode($quantums));
        foreach ($quantums as &$q) {
            $q['noc_sno'] = $noc_sno;
        }
        $result = $this->db->insert_batch('quantum', $quantums);
        log_message('debug', 'Quantum insert batch result: ' . ($result ? 'Success, affected rows: ' . $result : 'Failed'));
        return $result;
    }

    public function get_quantums_by_noc($noc_sno) {
        log_message('debug', 'Fetching quantums for NOC ID: ' . $noc_sno);
        $this->db->select('*');
        $this->db->from('quantum');
        $this->db->where('noc_sno', $noc_sno);
        $this->db->order_by('from_time', 'ASC');
        $result = $this->db->get()->result();
        log_message('debug', 'Quantums count: ' . count($result));
        return $result;
    }

    public function update_quantums($quantums, $noc_sno) {
        log_message('debug', 'Updating quantums for NOC ID: ' . $noc_sno);
        $this->delete_quantums_by_noc($noc_sno);
        return $this->insert_quantums($quantums, $noc_sno);
    }

    public function delete_quantums_by_noc($noc_sno) {
        log_message('debug', 'Deleting quantums for NOC ID: ' . $noc_sno);
        $this->db->where('noc_sno', $noc_sno);
        $result = $this->db->delete('quantum');
        log_message('debug', 'Quantum delete result: ' . ($result ? 'Success' : 'Failed'));
        return $result;
    }

    public function get_noc_stats_by_client($c_pid) {
        log_message('debug', 'Fetching NOC stats for C_Pid: ' . $c_pid);
        $this->db->select('
            COUNT(*) as total_nocs,
            SUM(CASE WHEN buy_sell = 1 THEN 1 ELSE 0 END) as buy_count,
            SUM(CASE WHEN buy_sell = 0 THEN 1 ELSE 0 END) as sell_count,
            SUM(CASE WHEN status = "approve" THEN 1 ELSE 0 END) as approved_count,
            SUM(CASE WHEN status = "reject" THEN 1 ELSE 0 END) as rejected_count
        ');
        $this->db->from('noc_table');
        $this->db->where('C_Pid', $c_pid);
        $result = $this->db->get()->row();
        log_message('debug', 'NOC stats result: ' . json_encode($result));
        return $result;
    }

    public function client_exists($c_pid) {
        log_message('debug', 'Checking if client exists: ' . $c_pid);
        $this->db->where('C_Pid', $c_pid);
        $count = $this->db->count_all_results('client_details');
        log_message('debug', 'Client exists: ' . ($count > 0 ? 'Yes' : 'No'));
        return $count > 0;
    }
}
?>