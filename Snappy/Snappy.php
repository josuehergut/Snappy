<?php	
	/* new namespace*/
	namespace Snappy;
	/*Access to the clas MySQL*/
	use MySQLi;
	/**
	 * @author Snappy
	 * Snappy PHP Query builder
	 * Snappy V1.0 || (C) Snappy
	 */	
	/* functions */
	include dirname(__FILE__).'\src\_async_alert.php';
	include dirname(__FILE__).'\src\help-asistant.php';

	/*Access to function in _async_alert.php*/
	
	/**	 
	 * -------------------------------------------------
	 *				->Consultas Permitidas<-
	 * -------------------------------------------------
	 *
	 * assignar tabla -> to(string)	 
	 * seleccionar cuando (WHERE) -> when(array, condition) 'campo' => 'valor', condition = >, <, <>, <=, >=, = 
	 * seleccionar cuando (WHERE NOT) -> when_no(array) 'campo' => 'valor'
	 * and-> and(array, condition)
	 * or-> or(array, condition)
	 * seleccionar hasta (LIMIT) -> limit(int) 'from, to'
	 * ordenar (ORDER BY) -> order(param,string) 'ASC', 'DESC', 'RAND()''
	 * contiene (LIKE %i%) ->like(array) 'campo' => 'valor'
	 * entre dos (BETWEEN) ->middle(value, value)
	 * insertar (INSERT INTO) -> add(tabla,array) 'campo' => 'valor'
	 * seleccionar (SELECT * FROM) -> get(tabla)
	 * borrar (DELETE FROM) ->remove(tabla, array) 'campo' => 'valor'
	 * editar (UPDATE) ->replace(tabla, array) 'campo' => 'valor'	 
	 * informacion sobre la conexión ->info()
	 */		
	/** 
	 * ---------------------------------------
	 *		Snappy
	 * ---------------------------------------
	 */
	abstract class Snappy{
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
	/**
	 * @author Snappy 2018
	 * -----------------------------------
	 *      query class
	 * -----------------------------------
	 */
	class query extends Snappy{
		private $async_var_table;//contiene el nombre de la tabla a acceder		
		private $async_var_when;
		private $async_var_when_no;
		private $async_var_limit;
		private $async_var_order;
		private $async_var_operator;
		private $async_var_like;
		private $async_var_between;		

		/**
		 * -----------------------------------
		 *			costruct
		 * -----------------------------------
		 */
		public function __construct($url ='localhost', $user = 'root', $pass = '', $name = 'db_name'){			
			$this->db_url  = filter_var($url, FILTER_SANITIZE_URL);
			$this->db_user = filter_var($user, FILTER_SANITIZE_STRING);
			$this->db_pass = filter_var($pass, FILTER_SANITIZE_STRING);
			$this->db_name = filter_var($name, FILTER_SANITIZE_STRING);
			$this->new_data_base();
		}
		/**
		 * ------------------------------------------
		 *		to asigna el nombre de la tabla
		 * ------------------------------------------
		 * @param strign $snd
		 */
		public function to($snd){
			$this->async_var_table = filter_var($snd, FILTER_SANITIZE_STRING);
			return $this;
		}
		/**
		 * ----------------------------------------------
		 *                      condition
		 *  asigna la condicion que se desea, y retorna un valor concatenado
		 * ----------------------------------------------
		 * @param string $Array
		 * @param string $condition
		 * @return string
		 */
		//new var
		private $async_rest = null;
		public function condition($async_array, $condition = '='){
			foreach ($async_array as $key_1 => $value_1){
				$this->async_rest = filter_var($key_1, FILTER_SANITIZE_STRING)." = '".filter_var($value_1, FILTER_SANITIZE_STRING)."'";
				if ($condition != '=') {
					$this->async_rest = filter_var($key_1, FILTER_SANITIZE_STRING)." ".$condition." '".filter_var($value_1, FILTER_SANITIZE_STRING)."'";
				}			
				return $this->async_rest; 
			}	
		}
		/**
		 * -----------------------------------------
		 *				when
		 * -----------------------------------------
		 * @param string $when
		 */
		public function when($when = '', $condition = '='){			
			if (is_array($when)) {
				$when = $this->condition($when, $condition);
			}
			else{
				$when = filter_var($when, FILTER_SANITIZE_STRING);
			}
			$this->async_var_when = ' WHERE ('.$when.')';
			return $this;
		}
		/**
		 * -------------------------------------------
		 *					middle
		 * -------------------------------------------
		 * @param string $valor_1 
		 * @param string $valor_2
		 */
		public function middle($value_01, $value_02){			
			$this->async_var_between .= 'BETWEEN '.filter_var($value_01, FILTER_VALIDATE_INT).' AND '.filter_var($value_01, FILTER_VALIDATE_INT);
			return $this;
		}
		/**
		 * -----------------------------------------
		 *				when_not
		 * -----------------------------------------
		 * @param string $when_not
		 */
		public function when_not($when_not = '', $condition = '='){
			if (is_array($when_not)) {
				$when_not = $this->condition($when_not, $condition);
			}
			else{
				$when_not = filter_var($when_not, FILTER_SANITIZE_STRING);
			}
			$this->async_var_when_no = 'WHERE NOT ('.$when_not.')';
			return $this;
		}
		/**
		 * -----------------------------------------
		 *				like
		 * -----------------------------------------		
		 * @param string $like
		 */
		public function like($like = ''){
			foreach ($like as $campo => $valor){
				$like = 'WHERE '.filter_var($campo, FILTER_SANITIZE_STRING)." LIKE '%".filter_var($valor, FILTER_SANITIZE_STRING)."%'";
			}
			$this->async_var_like = $like;
			return $this;
		}
		/**
		 * -------------------------------------------
		 *	super like
		 * -------------------------------------------
		 *
		 *
		private $des_str = null;
		public function super_like($param, $array = ''){			
			$this->des_str = explode(" ", $array);
			for ($i=0; $i < count($this->des_str); $i++) {				
				$sql[$i] = $this->db->query("SELECT * FROM conversations_messages WHERE 'message' LIKE '%".$this->des_str[$i]."%'") or die('error');
				echo count($sql[$i]);
			}
		}
		*/
		/**
		 * -----------------------------------------
		 *				and
		 * -----------------------------------------
		 * @param string $and
		 */
		public function and($and = '', $condition = '='){
			$and = $this->condition($and, $condition);
			$this->async_var_operator.= 'AND ('.$and.')';
			return $this;
		}
		/**
		 * -----------------------------------------
		 *				or
		 * -----------------------------------------
		 * @param string $or
		 */
		public function or($or = '', $condition = '='){
			$or = $this->condition($or, $condition);
			$this->async_var_operator.= 'or ('.$or.')';
			return $this;
		}
		/**
		 * -----------------------------------------
		 *				order
		 * -----------------------------------------
		 * @param string $order
		 */
		public function order($param, $order = '', $condition='='){
			if (is_array($param)) {
				$param = $this->condition($param, $condition);
			}			
			$this->async_var_order .= 'ORDER BY '.$param.' '.$order;
			return $this;
		}		
		/**
		 * ---------------------------------------
		 *			limit
		 * ---------------------------------------
		 * @param string $limit
		 */
		public function limit($limit = ''){
			$this->async_var_limit .= 'LIMIT '.$limit;
			return $this;
		}
		/**
		 * -------------------------------------
		 *			push
		 * -------------------------------------
		 * @param strign $array		 
		 */
		public function add($array = ''){			
			$_count_ = count($array);
			$_key_cons = null;
			$_value_cons = null;
			$i = 0;
			foreach ($array as $_object => $_value){
				$i++;
				if ($i < $_count_) {
					$_key_cons .= $_object.',';
					$_value_cons .= "'".$_value."',";
				}				
				else{
					$_key_cons .= $_object;
					$_value_cons .= "'".$_value."'";				
				}
			}
			$rtn = $this->db->query("INSERT INTO $this->async_var_table($_key_cons) VALUES($_value_cons)") or die(_MESSEGE($this->db->error, 'ERROR_INSERT_001', 'INSERT INTO '.$async_var_table.'('.$_key_cons.') VALUES('.$_value_cons.')', $this->db_name));
			return $rtn;
		}
		/**
		 * -------------------------------------------
		 *			get
		 * -------------------------------------------		 
		 */
		public function get(){			
			$_get_rst = $this->db->query("SELECT * FROM $this->async_var_table $this->async_var_when_no $this->async_var_when $this->async_var_operator $this->async_var_like $this->async_var_between $this->async_var_limit $this->async_var_order") or die(_MESSEGE($this->db->error, 'ERROR_SELECT_002', 'SELECT * FROM '.$this->async_var_table.' '.$this->async_var_when_no.' '.$this->async_var_when.' '.$this->async_var_operator.' '.$this->async_var_like.' '.$this->async_var_between.' '.$this->async_var_limit.' '.$this->async_var_order.'', $this->db_name));			
			return $_get_rst;
		}
		/**
		 * -------------------------------------------
		 *				clean
		 * -------------------------------------------
		 */
		public function remove(){			
			$async_var_fgtu7 = $this->db->query("DELETE FROM $this->async_var_table $this->async_var_when_no $this->async_var_when $this->async_var_operator $this->async_var_like") or die(_MESSEGE($this->db->error, 'ERROR_DELETE_003', 'DELETE FROM '.$this->async_var_table.' '.$this->async_var_when_no.' '.$this->async_var_when.' '.$this->async_var_operator.' '.$this->async_var_like.'', $this->db_name));			
			return $async_var_fgtu7;
		}
		/**
		 * -------------------------------------
		 *			edit
		 * -------------------------------------
		 * @param strign $array		 
		 */
		public function replace($array = ''){				
			$_count_ = count($array);
			$_key_cons = null;
			$_value_cons = null;
			$i = 0;
			if (!is_array($array)) {
				throw new \Exception("This is not an array");
			}
			foreach ($array as $_object => $_value){
				$i++;
				if ($i < $_count_) {
					$_key_cons.= " ".$_object." = '".$_value."',";
				}				
				else{
					$_key_cons.= " ".$_object." = '".$_value."'";					
				}
			}			
			$this->db->query("UPDATE $this->async_var_table SET $this->_key_cons $this->async_var_when") or die(_MESSEGE($this->db->error, 'ERROR_UPDATE_003', 'UPDATE '.$this->async_var_table.' SET '.$this->_key_cons.' '.$this->async_var_when.'', $this->db_name));
		}

		/**
		 * -----------------------------------------
		 *			structure
		 * -----------------------------------------
		 */


		/**
		 *
		 */
		public function json_encode(){
			return json_encode($json_encode);
		}

		public function info(){
			_INFO('Esta es la Información de tu db...', $this->db_name, $this->db_url, $this->db_user);
		}

		public function help($desc = ''){
			help_fn($desc, $this->db_name);
		}
		/**
		 * -------------------------------------------
		 *			Destruct
		 * -------------------------------------------
		 */
		public function __destruct(){
			$this->new_data_base();
			$this->db->close();
		}
	}		
?>