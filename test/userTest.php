<?php

namespace losthost\WeekTimerModel\test;
use PHPUnit\Framework\TestCase;
use losthost\WeekTimerModel\data\user;
use losthost\WeekTimerModel\data\plan_item;
use losthost\DB\DBList;
/**
 * Description of userTest
 *
 * @author drweb_000
 */
class userTest extends TestCase {
    
    public function testUserCreation() {
        
        $user = new user();
        $user->write();
        
        $list_plan_items = new DBList(plan_item::class, ['user' => $user->id]);
        $plan_items = $list_plan_items->asArray();
        
        $this->assertEquals(5, count($plan_items));
        $this->assertEquals('Lost', $plan_items[0]->title);
        $this->assertEquals('Plan', $plan_items[1]->title);
        $this->assertEquals('Sleep', $plan_items[2]->title);
        $this->assertEquals('Work', $plan_items[3]->title);
        $this->assertEquals('Reserve', $plan_items[4]->title);
        
    }
    
    public function testUserTimezone() {
        $user = new user();
        $user->time_zone = -1000;
        
        $this->assertEquals(new \DateTimeZone('GMT-10:00'), $user->getTimezone());
    }
}
