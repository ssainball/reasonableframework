<?php
/**
 * @file gnuboard.php
 * @date 2018-04-11
 * @author Go Namhyeon <gnh1201@gmail.com>
 * @brief Database Helper for Gnuboard 4, Gnuboard 5
 */

// get database prefix
if(!function_exists("gnb_get_db_prefix")) {
    function gnb_get_db_prefix($version=4) {
        return ($version > 4) ? "g5_" : "g4_";
    }
}

// get write table
if(!function_exists("gnb_get_write_table")) {
    function gnb_get_write_table($tablename, $version=4) {
        $write_prefix = gnb_get_db_prefix() . "write_";
        $write_table = $write_prefix . $tablename;
        return $write_table;
    }
}

// get write next
if(!function_exists("gnb_get_write_next")) {
    function gnb_get_write_next($tablename) {
        $row = exec_db_fetch("select min(wr_num) as min_wr_num from " . gnb_get_write_table($tablename));
        return (int)($row['min_wr_num'] - 1);
    }
}

// write post
if(!function_exists("gnb_write_post")) {
    function gnb_write_post($tablename, $data=array(), $version=4) {
        $result = false;
        
        $config = get_config();
        $mb_id = get_current_user_name();

        // load network helper
        loadHelper("networktool");

        $write_fields = array();
        $write_default_fields = array(
            "mb_id" => $mb_id,
            "wr_num" => gnb_get_write_next($tablename),
            "wr_reply" => "",
            "wr_parent" => "",
            "wr_comment_reply" => "",
            "ca_name" => "",
            "wr_option" => "",
            "wr_subject" => make_random_id(),
            "wr_content" => make_random_id(),
            "wr_link1" => "",
            "wr_link2" => "",
            "wr_link1_hit" => 0,
            "wr_link2_hit" => 0,
            "wr_trackback" => "",
            "wr_hit" => 0,
            "wr_good" => 0,
            "wr_nogood" => 0,
            "wr_password" => gnb_get_password(make_random_id()),
            "wr_name" => get_generated_name(),
            "wr_email" => "",
            "wr_homepage" => "",
            "wr_datetime" => get_current_datetime(),
            "wr_last" => get_current_datetime(),
            "wr_ip" => get_network_client_addr(),
            "wr_1" => "",
            "wr_2" => "",
            "wr_3" => "",
            "wr_4" => "",
            "wr_5" => "",
            "wr_6" => "",
            "wr_7" => "",
            "wr_8" => "",
            "wr_9" => "",
            "wr_10" => "",
        );

        foreach($write_default_fields as $k=>$v) {
            $write_fields[$k] = array_key_empty($k, $data) ? $v : $data[$k];
        }

        $write_keys = array_keys($write_fields);
        $write_table = gnb_get_write_table($tablename);

        // make SQL statements
        $sql = "";
        if(count($write_keys) > 0) {
            $sql .= "insert into " . $write_table . " (";
            $sql .= implode(", ", $write_keys); // key names
            $sql .= ") values (";
            $sql .= implode(", :", $write_keys); // bind key names
            $sql .= ")";

            $result = exec_db_query($sql, $write_fields);
        }

        return $result;
    }
}

if(!function_exists("gnb_set_post_parameters")) {
    function gnb_set_post_parameters($tablename, $wr_id, $bind=array()) {
        $flag = false;
        $excludes = array("wr_id");

        $write_table = gnb_get_write_table($tablename);
        $bind['wr_id'] = get_value_in_array("wr_id", $bind, $wr_id);

        $sql = "update " . $write_table . " set " . get_bind_to_sql_update_set($bind, $excludes) . " where wr_id = :wr_id";
        $flag = exec_db_query($sql, $bind);

        return $flag;
    }
}

// get member data
if(!function_exists("gnb_get_member")) {
    function gnb_get_member($mb_id, $tablename="member") {
        $result = array();

        $bind = array(
            "mb_id" => $mb_id,
        );

        $member_table = gnb_get_db_prefix() . $tablename;
        $result = exec_db_fetch("select * from " . $member_table . " where mb_id = :mb_id", $bind);

        return $result;
    }
}

// get password
if(!function_exists("gnb_get_password")) {
    function gnb_get_password($password) {
        $bind = array(
            "password" => $password,
        );
        $row = exec_db_fetch("select password(:password) as pass", $bind);
        return $row['pass'];
    }
}

// get config
if(!function_exists("gnb_get_config")) {
    function gnb_get_config($tablename="config") {
        $result = array();

        $config_table = gnb_get_db_prefix() . $tablename;
        $result = exec_db_fetch("select * from " . $config_table);

        return $result;
    }
}

// run login process
if(!function_exists("gnb_process_safe_login")) {
    function gnb_process_safe_login($user_name, $user_password) {
        $result = false;
        $mb = gnb_get_member($user_name);

        if(!array_key_empty("mb_id", $mb)) {
            $user_profile = array(
                "user_id" => $mb['mb_no'],
                "user_password" => get_password(gnb_get_password($mb['mb_password'])),
            );
            $result = process_safe_login($mb['mb_id'], gnb_get_password($mb['mb_password']), $user_profile);
        }
        
        return $result;
    }
}