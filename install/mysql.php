<?php
defined('INST_BASE_DIR') or die();
/**
 * mbe.ro
 *
 * @author     Ciprian Mocanu <http://www.mbe.ro> <ciprian@mbe.ro>
 * @license    Do whatever you like, just please reference the author
 * @version    1.56
 */
class mysql {
    var $con;
    function __construct($db=array()) {
        $default = array(
            'host' => 'localhost',
            'user' => 'root',
            'pass' => '',
            'db' => 'test'
        );
        $db = array_merge($default,$db);
        $db_name = $db['db'];
        $host = $db['host'];
        $username = $db['user'];
        $password = $db['pass'];
        $this->con = new PDO("mysql:dbname=$db_name;host=$host", $username, $password);
        if(!$this->con) throw new Exception('Error Connecting');
    }
    function __destruct() {
        return true;
    }
    function query($s='',$rows=false,$organize=true) {
        //if (!$q=mysql_query($s,$this->con)) return false;
        $sth = $this->con->prepare($s);
        $sth->execute();
        if ($rows!==false) $rows = intval($rows);
        $rez=array(); $count=0;
        $type = $organize ? PDO::FETCH_NUM : PDO::FETCH_ASSOC;
        while (($rows===false || $count<$rows) && $line=$sth->fetch($type)) {
            if ($organize) {
                foreach ($line as $field_id => $value) {
                    $meta = $sth->getColumnMeta($line);
                    $test = '';
                    //$table = mysql_field_table($q, $field_id);
                    //if ($table==='') $table=0;
                    //$field = mysql_field_name($q,$field_id);
                    //$rez[$count][$table][$field]=$value;
                }
            } else {
                $rez[$count] = $line;
            }
            ++$count;
        }

        return $rez;
    }
    function execute($s='') {
        $sth = $this->con->prepare($s);
        if ($sth->execute()) return true;
        return false;
    }
    function select($options) {
        $default = array (
            'table' => '',
            'fields' => '*',
            'condition' => '1',
            'order' => '1',
            'limit' => 50
        );
        $options = array_merge($default,$options);
        $sql = "SELECT {$options['fields']} FROM {$options['table']} WHERE {$options['condition']} ORDER BY {$options['order']} LIMIT {$options['limit']}";
        $sth = $this->con->prepare($sql);
        $sth->execute();
        return $sth->fetchAll();
    }
    function row($options) {
        $default = array (
            'table' => '',
            'fields' => '*',
            'condition' => '1',
            'order' => '1'
        );
        $options = array_merge($default,$options);
        $sql = "SELECT {$options['fields']} FROM {$options['table']} WHERE {$options['condition']} ORDER BY {$options['order']}";
        //$result = $this->query($sql,1,false);
        $sth = $this->con->prepare($sql);
        $sth->execute();
        $result = $sth->fetchAll();
        if (empty($result[0])) return false;
        return $result[0];
    }
    function get($table=null,$field=null,$conditions='1') {
        if ($table===null || $field===null) return false;
        $result=$this->row(array(
            'table' => $table,
            'condition' => $conditions,
            'fields' => $field
        ));
        if (empty($result[$field])) return false;
        return $result[$field];
    }
    function update($table=null,$array_of_values=array(),$conditions='FALSE') {
        if ($table===null || empty($array_of_values)) return false;
        $what_to_set = array();
        foreach ($array_of_values as $field => $value) {
            if (is_array($value) && !empty($value[0])) $what_to_set[]="`$field`='{$value[0]}'";
            else $what_to_set []= "`$field`='".$this->con->quote($value)."'";
        }
        $what_to_set_string = implode(',',$what_to_set);
        $sth = $this->con->prepare("UPDATE $table SET $what_to_set_string WHERE $conditions");
        return $sth->execute();
        //return $this->execute("UPDATE $table SET $what_to_set_string WHERE $conditions");
    }
    function insert($table=null,$array_of_values=array()) {
        if ($table===null || empty($array_of_values) || !is_array($array_of_values)) return false;
        $fields=array(); $values=array();
        foreach ($array_of_values as $id => $value) {
            $fields[]=$id;
            if (is_array($value) && !empty($value[0])) $values[]=$value[0];
            else $values[]="'".$this->con->quote($value)."'";
        }
        $s = "INSERT INTO $table (".implode(',',$fields).') VALUES ('.implode(',',$values).')';
        $sth = $this->con->prepare($s);
        if ($sth->execute()) return $this->con->lastInsertId();
        return false;
    }
    function delete($table=null,$conditions='FALSE') {
        if ($table===null) return false;
        return $this->execute("DELETE FROM $table WHERE $conditions");
    }
}