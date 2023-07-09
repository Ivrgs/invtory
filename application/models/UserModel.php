<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class UserModel extends CI_Model {  

	public function __construct(){
            parent::__construct();
            $this->load->database();
            $this->load->helper(array('form','url', 'string'));
            $this->load->library('form_validation');
      }
  public function getUser($user){
        $q = $this->db->get_where('tbl_users', array('email' => $user));
        return $q->row();
  }
  public function insertUser($data)  {
      $this->db->insert('tbl_users', $data);
  }

}
