#!/bin/bash

TXT_PATH=/data/www/alicedev
RES_PATH=/data/asgameres/alice

msg() {
	printf "\033[35m$1\033[0m"
}

sync_txt() {
	msg '============================================================\n'
	msg "From:$TXT_PATH/$1 To: $RES_PATH/$2/txt\n"
	msg '============================================================\n'
	msg 'Start sync txt from local server...\n'
	cd $TXT_PATH/$1 && rsync -avcR --delete . $RES_PATH/$2/txt
}

sync_res() {
	msg "From:$RES_PATH/$1 To: $RES_PATH/$2\n"
	msg 'Start sync res from local server...\n'
	cd $RES_PATH/$1 && rsync -avcR --delete --exclude='.svn' --exclude='*.fla' . $RES_PATH/$2
}

if [ `whoami` != 'alice' ]; then
	msg 'Please use alice account sync res!\n'
	exit -1
elif [ -z "$1" ]; then
	msg 'Usage: ./sync_res.sh <old-txt/new-txt>\n'
	msg '       ./sync_res.sh <old-resource-flash/new-resource-flash1/new-flash1-flash2>\n'
	exit -2
fi

case $1 in
	old-txt)
		sync_txt 'task_old' 'alice/resource'
	;;
	new-txt)
		sync_txt 'task' 'alice_new/resource'
	;;
	old-resource-flash)
		sync_res 'alice/resource' 'alice/flash'
	;;
	new-resource-flash1)
		sync_res 'alice_new/resource' 'alice_new/flash1'
	;;
	new-flash1-flash2)
		sync_res 'alice_new/flash1' 'alice_new/flash2'
	;;
	*)
		msg "Invalid param $1!\n"
		exit -3
	;;
esac
exit 0;
