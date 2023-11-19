<?php

namespace losthost\WeekTimerModel\test;
use PHPUnit\Framework\TestCase;
use losthost\WeekTimerModel\Model;
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
        
        $this->assertTrue(plan_item::tableExists());
        $this->assertTrue(timer_event::tableExists());
        $this->assertTrue(user::tableExists());
        
    }
    
    public function testStartTimer() {
        $plan_item1 = new plan_item(['id' => null, 'user' => 0, 'title' => 'test plan item one', 'is_archived' => false, 'is_system' => false], true);
        $plan_item1->write();
        $plan_item2 = new plan_item(['id' => null, 'user' => 0, 'title' => 'test plan item two', 'is_archived' => false, 'is_system' => false], true);
        $plan_item2->write();
        
        Model::startTimer($plan_item1->id);
        sleep(2);
        Model::startTimer($plan_item2->id);
        sleep(5);
        Model::stopTimer($plan_item2->id);
    }
    
}
