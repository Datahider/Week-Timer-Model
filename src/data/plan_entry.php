<?php

namespace losthost\WeekTimerModel\data;
use losthost\DB\DBObject;
use losthost\DB\DBView;

class plan_entry extends DBObject {
    
    const METADATA = [
        'id' => 'BIGINT(20) NOT NULL AUTO_INCREMENT',
        'plan' => 'BIGINT(20) NOT NULL',
        'plan_item' => 'BIGINT(20) NOT NULL',
        'time_planned' => 'BIGINT(20) NOT NULL',
        'sort_order' => 'INT(11)',
        'PRIMARY KEY' => 'id',
        'UNIQUE INDEX PLAN_ITEM_PLAN' => ['plan', 'plan_item'],
    ];
    
    protected function beforeInsert($comment, $data) {
        parent::beforeInsert($comment, $data);

        if (!isset($this->__data['sort_order'])) {
            $sql = 'SELECT MAX(sort_order)+1 as num FROM '. $this->tableName(). ' WHERE plan = ? AND sort_order < 2000000000';
            $new_sort_order = new DBView($sql, [$this->plan]);
            if ($new_sort_order->next() && !is_null($new_sort_order->num)) {
                $this->__data['sort_order'] = $new_sort_order->num;
            } else {
                $this->__data['sort_order'] = 1;
            }
        }
        
        if (!isset($this->__data['time_planned'])) {
            $this->__data['time_planned'] = 0;
        }
    }
}
