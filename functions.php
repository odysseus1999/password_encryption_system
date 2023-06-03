<?php
if(!class_exists('Database'))
    require_once "Database.php";
class Functions extends Database{
    private $ciphering;
    private $iv_length;
    private $options;
    private $encryption_iv;
    private $encryption_key;

    function __construct(){
        parent::__construct();
        $this->check_owner_credential();
        $this->authenticate();
        $this->ciphering = "AES-128-CTR";
        $this->iv_length = openssl_cipher_iv_length($this->ciphering);
        $this->options = 0;
        $this->encryption_iv = '1234567891011121';
        $this->encryption_key = '$2y$10$YESRl.59oqy0mIp7tbIN7.YkAVORWyQpJgfOd9qLkM3ZclhfJIpRq';
    }
    function __destruct(){
        parent::__destruct();
    }
    public function check_owner_credential(){
        $create_tbl = "CREATE TABLE IF NOT EXISTS `owner_credential` 
                        (`id` int(30) NOT NULL PRIMARY KEY AUTO_INCREMENT,
                         `firstname` varchar(250) NOT NULL,
                         `middlename` varchar(250) NULL,
                         `lastname` varchar(250) NOT NULL,
                         `password` text NOT NULL,
                         `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                         `updated_at` DATETIME NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP)";
        $this->conn->query($create_tbl);
        if($this->conn->error){
            die("Creating Owner Credential Table Failed. Error: ". $this->conn->error);
        }
        $check_owner = $this->conn->query("SELECT * FROM `owner_credential`");
        if($check_owner->num_rows <= 0){
            if(strpos($_SERVER['PHP_SELF'],"owner_registration.php") === false && strpos($_SERVER['PHP_SELF'],"api.php") === false)
            header("location: owner_registration.php");
        }else{
            if(strpos($_SERVER['PHP_SELF'],"owner_registration.php") !== false)
            header("location: index.php");
        }
    }
    public function authenticate(){
        if(strpos($_SERVER['PHP_SELF'],"login.php") !== false){
            session_destroy();
        }elseif(strpos($_SERVER['PHP_SELF'],"index.php") !== false && !isset($_SESSION['id'])){
            header('location: login.php');
        }
    }
    public function save_owner($post = []){
        if($post['password'] != $post['cpassword']){
            return ["error" => "Password does not match"];
        }else{

            $data = "";
            $post['password'] = password_hash($post['password'], PASSWORD_DEFAULT);
            foreach($post as $k  => $v){
                $post[$k] = $this->sanitize_data($v);
            }
            $stmt = $this->conn->prepare("INSERT INTO `owner_credential` (`firstname`, `middlename`, `lastname`, `password`) VALUES
                                            (?, ?, ?, ?)");
            $stmt->bind_param('ssss', $post['firstname'], $post['middlename'], $post['lastname'], $post['password']);
            $stmt->execute();
            if($this->conn->affected_rows > 0){
               
                return $this->conn->insert_id;
            }else{
                if(!$this->conn->error){
                    return ["error" => "Failed to save data due to unknown error."];
                }else{
                    return ["error" => $this->conn->error];
                }
            }
            
        }
    }
    public function login($post = []){
        $get = $this->conn->query("SELECT * FROM `owner_credential` order by id limit 1");
        
        if($get->num_rows >0){
            $result = $get->fetch_assoc();
            
            $is_verified = password_verify($post['password'], $result['password']);
            
            if($is_verified == true){
                foreach($result as $k => $v){
                    $_SESSION[$k] = $v;
                    return true;
                }
            }else{
                return ["error" => "Incorrect Password"];
            }
        }else{
            return ["error" => "Checking Owner Credential Failed."];
        }
    }

    public function encrypt_string($string=""){
        if(!empty($string)){
            $encryption = openssl_encrypt($string, $this->ciphering,
            $this->encryption_key, $this->options, $this->encryption_iv);
            $string = $encryption;
        }
        return $string;
    }

    public function decrypt_string($string=""){
        if(!empty($string)){
            $decryption = openssl_decrypt ($string, $this->ciphering,
            $this->encryption_key, $this->options, $this->encryption_iv);
            $string = $decryption;
        }
        return $string;
    }

}