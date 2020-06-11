<?php

require_once 'AStar.php';


# /-------------------\
# | - | 0 | 1 | 2 | 3 |
# |---+---+---+---+---|
# | A | R | * |   |   |
# |---+---+---+---+---|
# | B |   | * |   |   |
# |---+---+---+---+---|
# | C |   |   |   |   |
# |---+---+---+---+---|
# | D |   | * | Q |   |
# \-------------------/

$astar = new AStar(4, 4);
$astar->setStart(0, 0);    # A0
$astar->setFinish(2, 3);   # D2
$astar->addObstacle(1, 0); # A1
$astar->addObstacle(1, 1); # B1
$astar->addObstacle(1, 3); # D1
$astar->find();

$astar->debug(); # 0A -> 0B -> 0C -> 1C -> 2C -> 2D

echo $astar->countTurns() . PHP_EOL . PHP_EOL; # 3


# /-------------------\
# | - | 0 | 1 | 2 | 3 |
# |---+---+---+---+---|
# | A |   | * |   | R |
# |---+---+---+---+---|
# | B |   | * |   |   |
# |---+---+---+---+---|
# | C |   |   |   |   |
# |---+---+---+---+---|
# | D |   | * | Q |   |
# \-------------------/

$astar = new AStar(4, 4);
$astar->setStart(3, 0);    # A0
$astar->setFinish(2, 3);   # D2
$astar->addObstacle(1, 0); # A1
$astar->addObstacle(1, 1); # B1
$astar->addObstacle(1, 3); # D1
$astar->find();

$astar->debug(); # 3A -> 3B -> 3C -> 3D -> 2D

echo $astar->countTurns() . PHP_EOL . PHP_EOL; # 2


# /-------------------\
# | - | 0 | 1 | 2 | 3 |
# |---+---+---+---+---|
# | A | R |   | * |   |
# |---+---+---+---+---|
# | B |   |   |   |   |
# |---+---+---+---+---|
# | C |   |   |   |   |
# |---+---+---+---+---|
# | D |   |   |   | Q |
# \-------------------/

$astar = new AStar(4, 4);
$astar->setStart(0, 0);    # A0
$astar->setFinish(3, 3);   # D3
$astar->addObstacle(2, 0); # A2
$astar->find();

$astar->debug(); # 0A -> 0B -> 0C -> 0D -> 1D -> 2D -> 3D

echo $astar->countTurns() . PHP_EOL . PHP_EOL; # 2


# /-----------------------\
# | - | 0 | 1 | 2 | 3 | 4 |
# |---+---+---+---+---|---|
# | A | R | * |   |   |   |
# |---+---+---+---+---|---|
# | B |   | * |   | * |   |
# |---+---+---+---+---|---|
# | C |   | * |   | * |   |
# |---+---+---+---+---|---|
# | D |   |   |   | * | Q |
# \-----------------------/

$astar = new AStar(5, 4);
$astar->setStart(0, 0);    # A0
$astar->setFinish(4, 3);   # D4
$astar->addObstacle(1, 0); # A1
$astar->addObstacle(1, 1); # B1
$astar->addObstacle(1, 2); # C1
$astar->addObstacle(3, 1); # B3
$astar->addObstacle(3, 2); # C3
$astar->addObstacle(3, 3); # D3
$astar->find();

$astar->debug(); # 0A -> 0B -> 0C -> 0D -> 1D -> 2D -> 2C -> 2B -> 2A -> 3A -> 4A -> 4B -> 4C -> 4D

echo $astar->countTurns() . PHP_EOL; # 5