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
                    'user' => 'kitty',
                    'pass' => 'newpassword8',
                    'db' => 'webfiles'
            );
            $db = array_merge($default,$db);
            $this->con=mysqli_connect($db['host'],$db['user'],$db['pass'],$db['db']) or die ('Error connecting to MySQL');
         
            
    }
    /**
     * __destruct() kills the db connection
     */
    
    function __destruct() {
        mysqli_close($this->con);
    }
    /**
     * save() updates/inserts the row to the database.
     * if the row has a pk defined it will update that row
     * otherwise it inserts a new row.
     * 
     * todo: convert to parameters
     */
    public function save() {
        if (isset($this->row[$this->_pk])) {
            //update
            $sql = "update $this->_table set ";
            $sep = "";
            foreach ($this->row as $key => $value) {
               if ($key == $this->_pk) {
                   $where = " where $key = '$value'";
               } else {    
                $sql .= "$sep $key = '$value'";
                $sep = ",";
               }
            }
            $sql .= $where;
             $result = mysqli_query($this->con,$sql) or die(mysqli_error($this->con));
        } else {
            //insert
            $sql = "insert into $this->_table ";
             // implode keys of $array...
            $sql .= " (".implode(", ", array_keys($this->row)).")";

            // implode values of $array...
            $sql .= " VALUES ('".implode("', '", $this->row)."') ";
            $result = mysqli_query($this->con,$sql) or die(mysqli_error($this->con));
            $this->row[$this->_pk] = mysqli_insert_id($this->con); 
        }

    }
    
    /**
     * load() fetches the row with pk = $id and saves it to the class
     * 
     * @param type $id
     * @return \AbstractModel
     */
    public function load($id) {
        $sql = "select * from $this->_table where $this->_pk = ?";
        $stmt = $this->con->prepare($sql);
        $stmt->bind_param('s',$id);
        $stmt->execute();
        $result = $stmt->get_result();
        $this->row = $result->fetch_assoc();
        $stmt->close();

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
       
        $sql = "delete from $this->_table where $this->_pk = ?";
        $stmt = $this->con->prepare($sql);
        if ($id) {
            $stmt->bind_param('s',$id);
        } else if ($this->row[$this->_pk]) {
            $stmt->bind_param('s',$this->row[$this->_pk]);
        }

        $stmt->execute();
        $result = $stmt->get_result();

        $stmt->close();

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