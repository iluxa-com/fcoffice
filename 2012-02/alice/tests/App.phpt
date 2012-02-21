--TEST--
Check for class App
--SKIPIF--
--FILE--
<?php
require_once 'config.inc.php';

$key = 'string';
$val = 'Hello World!';
App::set($key, $val);
assert(App::get($key) === $val);

$key = 'int';
$val = 9527;
App::set($key, $val);
assert(App::get($key) === $val);

$key = 'float';
$val = 8.912;
App::set($key, $val);
assert(App::get($key) === $val);

$key = 'bool_true';
$val = true;
App::set($key, $val);
assert(App::get($key) === $val);

$key = 'bool_false';
$val = false;
App::set($key, $val);
assert(App::get($key) === $val);

$key = 'array';
$val = array('a' => 'A', 'b' => 5, 'c' => 0.123, 'd' => true, 'e' => false, 'f' => array(1, 3));
App::set($key, $val);
assert(App::get($key) === $val);

$key = 'object';
$val = new stdClass();
App::set($key, $val);
assert(App::get($key) === $val);

$notExistsKey = 'not_exists_key';
try {
    App::get($notExistsKey);
    echo 'can not run to here';
} catch (UserException $e) {
    
}

$defaultVal = 'defaultVal';
assert(App::get($notExistsKey, $defaultVal) === $defaultVal);
?>
--EXPECT--