<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class GameModel extends CI_Model {  
  var $table = 'tbl_inventory';
  var $table_cms = 'tbl_cms';
  var $table_account = 'tbl_user';
  var $column_order = array('product_name','pn_sn','brand','type','quantity','price','status', 'stored_date',null);
  var $column_search = array('product_name','brand', 'type','status', 'stored_date');
  var $order = array('product_name','brand');

  public function __construct(){
		parent::__construct();
    $this->load->database();
    $this->load->helper(array('form','url', 'string'));
    $this->load->library('form_validation');
    $this->load->model('GameModel');
  }

    //GAMES
    private function _get_datatables_query(){
		
        $this->db->from($this->table);

        $i = 0;
      
        foreach ($this->column_search as $item){
          if($_POST['search']['value']){
            if($i===0){
              $this->db->group_start(); 
              $this->db->like($item, $_POST['search']['value']);
            }else{
              $this->db->or_like($item, $_POST['search']['value']);
            }

            if(count($this->column_search) - 1 == $i) 
              $this->db->group_end();
            }
            $i++;
        }
        //Sorting
        if(isset($_POST['order'])){
          $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        }else if(isset($this->order)){
          $order = $this->order;
          $this->db->order_by(key($order), $order[key($order)]);
        }
    }
    
    function get_datatables(){
        $this->_get_datatables_query();
        if($_POST['length'] != 1)
        $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
	  }

    function count_filtered(){
      $this->_get_datatables_query();
      $query = $this->db->get();
      return $query->num_rows();
    }

    public function count_all(){
      $this->db->from($this->table);
      return $this->db->count_all_results();
    }

    public function getID($id){
      $this->db->from($this->table);
      $this->db->where('id',$id);
      $query = $this->db->get();
      return $query->row();
    }
      
    public function addGame($data){
      $this->db->insert($this->table, $data);
    }
    public function updateGame($where, $data){
      $this->db->update($this->table, $data, $where);
      return $this->db->affected_rows();
    }
    public function deleteGame($id){
      $this->db->where('id', $id);
      $this->db->delete($this->table);
		}
		public function TempDeleteGame($where, $data){
			$this->db->update($this->table, $data, $where);
      return $this->db->affected_rows();
    }

    //CMS Titles
    public function getCMSRepack(){
      $q = $this->db->order_by("title", "asc")->get_where($this->table_cms, array('type' => 'Brand'));
      return $q->result();
    }
    public function getCMSStatus(){
        $q = $this->db->order_by("title", "asc")->get_where($this->table_cms, array('type' => 'Status'));
        return $q->result();
    }
    public function getCMSGenre(){
        $q = $this->db->order_by("title", "asc")->get_where($this->table_cms, array('type' => 'Type'));
        return $q->result();
    }
    
    public function getCMSTypes(){     
      $q = $this->db->distinct()->select('type')->order_by("type", "asc")->get($this->table_cms);     
      return $q->result();
  }

  public function SelectCMSTypes($copy){
      $q = $this->db->order_by("title", "asc")->get_where($this->table_cms, array("type" => $copy));
      return $q->result();
 }

	//CMS Functions
    public function addCMS($data){
      $this->db->insert($this->table_cms, $data);
    }
    public function updateCMS($where, $data){
      $this->db->update($this->table_cms, $data, $where);
      return $this->db->affected_rows();
    }
    public function deleteCMS($loc, $where){
      $this->db->where($where, $loc);
      $this->db->delete($this->table_cms);
    }

    public function updateTableCMS($loc, $new){
      $this->db->update($this->table, $new, $loc);
      return $this->db->affected_rows();
    }

    public function exportData(){
      $q = $this->db->get($this->table);
      return $q->result();
		}   
		public function exportDataCMS(){
      $q = $this->db->get($this->table_cms);
      return $q->result();
		}   
		
	

}
