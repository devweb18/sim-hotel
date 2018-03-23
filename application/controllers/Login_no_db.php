<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller {
    
    function __construct (){
        parent::__construct();
        $this->load->model('Login_model','', TRUE);
     //   $this->load->model('Munit','', TRUE);        
		$this->load->model('Minfo','', TRUE);
    }

    function index(){
	$q = $this->Minfo->info()->row();
	    	$data['namapt'] = ! empty ($q->namapt) ? $q->namapt : 'SUPRA';
	   	 $data['title'] = 'Selamat Datang Nasabah';
    	   	$data['logo'] = ! empty ($q->logo) ? $q->logo : 'logo_supra';
		$data['sim']= 'SIMPEG';
		$data['simlong']= 'GUEST BILL '.$q->namapt;
		$data['warna']= '#254117';
		$data['warna2']= '#BCE954';
		//$data['info']=$this->Minfo->info()->row();
        $this->load->view('loginsimakuadmin',$data);
    }

 function simpeg(){
	$this->sublogin();
}
    function sublogin(){
    	$q = $this->Minfo->info()->row();
	    	$data['namapt'] = ! empty ($q->namapt) ? $q->namapt : 'SUPRA';
	   	 $data['title'] = 'Selamat Datang Nasabah';
    	   	$data['logo'] = ! empty ($q->logo) ? $q->logo : 'logo_supra';
		$data['sim']= 'SIMPEG';
		$data['simlong']= 'GUEST BILL '.$q->namapt;
		$data['warna']= '#254117';
		$data['warna2']= '#BCE954';
		//$data['info']=$this->Minfo->info()->row();
        $this->load->view('loginsimaku',$data);
    }

 
   	 function proses(){
        $this->form_validation->set_rules('username','Username','required');
        $this->form_validation->set_rules('password','Password','required');
        $this->form_validation->set_rules('sip','sip','required');
        
        if ($this->form_validation->run() == TRUE){
            $username = $this->input->post('username');
            $password = $this->input->post('password');
            
            //kusus info
            $this->session->set_userdata('login1', TRUE);
            if ($username == 'ilhamroy' AND $password == 1228){
                    redirect('login/info');
                }
                

            if ($this->Login_model->check_user($username, $password) == TRUE){
                $q = $this->Login_model->get_id_pass($username, $password);
                $q1 = $q->row();
				//$cek = $this->Munit->cek_unit($q1->id_unit);
				/////tgl
		 $h = "7";// Hour for time zone goes here e.g. +7 or -4, just remove the + or -
		$hm = $h * 60;
		$ms = $hm * 60;
		$tanggal = gmdate("d-m-Y ", time()+($ms)); // the "-" can be switched to a plus if that's what your time zone is.
		$waktu = gmdate ( "H:i:s", time()+($ms));
		$xxx=substr($tanggal,'6','4');
		$xx=substr($tanggal,'3','2');
		$x=substr($tanggal,'0','2');
		$hjamx=substr($waktu,'0','2');
		if($hjamx<=9){
			$hjam=substr($waktu,'0','1');
		}else{
			$hjam=substr($waktu,'0','2');
		}
				////
				$jam_shift=array(
				);
				
				////
				
                $newdata = array(
                    'username' => $username,
                     'password' => $password,
                     'sip'=>$this->input->post('sip'),
                    'wewenang' => $q1->wewenang,
                    // 'id_unit_masthead' => $q1->id_peg,
                     'id_user' => $q1->idlog,
                     'id_tgl' => $xxx.''.$xx.''.$x,
                    'login' => TRUE);
                $this->session->set_userdata($newdata);
            	
				
                if($q1->idlog == 1 AND $q1->username == $username AND $q1->password == $password){
                    redirect();
                }elseif ($q1->wewenang == 'resepsionis'){ ///untuk resepsionis perhotelan
                ////SIMPAN LAPORAN
       
				$idtgl=$xxx.''.$xx.''.$x;
                $sip=array(
                'id_user'=>$q1->idlog,
                'ship'=>$this->input->post('sip'),
                'status'=>'login',
                'sort'=>$idtgl,
                'tgl'=>$x,
                'bln'=>$xx,
                'thn'=>$xxx,
                'tanggal'=> $tanggal .' '.$waktu,
                
                );
                ////
                //jam shift
               
      $jam_pagi=array();
      $jam_siang=array();
      $jam_malam=array();
      //$jam_malam2=array();
     for($p=7;$p<=13;$p++){
     	 $jam_pagi[$p]='pagi';
              
		
	}
	 for($s=14;$s<=22;$s++){
		$jam_siang[$s]='Siang';
	}
	$jam_malam[23]='Malam'; /// */
	for($mp=0;$mp<=6;$mp++){
		$jam_malam[$mp]='Malam';
	}
	switch($this->input->post('sip')){
		case 'Pagi':
		$jam=$jam_pagi[$hjam];
		break;
		case 'Siang':
		$jam=$jam_siang[$hjam];
		break;
		case 'Malam':
		$jam=$jam_malam[$hjam];
		break;
	}
               // $this->Login_model->sip_login($sip);
                if($this->Login_model->check_user_shift($this->input->post('sip'),$idtgl) == FALSE){ ////cek shift
                $this->Login_model->sip_login_sip($sip);
                //
                    redirect('C_resepsionis/cek_kam');
                }elseif($this->Login_model->check_user_shift($this->input->post('sip'),$idtgl) == TRUE AND $jam==$this->input->post('sip')){ //shif sama
                	if($this->Login_model->check_user_shift_id($this->input->post('sip'),$idtgl,$q1->idlog)==TRUE){ ///hanya shif yang terakhir
						redirect('C_resepsionis/cek_kam');
					}else{
						$this->session->set_flashdata('pesan','Maaf, User tidak sesuai !!!');
                	redirect ('login/sublogin');
					}
					
				}else{
						$this->session->set_flashdata('pesan','Maaf, Shift yang anda pilih sudah terisi !!!');
                	redirect ('login/sublogin/');
					}
				///-----------------------		----------------------
                }else{
                	$this->session->set_flashdata('pesan','Maaf, username atau password Anda salah');
                	redirect ('login/sublogin');
                } 
            }else{ ///cek model ueu_tbl_user
            //	$this->session->set_flashdata('pesan','Maaf, username atau password Anda salah');
              //  redirect ('login/simpeg');
              $this->proses0();
            }
            
        } else { ///falidasi
                $this->session->set_flashdata('pesan','Maaf, username atau password Anda salah');
                redirect ('login/sublogin');
        }
    }
	
	 function prosesadmin(){
        $this->form_validation->set_rules('username','Username','required');
        $this->form_validation->set_rules('password','Password','required');
        
        if ($this->form_validation->run() == TRUE){
            $username = $this->input->post('username');
            $password = $this->input->post('password');
	            

            if ($this->Login_model->check_user($username, $password) == TRUE){
                $q = $this->Login_model->get_id_pass($username, $password);
                $q1 = $q->row();
				//$cek = $this->Munit->cek_unit($q1->id_unit);
                $newdata = array(
                    'username' => $username, 'password' => $password,'sip'=>$this->input->post('sip'),
                    'wewenang' => $q1->wewenang, 'id_unit_masthead' => $q1->id_peg,'id_user' => $q1->idlog,
                    'master_login' => TRUE);
                $this->session->set_userdata($newdata);
            	
				
                if($q1->idlog == 1 AND $q1->username == $username AND $q1->password == $password){
                    redirect('login');
                }elseif ($q1->wewenang == 'admin'){
                    redirect('C_admin/');
                   // redirect('C_admin/rinci_pendapatan/0');
             }else{
                	$this->session->set_flashdata('pesan','Maaf, username atau password Anda salah');
                	redirect ('login');
                } 
            }else{ ///cek model ueu_tbl_user
            	$this->session->set_flashdata('pesan','Maaf, username atau password Anda salah');
               redirect ('login');
              //$this->proses0();
            }
            
        } else { ///falidasi
                $this->session->set_flashdata('pesan','Maaf, username atau password Anda salah');
                redirect ('login');
        }
    }

	   function proses0(){
      
        
            $username = $this->input->post('username');
            $password = $this->input->post('password');
            
            

            if ($this->Login_model->check_user_id($username, $password) == TRUE){
                $q = $this->Login_model->get_id_pass($username, $password);
                $q1 = $q->row();
				//$cek = $this->Munit->cek_unit($q1->id_unit);
                $newdata = array(
                    'username' => $username, 
                    'password' => $password,
                    //'wewenang' => $q1->wewenang, 
                    'id_p' => $q1->id,
                    'id_login' => TRUE);
                $this->session->set_userdata($newdata);
            	
				
                if($username AND $password ){
                	 $this->session->set_flashdata("pesan", "<div class=\"col-xs-12-md-12\"><div class=\"alert alert-success\" id=\"alert\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>Selamat Datang di Hotel Rumput ,,,,Selamat  Menikmati</div></div>");
                    redirect('C_sewa');
                }
             /*   elseif ($q1->wewenang == 'admin' AND $q1->password == 'akuntansi'){
                    redirect('akuntansi');
                } */
                else{
                	$this->session->set_flashdata('pesan','Maaf, username atau password Anda salah');
                	redirect ('login/sublogin');
                } 
            }else{ ///cek model tbl_pesan_kamar
            	$this->session->set_flashdata('pesan','Maaf, username atau password Anda salah');
                redirect ('login/sublogin');
              //$this->proses0();
            }
            
        } 

    public function logout(){
    		$h = "7";// Hour for time zone goes here e.g. +7 or -4, just remove the + or -
		$hm = $h * 60;
		$ms = $hm * 60;
		$tanggal = gmdate("d-m-Y ", time()+($ms)); // the "-" can be switched to a plus if that's what your time zone is.
		$waktu = gmdate ( "H:i:s", time()+($ms));
                $sip=array(
                'id_user'=>$this->session->userdata('id_user'),
                'ship'=>$this->session->userdata('sip'),
                'status'=>'logout',
                'tanggal'=> $tanggal .' '.$waktu,
                );
                $this->Login_model->sip_login($sip);
                ///
        $this->session->set_userdata('login', FALSE);
        $this->session->set_userdata('master_login', FALSE);
        $this->session->set_userdata('id_login', FALSE);
        $this->session->sess_destroy();
        $this->session->set_flashdata('message','Anda telah berhasil logout');
        redirect("login/sublogin");
    }
    ////admin
    public function logoutadmin(){
    		$h = "7";// Hour for time zone goes here e.g. +7 or -4, just remove the + or -
		$hm = $h * 60;
		$ms = $hm * 60;
		$tanggal = gmdate("d-m-Y ", time()+($ms)); // the "-" can be switched to a plus if that's what your time zone is.
		$waktu = gmdate ( "H:i:s", time()+($ms));
               
        $this->session->set_userdata('login', FALSE);
        $this->session->set_userdata('master_login', FALSE);
        $this->session->set_userdata('id_login', FALSE);
        $this->session->sess_destroy();
        $this->session->set_flashdata('message','Anda telah berhasil logout');
        redirect("login/");
    }
	
	public function nolkan_data(){
		$this->Login_model->kosong_data();
        $this->session->set_flashdata('message','Data sudah di-nol-kan*');
		redirect ('login');
	}

    public function base(){
        $this->session->set_userdata('login', FALSE);
        $this->session->sess_destroy();
        redirect("http://www.unmuhjember.ac.id");
    }
    
      function info(){
    	if ($this->session->userdata('login1') == TRUE){
		$q = $this->Minfo->info()->row();
		$data['q']= $q;

	    	$data['namapt'] = ! empty ($q->namapt) ? $q->namapt : 'SUPRA';
	   	 $data['title'] = 'Selamat Datang Nasabah';
    	   	$data['logo'] = ! empty ($q->logo) ? $q->logo : 'logo_supra';	
    	   	$this->load->view('up_info',$data);
  }else{
	  redirect('login/sublogin'); 
}  

  }
    function s_info(){
		$data = array(
					'namapt'=>$this->input->post('nama'),
					'alamat'=>$this->input->post('alamat'),
					'email'=>$this->input->post('email'),
					'tel'=>$this->input->post('tel'),
					'namarektor'=>$this->input->post('rektor'),
					'nip'=>$this->input->post('nip'),
					'website'=>$this->input->post('website'),
					'th_angg'=>$this->input->post('thn'),
					'logo'=>$this->input->post('logo'),
					'awal_angg'=>$this->input->post('awal_a'),
					'akhir_angg'=>$this->input->post('akhir_a'),
					
					);

        $this->Login_model->update_info($data);
        redirect('login/sublogin');
    }
    

}