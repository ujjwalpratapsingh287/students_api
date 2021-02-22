<?php
	//Configuration File
	require_once("class.db.php");
	require_once("class.main.php");
	require_once("config.php");


	class main {
		public $link, $idx, $std_id, $api, $action;
		
		function __construct(){
			$db_connection = new db();
			$this->link = $db_connection->connect();
			return $this->link;
		} 
		
		//Main Function
		public function takecall(){
			if(strtolower($this->action) == 'show'){
				$query = " SELECT * FROM students WHERE std_id='$this->std_id' LIMIT 1";
				$results = SELF::fetch_by_query($query);
				if(count($results)==1) {
					return JSON_ENCODE($results);
				}
				else{
					$arr = array("error" => "No Student to display");
					return JSON_ENCODE($arr);
				}				
			}
			else if(strtolower($this->action) == 'del'){
				$query = $this->link->prepare("DELETE FROM students WHERE std_id= :std_id");
				$query->bindParam(':std_id', $this->std_id, PDO::PARAM_STR);
				$query->execute($values);
				$count = $query->rowCount();
				$arr = array("error" => "Student record deleted.");
				return JSON_ENCODE($arr);				
			}


			
		}



	
		//Add new Record
		public function add_new_record() {
			$query = $this->link->prepare("INSERT INTO ass_submissions(ass_id, ass_ref, ass_size, std_id, std_fname, date, time, ass_status, std_batch) VALUES(?,?,?,?,?,?,?,?,?)");
			$values = array($this->ass_id, $this->ass_ref, $this->ass_size_value, $this->std_id, $this->std_fname, DATE, TIME, 1, $this->std_batch);
			$query->execute($values);
			$count = $query->rowCount();
			return $count;		

		}

		public function fetch_by_query($query) {
			try {
				$query = $this->link->prepare($query);
				$query->execute();
				$results = $query->fetchAll();
				$this->column_name = $query->fetchColumn();
				$this->counter = $query->rowCount();
			}
			catch (Exception $e) {
				$results = array("ERROR");
			}	
			return $results;
		}

		public function updatex($tb, $col_name, $col_value, $where_name, $where_value) {
			$query = $this->link->prepare("UPDATE $tb SET $col_name = :col_value WHERE $where_name = :where_value");
			$query->bindParam(':col_value', $col_value, PDO::PARAM_STR);
			$query->bindParam(':where_value', $where_value, PDO::PARAM_STR); 
			$query->execute();
			return $query->rowCount();
		}
	}
?>