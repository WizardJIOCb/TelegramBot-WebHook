<?php
    
    class Command_start extends Command {
        
        public function execute($data){
                        
            $chat_id = $data["message"]["chat"]["id"];                        
            global $user;            
            apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => "<b>Приветсвуем вас в WORPG!</b>", "parse_mode" => "HTML"));              
            
        }
        
    }
    
?>