<?php

use test;
require('test.php');


class Test2 extends TestGo {

    public static function run()
    {
        TestGo::go();
    }

}

Test2::run();