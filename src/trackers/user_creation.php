<?php

namespace losthost\WeekTimerModel\trackers;

use losthost\WeekTimerModel\data\plan_item;
use losthost\DB\DBTracker;
use losthost\DB\DBEvent;


/**
 * Description of user_creation
 *
 * @author drweb_000
 */
class user_creation extends DBTracker {
    
    public function track(DBEvent $event) {
        
        $user = $event->object;
        
        $lost_time = new plan_item(['id' => null, 'user' => $user->id, 'title' => 'Lost Time', 'is_archived' => false, 'is_system' => true], true);
        $lost_time->write();
        
        $planning = new plan_item(['id' => null, 'user' => $user->id, 'title' => 'Planning', 'is_archived' => false, 'is_system' => true], true);
        $planning->write();
        
        $sleeping = new plan_item(['id' => null, 'user' => $user->id, 'title' => 'Sleeping', 'is_archived' => false, 'is_system' => true], true);
        $sleeping->write();
        
        $reserved = new plan_item(['id' => null, 'user' => $user->id, 'title' => 'Reserved', 'is_archived' => false, 'is_system' => true], true);
        $reserved->write();
    }
}
