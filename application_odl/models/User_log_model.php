<?php

defined("BASEPATH")OR exit("No direct script access allowed");
class upload_data_model extends CI_Model{
    public function log_user_access($user_id,$ip_address,$os,$browser){
        $data = array(
            "user_id"=> $user_id,
            "ip_address"=> $ip_address,
            "os"=> $os,
            "browser"=> $browser,
            "login_time"=>date("Y-m-d H:i:s"),
            );
            $this->db->insert("user_access_log",$data);
    }
}