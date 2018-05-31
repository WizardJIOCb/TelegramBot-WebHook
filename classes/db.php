<?php
    
    define("MYSQL_SERVER", "localhost");
    define("MYSQL_LOGIN", "*");
    define("MYSQL_PASSWORD", "*");
    define("MYSQL_DB", "*");
    
    class DB {
        
        // Коннектимся к базе
        private $link;
        function __construct() {
            
            $this->link = @mysql_connect(MYSQL_SERVER, MYSQL_LOGIN, MYSQL_PASSWORD);
            if (!$this->link) {
                echo('[MYSQL ERROR: ' . mysql_error()."]\r\n");
            }else{
                $r = mysql_select_db(MYSQL_DB);
                if (!$r) echo("[MYSQL ERROR: can't select DB]\r\n");            
                mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'", $this->link);    
            }
        }
        
        // Выполнить запрос
        public function Query($query){
                               
            $result = @mysql_query($query);    
            if (!$result) echo('[MYSQL ERROR: '.mysql_error()."]\r\n");
            return $result;
        }
        
        function __destruct() {
            
        }                
        
    }
    
?>