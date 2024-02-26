<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Customers_model extends CI_Model
{

    public function __construct() {
        parent::__construct();
    }

    public function getCustomerByID($id) {
        $q = $this->db->get_where('customers', array('id' => $id), 1);
        if( $q->num_rows() > 0 ) {
            return $q->row();
        }
        return FALSE;
    }

    public function searchData($searchTerm) {
        // Perform your database query here based on the search term (ID or name)
        // Example: Assuming you have a table named 'your_table'
        // $this->db->like('member_code', $searchTerm);
        // $this->db->or_like('name', $searchTerm);
        // $query = $this->db->get('customers');

        // return $query->result();


        $this->db->select('*');
        $this->db->from('customers');
        $this->db->where('customers.status', 1); // Adding the condition here
        $this->db->like('customers.member_code', $searchTerm);
        $this->db->or_like('customers.name', $searchTerm);

        // Joining with 'orders'
        $this->db->join('attendance', 'attendance.member_id = customers.member_code', 'left');

        // // Joining with 'order_details'
        // $this->db->join('order_details', 'order_details.order_id = orders.order_id', 'left');

        // // Joining with 'products'
        // $this->db->join('products', 'products.product_id = order_details.product_id', 'left');

        $query = $this->db->get();

        return $query->result();
    }

    public function addCustomer($data = array()) {
        if($this->db->insert('customers', $data)) {
            return $this->db->insert_id();
        }
        return false;
    }

    public function updateCustomer($id, $data = array()) {
        if($this->db->update('customers', $data, array('id' => $id))) {
            return true;
        }
        return false;
    }

    public function addCustomerCheckin($data = array()) {
        // if($this->db->insert('attendance', $data)) {
        //     return $this->db->insert_id();
        // }
        // return false;
        // $this->db->insert('tec_attendance', $data);
        // if ($this->db->error()) {
        //     print_r($this->db->error());
        // }

        if( $this->db->insert('attendance', $data)) {
            return true;
        }
        return FALSE;
    }
    public function updateustomerChecout($id, $data = array()) {
        if($this->db->update('attendance', $data, array('id_a' => $id))) {
            return true;
        }
        return false;
    }

    public function BannedCust($id, $data = array()) {
        if($this->db->update('customers', $data, array('id' => $id))) {
            return true;
        }
        return false;
    }
    // checkOutMember
    public function deleteCustomer($id) {
        if($this->db->delete('customers', array('id' => $id))) {
            return true;
        }
        return FALSE;
    }

    public function deleteAttendance($id) {
        if($this->db->delete('attendance', array('id_a' => $id))) {
            return true;
        }
        return FALSE;
    }

    

}
