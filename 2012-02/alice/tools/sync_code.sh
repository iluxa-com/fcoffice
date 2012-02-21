#!/bin/bash

CODE_PATH=/data/www/alicedev

msg() {
	printf "\033[35m$1\033[0m"
}

sync_code() {
	msg '=====================================================================\n'
	msg "$1\n"
	msg '=====================================================================\n'
	cd $CODE_PATH/$1
	if [ ! -d '.svn' ]; then
		msg 'Please checkout a copy from SVN first!\n'
		exit -4
	else
		msg 'Start sync code from SVN server...\n'
		svn update && svn status
	fi
}

if [ `whoami` != 'user_00' ]; then
	msg 'Please use user_00 account sync code!\n'
	exit -1
elif [ -z "$1" ]; then
	msg 'Usage: ./sync_code.sh <alice-devel-php/alice-devel-as/alice-test/alice-ga>\n'
	msg '       ./sync_code.sh <alice-new-devel-php/alice-new-devel-as/alice-new-test-one/alice-new-test-two/alice-new-ga>\n'
	msg '       ./sync_code.sh <old-all/new-all/all>\n'
	exit -2
fi

case $1 in
	alice-devel-php | alice-devel-as | alice-test | alice-ga)
		sync_code $1
	;;
	alice-new-devel-php | alice-new-devel-as | alice-new-test-one | alice-new-test-two | alice-new-ga)
		sync_code $1
	;;
	old-all)
		sync_code 'alice-devel-php'
		sync_code 'alice-devel-as'
		sync_code 'alice-test'
		sync_code 'alice-ga'
	;;
	new-all)
		sync_code 'alice-new-devel-php'
		sync_code 'alice-new-devel-as'
		sync_code 'alice-new-test-one'
		sync_code 'alice-new-test-two'
		sync_code 'alice-new-ga'
	;;
	all)
		sync_code 'alice-devel-php'
		sync_code 'alice-devel-as'
		sync_code 'alice-test'
		sync_code 'alice-ga'
		sync_code 'alice-new-devel-php'
		sync_code 'alice-new-devel-as'
		sync_code 'alice-new-test-one'
		sync_code 'alice-new-test-two'
		sync_code 'alice-new-ga'
	;;
	*)
		msg "Invalid param $1!\n"
		exit -3
	;;
esac
exit 0
