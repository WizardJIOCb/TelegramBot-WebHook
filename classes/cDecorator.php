<?php
    
    class cDecorator {
        
        private $class;
        
        function __construct($cl) {
            if (DEBUG) echo(get_called_class()."-created"."\r\n");
            $this->class = $cl;
        }
        
        function __destruct() {
            if (DEBUG) echo(get_called_class()."-destroyed"."\r\n");
        }
        
         public function __call($method, $args){            
            if (DEBUG) echo($method."-call-begin"."\r\n");
            $res = call_user_func_array(array($this->class, $method), $args);
            if (DEBUG) echo($method."-call-end"."\r\n");
            return $res;
        }
            
        
    }
    
?>