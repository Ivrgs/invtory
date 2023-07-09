<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {
	public function __construct(){
        parent::__construct();
        $this->load->helper(array('form','url', 'string'));
        $this->load->library(array('form_validation','email'));
		$this->load->model('GameModel','home');
		$this->load->model('UserModel');
    }

	public function index(){
		error_reporting(0);
		if($_SESSION['data'] == TRUE){
			redirect(base_url()."Inventory");
		}else{
			$this->load->view('includes/Header');
			$this->load->view('includes/NavigatorNo');
			$this->load->view('loginpage');
			$this->load->view('includes/Footer');
		}
	}
	public function Inventory(){
		if($_SESSION['data'] == TRUE){
			$this->load->view('includes/Header');
			$this->load->view('includes/Navigator');
			$this->load->view('index');
			$this->load->view('includes/Modals');
			$this->load->view('includes/Footer');
			$this->load->view('includes/ajax_list');

		}else{
			redirect(base_url());
		}
			
	}
	
	public function Logout(){
		$_SESSION['data'] = FALSE;
		redirect(base_url());
	}

	public function Login(){
		if($_SESSION['data'] == TRUE){
			redirect(base_url()."Inventory");
		}else{
			$user = $this->input->post('email');
			$logon = $this->UserModel->getUser($user);

			$this->form_validation->set_rules('password', 'Password Confirmation', 'required');
			$this->form_validation->set_rules('email', 'Email', 'required');
			
			if ($this->form_validation->run() == FALSE){
				redirect(base_url());
			}else{
				if ($logon->email == $this->input->post('email')) {
					if (password_verify($this->input->post('password'), $logon->password)) {
						$data = array(
							'Logged_in' => TRUE,
						);

						$_SESSION['data'] = $data;
						redirect(base_url()."Inventory", $_SESSION);
					} else {
						redirect(base_url());
					}
				}else{
					redirect(base_url());
				}	
			}
		}
	}

	public function Register(){
		if($_SESSION['data'] == TRUE){
			$data = array(
				'email' => $this->input->post('reg_email'),
				'password' => password_hash($this->input->post('reg_password'), PASSWORD_BCRYPT),
				'verify' => uniqid(),
			);
			$this->UserModel->insertUser($data);
			redirect(base_url()."Inventory");
		}else{
			redirect(base_url());
		}
	}

	//GAMES
	public function GameList(){
		
		$list = $this->home->get_datatables();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $game) {
			$no++;
			$row = array();
			$row[] = $game->product_name;
			$row[] = $game->pn_sn;
			$row[] = $game->brand;
			$row[] = $game->type;
			$row[] = $game->quantity;
			$row[] = $game->price;
			$row[] = $game->status;
			$row[] = $game->stored_date;

			if($game->stored_date == "" || $game->stored_date == "0000-00-00"){
				$dateformat = "";
			}else{
				$strtotime = strtotime($game->stored_date);
				$dateformat = date("F d, Y", $strtotime);
			}

			$row[] = $dateformat;
			$row[] = '<a class="btn btn-outline-primary" href="javascript:void(0)" title="Edit" onclick="edit_game('."'".$game->id."'".')"><i class="fa fa-pencil" aria-hidden="true"></i></a> 
			<a class="btn btn-outline-danger" href="javascript:void(0)" title="Delete" onclick="delete_game('."'".$game->id."'".')"><i class="fa fa-trash"></i></a>';

			$data[] = $row;
		}
		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->home->count_all(),
			"recordsFiltered" => $this->home->count_filtered(),
			"data" => $data,
		);
		echo json_encode($output);
	
	}

	public function AddGame(){
		$this->_validate();

		if($this->input->post('datepickerAddG') == ""){
			$dateformat = "";
		}else{
			$strtodate = strtotime($this->input->post('datepickerAddG'));
			$dateformat = date("Y-m-d", $strtodate);
		}

		$data = array(
			'product_name' => $this->input->post('field_product_name'),
			'pn_sn' =>$this->input->post('field_pn_sn'),
			'brand' => $this->input->post('field_brand'),
			'type' => $this->input->post('field_type'),
			'quantity' => $this->input->post('field_quantity'),
			'price' => $this->input->post('field_price'),
			'status' => $this->input->post('field_status'),
			'stored_date' => $dateformat,
		);

		$strtodate = strtotime($this->input->post('datepickerAddG'));
		$dateText = date("F d, Y", $strtodate);

		$this->home->addGame($data);
		echo json_encode(array("status" => TRUE));
	}	

	public function EditGame($id){
		$data = $this->home->getID($id);
		$strtodate = strtotime($data->date);
		$dateformat = date("F d, Y", $strtodate);

		$data->stored_date = $dateformat;
		echo json_encode($data);
	}
	

	public function UpdateGame(){
		$this->_validate();
		if($this->input->post('datepickerAddG') == ""){
			$dateformat = "";
		}else{
			$strtodate = strtotime($this->input->post('datepickerAddG'));
			$dateformat = date("Y-m-d", $strtodate);
		}
		
		$data = array(
			'product_name' => $this->input->post('field_product_name'),
			'pn_sn' =>$this->input->post('field_pn_sn'),
			'brand' => $this->input->post('field_brand'),
			'type' => $this->input->post('field_type'),
			'quantity' => $this->input->post('field_quantity'),
			'price' => $this->input->post('field_price'),
			'status' => $this->input->post('field_status'),
			'stored_date' => $dateformat,
		);

		$this->home->updateGame(array('id' => $this->input->post('id')), $data);
		echo json_encode(array("status" => TRUE));
	}

	public function DeleteGame(){
		//$this->_validate();
		$this->home->deleteGame( $this->input->post('id'));
		echo json_encode(array("status" => TRUE));
	
	}

	//CMS
	public function getDropdown(){
		$data = array(
			'getrepack' => $this->home->getCMSRepack(),
			'getstatus' =>$this->GameModel->getCMSStatus(),
			'getgenre' =>$this->GameModel->getCMSGenre(),
			'gettypes' =>$this->GameModel->getCMSTypes(),
			
		);
		echo json_encode($data);
		
	}
	public function SelCMSTypes(){ 
		echo json_encode($this->GameModel->SelectCMSTypes($this->input->post("data")));
	}

	public function AddCMS(){
		//$this->_validate();
		$data = array(
			'type' => $this->input->post('gtype'),
			'title' => $this->input->post('addnewcms'),
		);
	
		$this->home->addCMS($data);
		echo json_encode(array("status" => TRUE));
	}

	public function UpdateCMS(){
		$data = array(
		 	'title' => $this->input->post('updateType'),
			'type' => $this->input->post('gtypeUpdate'),			 
		);
		
		$this->home->updateCMS(array('title' => $this->input->post('selectType')), $data);
		$this->CMSUpdateTable();
	}
	public function CMSUpdateTable(){
		$loc = strtolower($this->input->post('gtypeUpdate'));
		$new =  array(
			$loc => $this->input->post('updateType'),
		);

		$this->home->updateTableCMS(array($loc => $this->input->post('selectType')), $new);
		echo json_encode(array("status" => TRUE));
		
	}

	public function DeleteCMS(){
		$data = array(
		   'title' => $this->input->post('selectType'),			 
	   	);

		   $this->home->deleteCMS($this->input->post('selectType'), $data);
		   $this->UpdateDelete();
	}
	public function UpdateDelete(){
		$loc = strtolower($this->input->post('gtypeUpdate'));
		$new =  array(
			$loc => " ",
		
		);
		$this->home->updateTableCMS(array($loc => $this->input->post('selectType')), $new);
		echo json_encode(array("status" => TRUE));
	}

	//EXPORT CSV
	public function Export(){
		if($_SESSION['data'] == TRUE){
			$data = $this->GameModel->exportData();

			$filename = 'ivrgs_invntory_backup_'.date('Y-m-d').'.csv'; 
			header("Content-Description: File Transfer"); 
			header("Content-Disposition: attachment; filename=$filename"); 
			header("Content-Type: application/csv; ");
			$file = fopen('php://output', 'w');
			
			$header = array("id","product_name", "pn_sn", "brand", "type", "quantity", "price", "status", "stored_date", "created_at", "updated_at"); 
			fputcsv($file, $header);

			foreach ($data as $g){ 
				$gam = array();
				$gam[] = $g->id;
				$gam[] = $g->product_name;
				$gam[] = $g->pn_sn;
				$gam[] = $g->brand;
				$gam[] = $g->type;
				$gam[] = $g->quantity;
				$gam[] = $g->price;
				$gam[] = $g->status;
				$gam[] = $g->stored_date;
				$gam[] = $g->created_at;
				$gam[] = $g->updated_at;
				fputcsv($file,$gam); 
			}

			fclose($file); 
			exit;
		}else{
			redirect(base_url());
		}
	}
	public function ExportCMS(){
		if($_SESSION['data'] == TRUE){
			$data = $this->GameModel->exportDataCMS();

			$filename = 'gamexcms_backup_'.date('Y-m-d').'.csv'; 
			header("Content-Description: File Transfer"); 
			header("Content-Disposition: attachment; filename=$filename"); 
			header("Content-Type: application/csv; ");
			$file = fopen('php://output', 'w');
			
			$header = array("id","type","title", "created_at", "updated_at"); 
			fputcsv($file, $header);

			foreach ($data as $g){ 
				$cms = array();
				$cms[] = $g->id;
				$cms[] = $g->type;
				$cms[] = $g->title;
				$cms[] = $g->created_at;
				$cms[] = $g->updated_at;
				fputcsv($file,$cms); 
			}

			fclose($file); 
			exit;
		}else{
			redirect(base_url());
		}
	}

	//Field Validations
	private function _validate(){
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;

		if($this->input->post('field_product_name') == ''){
			$data['inputerror'][] = 'field_product_name';
			$data['error_string'][] = 'Product Name';
			$data['status'] = FALSE;
		}

		if($data['status'] === FALSE){
			echo json_encode($data);
			exit();
		}

	}
}
