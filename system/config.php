<?php
if(!function_exists("get_config")) {
    function get_config() {
        $config = array();
        if($handle = opendir('./config')) {
            while (false !== ($file = readdir($handle))) {
                if ($file != "." && $file != ".." && end(explode('.', $file)) == 'ini') {
                    $ini = parse_ini_file('./config/' . $file);
                    foreach($ini as $k=>$v) {
                        $config[$k] = $v;
                    }
                }
            }
            closedir($handle);
        }
        
        return $config;
    }
}

$config = get_config();
