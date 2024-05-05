<?php

namespace losthost\WeekTimerModel\data;
use losthost\DB\DBObject;
use losthost\DB\DBView;

/**
 * Description of plan_item
 *
 * @author drweb_000
 */
class plan_item extends DBObject {
    
    const METADATA = [
        'id'    => 'BIGINT(20) NOT NULL AUTO_INCREMENT',
        'user'  => 'BIGINT(20) NOT NULL',
        'title' => 'VARCHAR(300) NOT NULL',
        'icon'  => 'VARCHAR(5)',                // Иконка для изображения на кнопке и в тексте
//        'has_bell' => 'TINYINT(1) NOT NULL',    // Выдавать сигнал для окончания
        'bell_after'  => 'BIGINT(20)',          // Время в секундах, через сколько выдать сигнал
        'is_archived' => 'TINYINT(1) NOT NULL',
//        'is_system' => 'TINYINT(1) NOT NULL',
        'type' => 'ENUM("lost", "plan", "reserve", "rest", "sleep", "work")',
        'sort_order' => 'INT NOT NULL DEFAULT 1',
        'PRIMARY KEY'   => 'id',
        'INDEX USER'    => 'user'
    ];
    
    public function __construct($data = [], $create = false) {
        parent::__construct($data, $create);
        
        if ($this->isNew()) {
            if (!isset($this->__data['is_archived'])) {
                $this->is_archived = false;
            }
        }
    }
    
    protected function beforeInsert($comment, $data) {
        parent::beforeInsert($comment, $data);

        if (!isset($this->__data['sort_order'])) {
            $sql = 'SELECT MAX(sort_order)+1 as num FROM '. $this->tableName(). ' WHERE user = ? AND sort_order < 2000000000';
            $new_sort_order = new DBView($sql, [$this->user]);
            if ($new_sort_order->next() && !is_null($new_sort_order->num)) {
                $this->__data['sort_order'] = $new_sort_order->num;
            } else {
                $this->__data['sort_order'] = 1;
            }
        }
    }
}
