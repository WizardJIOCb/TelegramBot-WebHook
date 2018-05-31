<?php
    
    class Command_update extends Command {
        
        public function execute($data){
                        
            $chat_id = $data["message"]["chat"]["id"];            
            global $user;
            
            apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => "<b>Приветсвуем вас в WORPG!</b>", "parse_mode" => "HTML"));  
            sleep(4);
            apiRequest("editMessageText", array('chat_id' => $chat_id, "message_id" => ($data["message"]["message_id"]+1), "text" => "<b>---------------------------------</b>", "parse_mode" => "HTML"));  
            
        }
        
    }
    
?>