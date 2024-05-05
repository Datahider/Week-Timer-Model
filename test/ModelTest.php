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
        
        $sleep = new plan_item(['title' => 'Sleep', 'user' => $user->id, 'type' => 'sleep']);
        $sleep_timer = $m->timerStartExistent($sleep->id);
        sleep(70); // wait 70 seconds (emulante sleeping)
        
        $planning = new plan_item(['user' => $user->id, 'type' => 'plan']);
        $planning_timer = $m->timerStartExistent($planning->id);
        sleep(10);
        
        $timer = $m->timerStartNew($user->id, 'Watching movie', '📽');
        $start_time = $timer->start_time;
        
        $m->timerChangeStartTime($timer->id, -60);
        
        $timer->fetch();
        $this->assertEquals(60, $start_time->getTimestamp() - $timer->start_time->getTimestamp());
        
        $sleep_timer->fetch();
        $this->assertEquals(20, $sleep_timer->duration);
        
        $planning_timer->fetch();
        
        
    }
    
    public function testExtendedStartTimeChange() {
        $m = Model::get();
        
        $user = $m->userCreate();
        $one_day_ago = date_interval_create_from_date_string("-1 day");
        
        $user->registered = $user->registered->add($one_day_ago);
        $user->restarted = $user->registered;
        $user->write();
        
        $planning = $m->timerGetActive($user->id);
        sleep(2);
        $test1 = $m->timerStartNew($user->id, "Test 1", "😀");
        sleep(2);
        $test2 = $m->timerStartNew($user->id, "Test 2", "😃");
        sleep(2);
        $test3 = $m->timerStartNew($user->id, "Test 3", "😄");
        
        $m->timerChangeStartTime($planning->id, -3600);
        $m->timerChangeStartTime($test1->id, -1800);
        $m->timerChangeStartTime($test2->id, -900);
        $m->timerChangeStartTime($test3->id, -300);
        $m->timerChangeStartTime($test2->id, 500);
        // TODO - сделать тесты для изменения времени в большую сторону (тогда конец может стать раньше нового начала, что с этим делать не понятно пока)
        // Доделать проверку того, что получилось. Пока проверил глазами
        $this->assertTrue(true);
    }
    
    public function testGetCurrentTimerEvent() {
        
        $m = Model::get();
        
        $user = $m->userCreate();
        $sleep = new plan_item(['user' => $user->id, 'type' => 'sleep']);
        $sleep_timer = $m->timerStartExistent($sleep->id);
        
        $active = $m->timerGetActive($user->id);
        $this->assertEquals($sleep_timer, $active);
        
    }
    
    public function testGetPlanItemNeighbors() {
        $model = Model::get();
        $user = $model->userCreate();
        
        $active = $model->timerGetActive($user->id);
        $neighbors = $model->planItemGetNeighbors($active->plan_item);
        
        $this->assertEquals(2, $neighbors['prev']->sort_order);
        $this->assertEquals(4, $neighbors['next']->sort_order);
        
        $prev_neighbors = $model->planItemGetNeighbors($neighbors['prev']->id);
        $before_prev_neighbors = $model->planItemGetNeighbors($prev_neighbors['prev']->id);
        
        $this->assertFalse(isset($before_prev_neighbors['prev']));
        $this->assertEquals(2, $before_prev_neighbors['next']->sort_order);
        
        $next_neighbors = $model->planItemGetNeighbors($neighbors['next']->id);
        
        $this->assertEquals(3, $next_neighbors['prev']->sort_order);
        $this->assertFalse(isset($next_neighbors['next']));
        
        
        
    }
    
    public function testGetSetParam() {
        
        $m = Model::get();
        
        $this->assertEquals('888', $m->getParam('test', '888'));
        $this->assertEquals('888', $m->getParam('test', '123'));
        
        $m->setParam('test', '1234');
        $this->assertEquals('1234', $m->getParam('test', '4321'));
    }
    
}
