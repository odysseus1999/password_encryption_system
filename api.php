<?php
require_once("config.php");
$action = isset($_GET['action']) ? $_GET['action'] : '';

switch($action){
    case 'setup_ownership':
        $insert = $func->save_owner($_POST);
        if(isset($insert['error'])){
            $resp = ["status" => "failed", "error" => $insert['error']];
        }else{
            if($insert){
                $_SESSION['success_message']= "Ownership Setup has saved successfully";
                $resp = ["status" => "success"];
            }else{
                $resp = ["status" => "failed", "error" => "There's an unknown error while saving the data."];
            }
        }
        echo json_encode($resp);
        break;
    case 'login':
        $login = $func->login($_POST);
        if(isset($login['error'])){
            $resp = ["status" => "failed", "error" => $login['error']];
        }else{
            if($login){
                $resp = ["status" => "success"];
            }else{
                $resp = ["status" => "failed", "error" => "There's an unknown error while loggin in."];
            }
        }
        echo json_encode($resp);
        break;
    case 'save':
        $_POST['password'] = $func->encrypt_string($_POST['password']);
        if(empty($_POST['id'])){
            $insert = $db->insert($_POST);
            if($insert){
                $resp = ["status" => "success"];
                $_SESSION['success_msg'] = "New Record has been addedd successfully.";
            }else{
                $resp = ["status" => "failed", "error" => "There's an unknown error while loggin in."];
            }
        }else{
            $update = $db->update($_POST);
            if($update){
                $resp = ["status" => "success"];
                $_SESSION['success_msg'] = "Record has been updated successfully.";
            }else{
                $resp = ["status" => "failed", "error" => "There's an unknown error while loggin in."];
            }
        }
        echo json_encode($resp);
        break;
    case 'get_single':
        $get = $db->single_fetch($_GET['id']);
        if($get){
            $get['password'] = $func->decrypt_string($get['password']);
            $resp = ["status" => "success", "data" => $get];
        }else{
            $resp = ["status" => "failed", "error" => "There's an unknown error while loggin in."];
        }
        echo json_encode($resp);
        break;
    case 'delete_record':
        $delete_data = $db->delete($_GET['id']);
        if($delete_data){
            $_SESSION['success_msg'] = "Record has been deleted successfully.";
            echo "<script> location.replace('index.php');</script>";
            exit;
        }
        break;
    case 'get_real_password':
        $pass = $_POST['password_encrypted'];
        echo json_encode(['status' => 'success', 'password_decrypt' => $func->decrypt_string($pass)]);
        break;
}
