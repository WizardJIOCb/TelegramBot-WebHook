<?php
    
    class Command {
        
        function __construct() {
            if (DEBUG) echo(get_called_class()."-created"."\r\n");
        }
        
        function __destruct() {
            if (DEBUG) echo(get_called_class()."-destroyed"."\r\n");
        }
            
        
    }
    
?>