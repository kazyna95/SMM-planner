<?php

require 'vendor/autoload.php';

//while(true){

    $ig = new \InstagramAPI\Instagram(false, false, $storageConfig = []);
    
    $pdo = \App\Service\DB::get();
    $stmt = $pdo->prepare("
        SELECT
            `tasks`.*,
            `accounts`.`login`,
            `accounts`.`password`
        FROM
            `tasks`
        LEFT JOIN
            `accounts`
            ON
            `accounts`.`id` = `tasks`.`id_account`
        ");
    
    $stmt->execute();
    $results = $stmt->fetchAll();

    $now = new DateTime("now");
    //echo $now->format('Y.m.d H:i:s');
   
    $publishDate = new DateTime($results[0]['publish_date']);
    //echo $publishDate->format('Y.m.d H:i:s');
    $diff = $publishDate->diff($now);    
    
    echo $diff->format('Y.m.d H:i:s');
    die();

    
    foreach($results as $task){
        //$publishDate = $task['publish_date'] - $now;        
        
        if($task['status'] !== 1){
            $filename = './uploads/' . sha1($task['id']) . '.jpeg';
            $metadata = [
                'caption' => $task['description']
            ];
            $ig->login($task['login'], $task['password']);
            $ig->timeline->uploadPhoto($filename, $metadata);
    
            $task['status'] =  1;
    
        }
    }
//}




