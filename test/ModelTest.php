<?php

namespace losthost\WeekTimerModel\test;
use PHPUnit\Framework\TestCase;
use losthost\WeekTimerModel\Model;

use losthost\WeekTimerModel\data\plan;
use losthost\WeekTimerModel\data\plan_item;
use losthost\WeekTimerModel\data\timer_event;
use losthost\WeekTimerModel\data\user;
use losthost\DB\DB;

/**
 * Description of ModelTest
 *
 * @author drweb_000
 */
class ModelTest extends TestCase {
    
    public function testModelInit() {
        
        $this->assertTrue(plan::tableExists());
        $this->assertTrue(plan_item::tableExists());
        $this->assertTrue(timer_event::tableExists());
        $this->assertTrue(user::tableExists());
        
    }
    
    public function testUserCreate() {
        $u = new user();
        $u->write();
        
        $this->assertGreaterThan(0, $u->id);
    }
    
    
    
    public function testStartingExistingAndNewItemsAndChangingStartTime() {
        
        $m = Model::get();
        
        $user = $m->userCreate();
        sleep(5); // wait 5 seconds (emulate planning)
        
        $sleep = new plan_item(['title' => 'Sleep', 'user' => $user->id, 'is_system' => true]);
        $sleep_timer = $m->timerStartExistent($sleep->id);
        sleep(70); // wait 70 seconds (emulante sleeping)
        
        $timer = $m->timerStartNew($user->id, 'Watching movie');
        $start_time = $timer->start_time;
        
        $m->timerChangeStartTime($timer->id, -1);
        
        $timer->fetch();
        $this->assertEquals(60, $start_time->getTimestamp() - $timer->start_time->getTimestamp());
        
        $sleep_timer->fetch();
        $this->assertEquals(10, $sleep_timer->duration);
        
        
    }
    
}
