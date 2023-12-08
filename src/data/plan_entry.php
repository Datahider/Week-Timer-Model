<?php

namespace losthost\WeekTimerModel\data;
use losthost\DB\DBObject;
use losthost\DB\DBView;
use losthost\DB\DBList;
use losthost\DB\DB;

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
    
    public function moveUp() {
        
        $upper_neighbor = $this->getUpperNeighbor();
        DB::beginTransaction();

        if ( $upper_neighbor ) {
            $tmp = $this->sort_order;            
            $this->sort_order = $upper_neighbor->sort_order;            
            $upper_neighbor->sort_order = $tmp;

            $upper_neighbor->write();
            $this->write();
        } else {
            $the_rest = new DBList(static::class, 'plan = ? AND id <> ? AND sort_order < 2000000000 ORDER BY sort_order', [$this->plan, $this->id]);
            $last_sort_order = $this->sort_order;
            while ($entry = $the_rest->next()) {
                $tmp = $entry->sort_order;
                $entry->sort_order = $last_sort_order;
                $last_sort_order = $tmp;
                $entry->write();
            }
            $this->sort_order = $last_sort_order;
            $this->write();
        }
        DB::commit();
        
    }
    
    public function moveDown() {
    
        $lower_neighbor = $this->getLowerNeighbor();
//        DB::beginTransaction();

        if ( $lower_neighbor ) {
            $tmp = $this->sort_order;            
            $this->sort_order = $lower_neighbor->sort_order;            
            $lower_neighbor->sort_order = $tmp;
            
            $lower_neighbor->write();
            $this->write();
        } else {
            $the_rest = new DBList(static::class, 'plan = ? AND id <> ? AND sort_order < 2000000000 ORDER BY sort_order DESC', [$this->plan, $this->id]);
            $last_sort_order = $this->sort_order;
            while ($entry = $the_rest->next()) {
                $tmp = $entry->sort_order;
                $entry->sort_order = $last_sort_order;
                $last_sort_order = $tmp;
                $entry->write();
            }
            $this->sort_order = $last_sort_order;
            $this->write();
        }
//        DB::commit();
        
    }
    
    protected function getUpperNeighbor() {
        $the_neighbor = new DBList(static::class, 'plan = ? AND sort_order < ? ORDER BY sort_order DESC LIMIT 1', [$this->plan, $this->sort_order]);
        return $the_neighbor->next();
    }
    
    protected function getLowerNeighbor() {
        $the_neighbor = new DBList(static::class, 'plan = ? AND sort_order > ? AND sort_order < 2000000000 ORDER BY sort_order LIMIT 1', [$this->plan, $this->sort_order]);
        return $the_neighbor->next();
    }
    
}
