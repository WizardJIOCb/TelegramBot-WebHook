<?php
    
    class Command_menu extends Command {
        
        public function execute($data){
                        
            $chat_id = $data["message"]["chat"]["id"];            
            global $user;
            
            apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => "Меню", 'reply_markup' => array(
                    'keyboard' => array(array('/help'),array('/update'),array('/start')),
                    'one_time_keyboard' => true,
                    'resize_keyboard' => true
            )));
            
        }
        
    }
    
?>