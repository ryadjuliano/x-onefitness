<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Customers extends MY_Controller
{

    function __construct() {
        parent::__construct();

        if (!$this->loggedIn) {
            redirect('login');
        }

        $this->load->library('form_validation');
        $this->load->model('customers_model');
    }

    function index() {

        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $this->data['page_title'] = lang('customers');
        $bc = array(array('link' => '#', 'page' => lang('customers')));
        $meta = array('page_title' => lang('customers'), 'bc' => $bc);
        $this->page_construct('customers/index', $this->data, $meta);
    }

    function attendance() {

        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $this->data['page_title'] = 'Attendance History';
        $bc = array(array('link' => '#', 'page' => 'Attendance' ));
        $meta = array('page_title' =>'Attendance', 'bc' => $bc);
        $this->page_construct('customers/attendance', $this->data, $meta);
    }

    function get_attendance() {

        // Assuming you have two time strings
// $time1 = '06:28:08';
// $time2 = '11:12:54';

// // Convert time strings to DateTime objects
// $datetime1 = DateTime::createFromFormat('H:i:s', $time1);
// $datetime2 = DateTime::createFromFormat('H:i:s', $time2);

// // Calculate the difference
// $interval = $datetime1->diff($datetime2);
        $this->load->library('datatables');
        $this->datatables
        ->select("attendance.*,attendance.id_a, customers.name as customer_name")
        ->from("attendance")
        ->join("customers", "attendance.member_id = customers.member_code", "left")
        ->add_column("Actions", "<div class='text-center'><div class='btn-group'><a href='" . site_url('customers/checkout/$1') . "' class='tip btn btn-success btn-xs' title=''><i class='fa fa-calendar'></i></a> <a href='" . site_url('customers/delete/$1') . "' onClick=\"return confirm('". $this->lang->line('alert_x_customer') ."')\" class='tip btn btn-danger btn-xs' title='".$this->lang->line("delete_customer")."'><i class='fa fa-trash-o'></i></a></div></div>", "id_a")
        ->unset_column('id_a');
        // src="' . base_url() . 'uploads/avatars/' . $user->gender . '.png"
        // print_r( $this->datatables->generate());
        // exit();
        echo $this->datatables->generate();

    }

    function get_customers() {

        $this->load->library('datatables');
        $this->datatables
        ->select("id, name,status, phone, email, member_code,image, start_date, end_date")
        ->from("customers")
        // ->add_column("Photo", "<div class='text-center'><div class='btn-group'><a href='"  . base_url() . 'uploads/members/$1' . "' class='tip btn btn-success btn-xs' target='_blank'><i class='fa fa-check'></i></a> </div></div>", "image")
        // exclamation
        // ->add_column("Active", "<div class='text-center'><div class='btn-group'> <a href='" . site_url('customers/banned/$1') . "' onClick=\"return confirm('". $this->lang->line('alert_x_customer') ."')\" class='tip btn btn-danger btn-xs' title='".$this->lang->line("delete_customer")."'><i class='fa fa-exclamation'></i></a></div></div>", "id")
        ->add_column("Actions", "<div class='text-center'><div class='btn-group'> 
        <a href='" . site_url('customers/edit/$1') . "'  class='tip btn btn-default btn-xs' title='Edit Member'><i class='fa fa-edit'></i></a>   
        <a href='" . site_url('customers/delete/$1') . "' onClick=\"return confirm('". $this->lang->line('alert_x_customer') ."')\" class='tip btn btn-danger btn-xs' title='".$this->lang->line("delete_customer")."'><i class='fa fa-trash-o'></i></a>
        <a href='" . site_url('customers/banned/$1') . "' onClick=\"return confirm('Apakah Kamu Yakin ingin Meng Nonaktifkan  ?')\" class='tip btn btn-warning btn-xs' title='Banned Member'><i class='fa fa-ban'></i></a>
        <a href='" . site_url('customers/active/$1') . "' onClick=\"return confirm('Apakah Kamu Yakin ingin Mengaktifkan Kembali ?')\" class='tip btn btn-success btn-xs' title='Active Member'><i class='fa fa-check'></i></a>   

        </div></div>", "id")
        ->unset_column('id');
        // src="' . base_url() . 'uploads/avatars/' . $user->gender . '.png"
        echo $this->datatables->generate();

    }

    public function generateMembershipNumber() {
        $prefix = 'X1F'; // You can customize the prefix
        $randomNumber = mt_rand(100000, 999999); // Generate a random 6-digit number

        $membershipNumber = $prefix . $randomNumber;

        return $membershipNumber;
    }

    function add() {

        $this->form_validation->set_rules('name', $this->lang->line("name"), 'required');
        $this->form_validation->set_rules('email', $this->lang->line("email_address"), 'valid_email');

        // $generatedMembershipNumber = $this->generateMembershipNumber();
        // echo $generatedMembershipNumber;
        if ($this->form_validation->run() == true) {

            $dob = $this->input->post('dob');
            $startDate = $this->input->post('start_date');

            $dateOfBirth = DateTime::createFromFormat('m/d/Y', $dob);
            $dateStartDate = DateTime::createFromFormat('m/d/Y', $startDate);

            // Convert the DateTime object to the desired format
            $formattedDateDOB = $dateOfBirth->format('Y-m-d');
            $formattedDateStartDate = $dateStartDate->format('Y-m-d');


            $data = array(
                'member_code' => $this->generateMembershipNumber(),
                'name' => $this->input->post('name'),
                'email' => $this->input->post('email'),
                'phone' => $this->input->post('phone'),
                'idcard' => $this->input->post('idcard'),
                'no_id' => $this->input->post('no_id'),
                'occupation' => $this->input->post('occupation'),
                'sex' => $this->input->post('sex'),
                'place' => $this->input->post('place'),
                'dob' => $formattedDateDOB,
                'address' => $this->input->post('address'),
                'start_date' => $formattedDateStartDate,
                'emergency_person' => $this->input->post('emergency_person'),
                'emergency_number' => $this->input->post('emergency_number'),
            );
            if ($_FILES['userfile']['size'] > 0) {

                $this->load->library('upload');

                $config['upload_path'] = 'uploads/members';
                $config['allowed_types'] = 'gif|jpg|png';
                $config['max_size'] = '500';
                $config['max_width'] = '800';
                $config['max_height'] = '800';
                $config['overwrite'] = FALSE;
                $config['encrypt_name'] = TRUE;
                $this->upload->initialize($config);

                if (!$this->upload->do_upload()) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect("customers/add");
                }

                $photo = $this->upload->file_name;
                $data['image'] = $photo;

                $this->load->library('image_lib');
                $config['image_library'] = 'gd2';
                $config['source_image'] = 'uploads/members/' . $photo;
                $config['new_image'] = 'uploads/members/thumbs/' . $photo;
                $config['maintain_ratio'] = TRUE;
                $config['width'] = 110;
                $config['height'] = 110;

                $this->image_lib->clear();
                $this->image_lib->initialize($config);

                if (!$this->image_lib->resize()) {
                    $this->session->set_flashdata('error', $this->image_lib->display_errors());
                    redirect("customers/add");
                }

            }
            // echo "<pre/>";
            // print_r($data);
            // exit;

        }
     

        if ( $this->form_validation->run() == true && $cid = $this->customers_model->addCustomer($data)) {

            if($this->input->is_ajax_request()) {
                echo json_encode(array('status' => 'success', 'msg' =>  $this->lang->line("customer_added"), 'id' => $cid, 'val' => $data['name']));
                die();
            }
            $this->session->set_flashdata('message', $this->lang->line("customer_added"));
            redirect("customers");

        } else {
            if($this->input->is_ajax_request()) {
                echo json_encode(array('status' => 'failed', 'msg' => validation_errors())); die();
            }

            $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
            $this->data['page_title'] = lang('add_customer');
            $bc = array(array('link' => site_url('customers'), 'page' => lang('customers')), array('link' => '#', 'page' => lang('add_customer')));
            $meta = array('page_title' => lang('add_customer'), 'bc' => $bc);
            $this->page_construct('customers/add', $this->data, $meta);

        }
    }

    function edit($id = NULL) {
        if (!$this->Admin) {
            $this->session->set_flashdata('error', $this->lang->line('access_denied'));
            redirect('pos');
        }
        if($this->input->get('id')) { $id = $this->input->get('id', TRUE); }

        $this->form_validation->set_rules('name', $this->lang->line("name"), 'required');
        $this->form_validation->set_rules('email', $this->lang->line("email_address"), 'valid_email');

        if ($this->form_validation->run() == true) {

            $data = array('name' => $this->input->post('name'),
                'email' => $this->input->post('email'),
                'phone' => $this->input->post('phone'),
                'address' => $this->input->post('address'),
                // 'cf2' => $this->input->post('cf2')
            );

        }

        if ( $this->form_validation->run() == true && $this->customers_model->updateCustomer($id, $data)) {

            $this->session->set_flashdata('message', $this->lang->line("customer_updated"));
            redirect("customers");

        } else {

            $this->data['customer'] = $this->customers_model->getCustomerByID($id);
            
            $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
            $this->data['page_title'] = lang('edit_customer');
            $bc = array(array('link' => site_url('customers'), 'page' => lang('customers')), array('link' => '#', 'page' => lang('edit_customer')));
            $meta = array('page_title' => lang('edit_customer'), 'bc' => $bc);
            $this->page_construct('customers/edit', $this->data, $meta);

        }
    }

    function delete($id = NULL) {
        if(DEMO) {
            $this->session->set_flashdata('error', $this->lang->line("disabled_in_demo"));
            redirect('pos');
        }

        if($this->input->get('id')) { $id = $this->input->get('id', TRUE); }

        if (!$this->Admin)
        {
            $this->session->set_flashdata('error', lang("access_denied"));
            redirect('pos');
        }

        if ( $this->customers_model->deleteCustomer($id) )
        {
            $this->session->set_flashdata('message', lang("customer_deleted"));
            redirect("customers");
        }

    }

    function checkout($id = NULL) {
        if(DEMO) {
            $this->session->set_flashdata('error', $this->lang->line("disabled_in_demo"));
            redirect('pos');
        }

        if($this->input->get('id')) { $id = $this->input->get('id', TRUE); }

        // if (!$this->Admin)
        // {
        //     $this->session->set_flashdata('error', lang("access_denied"));
        //     redirect('pos');
        // }
       
        $data = array(
            'check_out' =>date("h:i:s"),
            'status' => 0,
        );
        //  $res =  $this->customers_model->updateustomerChecout($code, $data);

        if ( $this->customers_model->updateustomerChecout($id, $data) )
        {
            // $this->session->set_flashdata('message', lang("customer_deleted"));
            redirect("customers/attendance");
        }   
    }

    function banned($id = NULL) {
        if(DEMO) {
            $this->session->set_flashdata('error', $this->lang->line("disabled_in_demo"));
            redirect('pos');
        }

        if($this->input->get('id')) { $id = $this->input->get('id', TRUE); }

        // if (!$this->Admin)
        // {
        //     $this->session->set_flashdata('error', lang("access_denied"));
        //     redirect('pos');
        // }
       
        $data = array(
            // 'check_out' =>date("h:i:s"),
            'status' => 0,
        );
        //  $res =  $this->customers_model->updateustomerChecout($code, $data);

        if ( $this->customers_model->BannedCust($id, $data) )
        {
            $this->session->set_flashdata('message', 'Customers Non Active');
            redirect("customers");
        }   
    }

    function active($id = NULL) {
        if(DEMO) {
            $this->session->set_flashdata('error', $this->lang->line("disabled_in_demo"));
            redirect('pos');
        }

        if($this->input->get('id')) { $id = $this->input->get('id', TRUE); }

        // if (!$this->Admin)
        // {
        //     $this->session->set_flashdata('error', lang("access_denied"));
        //     redirect('pos');
        // }
       
        $data = array(
            'status' => 1,
        );
        //  $res =  $this->customers_model->updateustomerChecout($code, $data);

        if ( $this->customers_model->BannedCust($id, $data) )
        {
            $this->session->set_flashdata('message', 'Customers Active');
            redirect("customers");
        }   
    }

    // 

}
