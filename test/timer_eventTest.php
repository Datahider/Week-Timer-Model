<?php

namespace losthost\WeekTimerModel\test;
use PHPUnit\Framework\TestCase;
use losthost\WeekTimerModel\data\timer_event;
use losthost\WeekTimerModel\data\plan_item;
use losthost\WeekTimerModel\data\user;

/**
 * Description of timer_eventTest
 *
 * @author drweb_000
 */
class timer_eventTest extends TestCase {
    
    public function testCreateNewAndStopPrevious() {
        
        $p = new plan_item(['id' => null, 'user' => 0, 'title' => 'testCreateNew 1'], true);
        $p->write();
        
        $e1 = new timer_event(['id' => null, 'plan_item' => $p->id], true);
        $e1->write();
        
        $t = new timer_event(['id' => $e1->id]);
        $this->assertLessThanOrEqual(1, abs(date_create()->getTimestamp() - $t->start_time->getTimestamp()));
        
        sleep(5);
        
        $e2 = new timer_event(['id' => null, 'plan_item' => $p->id], true);
        $e2->write();
        
        $e1->fetch();
        $this->assertEquals(5, $e1->duration);
        
    }
    
    public function testInParallel() {
        
        $user1 = new user();
        $user1->write();
        $user2 = new user();
        $user2->write();
        
        $item_plan1 = new plan_item(['user' => $user1->id, 'is_system' => true, 'title' => 'Plan']);
        $item_plan2 = new plan_item(['user' => $user2->id, 'is_system' => true, 'title' => 'Plan']);
        $item_lost1 = new plan_item(['user' => $user1->id, 'is_system' => true, 'title' => 'Lost']);
        $item_sleep2 = new plan_item(['user' => $user2->id, 'is_system' => true, 'title' => 'Sleep']);
        
        $event1_1 = new timer_event(['id' => null, 'plan_item' => $item_plan1->id], true);
        $event1_1->write();
        $event2_1 = new timer_event(['id' => null, 'plan_item' => $item_plan2->id], true);
        $event2_1->write();
        
        sleep(5);
        
        $event1_2 = new timer_event(['id' => null, 'plan_item' => $item_lost1->id], true);
        $event1_2->write();
        
        sleep(3);
        
        $event2_2 = new timer_event(['id' => null, 'plan_item' => $item_sleep2->id], true);
        $event2_2->write();
        
        $event1_1->fetch();
        $event2_1->fetch();
        
        $this->assertEquals(5, $event1_1->duration);
        $this->assertEquals(8, $event2_1->duration);
        
    }
}
