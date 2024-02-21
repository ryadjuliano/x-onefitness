<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends MY_Controller
{

    public function __construct() {
        parent::__construct();

        if (! $this->loggedIn) {
            redirect('login');
        }
        if (version_compare($this->Settings->version, '4.0.14', '<=')) {
            $this->load->model('db_update');
            $this->db_update->update();
        }
        $this->load->model('welcome_model');
        $this->load->model('customers_model');
        $this->load->model('reports_model');

        
        if ($register = $this->site->registerData($this->session->userdata('user_id'))) {
            $register_data = array('register_id' => $register->id, 'cash_in_hand' => $register->cash_in_hand, 'register_open_time' => $register->date, 'store_id' => $register->store_id);
            $this->session->set_userdata($register_data);
        }
    }

    function index() {
        $year = date('Y');
        $month = date('m');
        
        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $this->data['topProducts'] = $this->welcome_model->topProducts();
        $this->data['chartData'] = $this->welcome_model->getChartData();
        $this->data['checkinData'] = $this->welcome_model->getCheckin();
        $this->data['members'] = $this->welcome_model->getMember();
        $sales = $this->reports_model->getDailySales($year, $month);
        
        $start = $year.'-'.$month.'-01 00:00:00';
        $end = $year.'-'.$month.'-'.days_in_month($month, $year).' 23:59:59';
        // $this->data['total_purchases'] = $this->reports_model->getTotalPurchases($start, $end);
        $this->data['total_sales']= $this->reports_model->getTotalSales($start, $end);
        // $this->data['total_expenses'] = $this->reports_model->getTotalExpenses($start, $end);

        // foreach($sales as $row) {
        //     $this->data['income'] = $row->paid;
        // }

        // 
        // $check  = $this->welcome_model->getCheckin();
        
        // print_r($ddd);
        // exit();
        $this->data['page_title'] = lang('dashboard');
        $bc = array(array('link' => '#', 'page' => lang('dashboard')));
        $meta = array('page_title' => lang('dashboard'), 'bc' => $bc);
        $this->page_construct('dashboard', $this->data, $meta);

    }
    
    function get_members () {

        $searchTerm = $this->input->get('search'); // Assuming you pass the search term through the query parameter
        $result = $this->customers_model->searchData($searchTerm);

        // Create JSON response
        $jsonData = json_encode($result);

        // Set the content type to JSON
        $this->output->set_content_type('application/json');
        $this->output->set_output($jsonData);

    }

    public function checkin () {
        // $this->input->post('type'),
       $code = $this->input->get('member_code'); // Assuming you pass the search term through the query parameter
       $identity = $this->session->userdata($this->config->item('identity'));
        $data = array(
                'name' => "X-ONE Fitness",
                'member_id' => $code,
                'date_checkin' => date('Y-m-d'),
                'check_in' =>date("h:i:s"),
                'created_by' => $identity['username'],
                'status' => 1,
                'created_at' => date('Y-m-d h:i:s')
            );
        $res =  $this->customers_model->addCustomerCheckin($data);
        if($res) {
            $result = array('status' => 'success', 'message' => 'Data received successfully');
        } else {
            $result = array('status' => 'success', 'message' => 'Data received successfully');
        }
        // Create JSON response
        echo json_encode($result);
    }

    public function checkout () {
        // $this->input->post('type'),
       $code = $this->input->get('id_att'); 
        $data = array(
                'check_out' =>date("h:i:s"),
                'status' => 0,
            );
        $res =  $this->customers_model->updateustomerChecout($code, $data);
        if($res) {
            $result = array('status' => 'success', 'message' => 'Data received successfully');
        } else {
            $result = array('status' => 'error', 'message' => 'Data not successfully');
        }
        // Create JSON response
        echo json_encode($result);
    }

    function disabled() {
        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $this->data['page_title'] = lang('disabled_in_demo');
        $bc = array(array('link' => '#', 'page' => lang('disabled_in_demo')));
        $meta = array('page_title' => lang('disabled_in_demo'), 'bc' => $bc);
        $this->page_construct('disabled', $this->data, $meta);
    }

    public function signing($req = NULL) {
        if (!$req) {
            header("Content-type: text/plain");
            echo file_get_contents('./files/public.pem');
            exit(0);
        } else {

            $privateKey = openssl_get_privatekey(file_get_contents('./files/private.pem'), 'S3cur3P@ssw0rd');
            $signature = null;
            openssl_sign($req, $signature, $privateKey);

            if ($signature) {
                header("Content-type: text/plain");
                echo base64_encode($signature);
                exit(0);
            }

            echo '<h1>Error signing message</h1>';
            exit(1);
        }
    }

}
