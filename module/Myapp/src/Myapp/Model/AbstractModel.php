<?php
/**
* This class allows for interaction with a table via $_table and $_pk
* when you extend it be sure to define them in your new class.
*   protected $_table = "contacts";
*   protected $_pk	  = "id";
* 
* @package    AbstractModel
* @author      paul heika <bike8@hotmail.com>
*/

Class  AbstractModel
{   var $con;  // holds the database connection

/**
 * __construct() creates the connection to the database
 * it accepts an array containing the login information
 * 
 * @param type $db
 */
    function __construct($db=array()) {
            $default = array(
                    'host' => 'localhost',
                    'user' => 'user',
                    'pass' => 'password',
                    'db' => 'database'
            );
             $db = array_merge($default,$db);

         $this->con = new PDO("mysql:host=".$db['host'].";dbname=".$db['db'].";charset=utf8", $db['user'], $db['pass'], array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
            
    }
    /**
     * __destruct() kills the db connection
     */
    
    function __destruct() {
        $this->con = NULL;
    }
    /**
     * save() updates/inserts the row to the database.
     * if the row has a pk defined it will update that row
     * otherwise it inserts a new row.
     * 
     */
    public function save() {
        try {
            if (isset($this->row[$this->_pk])) {
                //update
                $sql = "update $this->_table set ";
                $sep = "";
                foreach ($this->row as $key => $value) {
                   if ($key == $this->_pk) {
                       $where = " where $key = :$key";
                   } else {    
                    $sql .= "$sep $key = :$key";
                    $sep = ",";
                   }
                   //build execute array
                   $colonKey = ":".$key;
                   $executeArray[$colonKey] = $value;
                }
                $sql .= $where;

                $stmt = $this->con->prepare($sql);
                $stmt->execute($executeArray);
                $affected_rows = $stmt->rowCount();

            } else {
                //insert
                $sql = "insert into $this->_table ";
                 // implode keys of $array...
                $sql .= " (".implode(", ", array_keys($this->row)).")";
                $placeHolder = join(',',array_fill(0,count($this->row),'?'));
                $sql .= " VALUES (".$placeHolder.")";
                $stmt = $this->con->prepare($sql);
                $stmt->execute(array_values($this->row));
                $this->row[$this->_pk] = $this->con->lastInsertId();
            }

        } catch(PDOException $ex) {
            echo "PDO Error ".$ex-getMessage();    
        }

    }
    
    /**
     * load() fetches the row with pk = $id and saves it to the class
     * 
     * @param type $id
     * @return \AbstractModel
     */
    public function load($id) {

        try {
            $sql = "select * from $this->_table where $this->_pk = ?";
            $stmt = $this->con->prepare($sql);
            $stmt->execute(array($id));
            $this->row = $stmt->fetch(PDO::FETCH_ASSOC);
            

        } catch(PDOException $ex) {
            echo "PDO Error ".$ex-getMessage();    
        }

        return $this;
    }
    
    /**
     * delete() deletes the row matching $id from the table
     * 
     * unsure if $id is supposed to be optional as the readme lists it as required
     * but the call in contact_tests.php is missing the id, so i made it work
     * both ways
     *  
     * @param type $id
     */
    public function delete($id = false) {

         try {
             
            $sql = "delete from $this->_table where $this->_pk = ?";
            $stmt = $this->con->prepare($sql);
            if ($id) {
                $stmt->execute(array($id));
            } else if ($this->row[$this->_pk]) {
                $stmt->execute(array($this->row[$this->_pk]));
            }
                       

        } catch(PDOException $ex) {
            echo "PDO Error ".$ex-getMessage();    
        }

    }
    
    /**
     * getData() accepts an optional key and returns only that key/value pair
     * otherwise the entire row.
     * 
     * @param type $key
     * @return type
     */
    public function getData($key=false) {
        if ($key){
            // return only key=>value pair
            return $this->row[$key];
        } else {
            return $this->row;
        }
        
    }
    /**
     * $setData() assigns  values to the row as either a single key/value pair
     * or an array of key/value pairs.
     * 
     * @param type $arr
     * @param type $value
     * @return \AbstractModel
     */
    public function setData($arr, $value=false) {
       
        if (!is_array($arr) AND $value) {
            //single key/value
            $this->row[$arr] = $value;  
        } else if (is_array($arr)) {
            //array of key/values
            $this->row = $arr;
        }
        return $this;
    }
}