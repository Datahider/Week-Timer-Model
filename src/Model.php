<?php

namespace losthost\WeekTimerModel;
use losthost\DB\DB;
use losthost\WeekTimerModel\data\week;
use losthost\DB\DBValue;
use losthost\DB\DBEvent;

use losthost\WeekTimerModel\data\plan_item;
use losthost\WeekTimerModel\data\timer_event;
use losthost\WeekTimerModel\data\user;

use losthost\WeekTimerModel\trackers\user_creation;

/**
 * Description of Model
 *
 * @author drweb_000
 */
class Model {
    
    static public function init($db_host, $db_user, $db_pass, $db_name, $db_prefix) {
        DB::connect($db_host, $db_user, $db_pass, $db_name, $db_prefix);

        plan_item::initDataStructure();
        timer_event::initDataStructure();
        user::initDataStructure();
    }
    
}
