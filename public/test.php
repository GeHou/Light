<?php
$str="UserAddController";
    /*
    preg_match_all("/([a-zA-Z]{1}[a-z]*)?[^A-Z]/",$str,$array);
    */
    $array=preg_split("/(?=[A-Z])/",$str, -1, PREG_SPLIT_NO_EMPTY);
    print_r($array);
    ?>