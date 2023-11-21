<?php

namespace losthost\WeekTimerModel;
use losthost\DB\DB;
use losthost\WeekTimerModel\data\week;
use losthost\DB\DBValue;
use losthost\DB\DBEvent;

use losthost\WeekTimerModel\data\plan;
use losthost\WeekTimerModel\data\plan_item;
use losthost\WeekTimerModel\data\timer_event;
use losthost\WeekTimerModel\data\user;

use losthost\DB\DBView;

use Exception;

/**
 * Удобные штуки для работы с данными
 * чтобы не надо было помнить порядок и названия аргументов
 *
 * @author drweb_000
 */
class Model {
    
    static protected Model $model;

    static public function init($db_host, $db_user, $db_pass, $db_name, $db_prefix) : Model {
        if (isset(self::$model)) {
            return self::$model;
        }
        
        DB::connect($db_host, $db_user, $db_pass, $db_name, $db_prefix);

        plan::initDataStructure();
        plan_item::initDataStructure();
        timer_event::initDataStructure();
        user::initDataStructure();
        
        self::$model = new Model('DGzWG_n57QMbKT');
        return self::$model;
    }
    
    static public function get() : Model {
        return self::$model;
    }
    
    public function __construct($secret="Use Model::init to get an instance of Model.") {
        if ($secret != 'DGzWG_n57QMbKT') {
            throw new Exception("Use Model::init to get an instance of Model.");
        }
    }
    
    /**
     * User
     */
    public function userCreate(?string $name=null, ?int $telegram_id=null) {
        $user = new user(['id' => null, 'name' => $name, 'telegram_id' => $telegram_id], true);
        $user->write();
        return $user;
    }
    
    /**
     * Timer
     */
    public function timerStartExistent(int $plan_item_id) {
        $timer = new timer_event(['id' => null, 'plan_item' => $plan_item_id], true);
        $timer->write();
        return $timer;
    }
    
    public function timerStartNew(int $user_id, string $title) {
        $plan_item = new plan_item(['id' => null, 'user' => $user_id, 'title' => $title], true);
        $plan_item->write();
        $timer = new timer_event(['id' => null, 'plan_item' => $plan_item->id], true);
        $timer->write();
        return $timer;
    }
    
    public function timerChangeStartTime(int $event_id, int $minutes) {
        $timer = new timer_event(['id' => $event_id]);
        
        $sql = <<<END
                SELECT te.id 
                FROM [timer_event] AS te 
                    INNER JOIN [plan_item] AS pi ON te.plan_item = pi.id
                WHERE 
                    pi.user = (SELECT user FROM [plan_item] WHERE id = ?)
                    AND te.end_time = ?
                END;
        
        $view = new DBView($sql, [$timer->plan_item, $timer->start_time]);
        
        $interval = date_interval_create_from_date_string("$minutes min");
        
        DB::beginTransaction();
        $timer->start_time = $timer->start_time->add($interval);
        $timer->write();
        if ($view->next()) {
            $timer_before = new timer_event(['id' => $view->id]);
            $timer_before->end_time = $timer->start_time;
            $timer_before->write();
        }
        DB::commit();
    }
}
