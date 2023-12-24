<?php

namespace losthost\WeekTimerModel;
use losthost\DB\DB;
use losthost\WeekTimerModel\data\week;
use losthost\DB\DBValue;
use losthost\DB\DBEvent;
use losthost\DB\DBList;
use losthost\WeekTimerModel\data\param;

use losthost\WeekTimerModel\data\plan;
use losthost\WeekTimerModel\data\plan_item;
use losthost\WeekTimerModel\data\plan_entry;
use losthost\WeekTimerModel\data\timer_event;
use losthost\WeekTimerModel\data\user;
use losthost\WeekTimerModel\data\time_zone;

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

    static public function init(string $db_host='', string $db_user='', string $db_pass='', string $db_name='', string $db_prefix='') : Model {
        if (isset(self::$model)) {
            return self::$model;
        }
        
        if ($db_host. $db_user. $db_pass. $db_name. $db_prefix != '') {
            DB::connect($db_host, $db_user, $db_pass, $db_name, $db_prefix);
        }

        plan::initDataStructure();
        plan_item::initDataStructure();
        plan_entry::initDataStructure();
        timer_event::initDataStructure();
        user::initDataStructure();
        time_zone::initDataStructure();
        
        self::$model = new Model('DGzWG_n57QMbKT');
        
        static::$model->update();
        
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
    
    protected function update() {
        $version = $this->getParam('db_version', '000000000');
        
        if ($version < '202312231') {
            DB::beginTransaction();
            DB::exec('UPDATE [user] SET restarted = registered WHERE restarted < registered');
            $version = '202312231';
            static::$model->setParam('db_version', $version);
            DB::commit();
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
    
    public function timerStartNew(int $user_id, string $title, string $icon) {
        $plan_item = new plan_item(['id' => null, 'user' => $user_id, 'title' => $title, 'icon' => $icon], true);
        $plan_item->write();
        $timer = new timer_event(['id' => null, 'plan_item' => $plan_item->id], true);
        $timer->write();
        return $timer;
    }
    
    public function timerChangeStartTime(int $event_id, int $minutes) {
        $timer = new timer_event(['id' => $event_id]);
        $old_start_time = $timer->start_time;
        
        $interval = date_interval_create_from_date_string("$minutes min");
        
        DB::beginTransaction();
        $timer->start_time = $timer->start_time->add($interval);
        $timer->write();
        
        $modify = new DBList(timer_event::class, 'id <> ? AND end_time >= ? AND plan_item IN (SELECT id FROM [plan_item] WHERE user = (SELECT user FROM [plan_item] WHERE id = ?))', [$timer->id, min($timer->start_time,$old_start_time), $timer->plan_item]);
        while ($event = $modify->next()) {
            if ($event->start_time >= $timer->start_time) {
                $event->delete();
            } else {
                $event->end_time = $timer->start_time;
                $event->write();
            }
        }
        DB::commit();
    }
    
    public function timerGetActive(int $user_id) : timer_event {
        
        $found = new DBView(<<<END
                SELECT 
                    t_events.id AS id
                FROM 
                    [timer_event] AS t_events 
                    INNER JOIN [plan_item] AS t_items
                        ON t_items.id = t_events.plan_item
                WHERE
                    t_events.end_time IS NULL
                    AND t_items.user = ?
                END, 
                $user_id
        );
        
        if ($found->next()) {
            return new timer_event(['id' => $found->id]);
        }
        return false;
    }
    
    public function planItemGetNeighbors($plan_item_id) {
        
        $item = new plan_item(['id' => $plan_item_id]);
        
        $data = new DBView("SELECT id, sort_order FROM [plan_item] WHERE user = :user_id AND (sort_order = :sort_order - 1 OR sort_order = :sort_order + 1) ORDER BY sort_order", [
            'user_id' => $item->user,
            'sort_order' => $item->sort_order
        ]);

        $neighbors = [];
        while ($data->next()) {
            if ($data->sort_order > $item->sort_order) {
                $neighbors['next'] = new plan_item(['id' => $data->id]);
            } else {
                $neighbors['prev'] = new plan_item(['id' => $data->id]);
            }
        }
        
        return $neighbors;
    }
    
    public function getParam(string $name, string $default) : string {
        $param = new param(['name' => $name], true);
        if ($param->isNew()) {
            $param->value = $default;
            $param->write();
        }
        return $param->value;
    }
    
    public function setParam(string $name, string $value) {
        $param = new param(['name' => $name], true);
        $param->value = $value;
        $param->write();
    }
}
