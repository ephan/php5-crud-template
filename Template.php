<?PHP
require_once PATH_BASE . '/lib/connect.php';
define (cacheTTLInSeconds, 600); //10 minutes

//change classname
class TEMPLATE {
//member vars
	public static $selectQueryFormatStr = "SELECT * FROM %s WHERE %s=%d";
	public static $primaryKey = 'id';
	public static $table = 'table';

	function __construct() {
//change this var
		if ($FIRSTREQUIREDVAR != '') {
			foreach($this as $var => $value) {
				$this->$var .= $$var;
			}
		}
//change this var
		else if (${self::$primaryKey}!=''){
//change this var
			$this->get(${self::$primaryKey});
		}
	}//constructor
	
	private function get($id) {
		$query = sprintf(self::$selectQueryFormatStr, self::$table, self::$primaryKey, $id);

		$result = mysql_query($query);
		if ($row = mysql_fetch_assoc($result)) {
			foreach ($row as $key=>$value) {
				$$key = $value;
			}
			foreach($this as $var => $value) {
				$this->$var .= $$var;
			}
		}		
	}//get()
	
	public function save() {
		if ($this->id == '') {

			$result = 0;
			$query = "INSERT INTO " . self::$table . "  set ";
			$baseInsertQueryLength = strlen($query);
			foreach($this as $var => $value) {
				if ($var != self::$primaryKey) {
					$query .= $var . "='" . mysql_real_escape_string((strip_tags((($value))))) . "', ";
				}
			}
			if (strlen($query) > $baseInsertQueryLength) {
				$query = substr($query, 0, strlen($query)-2);
				//echo $query; exit;
				$result = mysql_query($query);
			}

			return $result; //true if successful, false otherwise
		}
		else { //update
			$result = 0;
			$query = "update " . self::$table . " set ";
			$baseUpdateQueryLength = strlen($query);
			foreach($this as $var => $value) {
				if ($var != self::$primaryKey) {
					$query .= $var . "='" . mysql_real_escape_string((strip_tags(($value)))) . "', ";
				}
			}
			if (strlen($query) > $baseUpdateQueryLength) {
				$query = substr($query, 0, strlen($query)-2);
				$query .= " where " . self::$primaryKey . "='" . mysql_real_escape_string($this->id) . "'";
				//echo $query;
				$result = mysql_query($query);
			}
			return $result; //# rows that are changed/updated, 0 otherwise
		}
	}//save()
}//class
?>