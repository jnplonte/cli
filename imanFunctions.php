<?php
namespace Cilex\Command;

class imanFunctions
{
    private $app;

    private $conn;

    public function __construct()
    {
        $this->app = new \Cilex\Application('Cilex');
        $this->app->register(new \Cilex\Provider\ConfigServiceProvider(), array('config.path' => 'config.json'));
    }

    protected function _DBConnect(){
        $this->conn = mysqli_connect($this->app['config']->dbServer, $this->app['config']->dbUser, $this->app['config']->dbPassword, $this->app['config']->dbName);
    }

    public function _insertUser($name=null){
        $this->_DBConnect();

        if (!$this->conn) {
            return false;   
        } 

        $sql1 = "INSERT INTO user (name) VALUES ('".$name."')"; 

        if (mysqli_query($this->conn, $sql1)) {
            $pVal = true;
            $last_id = mysqli_insert_id($this->conn);
            $sql2 = "INSERT INTO user_balance (user_id) VALUES (".$last_id.")";
            if (mysqli_query($this->conn, $sql2)) {
                $pVal = true;
            }else{
                $pVal = false;
            }
        } else {
            $pVal = false;
        }

        mysqli_close($this->conn);

        return $pVal;
    }

    
    public function _checkUser($name=null){
        $this->_DBConnect();

        if (!$this->conn) {
            return false;   
        } 

        $sql = "SELECT id, name FROM user where name like '%".$name."%'";
        $result = mysqli_query($this->conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            while($row = mysqli_fetch_assoc($result)) {
                $pVal[] = array('id' => $row["id"], 'name' => $row["name"]);
            }
        } else {
            $pVal = false;
        }

        mysqli_close($this->conn);

        return $pVal;
    }

    public function _deleteUser($id=null){
        $this->_DBConnect();

        if (!$this->conn) {
            return false;   
        } 

        $sql1 = "DELETE FROM user WHERE id=".$id;
        if (mysqli_query($this->conn, $sql1)) {
            $pVal = true;
            $sql2 = "DELETE FROM user_balance WHERE user_id = '".$id."'";
            if (mysqli_query($this->conn, $sql2)) {
                $pVal = true;
            }else{
                $pVal = false;
            }
        } else {
            $pVal = false;
        }

        mysqli_close($this->conn);

        return $pVal;
    }

    public function _displayBalance($id=null){
        $this->_DBConnect();

        if (!$this->conn) {
            return "0.00";   
        } 

        $sql = "SELECT balance FROM user_balance where user_id = '".$id."'";
        $result = mysqli_query($this->conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            while($row = mysqli_fetch_assoc($result)) {
                $pVal = number_format($row["balance"], 2, '.', ',');
            }
        } else {
            $pVal = "0.00";
        }

        mysqli_close($this->conn);

        return $pVal;
    }

    public function _addBalance($id=null, $amount=null){
        $this->_DBConnect();

        if (!$this->conn) {
            return "0.00";   
        } 

        $sql1 = "SELECT balance FROM user_balance where user_id = '".$id."'";
        $result = mysqli_query($this->conn, $sql1);

        if (mysqli_num_rows($result) > 0) {
            while($row = mysqli_fetch_assoc($result)) {
                $pVal = floatval($row["balance"]) + floatval($amount);
            }
        } else {
            $pVal = float($amount);
        }

        $sql2 = "UPDATE user_balance SET balance='".$pVal."', transaction_log='add ".$amount." balance to account', lastupdate='".date('Y-m-d H:i:s')."' WHERE user_id = '".$id."'";
        if (mysqli_query($this->conn, $sql2)) {
            $pVal = true;
        }else{
            $pVal = false;
        }

        mysqli_close($this->conn);

        return $pVal;
    }

    public function _removeBalance($id=null, $amount=null){
        $this->_DBConnect();

        if (!$this->conn) {
            return "0.00";   
        } 

        $sql1 = "SELECT balance FROM user_balance where user_id = '".$id."'";
        $result = mysqli_query($this->conn, $sql1);

        if (mysqli_num_rows($result) > 0) {
            while($row = mysqli_fetch_assoc($result)) {
                $pVal = floatval($row["balance"]) - floatval($amount);
            }
        } else {
            $pVal = float($amount);
        }

        $allowUpdate = true;
        if($pVal < 0){
            $allowUpdate = false;
            $sql3 = "SELECT overdraft FROM user where id = '".$id."'";
            $result = mysqli_query($this->conn, $sql3);
            if (mysqli_num_rows($result) > 0) {
                while($row = mysqli_fetch_assoc($result)) {
                    $ovVal = $row["overdraft"];
                }
            } else {
                $ovVal = 0;
            }
            
            if($ovVal == 1){
                $allowUpdate = true;
            }
        }

        if($allowUpdate){
            $sql2 = "UPDATE user_balance SET balance='".$pVal."', transaction_log='remove ".$amount." balance to account', lastupdate='".date('Y-m-d H:i:s')."' WHERE user_id = '".$id."'";
            if (mysqli_query($this->conn, $sql2)) {
                $pVal = true;
            }else{
                $pVal = false;
            }
        }else{
            $pVal = false;
        }

        mysqli_close($this->conn);

        return $pVal;
    }

    public function _overdraftAccount($id=null){
        $this->_DBConnect();

        if (!$this->conn) {
            return false;   
        } 

        $sql = "UPDATE user SET overdraft='1' WHERE id = '".$id."'";
        if (mysqli_query($this->conn, $sql)) {
            $pVal = true;
        }else{
            $pVal = false;
        }

        mysqli_close($this->conn);

        return $pVal;
    }

    public function _currencyList(){
        return (array) $this->app['config']->currencyList;
    }

    public function _productList(){
        return (array) $this->app['config']->productList;
    }
}
