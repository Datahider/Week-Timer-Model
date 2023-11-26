<?php
namespace losthost\WeekTimerModel\data;
use losthost\DB\DBObject;
use losthost\DB\DBView;
use losthost\DB\DB;

final class time_zone extends DBObject {
    
    const METADATA = [
        'id' => 'INT(11) NOT NULL',
        'name' => 'VARCHAR(10) NOT NULL',
        'gmt_plus_minutes' => 'INT(11) NOT NULL',
        'PRIMARY KEY' => 'id'
    ];
    
    protected static function createTable() {
        parent::createTable();
        
        $sql = 'INSERT INTO '. static::tableName(). ' (id, name, gmt_plus_minutes) VALUES (?, ?, ?)';
        $sth = DB::prepare($sql);
        
        $sth->execute([-1200, 'GMT-12:00', -720]);
        $sth->execute([-1100, 'GMT-11:00', -660]);
        $sth->execute([-1000, 'GMT-10:00', -600]);
        $sth->execute([-930, 'GMT-09:30', -570]);
        $sth->execute([-900, 'GMT-09:00', -540]);
        $sth->execute([-800, 'GMT-08:00', -480]);
        $sth->execute([-700, 'GMT-07:00', -420]);
        $sth->execute([-600, 'GMT-06:00', -360]);
        $sth->execute([-500, 'GMT-05:00', -300]);
        $sth->execute([-400, 'GMT-04:00', -240]);
        $sth->execute([-330, 'GMT-03:30', -210]);
        $sth->execute([-300, 'GMT-04:00', -180]);
        $sth->execute([-230, 'GMT-02:30', -150]);
        $sth->execute([-200, 'GMT-02:00', -120]);
        $sth->execute([-100, 'GMT-01:00', -60]);
        $sth->execute([0, 'GMT', 0]);
        $sth->execute([100, 'GMT+01:00', 60]);
        $sth->execute([200, 'GMT+02:00', 120]);
        $sth->execute([300, 'GMT+03:00', 180]);
        $sth->execute([330, 'GMT+03:30', 210]);
        $sth->execute([400, 'GMT+04:00', 240]);
        $sth->execute([430, 'GMT+04:30', 270]);
        $sth->execute([500, 'GMT+05:00', 300]);
        $sth->execute([530, 'GMT+05:30', 330]);
        $sth->execute([545, 'GMT+05:45', 345]);
        $sth->execute([600, 'GMT+06:00', 360]);
        $sth->execute([630, 'GMT+06:30', 390]);
        $sth->execute([700, 'GMT+07:00', 420]);
        $sth->execute([800, 'GMT+08:00', 480]);
        $sth->execute([845, 'GMT+08:45', 525]);
        $sth->execute([900, 'GMT+09:00', 540]);
        $sth->execute([930, 'GMT+09:30', 570]);
        $sth->execute([1000, 'GMT+10:00', 600]);
        $sth->execute([1030, 'GMT+10:30', 630]);
        $sth->execute([1100, 'GMT+11:00', 660]);
        $sth->execute([1200, 'GMT+12:00', 720]);
        $sth->execute([1245, 'GMT+12:45', 765]);
        $sth->execute([1300, 'GMT+13:00', 780]);
        $sth->execute([1345, 'GMT+13:45', 825]);
        $sth->execute([1400, 'GMT+14:00', 840]);
    }
    
    public function write($comment = '', $data = null) {
        throw new \Exception('This is a read-only class/object.');
    }
    
    public function __set($name, $value) {
        throw new \Exception('This is a read-only class/object.');
    }
    
    public function goWest() {
        $new = new DBView("SELECT id FROM [time_zone] WHERE id < ? ORDER BY id DESC LIMIT 1", $this->id);
        if (!$new->next()) {
            $new = new DBView("SELECT id FROM [time_zone] ORDER BY id DESC LIMIT 1");
            $new->next();
        }
        $this->fetch(['id' => $new->id]);
    }
    
    public function goEast() {
        $new = new DBView("SELECT id FROM [time_zone] WHERE id > ? ORDER BY id ASC LIMIT 1", $this->id);
        if (!$new->next()) {
            $new = new DBView("SELECT id FROM [time_zone] ORDER BY id ASC LIMIT 1");
            $new->next();
        }
        $this->fetch(['id' => $new->id]);
    }
}
