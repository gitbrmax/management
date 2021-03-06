<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class bast extends CI_Controller {

    // db2 digunakan untuk mengakses database ke-2
    private $db2;

    public function __construct() {
        parent::__construct();
        if($this->session->userdata('logged_in')==FALSE){
            redirect('User');    
        }
        date_default_timezone_set("Asia/Bangkok"); 
        $this->db2 = $this->load->database('hvc', TRUE);
        $this->load->model(array('bastmodel','salesmodel','graparimodel','Branchmodel','Reportmodel','Sales_channelmodel','Paketmodel','usersmodel','Salespersonmodel','Sales_channelmodel','Msisdnmodel','Reasonmodel'));
    }
    
    public function index(){
        redirect('bast/view');
        
    }

    public function view($action='')
    {   
        $data=array();
        $sess_branch = $this->session->userdata('branch_id');
        $sess_level = $this->session->userdata('level');
        if($sess_level == 4){
            $data['bast']=$this->bastmodel->get_all_by('');
        }else{
            $data['bast']=$this->bastmodel->get_all_by($sess_branch);
        } 
        if($action=='asyn'){
            $this->load->view('content/bast/list',$data);
        }else if($action==''){
            $this->load->view('theme/include/header');
            $this->load->view('content/bast/list',$data);
            $this->load->view('theme/include/footer');
        }
    }

    /** Method For View,insert, update and delete Bast Header  **/
    public function create($action='', $param1='')
    {
        $data=array();  
        $sess_branch = $this->session->userdata('branch_id');
        $sess_level = $this->session->userdata('level');
        if($sess_level == 4){
            $data['bast_list']=$this->bastmodel->get_all_bydate('');
            $data['branch']=$this->Branchmodel->get_all();
        }else{
            $data['bast_list']=$this->bastmodel->get_all_bydate($sess_branch);
            $data['branch']=$this->Branchmodel->get_all_by($sess_branch);
        }
        
        //----For ajax load-----//
        if($action=='asyn'){
            $this->load->view('content/bast/add',$data);
        }else if($action==''){  
            $this->load->view('theme/include/header');
            $this->load->view('content/bast/add',$data);
            $this->load->view('theme/include/footer');
        }
        
        //----End Page Load------//
        //----For Insert update and delete-----//
        if($action=='insert'){
            $data=array();
            $do=$this->input->post('action',true);     
            $data['no_bast']=$no_bast=$this->input->post('bno_bast',true); 
            $data['branch_id']=$this->input->post('bbranch',true); 
            $data['tanggal_masuk']=$this->input->post('btanggal_masuk',true);  
            $data['id_users']=$this->session->userdata('id_users');  
            //-----Validation-----//    
            if($data['no_bast']!="" && $data['branch_id']!="" && $data['tanggal_masuk']!=""){
                if($do=='insert'){
                    //Check Duplicate Entry    
                    if(!value_exists("bast_header","no_bast",$data['no_bast'])){
                        if($sess_level == 4){
                            $jml_bast=$this->bastmodel->get_all_bydate('');
                        }else{
                            $jml_bast=$this->bastmodel->get_all_bydate($sess_branch);
                        }

                        if(count($jml_bast)>=2){
                            echo '{"result":"false", "message":"Pembuatan BAST maksimal 2 kali dalam sehari.!!"}';;
                        }else{
                            if($this->db->insert('bast_header',$data)){
                                $last_id=$this->db->insert_id();    
                                echo '{"result":"true", "action":"insert", "last_id":"'.$last_id.'"}';
                            }
                        }
                    }else{
                        echo '{"result":"false", "message":"This Name Is Already Exists !"}';;
                    }
                }else if($do=='update'){
                    $id=$this->input->post('id_header',true); 
                    $no_bast2=$this->input->post('bno_bast2',true);
                    if($no_bast2 == ""){
                        $data['no_bast'] = $no_bast;
                    }else{
                        $data['no_bast'] = $no_bast2;
                    }
                    //Check Duplicate Entry  
                    if(!value_exists("bast_header","no_bast",$no_bast2)){ 
                        $this->db->where('id_header', $id);
                        $this->db->update('bast_header', $data);
                        echo '{"result":"true","action":"update"}'; 
                    }else{
                        echo '{"result":"false", "message":"This Name Is Already Exists !"}';;
                    }      
                }    
            }else{
                echo '{"result":"false", "message":"All Field Must Required With Valid Length !"}';
            }
            //----End validation----//
                
        }else if($action=='remove'){    
            $this->db->delete('bast_header', array('id_header' => $param1));        
        }else if($action=='receive'){    
            $datareceive['tanggal_terima'] = date('Y-m-d h:i:s');
            $this->db->where('id_header', $param1);
            $this->db->update('bast_header', $datareceive);     
        } 
    }
    /** Method For Add New Account and Account Page View **/    
    public function add($no_bast='',$action='',$param1='')
    {
        $sess_level = $this->session->userdata('level');
        $sess_branch = $this->session->userdata('branch_id');
        if($sess_level==4){
            $datax['msisdn']=$this->Msisdnmodel->get_all();
            $datax['branch']=$this->Branchmodel->get_all();
            $datax['tl']=$this->usersmodel->get_all_tl();
            $datax['sub_channel']=$this->Sales_channelmodel->get_all();
            $datax['sales_person']=$this->Salespersonmodel->get_all();
            $datax['grapari']=$this->graparimodel->get_all();
            $datax['validasi']=$this->usersmodel->get_all_validasi();

        }else{
            $datax['msisdn']=$this->Msisdnmodel->get_all_by_status($sess_branch);
            $datax['branch']=$this->Branchmodel->get_all_by($sess_branch);
            $datax['tl']=$this->usersmodel->get_all_tl_by($sess_branch);
            $datax['sub_channel']=$this->Sales_channelmodel->get_all_by($sess_branch);
            $datax['sales_person']=$this->Salespersonmodel->get_all_by($sess_branch);
            $datax['grapari']=$this->graparinmodel->get_all_by($sess_branch);
            $datax['validasi']=$this->usersmodel->get_all_validasi_by($sess_branch);
        }
        
        $datax['paket']=$this->Paketmodel->get_all();
        $qbast = $this->bastmodel->get_all_by_no_bast_row($no_bast);
        if(empty($qbast)){
            redirect('Admin/home');
        }
        $datax['no_bast'] = $qbast->no_bast;
        $datax['tanggal_masuk'] = $qbast->tanggal_masuk;
        if($action=='asyn'){
            $this->load->view('content/bast/add_sales',$datax);
        }else if($action==''){
            $this->load->view('theme/include/header');
            $this->load->view('content/bast/add_sales',$datax);
            $this->load->view('theme/include/footer');
        }
        //----End Page Load------//
        //----For Insert update and delete-----// 
        if($action=='insert'){  
            $data=array();
            $do                         =addslashes($this->input->post('action',true));     
            $data['no_bast']            = $no_bast = addslashes($this->input->post('sno_bast',true)); 
            $data['msisdn']             = $msisdn = addslashes($this->input->post('smsisdn',true)); 
            $data['nama_pelanggan']     =addslashes($this->input->post('snama_pelanggan',true)); 
            $data['alamat']             =addslashes($this->input->post('salamat',true)); 
            $data['alamat2']            =addslashes($this->input->post('salamat2',true));  
            $data['no_hp']              =addslashes($this->input->post('sno_hp',true));  
            $data['ibu_kandung']        =addslashes($this->input->post('sibu_kandung',true));  
            $data['tanggal_masuk']      =$tanggal_masuk = addslashes($this->input->post('stanggal_masuk',true));
            $data['paket_id']              =addslashes($this->input->post('spaket',true));
            $data['discount']           =addslashes($this->input->post('sdiscount',true));
            $data['periode']            =addslashes($this->input->post('speriode',true));
            $data['jenis_event']        =addslashes($this->input->post('sjenis_event',true));
            $data['nama_event']         =addslashes($this->input->post('snama_event',true));
            $data['status']             ="masuk";
        
            $data['branch_id']          =addslashes($this->input->post('sbranch',true));
            $data['sub_sales_channel']  = $sub_channel = addslashes($this->input->post('ssub_channel',true));
            $data['detail_sub']         =addslashes($this->input->post('sdetail_sub',true));
            $data['TL']                 = $tl =addslashes($this->input->post('sTL',true));
            $data['sales_person']       =addslashes($this->input->post('ssales_person',true));

            $data['tanggal_input']      = date('Y-m-d h:i:s');   
                 
            //-----Validation-----//   
            $this->form_validation->set_rules('smsisdn', 'MSISDN', 'trim|required|xss_clean|min_length[10]|max_length[14]|numeric');
            $this->form_validation->set_rules('snama_pelanggan', 'Nama Pelanggan', 'trim|required|xss_clean|min_length[3]');
            $this->form_validation->set_rules('salamat', 'Alamat ', 'trim|required|xss_clean|min_length[10]');
            $this->form_validation->set_rules('salamat2', 'Alamat 2', 'trim|required|xss_clean|min_length[5]');
            $this->form_validation->set_rules('sno_hp', 'No HP', 'trim|required|xss_clean|min_length[10]|max_length[14]|numeric');
            $this->form_validation->set_rules('sibu_kandung', 'Ibu Kandung', 'trim|required|xss_clean|min_length[3]');
            $this->form_validation->set_rules('stanggal_masuk', 'Tanggal Masuk', 'trim|required|xss_clean');
            $this->form_validation->set_rules('spaket', 'Paket', 'trim|required|xss_clean');
            $this->form_validation->set_rules('sdiscount', 'Discount', 'trim|required|xss_clean');
            $this->form_validation->set_rules('speriode', 'Periode', 'trim|required|xss_clean');
            $this->form_validation->set_rules('sjenis_event', 'Jenis Event', 'trim|required|xss_clean');
            $this->form_validation->set_rules('snama_event', 'Nama Event', 'trim|required|xss_clean|min_length[3]');

            $this->form_validation->set_rules('sbranch', 'Branch', 'trim|required|xss_clean');
            $this->form_validation->set_rules('ssub_channel', 'Sub Channel', 'trim|required|xss_clean');
            $this->form_validation->set_rules('sdetail_sub', 'Detail Sub', 'trim|required|xss_clean|min_length[3]');
            $this->form_validation->set_rules('sTL', 'TL', 'trim|required|xss_clean');
            $this->form_validation->set_rules('ssales_person', 'Sales Person', 'trim|required|xss_clean');

            if($do == "update"){
                $msisdn1 = addslashes($this->input->post('smsisdn1',true)); 
                $this->form_validation->set_rules('smsisdn1', 'MSISDN', 'trim|xss_clean|min_length[10]|max_length[14]|numeric');
            }

            if (!$this->form_validation->run() == FALSE)
            { 
                if($do=='insert'){
                    
                    if(value_exists("new_psb","msisdn",$msisdn)) {  

                        echo "This MSISDN Is Already Exists !!!!"; 
                                            
                    }else{
                        $user_tl = $this->usersmodel->get_users_by_id($tl);
                        $query_channel = $this->Sales_channelmodel->get_sales_channel_by_id($sub_channel);
                        $data['sales_channel'] = $query_channel->sales_channel;

                        if(count($user_tl)>0) { 
                            $data['TL'] = $user_tl->username;
                        }

                        $this->db->insert('new_psb',$data);

                        $datastatus['status']             ="masuk";
                        $this->db->where('msisdn', $msisdn);
                        $this->db->update('msisdn', $datastatus);

                        echo "true";
                    }
                     
                }else if($do=='update'){
                    
                    if($msisdn1 == "" || $msisdn1 == null){
                        $data['msisdn'] = $msisdn;
                    }else{
                        $data['msisdn'] = $msisdn1;
                    }

                    if(value_exists("new_psb","msisdn",$msisdn1)) {  

                        echo "This MSISDN Is Already Exists !!!!"; 
                                            
                    }else{

                        $user_tl = $this->usersmodel->get_users_by_id($tl);
                        $query_channel = $this->Sales_channelmodel->get_sales_channel_by_id($sub_channel);
                        $data['sales_channel'] = $query_channel->sales_channel;

                        if(count($user_tl)>0) { 
                            $data['TL'] = $user_tl->username;
                        }

                        $id=$this->input->post('psb_id',true);
                        
                        $this->db->where('psb_id', $id);
                        $this->db->update('new_psb', $data);

                        echo "true";
                    }
                    
                }         
            }else{
                //echo "All Field Must Required With Valid Length !";
                echo validation_errors('<span class="ion-android-alert failedAlert2"> ','</span>');

            }
            //----End validation----//         
        }
        else if($action=='remove'){    
            // $datastatus['status']             ="masuk";
            // $this->db->where('msisdn', $msisdn);
            // $this->db->update('msisdn', $datastatus);

            $this->db->delete('new_psb', array('psb_id' => $param1));       
        }
    }

    //Cek MSISDN// 
    public function cek_bast($action='', $param1='')
    {
        $data=array();
        if($action=='asyn'){
            $this->load->view('content/bast/cek_bast',$data);
        }else if($action==''){
            $this->load->view('theme/include/header');
            $this->load->view('content/bast/cek_bast',$data);
            $this->load->view('theme/include/footer');
        }else if($action=='receive'){    
            $datareceive['tanggal_terima'] = date('Y-m-d h:i:s');
            $datareceive['id_penerima'] = $this->session->userdata('id_users');
            $this->db->where('id_header', $param1);
            $this->db->update('bast_header', $datareceive);    
            echo "terima"; 
        } else if($action=='view'){
            $cek    =addslashes(trim($this->input->post('cek',true))); 
            $this->form_validation->set_rules('cek', 'MSISDN', 'trim|required|xss_clean');
            if (!$this->form_validation->run() == FALSE)
            {
                $bastData=$this->bastmodel->get_all_by_no_bast($cek);
                if(empty($bastData)){
                    echo "false";
                }else{
                    $no=1 ;
                    foreach ($bastData as $row) { ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo $row->no_bast ?></td>
                            <td><?php echo $row->nama_branch ?></td>
                            <td><?php echo $row->tanggal_masuk ?></td>
                            <td><?php echo $row->tanggal_terima ?></td>
                            <td><?php if(!empty($row->tanggal_terima)){
                                $tgl_terima = new DateTime($row->tanggal_terima);
                            }else{
                                $tgl_terima = new DateTime();
                            }
                            $tgl_masuk = new DateTime($row->tanggal_masuk);
                            $diff = $tgl_terima->diff($tgl_masuk); echo $diff->d." Hari"; ?></td>
                            <td><?php echo $row->nama ?></td>
                            <td><?php echo $row->nama_penerima ?></td>
                            <td><?php echo $row->jumlah." MSISDN" ?></td>
                            <td>
                            <?php 
                            if($this->session->userdata('branch_id')==$row->branch_id && ($this->session->userdata('level')==4 || $this->session->userdata('level')==5)){ ?>
                            <a class="mybtn btn-default btn-xs" style="cursor: pointer;" data-toggle="tooltip" title="Click For Add MSISDN" href="<?php echo site_url('bast/add/'.$row->no_bast) ?>">Tambah</a>
                            <?php } 
                            if($this->session->userdata('branch_id')==$row->branch_id && empty($row->tanggal_terima) && ($this->session->userdata('level')>5 || $this->session->userdata('level')==4)){ ?>
                                <input type="hidden" name="xno_bast" id="xno_bast" value="<?php echo $row->no_bast?>">
                                <a class="mybtn btn-success btn-xs bast-terima-btn" style="cursor: pointer;" data-toggle="tooltip" title="Click For Receive" href="<?php echo site_url('bast/cek_bast/receive/'.$row->id_header) ?>">Terima</a>
                            <?php } ?>
                            <a href="<?php echo site_url('bast/detail').'/'.$row->no_bast; ?>" data-toggle="tooltip" title="Click For Detail BAST" class="mybtn btn-info btn-xs">Detail</a></td>
                        </tr>
                    <?php 
                    
                    }  
                }
            }else{
                echo validation_errors('<span class="ion-android-alert failedAlert2"> ','</span>');
            }
        }

    }

    function load_modal()
    {
        $data['no_bast'] = $no_bast = $this->input->post('no_bast',true);

        $data['detail_bast']=$this->salesmodel->getBASTDetail($no_bast);
        $this->load->view('content/bast/load_modal_bast',$data);
    }

    /** Method For get sales information for sales Edit **/ 
    public function edit($no_bast,$psb_id,$action='')
    {
        $data=array();
        $sess_level = $this->session->userdata('level');
        $sess_branch = $this->session->userdata('branch_id');
        if($sess_level==4){
            $datax['msisdn']=$this->Msisdnmodel->get_all();
            $datax['branch']=$this->Branchmodel->get_all();
            $datax['tl']=$this->usersmodel->get_all_tl();
            $datax['sub_channel']=$this->Sales_channelmodel->get_all();
            $datax['sales_person']=$this->Salespersonmodel->get_all();
            $datax['validasi']=$this->usersmodel->get_all_validasi();

        }else{
            $datax['msisdn']=$this->Msisdnmodel->get_all_by_status($sess_branch);
            $datax['branch']=$this->Branchmodel->get_all_by($sess_branch);
            $datax['tl']=$this->usersmodel->get_all_tl_by($sess_branch);
            $datax['sub_channel']=$this->Sales_channelmodel->get_all_by($sess_branch);
            $datax['sales_person']=$this->Salespersonmodel->get_all_by($sess_branch);
        }

        $datax['edit_sales']=$this->salesmodel->get_sales_by_id($psb_id); 
        $datax['paket']=$this->Paketmodel->get_all();
        $datax['no_bast'] = $no_bast;
        if($action=='asyn'){
            $this->load->view('content/bast/add_sales',$datax);
        }else if($action==''){
            $this->load->view('theme/include/header');
            $this->load->view('content/bast/add_sales',$datax);
            $this->load->view('theme/include/footer');
        }    
    }

    /** Method For get sales information for sales Edit **/ 
    public function validasi($no_bast,$psb_id,$action='')
    {
        $data=array();
        $sess_level = $this->session->userdata('level');
        $sess_branch = $this->session->userdata('branch_id');
        $datax['reason']=$this->Reasonmodel->get_all(); 
        if($sess_level==4){
            $datax['msisdn']=$this->Msisdnmodel->get_all();
            $datax['branch']=$this->Branchmodel->get_all();
            $datax['grapari']=$this->graparimodel->get_all();
            $datax['tl']=$this->usersmodel->get_all_tl();
            $datax['sub_channel']=$this->Sales_channelmodel->get_all();
            $datax['sales_person']=$this->Salespersonmodel->get_all();

        }else{
            $datax['msisdn']=$this->Msisdnmodel->get_all_by_status($sess_branch);
            $datax['branch']=$this->Branchmodel->get_all_by($sess_branch);
            $datax['grapari']=$this->graparimodel->get_all_by($sess_branch);
            $datax['tl']=$this->usersmodel->get_all_tl_by($sess_branch);
            $datax['sub_channel']=$this->Sales_channelmodel->get_all_by($sess_branch);
            $datax['sales_person']=$this->Salespersonmodel->get_all_by($sess_branch);
        }

        $datax['edit_sales']=$this->salesmodel->get_sales_by_id($psb_id); 
        $datax['paket']=$this->Paketmodel->get_all();
        $datax['no_bast'] = $no_bast;
        $datax['psb_id'] = $psb_id;
        if($action=='asyn'){
            $this->load->view('content/bast/validasi_sales',$datax);
        }else if($action==''){
            $this->load->view('theme/include/header');
            $this->load->view('content/bast/validasi_sales',$datax);
            $this->load->view('theme/include/footer');
        }

        if($action=='insert'){  
            $data=array();
            $do                         =addslashes($this->input->post('action',true));     
            $data['no_bast']            = $no_bast = addslashes($this->input->post('sno_bast',true)); 
            $data['branch_id']          =addslashes($this->input->post('sbranch',true));
            $data['id_grapari']          =addslashes($this->input->post('sgrapari',true));
            $data['sub_sales_channel']  = $sub_channel = addslashes($this->input->post('ssub_channel',true));
            $data['detail_sub']         =addslashes($this->input->post('sdetail_sub',true));
            $data['TL']                 = $tl =addslashes($this->input->post('sTL',true));
            $data['sales_person']       =addslashes($this->input->post('ssales_person',true));
            $data['tanggal_masuk']      =$tanggal_masuk = addslashes($this->input->post('stanggal_masuk',true));
            $data['tanggal_validasi']   =$tanggal_validasi = addslashes(date('Y-m-d h:i:s'));


            $data['msisdn']             = $msisdn = addslashes($this->input->post('smsisdn',true)); 
            $data['nama_pelanggan']     =addslashes($this->input->post('snama_pelanggan',true)); 
            $data['alamat']             =addslashes($this->input->post('salamat',true)); 
            $data['alamat2']            =addslashes($this->input->post('salamat2',true));  
            $data['no_hp']              =addslashes($this->input->post('sno_hp',true));  
            $data['ibu_kandung']        =addslashes($this->input->post('sibu_kandung',true));  
            $data['paket_id']           =addslashes($this->input->post('spaket',true));
            $data['discount']           =addslashes($this->input->post('sdiscount',true));
            $data['periode']            =addslashes($this->input->post('speriode',true));
            $data['jenis_event']        =addslashes($this->input->post('sjenis_event',true));
            $data['nama_event']         =addslashes($this->input->post('snama_event',true));
            $data['status']             =$status = addslashes($this->input->post('sstatus',true));
            $data['deskripsi']          =addslashes($this->input->post('id_reason',true));
            $data['validasi_by']          =addslashes($this->session->userdata('username'));
        
                 
            //-----Validation-----//   
            $this->form_validation->set_rules('smsisdn', 'MSISDN', 'trim|required|xss_clean|min_length[10]|max_length[14]|numeric');
            $this->form_validation->set_rules('snama_pelanggan', 'Nama Pelanggan', 'trim|required|xss_clean|min_length[3]');
            $this->form_validation->set_rules('salamat', 'Alamat ', 'trim|required|xss_clean|min_length[10]');
            $this->form_validation->set_rules('salamat2', 'Alamat 2', 'trim|required|xss_clean|min_length[5]');
            $this->form_validation->set_rules('sno_hp', 'No HP', 'trim|required|xss_clean|min_length[10]|max_length[14]|numeric');
            $this->form_validation->set_rules('sibu_kandung', 'Ibu Kandung', 'trim|required|xss_clean|min_length[3]');
            $this->form_validation->set_rules('spaket', 'Paket', 'trim|required|xss_clean');
            $this->form_validation->set_rules('sdiscount', 'Discount', 'trim|required|xss_clean');
            $this->form_validation->set_rules('speriode', 'Periode', 'trim|required|xss_clean');
            $this->form_validation->set_rules('sjenis_event', 'Jenis Event', 'trim|required|xss_clean');
            $this->form_validation->set_rules('snama_event', 'Nama Event', 'trim|required|xss_clean|min_length[3]');
            $this->form_validation->set_rules('sstatus', 'Status', 'trim|required|xss_clean');
            $this->form_validation->set_rules('id_reason', 'Keterangan', 'trim|required|xss_clean');

            $this->form_validation->set_rules('sno_bast', 'No BAST', 'trim|required|xss_clean');
            $this->form_validation->set_rules('sbranch', 'Branch', 'trim|required|xss_clean');
            $this->form_validation->set_rules('sgrapari', 'Grapari', 'trim|required|xss_clean');
            $this->form_validation->set_rules('ssub_channel', 'Sub Channel', 'trim|required|xss_clean');
            $this->form_validation->set_rules('sdetail_sub', 'Detail Sub', 'trim|required|xss_clean|min_length[3]');
            $this->form_validation->set_rules('sTL', 'TL', 'trim|required|xss_clean');
            $this->form_validation->set_rules('ssales_person', 'Sales Person', 'trim|required|xss_clean');
            $this->form_validation->set_rules('stanggal_masuk', 'Tanggal Masuk', 'trim|required|xss_clean');
            $this->form_validation->set_rules('stanggal_validasi', 'Tanggal Validasi', 'trim|required|xss_clean');

            if($do == "validasi"){
                $msisdn1 = addslashes($this->input->post('smsisdn1',true)); 
                $this->form_validation->set_rules('smsisdn1', 'MSISDN', 'trim|xss_clean|min_length[10]|max_length[14]|numeric');
            }

            if (!$this->form_validation->run() == FALSE)
            { 
                if($do=='validasi'){
                    
                    if($msisdn1 == "" || $msisdn1 == null){
                        $data['msisdn'] = $msisdn;
                    }else{
                        $data['msisdn'] = $msisdn1;
                    }

                    if(value_exists("new_psb","msisdn",$msisdn1)) {  

                        echo "This MSISDN Is Already Exists !!!!"; 
                                            
                    }else{

                        $user_tl = $this->usersmodel->get_users_by_id($tl);
                        $query_channel = $this->Sales_channelmodel->get_sales_channel_by_id($sub_channel);
                        $data['sales_channel'] = $query_channel->sales_channel;

                        if(count($user_tl)>0) { 
                            $data['TL'] = $user_tl->username;
                        }

                        $id=$this->input->post('psb_id',true);
                        
                        $this->db->where('psb_id', $id);
                        $this->db->update('new_psb', $data);

                        $datastatus['status']             =$status;
                        $this->db->where('msisdn', $msisdn);
                        $this->db->update('msisdn', $datastatus);

                        echo "true";
                    }
                    
                }         
            }else{
                //echo "All Field Must Required With Valid Length !";
                echo validation_errors('<span class="ion-android-alert failedAlert2"> ','</span>');

            }
            //----End validation----//         
        } 
    }

    /** Method For get sales information for sales Edit **/ 
    public function aktivasi($no_bast,$psb_id,$action='')
    {
        $data=array();
        $sess_level = $this->session->userdata('level');
        $sess_branch = $this->session->userdata('branch_id');
        $datax['reason']=$this->Reasonmodel->get_all(); 
        if($sess_level==4){
            $datax['msisdn']=$this->Msisdnmodel->get_all();
            $datax['branch']=$this->Branchmodel->get_all();
            $datax['grapari']=$this->graparimodel->get_all();
            $datax['tl']=$this->usersmodel->get_all_tl();
            $datax['sub_channel']=$this->Sales_channelmodel->get_all();
            $datax['sales_person']=$this->Salespersonmodel->get_all();
            $datax['validasi']=$this->usersmodel->get_all_validasi();

        }else{
            $datax['msisdn']=$this->Msisdnmodel->get_all_by_status($sess_branch);
            $datax['branch']=$this->Branchmodel->get_all_by($sess_branch);
            $datax['grapari']=$this->graparimodel->get_all_by($sess_branch);
            $datax['tl']=$this->usersmodel->get_all_tl_by($sess_branch);
            $datax['sub_channel']=$this->Sales_channelmodel->get_all_by($sess_branch);
            $datax['sales_person']=$this->Salespersonmodel->get_all_by($sess_branch);
            $datax['validasi']=$this->usersmodel->get_all_validasi_by($sess_branch);
        }

        $datax['edit_sales']=$this->salesmodel->get_sales_by_id($psb_id); 
        $datax['paket']=$this->Paketmodel->get_all();
        $datax['no_bast'] = $no_bast;
        $datax['psb_id'] = $psb_id;
        if($action=='asyn'){
            $this->load->view('content/bast/aktivasi_sales',$datax);
        }else if($action==''){
            $this->load->view('theme/include/header');
            $this->load->view('content/bast/aktivasi_sales',$datax);
            $this->load->view('theme/include/footer');
        }

        if($action=='insert'){  
            $data=array();
            $do                         =addslashes($this->input->post('action',true));     
            $data['no_bast']            = $no_bast = addslashes($this->input->post('sno_bast',true)); 
            $data['branch_id']          =addslashes($this->input->post('sbranch',true));
            $data['id_grapari']          =addslashes($this->input->post('sgrapari',true));
            $data['sub_sales_channel']  = $sub_channel = addslashes($this->input->post('ssub_channel',true));
            $data['detail_sub']         =addslashes($this->input->post('sdetail_sub',true));
            $data['TL']                 = $tl =addslashes($this->input->post('sTL',true));
            $data['sales_person']       =addslashes($this->input->post('ssales_person',true));
            $data['tanggal_masuk']      =$tanggal_masuk = addslashes($this->input->post('stanggal_masuk',true));
            $data['tanggal_validasi']   =$tanggal_validasi = addslashes($this->input->post('stanggal_validasi',true));
            $data['tanggal_aktif']      =$tanggal_aktif = addslashes(date('Y-m-d h:i:s'));


            $data['msisdn']             = $msisdn = addslashes($this->input->post('smsisdn',true)); 
            $data['nama_pelanggan']     =addslashes($this->input->post('snama_pelanggan',true)); 
            $data['alamat']             =addslashes($this->input->post('salamat',true)); 
            $data['alamat2']            =addslashes($this->input->post('salamat2',true));  
            $data['no_hp']              =addslashes($this->input->post('sno_hp',true));  
            $data['ibu_kandung']        =addslashes($this->input->post('sibu_kandung',true));  
            $data['paket_id']           =addslashes($this->input->post('spaket',true));
            $data['discount']           =addslashes($this->input->post('sdiscount',true));
            $data['periode']            =addslashes($this->input->post('speriode',true));
            $data['bill_cycle']         =addslashes($this->input->post('sbill_cycle',true));
            $data['fa_id']              = $fa_id = addslashes($this->input->post('sfa_id',true));
            $data['account_id']         = $account_id =addslashes($this->input->post('saccount_id',true));
            $data['jenis_event']        =addslashes($this->input->post('sjenis_event',true));
            $data['nama_event']         =addslashes($this->input->post('snama_event',true));
            $data['status']             =$status = addslashes($this->input->post('sstatus',true));
            $data['deskripsi']          =addslashes($this->input->post('id_reason',true));
            $data['validasi_by']        =addslashes($this->input->post('svalidasi_by',true));
            $data['username']           =addslashes($this->input->post('susername',true));
        
                 
            //-----Validation-----//   
            $this->form_validation->set_rules('smsisdn', 'MSISDN', 'trim|required|xss_clean|min_length[10]|max_length[14]|numeric');
            $this->form_validation->set_rules('snama_pelanggan', 'Nama Pelanggan', 'trim|required|xss_clean|min_length[3]');
            $this->form_validation->set_rules('salamat', 'Alamat ', 'trim|required|xss_clean|min_length[10]');
            $this->form_validation->set_rules('salamat2', 'Alamat 2', 'trim|required|xss_clean|min_length[5]');
            $this->form_validation->set_rules('sno_hp', 'No HP', 'trim|required|xss_clean|min_length[10]|max_length[14]|numeric');
            $this->form_validation->set_rules('sibu_kandung', 'Ibu Kandung', 'trim|required|xss_clean|min_length[3]');
            $this->form_validation->set_rules('spaket', 'Paket', 'trim|required|xss_clean');
            $this->form_validation->set_rules('sdiscount', 'Discount', 'trim|required|xss_clean');
            $this->form_validation->set_rules('speriode', 'Periode', 'trim|required|xss_clean');
            $this->form_validation->set_rules('sbill_cycle', 'Bill Cycle', 'trim|required|xss_clean');
            $this->form_validation->set_rules('sfa_id', 'FA ID', 'trim|required|xss_clean|numeric');
            $this->form_validation->set_rules('saccount_id', 'Account ID', 'trim|required|xss_clean|numeric');
            $this->form_validation->set_rules('sjenis_event', 'Jenis Event', 'trim|required|xss_clean');
            $this->form_validation->set_rules('snama_event', 'Nama Event', 'trim|required|xss_clean|min_length[3]');
            $this->form_validation->set_rules('sstatus', 'Status', 'trim|required|xss_clean');
            $this->form_validation->set_rules('id_reason', 'Keterangan', 'trim|required|xss_clean');
            $this->form_validation->set_rules('svalidasi_by', 'Validasi By', 'trim|required|xss_clean');

            $this->form_validation->set_rules('sno_bast', 'No BAST', 'trim|required|xss_clean');
            $this->form_validation->set_rules('sbranch', 'Branch', 'trim|required|xss_clean');
            $this->form_validation->set_rules('sgrapari', 'Grapari', 'trim|required|xss_clean');
            $this->form_validation->set_rules('ssub_channel', 'Sub Channel', 'trim|required|xss_clean');
            $this->form_validation->set_rules('sdetail_sub', 'Detail Sub', 'trim|required|xss_clean|min_length[3]');
            $this->form_validation->set_rules('sTL', 'TL', 'trim|required|xss_clean');
            $this->form_validation->set_rules('ssales_person', 'Sales Person', 'trim|required|xss_clean');
            $this->form_validation->set_rules('stanggal_masuk', 'Tanggal Masuk', 'trim|required|xss_clean');
            $this->form_validation->set_rules('stanggal_validasi', 'Tanggal Validasi', 'trim|required|xss_clean');

            if($do == "aktivasi"){
                $msisdn1 = addslashes($this->input->post('smsisdn1',true)); 
                $this->form_validation->set_rules('smsisdn1', 'MSISDN', 'trim|xss_clean|min_length[10]|max_length[14]|numeric');
            }

            if (!$this->form_validation->run() == FALSE)
            { 
                if($do=='aktivasi'){
                    $account = $this->salesmodel->get_sales_by_account($account_id); 
                    $fa = $this->salesmodel->get_sales_by_fa($fa_id); 
                    if($msisdn1 == "" || $msisdn1 == null){
                        $msisdn = $msisdn;
                        $data['msisdn'] = $msisdn;
                    }else{
                        $msisdn = $msisdn1;
                        $data['msisdn'] = $msisdn1;
                    }

                    if(value_exists("new_psb","msisdn",$msisdn1)) {  

                        echo "This MSISDN Is Already Exists !!!!"; 
                                            
                    }else if($account->jumlah>10){

                        echo "Account ID sudah digunakan untuk 10 MSISDN";

                    }else if($fa->jumlah>10){

                        echo "FA ID sudah digunakan untuk 10 MSISDN";

                    }else{

                        $user_tl = $this->usersmodel->get_users_by_id($tl);
                        $query_channel = $this->Sales_channelmodel->get_sales_channel_by_id($sub_channel);
                        $data['sales_channel'] = $query_channel->sales_channel;

                        if(count($user_tl)>0) { 
                            $data['TL'] = $user_tl->username;
                        }

                        $id=$this->input->post('psb_id',true);
                        
                        $this->db->where('psb_id', $id);
                        $this->db->update('new_psb', $data);

                        $datastatus['status']             =$status;
                        $this->db->where('msisdn', $msisdn);
                        $this->db->update('msisdn', $datastatus);

                        echo "true";
                    }
                    
                }         
            }else{
                //echo "All Field Must Required With Valid Length !";
                echo validation_errors('<span class="ion-android-alert failedAlert2"> ','</span>');

            }
            //----End validation----//         
        } 
    }

    /** Method For get sales information for sales Edit **/ 
    public function edits($id_header,$action='')
    {
        $data=array();
        $sess_branch = $this->session->userdata('branch_id');
        $sess_level = $this->session->userdata('level');
        if($sess_level == 4){
            $data['bast_list']=$this->bastmodel->get_all_bydate('');
            $data['branch']=$this->Branchmodel->get_all();
        }else{
            $data['bast_list']=$this->bastmodel->get_all_bydate($sess_branch);
            $data['branch']=$this->Branchmodel->get_all_by($sess_branch);
        }

        $data['edit_bast']=$edit_bast=$this->bastmodel->get_all_by_id($id_header); 
        if(empty($edit_bast)){
            redirect('Admin/home');
        }

        if($action=='asyn'){
            $this->load->view('content/bast/add',$data);
        }else if($action==''){
            $this->load->view('theme/include/header');
            $this->load->view('content/bast/add',$data);
            $this->load->view('theme/include/footer');
        }    
    }

    public function detail($no_bast='', $action='')
    {   
        $data=array();
        $data['edit_bast']=$edit_bast=$this->bastmodel->get_all_by_no_bast_row($no_bast); 
        if(empty($edit_bast)){
            redirect('Admin/home');
        }
        $data['detail_bast']=$this->salesmodel->getBASTDetail($no_bast);
        $data['cno_bast']=$no_bast;
        if($action=='asyn'){
            $this->load->view('content/bast/list_detail',$data);
        }else if($action==''){
            $this->load->view('theme/include/header');
            $this->load->view('content/bast/list_detail',$data);
            $this->load->view('theme/include/footer');
        }
    }

    public function export($no_bast='', $action=''){
        if($action=='asyn'){
            $channel = array(0=>'ALL', 1=>'TSA', 2=>'MOGI', 3=>'MITRA AD', 4=>'MITRA DEVICE', 5=>'OTHER', 6=>'GraPARI Owned', 7=>'GraPARI Mitra', 8=>'GraPARI Manage Service', 9=>'Plasa Telkom', null=>'-');
            $jenis_event=array(1=>'Industrial Park',2=>'Mall to Mall',3=>'Office to Office',4=>'Other',5=>'Mandiri',6=>'Telkomsel');
            $discount=array(''=>'', 1=>'0',2=>'25',3=>'50',4=>'100',5=>'FreeMF');
            $periode=array(''=>'', 1=>'0',2=>'1',3=>'3',4=>'6',5=>'12');
            $object = new PHPExcel();

            $object->setActiveSheetIndex(0);
            //Array Hari
            $array_hari = array(1=>"Senin","Selasa","Rabu","Kamis","Jumat","Sabtu","Ahad");
            $hari = $array_hari[date("N")];

            //Format Tanggal
            $tanggal = date ("j");
            
            //Array Bulan
            $array_bulan = array(1=>"Januari","Februari","Maret", "April", "Mei", "Juni","Juli","Agustus","September","Oktober", "November","Desember");
            $bulan = $array_bulan[date("n")];

            //Format Tahun
            $tahun = date("Y");

            $data['edit_bast']=$edit_bast=$this->bastmodel->get_all_by_no_bast_row($no_bast); 
            // if(empty($edit_bast)){
            //     redirect('Admin/home');
            // }

            $data['detail_bast']=$detail=$this->salesmodel->getBASTDetail($no_bast);

            $object->getActiveSheet()->setCellValueByColumnAndRow(0, 1, "BERITA ACARA SERAH TERIMA");
            $object->getActiveSheet()->setCellValueByColumnAndRow(0, 2, "PT. TELEKOMUNIKASI SELULAR");
            $object->getActiveSheet()->setCellValueByColumnAndRow(0, 3, "");
            $object->getActiveSheet()->setCellValueByColumnAndRow(0, 4, "-------------------------------------------------------");
            $object->getActiveSheet()->setCellValueByColumnAndRow(0, 5, "NOMOR");
            $object->getActiveSheet()->setCellValueByColumnAndRow(1, 5, ":");
            $object->getActiveSheet()->setCellValueByColumnAndRow(2, 5, $no_bast);
            $object->getActiveSheet()->setCellValueByColumnAndRow(0, 6, "");
            $object->getActiveSheet()->setCellValueByColumnAndRow(0, 7, "Pada hari ini ".strtoupper($hari)." tanggal ".$tanggal." bulan ".strtoupper($bulan)." tahun ".$tahun.", telah diserahkan data dari : ");
            $object->getActiveSheet()->setCellValueByColumnAndRow(0, 8, "");
            $object->getActiveSheet()->setCellValueByColumnAndRow(0, 9, "Pihak I");
            $object->getActiveSheet()->setCellValueByColumnAndRow(0, 10, "Nama");
            $object->getActiveSheet()->setCellValueByColumnAndRow(1, 10, ":");
            $object->getActiveSheet()->setCellValueByColumnAndRow(2, 10, strtoupper($edit_bast->nama));
            $object->getActiveSheet()->setCellValueByColumnAndRow(0, 11, "Jabatan");
            $object->getActiveSheet()->setCellValueByColumnAndRow(1, 11, ":");
            $object->getActiveSheet()->setCellValueByColumnAndRow(2, 11, "FOS HVC ".strtoupper($edit_bast->nama_branch));
            $object->getActiveSheet()->setCellValueByColumnAndRow(0, 12, "");
            $object->getActiveSheet()->setCellValueByColumnAndRow(0, 13, "Telah diserah terimakan Formulir PSB dengan kelengkapan dokumen Foto Copy KTP, MATERAI, KETERANGAN DUKCAPIL, sebanyak ".count($detail)." DATA");
            $object->getActiveSheet()->setCellValueByColumnAndRow(0, 14, "");
            $object->getActiveSheet()->setCellValueByColumnAndRow(0, 15, "KEPADA");
            $object->getActiveSheet()->setCellValueByColumnAndRow(0, 16, "Pihak II");
            $object->getActiveSheet()->setCellValueByColumnAndRow(0, 17, "Nama");
            $object->getActiveSheet()->setCellValueByColumnAndRow(1, 17, ":");
            $object->getActiveSheet()->setCellValueByColumnAndRow(2, 17, strtoupper($edit_bast->nama_penerima));
            $object->getActiveSheet()->setCellValueByColumnAndRow(0, 18, "Jabatan");
            $object->getActiveSheet()->setCellValueByColumnAndRow(1, 18, ":");
            $object->getActiveSheet()->setCellValueByColumnAndRow(2, 18, "FOS GRAPARI ".strtoupper($edit_bast->nama_branch));
            $object->getActiveSheet()->setCellValueByColumnAndRow(0, 19, "");
            $object->getActiveSheet()->setCellValueByColumnAndRow(0, 20, "Dengan telah ditandatanganinya Berita Acara Serah Terima data ini, maka selanjutnya data yang telah diserahkan akan di proses sesuai ketentuan berlaku.");
            $object->getActiveSheet()->setCellValueByColumnAndRow(0, 21, "");
            $object->getActiveSheet()->setCellValueByColumnAndRow(0, 22, "Demikian Berita Acara Serah Terima data ini dibuat dan untuk dapat dipergunakan sebagaimana mestinya.");
            $object->getActiveSheet()->setCellValueByColumnAndRow(0, 23, "");
            $object->getActiveSheet()->setCellValueByColumnAndRow(0, 24, "");
            $object->getActiveSheet()->setCellValueByColumnAndRow(0, 25, "Yang Menyerahkan");
            $object->getActiveSheet()->setCellValueByColumnAndRow(5, 25, "Yang Menerima");
            $object->getActiveSheet()->setCellValueByColumnAndRow(0, 26, "");
            $object->getActiveSheet()->setCellValueByColumnAndRow(0, 27, "");
            $object->getActiveSheet()->setCellValueByColumnAndRow(0, 28, "");
            if(!empty($edit_bast->nama)){ 
                $var_menyerahkan = strtoupper($edit_bast->nama); 
            } else { 
                $var_menyerahkan = "..............."; 
            }
            $object->getActiveSheet()->setCellValueByColumnAndRow(0, 29, $var_menyerahkan);
            if(!empty($edit_bast->nama_penerima)){ 
                $var_menerima = strtoupper($edit_bast->nama_penerima); 
            } else { 
                $var_menerima = "..............."; 
            }
            $object->getActiveSheet()->setCellValueByColumnAndRow(5, 29, $var_menerima);
            $object->getActiveSheet()->setCellValueByColumnAndRow(0, 30, "");
            $object->getActiveSheet()->setCellValueByColumnAndRow(0, 31, "");
            $object->getActiveSheet()->setCellValueByColumnAndRow(0, 32, "Lampiran");
            $object->getActiveSheet()->setCellValueByColumnAndRow(0, 33, "");

            $table_columns = array("NO", "PSB_ID", "MSISDN", "NAMA_PELANGGAN", "PAKET", "NO_HP", "SALES_PERSON", "TL", "TANGGAL_MASUK", "TANGGAL_VALIDASI", "TANGGAL_AKTIF", "SERVICE (HARI)");

            $column = 0;

            foreach($table_columns as $field)
            {
                $object->getActiveSheet()->setCellValueByColumnAndRow($column, 34, $field);
                $column++;
            }

            $excel_row = 35;
            $no = 1;
            foreach($detail as $row)
            {
                if(!empty($row->tanggal_aktif)){
                    $tgl_aktif = new DateTime($row->tanggal_aktif);
                }else{
                    $tgl_aktif = new DateTime();
                }
                $tgl_masuk = new DateTime($row->tanggal_masuk);
                $diff = $tgl_aktif->diff($tgl_masuk); 

                $object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, $no++);
                $object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, strtoupper($row->psb_id));
                $object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, strtoupper($row->msisdn));
                $object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, strtoupper($row->nama_pelanggan));
                $object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, strtoupper($row->nama_paket));
                $object->getActiveSheet()->setCellValueByColumnAndRow(5, $excel_row, strtoupper($row->no_hp));
                $object->getActiveSheet()->setCellValueByColumnAndRow(6, $excel_row, strtoupper($row->sales_person));
                $object->getActiveSheet()->setCellValueByColumnAndRow(7, $excel_row, strtoupper($row->nama_tl));
                $object->getActiveSheet()->setCellValueByColumnAndRow(8, $excel_row, strtoupper(date('Y-m-d', strtotime($row->tanggal_masuk))));
                $object->getActiveSheet()->setCellValueByColumnAndRow(9, $excel_row, strtoupper(date('Y-m-d', strtotime($row->tanggal_validasi))));
                $object->getActiveSheet()->setCellValueByColumnAndRow(10, $excel_row, strtoupper(date('Y-m-d', strtotime($row->tanggal_aktif))));
                $object->getActiveSheet()->setCellValueByColumnAndRow(11, $excel_row, strtoupper($diff->d));
                $excel_row++;
            }

            $filename = "BAST-Exported-on-".date("Y-m-d-H-i-s").".xls";

            $object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');
            header("Pragma: public");
            header("Expires: 0");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Content-Type: application/force-download");
            header("Content-Type: application/octet-stream");
            header("Content-Type: application/download");;
            header("Content-Disposition: attachment;filename=$filename");
            $object_writer->save('php://output');
        }
    }
}
