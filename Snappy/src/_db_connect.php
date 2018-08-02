<?php
	namespace DB;
	/** 
	 * ---------------------------------------
	 *		vitaDB class
	 * ---------------------------------------
	 */
	abstract class Async{
		public $db;
		public $db_url;
		public $db_user;
		public $db_pass;
		public $db_name;		
		/**
		  * --------------------------------
		  *			new_data_base
		  * --------------------------------
		  */
		public function new_data_base(){
			$this->db = new MySQLi($this->db_url, $this->db_user, $this->db_pass, $this->db_name);
    		if ($this->db->connect_errno) {
      			die( "ERROR: (" . $this->db->mysqli_connect_errno() . ") " . $this->db->mysqli_connect_error());
    		}
    		else{
    			return $this->db;    			
    		}
		}
	}
?>