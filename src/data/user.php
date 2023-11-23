<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace losthost\WeekTimerModel\data;
use losthost\DB\DBObject;
use losthost\DB\DB;
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
        'registered'    => 'DATETIME NOT NULL DEFAULT "1111-11-11"',
        'time_zone'     => 'INT(11) NOT NULL',
        'pending_time_zone' => 'INT(11)',
        'start_shown' => 'TINYINT(1) NOT NULL DEFAULT 0',
        'PRIMARY KEY'   => 'id',
        'UNIQUE INDEX NAME'    => 'name',
        'UNIQUE INDEX TELEGRAM_ID'    => 'telegram_id',
    ];

    protected function beforeInsert($comment, $data) {
        parent::beforeInsert($comment, $data);
        if (!isset($this->__data['name'])) {
            $this->__data['name'] = 'tmp';
        }
        if (!isset($this->__data['week_start'])) {
            $this->__data['week_start'] = 'mon';
        }
        if (!isset($this->__data['time_zone'])) {
            $this->__data['time_zone'] = 300;
        }
        if (!isset($this->__data['start_shown'])) {
            $this->__data['start_shown'] = 0;
        }
        if (!isset($this->__data['registered'])) {
            $this->__data['registered'] = date_create()->format(DB::DATE_FORMAT);
        }
    }
    
    protected function intranInsert($comment, $data) {
        
        parent::intranInsert($comment, $data);

        $lost_time = new plan_item(['id' => null, 'user' => $this->id, 'title' => 'Lost', 'is_system' => true], true);
        $lost_time->write();
        
        $planning = new plan_item(['id' => null, 'user' => $this->id, 'title' => 'Plan', 'is_system' => true], true);
        $planning->write();
        
        $sleeping = new plan_item(['id' => null, 'user' => $this->id, 'title' => 'Sleep', 'is_system' => true], true);
        $sleeping->write();
        
        $sleeping = new plan_item(['id' => null, 'user' => $this->id, 'title' => 'Work', 'is_system' => true], true);
        $sleeping->write();
        
        $reserved = new plan_item(['id' => null, 'user' => $this->id, 'title' => 'Reserve', 'is_system' => true], true);
        $reserved->write();
        
        $timer = new timer_event(['id' => null, 'plan_item' => $planning->id], true);
        $timer->write();
        
        $this->__data['name'] = 'u'. $this->id;
        $this->update($comment, $data);
        
    }
}
