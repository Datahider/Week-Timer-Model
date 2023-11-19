<?php

use losthost\DB\DB;
use losthost\WeekTimerModel\Model;

require_once '../vendor/autoload.php';

define('DB_HOST', 'localhost');
define('DB_USER', 'test');

//define('DB_PASS', '');
require_once 'db_pass.php';

define('DB_NAME', 'test');
define('DB_PREF', 'wtm_');

DB::connect(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PREF);
DB::dropAllTables(true, true);
Model::init(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PREF);
