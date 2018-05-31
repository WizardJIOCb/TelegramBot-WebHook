<?php
    
    class Command_help extends Command {            
        
        public function execute($data){        
            
            $chat_id = $data["message"]["chat"]["id"];
            apiRequest("sendMessage", array('chat_id' => $chat_id, "text" =>
                                            "Помошь по игре
/help
/update
/menu
/wiki [search]"
                                            ));  
            
        }
        
    }
    
?>