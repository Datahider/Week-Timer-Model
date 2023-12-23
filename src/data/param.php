<?php

namespace losthost\WeekTimerModel\data;
use losthost\WeekTimerModel\data\param;
use losthost\DB\DBObject;


class param extends DBObject {
    
    const METADATA = [
        'name' => 'VARCHAR(50) NOT NULL',
        'value' => 'VARCHAR(1024)',
        'PRIMARY KEY' => 'name'
    ];
}
