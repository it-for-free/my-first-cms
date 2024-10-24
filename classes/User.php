<?php

    class User
    {
        public $login = null;
        
        public $password = null;
        
        public $active = null;
        
        public function __construct($data = []) {
            if (isset($data['login'])){
                $this->login = $data['login'];
            }
            if (isset($data['password'])){
                $this->password = $data['password'];
            }
            if (isset($data['active'])){
                $this->active = $data['active'];
            }else {
                $this->active = 0;
            }
        }
        public static function getList()
        {
            $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
            $sql = "SELECT SQL_CALC_FOUND_ROWS * FROM users";
            $st = $conn->query($sql);
            $list = array ();
            while ($row = $st->fetch()){
                $user = new User($row);
                $list[] = $user;
            }
            $sql = "SELECT FOUND_ROWS() AS totalRows";
            $totalRows = $conn->query($sql)->fetch();
            $conn = null;
            return (array(
                "results" =>$list,
                "totalRows" => $totalRows[0]
            )
            );
        }
        public static function getByLogin($login){
            $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
            $sql = "SELECT * FROM users WHERE login = :login";
            $st = $conn->prepare($sql);
            $st->bindValue(":login",$login,PDO::PARAM_STR);
            $st->execute();
            
            $row = $st->fetch();
            $conn = null;
            
            if($row){
                return new User($row);
            }
        }
        public function update($params){
             if ($params['login'] != $params['userLogin'] && $this->getByLogin($params['login'])) {
              return true;
            }
            $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
            $sql = "UPDATE users SET login=:login, password=:password, active =:active  WHERE login =:userLogin";
            $st = $conn->prepare($sql);
            $st->bindValue(":login", $this->login,PDO::PARAM_STR);
            $st->bindValue(":password", $this->password,PDO::PARAM_STR);
            $st->bindValue(":active", $this->active,PDO::PARAM_STR);
            $st->bindValue(":userLogin", $params['userLogin'],PDO::PARAM_STR);
            $st->execute();
            $conn = null;
        }
        public function insert($params){
            if ($this->getByLogin($params['login'])){
                return true;
            }
            $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
            $sql = "INSERT INTO users (login, password, active) VALUES (:login, :password, :active)";
            $st = $conn->prepare($sql);
            $st->bindValue(":login",$this->login,PDO::PARAM_STR);
            $st->bindValue(":password",$this->password,PDO::PARAM_STR);
            $st->bindValue(":active",$this->active,PDO::PARAM_STR);
            $st->execute();
            $conn = null;
        }
        public function delete(){
            $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
            $st = $conn->prepare("DELETE FROM users WHERE login = :login LIMIT 1");
            $st->bindValue(":login",$this->login,PDO::PARAM_STR);
            $st->execute();
            $conn = null;
        }
    }
