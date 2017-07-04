<?php
namespace Iman\Command;

class imanFunctions
{
    private $app;

    private $conn;

    function __construct(){
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

        $sqlInsertUser = "INSERT INTO user (name) VALUES ('".$name."')";
        $returnValue = true;

        if (mysqli_query($this->conn, $sqlInsertUser)) {
            $last_id = mysqli_insert_id($this->conn);
            $sqlInsertBalance = "INSERT INTO user_balance (user_id) VALUES (".$last_id.")";
            if (!mysqli_query($this->conn, $sqlInsertBalance)) {
              $returnValue = false;
            }
        } else {
            $returnValue = false;
        }

        mysqli_close($this->conn);

        return $returnValue;
    }

    public function _checkUser($name=null){
        $this->_DBConnect();

        if (!$this->conn) {
            return false;
        }

        $sqlSelect = "SELECT id, name FROM user where name = '".$name."'";
        $result = mysqli_query($this->conn, $sqlSelect);

        if (mysqli_num_rows($result) > 0) {
            while($row = mysqli_fetch_assoc($result)) {
                $returnValue[] = array('id' => $row["id"], 'name' => $row["name"]);
            }
        } else {
            $returnValue = false;
        }

        mysqli_close($this->conn);

        return $returnValue;
    }

    public function _deleteUser($id=null){
        $this->_DBConnect();

        if (!$this->conn) {
            return false;
        }

        $sqlDelete = "DELETE FROM user WHERE id=".$id;
        $returnValue = true;

        if (mysqli_query($this->conn, $sqlDelete)) {
            $sqlDeleteBalance = "DELETE FROM user_balance WHERE user_id = '".$id."'";
            if (!mysqli_query($this->conn, $sqlDeleteBalance)) {
                $returnValue = false;
            }
        } else {
            $returnValue = false;
        }

        mysqli_close($this->conn);

        return $returnValue;
    }

    public function _displayBalance($id=null){
        $this->_DBConnect();

        if (!$this->conn) {
            return "0.00";
        }

        $sqlSelect = "SELECT balance FROM user_balance where user_id = '".$id."'";
        $result = mysqli_query($this->conn, $sqlSelect);

        if (mysqli_num_rows($result) > 0) {
            while($row = mysqli_fetch_assoc($result)) {
                $returnValue = number_format($row["balance"], 2, '.', ',');
            }
        } else {
            $returnValue = "0.00";
        }

        mysqli_close($this->conn);

        return $returnValue;
    }

    public function _addBalance($id=null, $amount=null){
        $this->_DBConnect();

        if (!$this->conn) {
            return "0.00";
        }

        $sqlSelect = "SELECT balance FROM user_balance where user_id = '".$id."'";
        $result = mysqli_query($this->conn, $sqlSelect);

        if (mysqli_num_rows($result) > 0) {
            while($row = mysqli_fetch_assoc($result)) {
                $balanceVal = floatval($row["balance"]) + floatval($amount);
            }
        } else {
            $balanceVal = float($amount);
        }

        $sqlUpdate = "UPDATE user_balance SET balance='".$balanceVal."', transaction_log='add ".$amount." balance to account', last_update='".date('Y-m-d H:i:s')."' WHERE user_id = '".$id."'";
        $returnValue = true;
        if (!mysqli_query($this->conn, $sqlUpdate)) {
            $returnValue = false;
        }

        mysqli_close($this->conn);

        return $returnValue;
    }

    public function _removeBalance($id=null, $amount=null){
        $this->_DBConnect();

        if (!$this->conn) {
            return "0.00";
        }

        $sqlBalance = "SELECT balance FROM user_balance where user_id = '".$id."'";
        $result = mysqli_query($this->conn, $sqlBalance);

        if (mysqli_num_rows($result) > 0) {
            while($row = mysqli_fetch_assoc($result)) {
                $balanceVal = floatval($row["balance"]) - floatval($amount);
            }
        } else {
            $balanceVal = float($amount);
        }

        $allowUpdate = true;
        if($balanceVal < 0){
            $allowUpdate = false;
            $sqlOverdraft = "SELECT overdraft FROM user where id = '".$id."'";
            $result = mysqli_query($this->conn, $sqlOverdraft);
            if (mysqli_num_rows($result) > 0) {
                while($row = mysqli_fetch_assoc($result)) {
                    $overdraftVal = $row["overdraft"];
                }
            } else {
                $overdraftVal = 0;
            }

            if($overdraftVal == 1){
                $allowUpdate = true;
            }
        }

        $returnValue = true;
        if($allowUpdate){
            $sqlRemoveBalance = "UPDATE user_balance SET balance='".$balanceVal."', transaction_log='remove ".$amount." balance to account', last_update='".date('Y-m-d H:i:s')."' WHERE user_id = '".$id."'";
            if (!mysqli_query($this->conn, $sqlRemoveBalance)) {
                $returnValue = false;
            }
        }else{
            $returnValue = false;
        }

        mysqli_close($this->conn);

        return $returnValue;
    }

    public function _overdraftAccount($id=null){
        $this->_DBConnect();

        if (!$this->conn) {
            return false;
        }

        $sqlUpdate = "UPDATE user SET overdraft='1' WHERE id = '".$id."'";
        $returnValue = true;

        if (!mysqli_query($this->conn, $sqlUpdate)) {
          $returnValue = false;
        }

        mysqli_close($this->conn);

        return $returnValue;
    }
}
