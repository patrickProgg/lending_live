<?php
defined('BASEPATH') or exit('No direct script access allowed');

class PullOut_cont extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
    }

    public function get_pull_out()
    {
        $start = $this->input->post('start');
        $length = $this->input->post('length');
        $searchValue = trim($this->input->post('search')['value']);
        $history = $this->input->post('history');

        $order = $this->input->post('order');
        $orderColumnIndex = isset($order[0]['column']) ? $order[0]['column'] : 0;
        $orderDir = isset($order[0]['dir']) ? $order[0]['dir'] : 'DESC';

        $columns = [
            0 => 'id',
            1 => 'date_added',
            2 => 'process_fee',
            3 => 'ticket',
            4 => 'profit_share',
            5 => 'pull_out',
            6 => 'pull_out_capital',
            7 => 'total_pull_out'
        ];

        $orderColumn = $columns[$orderColumnIndex];

        $this->db->select('
            id, 
            date_added, 
            process_fee, 
            ticket, 
            profit_share, 
            pull_out, 
            pull_out_capital,
            total_pull_out
        ');

        if ($history) {
            $this->db->where('status', '1');
        } else {
            $this->db->where('status', '0');
        }

        $this->db->from('tbl_pull_out');

        if (!empty($searchValue)) {
            $this->db->group_start();
            $this->db->like('id', $searchValue);
            $this->db->or_like('date_added', $searchValue);
            $this->db->or_like('process_fee', $searchValue);
            $this->db->or_like('ticket', $searchValue);
            $this->db->or_like('profit_share', $searchValue);
            $this->db->or_like('pull_out', $searchValue);
            $this->db->or_like('total_pull_out', $searchValue);
            $this->db->group_end();
        }

        $this->db->order_by($orderColumn, $orderDir);

        $subQuery = clone $this->db;
        $recordsFiltered = $subQuery->get()->num_rows();

        $this->db->limit($length, $start);

        $query = $this->db->get();
        $data = $query->result_array();

        $this->db->select_sum('process_fee');
        $this->db->select_sum('ticket');
        $this->db->select_sum('profit_share');
        $this->db->select_sum('pull_out');
        $this->db->select_sum('pull_out_capital');
        $this->db->select_sum('total_pull_out');

        $this->db->where('status !=', '1');
        $this->db->from('tbl_pull_out');

        if (!empty($searchValue)) {
            $this->db->group_start();
            $this->db->like('id', $searchValue);
            $this->db->or_like('date_added', $searchValue);
            $this->db->or_like('process_fee', $searchValue);
            $this->db->or_like('ticket', $searchValue);
            $this->db->or_like('profit_share', $searchValue);
            $this->db->or_like('pull_out', $searchValue);
            $this->db->or_like('total_pull_out', $searchValue);
            $this->db->group_end();
        }

        $totalRow = $this->db->get()->row();
        $totalFee = $totalRow->process_fee ?? 0;
        $totalTicket = $totalRow->ticket ?? 0;
        $totalProfit = $totalRow->profit_share ?? 0;
        $totalPullOut = $totalRow->pull_out ?? 0;
        $totalPullOutCapital = $totalRow->pull_out_capital ?? 0;
        $totalAmount = $totalRow->total_pull_out ?? 0;

        echo json_encode([
            "draw" => intval($this->input->post('draw')),
            "recordsTotal" => $recordsFiltered,
            "recordsFiltered" => $recordsFiltered,
            "total_fee" => $totalFee,
            "total_ticket" => $totalTicket,
            "total_profit" => $totalProfit,
            "total_pull_out" => $totalPullOut,
            "total_pull_out_capital" => $totalPullOutCapital,
            "total_amt" => $totalAmount,
            "data" => $data
        ]);
    }
    public function add_pull_out()
    {
        $total_pull_out = $this->input->post('total_amt');
        $process_fee = $this->input->post('process_fee');
        $ticket = $this->input->post('ticket');
        $profit_share = $this->input->post('profit');
        $pull_out = $this->input->post('pull_out');
        $pull_out_capital = $this->input->post('pull_out_capital');

        $pull_out_details = [
            'process_fee' => $process_fee,
            'ticket' => $ticket,
            'profit_share' => $profit_share,
            'pull_out' => $pull_out,
            'pull_out_capital' => $pull_out_capital,
            'total_pull_out' => $total_pull_out,
            'date_added' => $this->input->post('date_added'),
        ];

        $total_pull_out = $this->input->post('total_amt');

        $inserted = $this->db->insert('tbl_pull_out', $pull_out_details);

        // $this->db->set('pull_out_bal', 'pull_out_bal + ' . $total_pull_out, FALSE)
        //     ->where('id', 1)
        //     ->update('tbl_balance');

        if ($inserted) {
            $this->db->set('processing_fee', 'processing_fee + ' . $process_fee, FALSE);
            $this->db->set('ticket', 'ticket + ' . $ticket, FALSE);
            $this->db->set('profit', 'profit + ' . $profit_share, FALSE);
            $this->db->set('expansion', 'expansion + ' . $pull_out, FALSE);
            $this->db->set('capital', 'capital + ' . $pull_out_capital, FALSE);
            $this->db->set('pull_out_bal', 'pull_out_bal + ' . $total_pull_out, FALSE);
            $this->db->where('id', 1);
            $this->db->update('tbl_balance');
        }

        if (!$inserted) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Failed to add pull out record.'
            ]);
        } else {
            echo json_encode([
                'status' => 'success',
                'message' => 'Pull out saved successfully.',
            ]);
        }
    }

    public function update_pull_out($id)
    {
        // Get old record
        $old_record = $this->db->select('process_fee, ticket, profit_share, pull_out, pull_out_capital, total_pull_out')
            ->where('id', $id)
            ->get('tbl_pull_out')
            ->row();

        if (!$old_record) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Record not found.'
            ]);
            return;
        }

        // Mapping: pull_out column -> balance column
        $field_mapping = [
            'process_fee' => 'processing_fee',
            'ticket' => 'ticket',
            'profit_share' => 'profit',
            'pull_out' => 'expansion',
            'pull_out_capital' => 'capital',
            'total_pull_out' => 'pull_out_bal'
        ];

        // Special mapping for POST fields (some have different names)
        $post_mapping = [
            'process_fee' => 'process_fee',
            'ticket' => 'ticket',
            'profit_share' => 'profit',
            'pull_out' => 'pull_out',
            'pull_out_capital' => 'pull_out_capital',
            'total_pull_out' => 'total_amt'  // Special case
        ];

        $pull_out_details = [];
        $differences = [];

        foreach ($field_mapping as $pull_field => $balance_field) {
            // Get the POST field name
            $post_field = $post_mapping[$pull_field];

            // Get new value from POST
            $new_value = (float) $this->input->post($post_field) ?: 0;
            $old_value = (float) $old_record->$pull_field ?: 0;
            $difference = $new_value - $old_value;

            $pull_out_details[$pull_field] = $new_value;
            $differences[$balance_field] = $difference;
        }

        // Add date_added
        $pull_out_details['date_added'] = $this->input->post('date_added');

        // Start transaction
        $this->db->trans_start();

        // Update pull out record
        $this->db->where('id', $id);
        $updated = $this->db->update('tbl_pull_out', $pull_out_details);

        if ($updated) {
            // Update balance table
            foreach ($differences as $balance_field => $difference) {
                if ($difference != 0) {
                    $this->db->set($balance_field, "{$balance_field} + {$difference}", FALSE);
                }
            }

            $this->db->where('id', 1);
            $this->db->update('tbl_balance');
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE || !$updated) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Failed to update pull out record.'
            ]);
        } else {
            echo json_encode([
                'status' => 'success',
                'message' => 'Pull out data updated successfully.',
            ]);
        }
    }

    public function delete_id()
    {
        $id = $this->input->post('id');

        // Get the full record before deleting/archiving
        $record = $this->db->select('process_fee, ticket, profit_share, pull_out, pull_out_capital, total_pull_out')
            ->where('id', $id)
            ->get('tbl_pull_out')
            ->row();

        if (!$record) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Record not found.'
            ]);
            return;
        }

        // Get all values from the record
        $process_fee = (float) $record->process_fee ?: 0;
        $ticket = (float) $record->ticket ?: 0;
        $profit_share = (float) $record->profit_share ?: 0;
        $pull_out = (float) $record->pull_out ?: 0;
        $pull_out_capital = (float) $record->pull_out_capital ?: 0;
        $total_pull_out = (float) $record->total_pull_out ?: 0;

        // Soft delete - set status to 1 (archived/deleted)
        $data = [
            'status' => "1"
        ];

        // Start transaction
        $this->db->trans_start();

        // Update the pull out record (soft delete)
        $this->db->where('id', $id);
        $updated = $this->db->update('tbl_pull_out', $data);

        if ($updated) {
            // Subtract all amounts from balance table
            $this->db->set('processing_fee', 'processing_fee - ' . $process_fee, FALSE);
            $this->db->set('ticket', 'ticket - ' . $ticket, FALSE);
            $this->db->set('profit', 'profit - ' . $profit_share, FALSE);
            $this->db->set('expansion', 'expansion - ' . $pull_out, FALSE);
            $this->db->set('capital', 'capital - ' . $pull_out_capital, FALSE);
            $this->db->set('pull_out_bal', 'pull_out_bal - ' . $total_pull_out, FALSE);
            $this->db->where('id', 1);
            $this->db->update('tbl_balance');
        }

        // Complete transaction
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE || !$updated) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Failed to delete pull out record.'
            ]);
        } else {
            echo json_encode([
                'status' => 'success',
                'message' => 'Pull out data deleted successfully.',
            ]);
        }
    }

    public function get_total_pullout()
    {
        $this->db->select('pull_out_bal');
        $query = $this->db->get('tbl_balance');
        $result = $query->row();
        echo json_encode([
            'total_pullout' => $result->pull_out_bal ?? 0
        ]);
    }

    public function add_withdrawal()
    {
        $total_pullout = floatval($this->input->post('total_pullout'));
        $amount = floatval($this->input->post('amount'));
        $notes = $this->input->post('notes');

        $current_balance = floatval($total_pullout);

        $withdrawal_details = [
            'withdraw_amt' => $amount,
            'note' => $notes,
            'date_added' => date('Y-m-d H:i:s')
        ];

        $inserted = $this->db->insert('tbl_withdrawal', $withdrawal_details);

        if (!$inserted) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Failed to add withdrawal record.'
            ]);
            return;
        }

        $new_balance = $current_balance - $amount;

        $this->db->where('id', 1);
        $this->db->update('tbl_balance', ['pull_out_bal' => $new_balance]);

        if ($this->db->affected_rows() > 0) {
            echo json_encode([
                'status' => 'success',
                'message' => 'Withdrawal saved successfully.',
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Failed to update balance.'
            ]);
        }
    }

    public function get_withdrawal_history()
    {
        // Get all withdrawals ordered by date (latest first)
        $withdrawals = $this->db->select('*')
            ->from('tbl_withdrawal')
            ->order_by('date_added', 'DESC')
            ->get()
            ->result();

        if (empty($withdrawals)) {
            echo json_encode([
                'status' => 'success',
                'data' => [],
                'total' => 0,
                'count' => 0
            ]);
            return;
        }

        // Calculate total
        $total = 0;
        foreach ($withdrawals as $w) {
            $total += floatval($w->withdraw_amt);
        }

        echo json_encode([
            'status' => 'success',
            'data' => $withdrawals,
            'total' => number_format($total, 2),
            'count' => count($withdrawals)
        ]);
    }
}