<?php
    
        
    // Telegram API
    include "classes/telegram-core.php";        
    
    // Отладка
    define('DEBUG', true);  
    
    // Проверяем токен
    if ($_REQUEST["token"] != BOT_TOKEN){
        file_put_contents(__DIR__."/bot.log", "Wrong telegram token\r\n");       
        return;
    }

    // Логирование (начало)
    ob_start("ob_done");                 
    function ob_done($data){        
        file_put_contents(__DIR__."/bot.log", $data."\r\n");       
        return $data;
    }

    // Для вывода ошибок
    try{                
        
        // Прототип команды и декоратора
        include "commands/command.php";                
        include "classes/cDecorator.php";                
        
        // Обработка сообщения    
        function processMessage($data) {
            
            // Делаем данные глобальными
            global $input;
            $input = $data;
            
            // Основные данные
            $mData = $data["message"];
            $message_id = $mData['message_id'];
            $chat_id = $mData['chat']['id'];
            $message = $mData['text'];
            
            // Создаём класс юзера и базу
            include(__DIR__."/classes/db.php");
            include(__DIR__."/classes/user.php");
            global $user, $db;
            $db = new DB();
            $user = new User((int)$data["message"]["from"]["id"], true);                    
              
            // Если первый слэш то это команда
            if ($message[0] == "/"){
              
                // Обрезаем слэш
                $message = substr($message, 1);

                // Пробуем выполнить команду
                if (DEBUG) echo("Command: ".$message."\r\n");
                if (file_exists(__DIR__."/commands/".$message.".php")){                                                                                                                    

                    // Создаём и выполняем команду
                    createCommandWD($message)->execute($data);
                    
                    // Отлака
                    if (DEBUG) echo($message."-done\r\n");
                    
                // Команда не найдена
                }else{
                    createCommandWD("not_found")->execute($data);
                }
            
            // Пришёл пустой текст
            }else{                
                createCommandWD("raw_text")->execute($data);
            }
        }
        
        // Пробуем обработать сообщение
        $input = json_decode(file_get_contents("php://input"), true);                
        processMessage($input);     
    
    // Если ошибка то дампим её
    }catch(Exception $e){
        if (DEBUG) echo(print_r($e, true));
    }
    
    // Завершаем логирование
    ob_end_flush();
?>