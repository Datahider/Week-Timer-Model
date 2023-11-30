<?php

namespace losthost\WeekTimerModel\test;
use PHPUnit\Framework\TestCase;
use losthost\WeekTimerModel\data\plan;


class planTest extends TestCase {
    
    public function testCanCreatePlan() {
        
        $plan = new plan(['id' => null, 'user' => 1, 'title' => 'Test plan'], true);
        $plan->write();
        
        $this->assertTrue(true);
        
    }
}
