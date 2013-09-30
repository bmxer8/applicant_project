<?php

Class  AbstractModel
{   var $con;
    function __construct($db=array()) {
            $default = array(
                    'host' => 'localhost',
                    'user' => 'user',
                    'pass' => 'password',
                    'db' => 'database'
            );
            $db = array_merge($default,$db);
            $this->con=mysql_connect($db['host'],$db['user'],$db['pass'],true) or die ('Error connecting to MySQL');
            mysql_select_db($db['db'],$this->con) or die('Database '.$db['db'].' does not exist!');
    }
    function __destruct() {
            mysql_close($this->con);
    }

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
             $result = mysql_query($sql,$this->con) or die(mysql_error());
        } else {
            //insert
            $sql = "insert into $this->_table ";
             // implode keys of $array...
            $sql .= " (".implode(", ", array_keys($this->row)).")";

            // implode values of $array...
            $sql .= " VALUES ('".implode("', '", $this->row)."') ";
            $result = mysql_query($sql,$this->con) or die(mysql_error());
            $this->row[$this->_pk] = mysql_insert_id($this->con); 
        }
        
       
        
    }
    public function load($id) {
        $sql = "select * from $this->_table where $this->_pk = '$id'";
        $query = mysql_query($sql,$this->con) or die(mysql_error());
        $this->row = mysql_fetch_array($query,MYSQL_ASSOC);
        return $this;
    }
    public function delete($id = false) {
        /* unsure if $id is supposed to be optional as the readme lists it at required
         * but the call in contact_tests.php is missing the id, so i made it work
         * both ways
        */
        if ($id) {
            $sql = "delete from $this->_table where $this->_pk = '$id'"; 
        } else if ($this->row[$this->_pk]) {
            $sql = "delete from $this->_table where $this->_pk = '".$this->row[$this->_pk]."'";
        }
        $result = mysql_query($sql,$this->con) or die(mysql_error());

    }
    public function getData($key=false) {
        if ($key){
            // return only key=>value pair
            return $this->row[$key];
        } else {
            return $this->row;
        }
        
    }
    public function setData($arr, $value=false) {
        // need to sanitize
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