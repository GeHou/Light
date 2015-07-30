<?php

require('test.php');
use Test;
class Test2 extends TestGo {

    public static function run()
    {
        TestGo::go();
    }

}

Test2::run();