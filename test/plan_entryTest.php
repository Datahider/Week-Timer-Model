<?php

namespace losthost\WeekTimerModel\test;
use PHPUnit\Framework\TestCase;
use losthost\WeekTimerModel\data\plan_entry;
use losthost\WeekTimerModel\data\user;
use losthost\WeekTimerModel\data\plan;
use losthost\DB\DBList;


class plan_entryTest extends TestCase {
    
    public function testCanCreateEntry() {
        
        $user = new user();
        $user->write();
        $entries = new DBList(plan_entry::class, ['plan' => $user->active_plan]);
        
        $this->assertInstanceOf(plan_entry::class, $entries->asArray()[0]);
        
    }
    
    public function testMovingPlanEntry() {

        $user = new user();
        $user->write();
        $entries0 = (new DBList(plan_entry::class, 'plan = ? ORDER BY sort_order', [$user->active_plan]))->asArray();
        
        $entries0[0]->moveDown();
        $entries0[3]->moveDown();
        $entries1 = (new DBList(plan_entry::class, 'plan = ? ORDER BY sort_order', [$user->active_plan]))->asArray();
        
        $this->assertEquals($entries0[0]->id, $entries1[2]->id);
        $this->assertEquals($entries0[1]->id, $entries1[1]->id);
        $this->assertEquals($entries0[2]->id, $entries1[3]->id);
        $this->assertEquals($entries0[3]->id, $entries1[0]->id);
        $this->assertEquals($entries0[4]->id, $entries1[4]->id);
                                
        $entries1[3]->moveUp(); 
        $entries1[0]->moveUp(); 
        $entries2 = (new DBList(plan_entry::class, 'plan = ? ORDER BY sort_order', [$user->active_plan]))->asArray();
        
        $this->assertEquals($entries1[0]->id, $entries2[3]->id);
        $this->assertEquals($entries1[1]->id, $entries2[0]->id);
        $this->assertEquals($entries1[2]->id, $entries2[2]->id);
        $this->assertEquals($entries1[3]->id, $entries2[1]->id);
        $this->assertEquals($entries1[4]->id, $entries2[4]->id);
    }
}
