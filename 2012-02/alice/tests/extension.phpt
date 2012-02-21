--TEST--
Check for extension loaded or not
--SKIPIF--
--FILE--
<?php
require_once 'config.inc.php';

$nameArr = array(
    'curl',
    'json',
    'mbstring',
    'session',
    'iconv',
    'mysql',
    'mysqli',
    'PDO',
    'memcache',
    'memcached',
    'redis',
);
foreach ($nameArr as $name) {
    assert(extension_loaded($name) === true);
}
?>
--EXPECT--