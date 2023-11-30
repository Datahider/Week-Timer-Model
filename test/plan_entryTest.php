<?php

namespace losthost\WeekTimerModel\test;
use PHPUnit\Framework\TestCase;
use losthost\WeekTimerModel\data\plan_entry;


class plan_entryTest extends TestCase {
    
    public function testCanCreateEntry() {
        
        $plan_entry = new plan_entry(['id' => null, 'plan' => 1, 'plan_item' => 1, 'time_planned' => 10000], true);
        $plan_entry->write();
        
        $this->assertTrue(true);
        
    }
}
