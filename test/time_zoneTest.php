<?php
namespace losthost\WeekTimerModel\test;
use PHPUnit\Framework\TestCase;
use losthost\WeekTimerModel\data\time_zone;
use losthost\DB\DB;

class time_zoneTest extends TestCase {
    
    public function testTimezoneCreation() {
        if (time_zone::tableExists()) {
            DB::exec("DROP TABLE ". time_zone::tableName());
        }
        time_zone::initDataStructure(true);
        
        $this->assertTrue(time_zone::tableExists());
    }
    
    public function testTimezoneGoWest() {
        
        $tz = new time_zone(['id' => 300]);
        $this->assertEquals(180, $tz->gmt_plus_minutes);
        $tz->goWest();
        $this->assertEquals(120, $tz->gmt_plus_minutes);
        $tz->goWest();
        $this->assertEquals(60, $tz->gmt_plus_minutes);
    }
    
    public function testTimezoneGoEast() {
        
        $tz = new time_zone(['id' => 300]);
        $this->assertEquals(180, $tz->gmt_plus_minutes);
        $tz->goEast();
        $this->assertEquals(210, $tz->gmt_plus_minutes);
        $tz->goEast();
        $this->assertEquals(240, $tz->gmt_plus_minutes);
    }
 
    public function testTimezoneOverflow() {
        
        $tz = new time_zone(['id' => 1400]);
        $this->assertEquals(840, $tz->gmt_plus_minutes);
        $tz->goEast();
        $this->assertEquals(-720, $tz->gmt_plus_minutes);
        $tz->goWest();
        $this->assertEquals(840, $tz->gmt_plus_minutes);
    }
    
    public function testTimezoneWriteException() {
        
        $tz = new time_zone(['id' => 1400]);

        $this->expectExceptionMessage("read-only");
        $tz->write();
    }
    
    public function testTimezoneSetException() {
        
        $tz = new time_zone(['id' => 1400]);

        $this->expectExceptionMessage("read-only");
        $tz->name = 'new name';
    }
    
}
