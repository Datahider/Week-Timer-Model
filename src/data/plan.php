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
        'PRIMARY KEY'   => 'id',
        'INDEX USER'    => 'user'
    ];
    
}
