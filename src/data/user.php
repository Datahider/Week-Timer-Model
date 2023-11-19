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

}
