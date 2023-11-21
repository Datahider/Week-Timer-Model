<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace losthost\WeekTimerModel\data;
use losthost\DB\DBObject;
/**
 * Description of plan_item
 *
 * @author drweb_000
 */
class plan extends DBObject {
    
    const METADATA = [
        'id'    => 'BIGINT(20) NOT NULL AUTO_INCREMENT',
        'user'  => 'BIGINT(20) NOT NULL',
        'title' => 'VARCHAR(300) NOT NULL',
        'start' => 'DATETIME NOT NULL',
        'end'   => 'DATETIME NOT NULL',
        'plan_item' => 'BIGINT(20) NOT NULL',
        'time_planned' => 'BIGINT(20) NOT NULL',
        'PRIMARY KEY'   => 'id',
        'INDEX USER'    => 'user',
        'INDEX START_END' => ['start', 'end']
    ];
    
    public function __construct($data = [], $create = false) {
        parent::__construct($data, $create);
        
        if ($this->isNew()) {
            if (!isset($this->__data['time_planned'])) {
                $this->time_planned = 0;
            }
        }
    }
}
