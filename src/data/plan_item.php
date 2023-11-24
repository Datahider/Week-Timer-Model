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
class plan_item extends DBObject {
    
    const METADATA = [
        'id'    => 'BIGINT(20) NOT NULL AUTO_INCREMENT',
        'user'  => 'BIGINT(20) NOT NULL',
        'title' => 'VARCHAR(300) NOT NULL',
        'icon'  => 'VARCHAR(5)',                // Иконка для изображения на кнопке и в тексте
//        'has_bell' => 'TINYINT(1) NOT NULL',    // Выдавать сигнал для окончания
//        'bell_after'  => 'BIGINT(20)',          // Время в секундах, через сколько выдать сигнал
        'is_archived' => 'TINYINT(1) NOT NULL',
//        'is_system' => 'TINYINT(1) NOT NULL',
        'type' => 'ENUM("lost", "plan", "sleep", "work", "reserve")',
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
}
