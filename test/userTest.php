<?php

namespace losthost\WeekTimerModel\test;
use PHPUnit\Framework\TestCase;
use losthost\WeekTimerModel\data\user;
use losthost\WeekTimerModel\data\plan_item;
use losthost\DB\DBView;
use losthost\DB\DB;
/**
 * Description of userTest
 *
 * @author drweb_000
 */
class userTest extends TestCase {
    
    public function testUserCreation() {
        
        $user = new user();
        $user->write();
        
        $items_view = new DBView("SELECT title FROM [plan_item] WHERE user = ? ORDER BY sort_order ASC", $user->id);
        
        foreach (['Sleep', 'Work', 'Plan', 'Rest', 'Lost'] as $title) {
            $items_view->next();
            $this->assertEquals($title, $items_view->title);
        }
        $this->assertFalse($items_view->next());
        
    }
    
    public function testUserTimezone() {
        $user = new user();
        $user->time_zone = -1000;
        
        $this->assertEquals(new \DateTimeZone('GMT-10:00'), $user->getTimezone());
    }
    
    public function testWritingAndReadingTime() {
        $user = new user();
        $user->time_zone = 500;
        $user->write();
        
        $this->assertEquals(date_create('now', $user->getTimezone())->format(DB::DATE_FORMAT), $user->registered->format(DB::DATE_FORMAT));
        
        $user->registered = date_create('2022-10-11', $user->getTimezone());
        $user->write();
        
        $this->assertEquals(date_create('2022-10-11', $user->getTimezone()), $user->registered);
    }
}
