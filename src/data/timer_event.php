<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace losthost\WeekTimerModel\data;
use losthost\DB\DBObject;
use losthost\DB\DBView;
/**
 * Description of timer_event
 *
 * @author drweb_000
 */
class timer_event extends DBObject {

    const METADATA = [
        'id'         => 'BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT',
        'plan_item'  => 'BIGINT(20) UNSIGNED',
        'comment'    => 'VARCHAR(300)',
        'start_time' => 'DATETIME NOT NULL',
        'end_time'   => 'DATETIME',
        'duration'   => 'BIGINT(20)',
        'PRIMARY KEY'               => 'id',
        'INDEX PLAN_ITEM'           => 'plan_item',
        'INDEX PLAN_ITEM_START_END' => ['plan_item', 'start_time', 'end_time']
    ];
    
    public function __construct($data = [], $create = false) {
        parent::__construct($data, $create);
        if ($this->isNew()) {
            // set defaults
            if (empty($this->__data['start_time'])) {
                $this->start_time = new \DateTime();
            }
        }
    }

    protected function intranInsert($comment, $data) {
        
        $current_plan_item = new plan_item(['id' => $this->plan_item]);
        $started_id = new DBView(<<<END
                SELECT e.id 
                FROM [timer_event] AS e 
                    INNER JOIN [plan_item] AS i 
                    ON e.plan_item = i.id 
                        AND i.user = ? 
                        AND e.id <> ?
                        AND e.duration IS NULL
                END, [$current_plan_item->user, $this->id]);
        
        if ($started_id->next()) {
            $started = new timer_event(['id' => $started_id->id]);
            $started->end_time = $this->start_time;
            $started->write();
        }

        parent::intranInsert($comment, $data);
        
    }
    
    protected function beforeInsert($comment, $data) {
        if ($this->end_time) {
            $this->duration = $this->end_time->getTimestamp() - $this->start_time->getTimestamp();
        } elseif ($this->duration) {
            $this->end_time = $this->start_time->add(date_interval_create_from_date_string($this->duration(' seconds')));
        }
        parent::beforeInsert($comment, $data);
    }
    
    protected function beforeUpdate($comment, $data) {
        if ($this->end_time) {
            $this->duration = $this->end_time->getTimestamp() - $this->start_time->getTimestamp();
        } elseif ($this->duration) {
            $this->end_time = $this->start_time->add(date_interval_create_from_date_string($this->duration(' seconds')));
        }    
        parent::beforeUpdate($comment, $data);
    }
    
}
