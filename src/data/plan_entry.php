<?php

namespace losthost\WeekTimerModel\data;
use losthost\DB\DBObject;

class plan_entry extends DBObject {
    
    const METADATA = [
        'id' => 'BIGINT(20) NOT NULL AUTO_INCREMENT',
        'plan' => 'BIGINT(20) NOT NULL',
        'plan_item' => 'BIGINT(20) NOT NULL',
        'time_planned' => 'BIGINT(20) NOT NULL',
        'PRIMARY KEY' => 'id',
        'INDEX PLAN' => 'plan',
    ];
}
