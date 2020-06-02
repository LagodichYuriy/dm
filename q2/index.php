<?php

require_once 'PriorityQueue.php';

$queue = new PriorityQueue();
$queue->enqueue('a', 1);
$queue->enqueue('b', 1);
$queue->enqueue('c', 10);
$queue->enqueue('d', 3);

print_r($queue->dequeue() . PHP_EOL); # "c"
print_r($queue->dequeue() . PHP_EOL); # "d"
print_r($queue->dequeue() . PHP_EOL); # "a"
print_r($queue->dequeue() . PHP_EOL); # "b"
print_r($queue->dequeue() . PHP_EOL); # NULL