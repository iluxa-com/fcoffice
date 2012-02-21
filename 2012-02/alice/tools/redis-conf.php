#!/usr/local/services/php-5.3.5/bin/php
<?php
if ($argc < 2) {
    echo <<<EOT
\033[35mUsage: ./redis-conf.php <port>
\033[0m
EOT;
    exit;
}
$port = $argv[1];
if ($port != intval($port) || $port < 1024 || $port > 65535) {
    exit("Invalid port({$port})!\n");
}
$dir = '/usr/local/services/redis';

// redis.conf@redis-2.2.11
// cat redis.conf | grep '^[^#]'
// cat redis.conf | grep -Ev '^#|^$'
// cat redis.conf | grep -v '^#' | grep -v '^$'
// cat redis.conf | awk '/^[^#]/'
echo <<<EOT
daemonize yes
pidfile {$dir}/pid/{$port}.pid
port {$port}
timeout 300
loglevel verbose
logfile stdout
databases 16
save 900 1
save 300 10
save 60 10000
rdbcompression no
dbfilename dump-{$port}.rdb
dir {$dir}/dump
slave-serve-stale-data yes
appendonly no
appendfsync everysec
no-appendfsync-on-rewrite no
vm-enabled no
vm-swap-file /tmp/redis-{$port}.swap
vm-max-memory 0
vm-page-size 32
vm-pages 134217728
vm-max-threads 4
hash-max-zipmap-entries 512
hash-max-zipmap-value 64
list-max-ziplist-entries 512
list-max-ziplist-value 64
set-max-intset-entries 512
activerehashing yes
EOT;
?>
