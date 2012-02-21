--TEST--
Check for class redis @ phpredis
--SKIPIF--
--FILE--
<?php
require_once 'config.inc.php';

$r = new Redis();
$r->connect('127.0.0.1', 6002);

$dataArr = array(
    0 => 'A',
    1 => 'B',
);
assert($r->hMset('__test_hmset', $dataArr) === true);

$r->hSet('__test_hincrby', 'key', 1);
assert($r->hIncrBy('__test_hincrby', 'key', -1) === 0);

assert($r->renameNx('__test_hincrby', '__test_hincrby2') === true);

$r->delete('__test_hmset', '__test_hincrby', '__test_hincrby2');
?>
--EXPECT--