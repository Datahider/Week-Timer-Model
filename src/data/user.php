<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace losthost\WeekTimerModel\data;
use losthost\DB\DBObject;
/**
 * Description of user
 *
 * @author drweb_000
 */
class user extends DBObject {
    
    const METADATA = [
        'id'    => 'BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT',
        'name'  => 'VARCHAR(50) NOT NULL',
        'telegram_id'   => 'BIGINT(20) UNSIGNED',
        'week_start'    => 'ENUM("mon", "sun") NOT NULL',
        'PRIMARY KEY'   => 'id',
        'UNIQUE INDEX NAME'    => 'name',
        'UNIQUE INDEX TELEGRAM_ID'    => 'telegram_id',
    ];

    protected function beforeInsert($comment, $data) {
        parent::beforeInsert($comment, $data);
        $this->__data['name'] = 'tmp';
        $this->__data['week_start'] = 'mon';
    }
    
    protected function intranInsert($comment, $data) {
        
        parent::intranInsert($comment, $data);

        $lost_time = new plan_item(['id' => null, 'user' => $this->id, 'title' => 'Lost Time', 'is_archived' => false, 'is_system' => true], true);
        $lost_time->write();
        
        $planning = new plan_item(['id' => null, 'user' => $this->id, 'title' => 'Planning', 'is_archived' => false, 'is_system' => true], true);
        $planning->write();
        
        $sleeping = new plan_item(['id' => null, 'user' => $this->id, 'title' => 'Sleeping', 'is_archived' => false, 'is_system' => true], true);
        $sleeping->write();
        
        $reserved = new plan_item(['id' => null, 'user' => $this->id, 'title' => 'Reserved', 'is_archived' => false, 'is_system' => true], true);
        $reserved->write();
        
        $this->__data['name'] = 'u'. $this->id;
        $this->update($comment, $data);
        
    }
}
