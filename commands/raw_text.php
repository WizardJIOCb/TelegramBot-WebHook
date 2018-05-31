<?php
    
    class Command_raw_text extends Command {
        
        public function execute($data){        
            
            $chat_id = $data["message"]["chat"]["id"];
            apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => "Обычный текст не принимается"));  
            
        }
        
    }
    
?>