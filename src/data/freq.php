<?php

namespace losthost\WeekTimerModel\data;

use losthost\DB\DBObject;

/**
 * Хранение частот выбора следующих plan_item
 */
class freq extends DBObject {
    
    const METADATA = [
        'id' => 'BIGINT(20) NOT_NULL AUTO_INCREMENT',
        'plan_item' => 'BIGINT(20) NOT NULL',
        'next' => 'BIGINT(20) NOT NULL',
        'freq' => 'DECIMAL(20,6) NOT NULL',
        'PRIMARY KEY' => 'id'
    ];
}
