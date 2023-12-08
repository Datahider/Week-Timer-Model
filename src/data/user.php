<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace losthost\WeekTimerModel\data;
use losthost\DB\DBObject;
use losthost\DB\DB;
use DateTimeZone;
use DateTimeImmutable;

/**
 * Description of user
 *
 * @author drweb_000
 */
class user extends DBObject {
    
    protected string $__timezone_name;
    protected DateTimeZone $__timezone;
    
    const METADATA = [
        'id'    => 'BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT',
        'name'  => 'VARCHAR(50) NOT NULL',
        'telegram_id'   => 'BIGINT(20) UNSIGNED',
        'week_start'    => 'ENUM("mon", "sun") NOT NULL',
        'registered'    => 'DATETIME NOT NULL DEFAULT "1111-11-11"',
        'time_zone'     => 'INT(11) NOT NULL',
        'pending_time_zone' => 'INT(11)',
        'start_shown' => 'TINYINT(1) NOT NULL DEFAULT 0',
        'report_show_titles' => 'TINYINT(1) NOT NULL DEFAULT 1',
        'active_plan' => 'BIGINT(20)',
        'active_message' => 'BIGINT(20)', // Идентификатор активного отчета (где надо тыкать по иконкам)
        'info_message' => 'BIGINT(20)',   // Идентификатор сообщения с дополнительной информацией/ошибками
        'PRIMARY KEY'   => 'id',
        'UNIQUE INDEX NAME'    => 'name',
        'UNIQUE INDEX TELEGRAM_ID'    => 'telegram_id',
    ];

    public function getTimezoneName() : string {
        $tz = new time_zone(['id' => $this->time_zone]);
        return $tz->name;
    }
    
    public function getTimezone() : DateTimeZone {
        return new DateTimeZone($this->getTimezoneName());
    }
    
    public function getWeekStart(?DateTimeImmutable $datetime=null) {
        if ($datetime == null) {
            $datetime = date_create_immutable('now', $this->getTimezone());
        }
        
        if ($this->week_start == 'mon' && $datetime->format('w') == 1 || $this->week_start == 'sun' && $datetime->format('w') == 0) {
            $first_day = 'today';
        } elseif ($this->week_start == 'mon') {
            $first_day = 'last monday';
        } else {
            $first_day = 'last sunday';
        }

        return $datetime->modify($first_day);
    }
    
    protected function toDateTime($value) {
        if ($value === null) {
            return null;
        }
        $result = new \DateTimeImmutable($value);
        return $result->setTimezone($this->getTimezone());
    }
    
    protected function formatDateTime($value) {
        if (is_a($value, '\DateTime') || is_a($value, '\DateTimeImmutable')) {
            return $value->setTimezone(new DateTimeZone('GMT'))->format(DB::DATE_FORMAT);
        }
        
        throw new \Exception("The value must be of type DateTime or DateTimeImmutable.", -10003);
    }
    
    protected function beforeInsert($comment, $data) {
        parent::beforeInsert($comment, $data);
        if (!isset($this->__data['name'])) {
            $this->__data['name'] = 'tmp';
        }
        if (!isset($this->__data['week_start'])) {
            $this->__data['week_start'] = 'mon';
        }
        if (!isset($this->__data['time_zone'])) {
            $this->__data['time_zone'] = 300;
        }
        if (!isset($this->__data['start_shown'])) {
            $this->__data['start_shown'] = 0;
        }
        if (!isset($this->__data['registered'])) {
            $this->__data['registered'] = date_create()->format(DB::DATE_FORMAT);
        }
        if (!isset($this->__data['report_show_titles'])) {
            $this->__data['report_show_titles'] = 1;
        }
    }
    
    protected function intranInsert($comment, $data) {
        
        parent::intranInsert($comment, $data);

        $lost_time = new plan_item(['id' => null, 'user' => $this->id, 'title' => 'Lost', 'icon' => '🗑', 'type' => 'lost', 'sort_order' => 2000000000], true);
        $lost_time->write();
        
        $sleeping = new plan_item(['id' => null, 'user' => $this->id, 'title' => 'Sleep', 'icon' => '🛌', 'type' => 'sleep'], true);
        $sleeping->write();
        
        $working = new plan_item(['id' => null, 'user' => $this->id, 'title' => 'Work', 'icon' => '🛠', 'type' => 'work'], true);
        $working->write();
        
        $planning = new plan_item(['id' => null, 'user' => $this->id, 'title' => 'Plan', 'icon' => '🗓', 'type' => 'plan'], true);
        $planning->write();
        
        $rest = new plan_item(['id' => null, 'user' => $this->id, 'title' => 'Rest', 'icon' => '🧘', 'type' => 'rest'], true);
        $rest->write();
        
        $timer = new timer_event(['id' => null, 'plan_item' => $planning->id], true);
        $timer->write();
        
        $plan = new plan(['id' => null, 'user' => $this->id, 'title' => 'My plan'], true);
        $plan->write();
        
        (new plan_entry(['id' => null, 'plan' => $plan->id, 'plan_item' => $lost_time->id, 'time_planned' => 0, 'sort_order' => 2000000000], true))->write();
        (new plan_entry(['id' => null, 'plan' => $plan->id, 'plan_item' => $sleeping->id, 'time_planned' => 201600], true))->write();
        (new plan_entry(['id' => null, 'plan' => $plan->id, 'plan_item' => $working->id, 'time_planned' => 144000], true))->write();
        (new plan_entry(['id' => null, 'plan' => $plan->id, 'plan_item' => $planning->id], true))->write();
        (new plan_entry(['id' => null, 'plan' => $plan->id, 'plan_item' => $rest->id], true))->write();
        
        $this->__data['name'] = 'u'. $this->id;
        $this->__data['active_plan'] = $plan->id;
        $this->update($comment, $data);
        
    }
}
