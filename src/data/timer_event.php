<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace losthost\WeekTimerModel\data;
use losthost\DB\DBObject;
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
}
