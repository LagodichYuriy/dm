<?php

require_once 'User.php';
require_once 'UserEvent.php';
require_once 'Users.php';


$users = new Users('analytics.csv');


# 1) What percentage of users are completing LEVEL 5?

print_r($users->solveFirst() . PHP_EOL); # 6%


# 2) what's the average revenue per user, split by country?

print_r($users->solveSecond()); # [FR] => 0
                                # [US] => 0.15
                                # [CA] => 0.2
                                # [DE] => 0.04
                                # [UK] => 0.066666666666667

# 3) Of the users that completed LEVEL 5, what event was the most likely to cause them to not return?

print_r($users->solveThird()); # Obviously, something happens after lvl 8 (LEVEL_8_COMPLETE)
                               # Maybe, they stuck or don't know what to do

