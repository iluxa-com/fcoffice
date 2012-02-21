#!/bin/bash

#判断参数个数
if [ $# != 3 ]; then
	echo "This script need 3 arguments($0 $*)!"
	echo "Usage: ./run.sh <platform> <0|1> <php-script>"
	exit -1
fi

PLATFORM=$1
NEED_LOG=$2
FILENAME=$3

cd `dirname $0`

#判断文件是否存在
if [ ! -f $FILENAME ]; then
	echo "The file($FILENAME) doesn't exists!"
	exit -2
fi

LOG_DIR=/tmp/${PLATFORM}_crontab

#如果目录不存在,自动创建
if [ ! -d $LOG_DIR ]; then
	mkdir -p $LOG_DIR
fi

#非TX平台php安装路径
PHP_PATH1=/usr/local/services/php-5.3.5

#TX平台php安装路径
PHP_PATH2=/data/webserver/php-5.3.5

#路径选择
if [ -d $PHP_PATH1 ]; then
	PHP_PATH=$PHP_PATH1
elif [ -d $PHP_PATH2 ]; then
	PHP_PATH=$PHP_PATH2
else
	echo "No php installed on your system!"
	exit -3
fi

#是否需要记录输出
if [ $NEED_LOG == '0' ]; then
	$PHP_PATH/bin/php -c $PHP_PATH/etc/php.ini -f $FILENAME $PLATFORM > /dev/null &
else
	$PHP_PATH/bin/php -c $PHP_PATH/etc/php.ini -f $FILENAME $PLATFORM >> $LOG_DIR/$FILENAME &
fi
exit 0
