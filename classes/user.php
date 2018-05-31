<?php
    
    // Класс юзера
    class User {
                    
        public $fields = array();
        
        function __construct($id, $my = false) {
            
            global $db;
            $id = (int)$id;
            
            // Если получаем своего юзера то проверяем не нужно ли его создать            
            if ($my){       
                global $input;                           
                $res = $db->Query("SELECT id FROM user WHERE telegram_id = ".$id);
                $row = @mysql_fetch_array($res, MYSQL_ASSOC);
                if (!$row){
                    $db->Query("INSERT INTO user (`telegram_id`, `data`) VALUES (".$id.", '{}')");
                }
                $db->Query("UPDATE user SET dt_last_login = ".time().", chat_id = ".(int)$input["message"]["chat"]["id"].", message_id = ".(int)$input["message"]["message_id"]." WHERE telegram_id = ".$id);
            }
            
            // Получаем юзера            
            $res = $db->Query("SELECT * FROM user WHERE telegram_id = ".$id);
            $this->fields = @mysql_fetch_array($res, MYSQL_ASSOC);
            
        }
        
        function __destruct() {
            
        }
        
    }
    
?>