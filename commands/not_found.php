<?php
    
    class Command_not_found extends Command {
        
        public function execute($data){        
            
            $chat_id = $data["message"]["chat"]["id"];
            apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => "Команда не найдена"));  
            
        }
        
    }
    
?>