#!/bin/bash

SVN_URL=svn://122.11.57.40:6661
TARGET_PATH=/data/www/alicedev/version-release
ZIP_FILENAME=tencent.alice.zip

msg() {
	printf "\033[35m$1\033[0m"
}

sync_by_svn() {
	if [ ! -d $2 ]; then
		msg "The directory($2) is not exists, create it first!\n"
		mkdir -p $2
	fi
	cd $2
	if [ ! -d '.svn' ]; then
		msg "Start checkout code from SVN server ...\n"
		msg "SRC: $1\n"
		msg "DST: $2\n"
		msg "VER: $3\n"
		svn checkout $1 . -r $3 && svn status
	else
		msg "Start update code from SVN server ...\n"
		msg "SRC: $1\n"
		msg "DST: $2\n"
		msg "VER: $3\n"
		svn update . -r $3 && svn status
	fi
	printf $3 > version.txt
}

sync_by_rsync() {
	msg "Start rsync file ...\n"
	msg "SRC: $1\n"
	msg "DST: $2\n"
	if [ -f "$2/version.txt" ]; then
		VER_SRC=`cat $2/version.txt`
	else
		VER_SRC=0
	fi
	if [ ! -d $TARGET_PATH/tmp ]; then
		mkdir -p $TARGET_PATH/tmp
	fi
	cd $TARGET_PATH/tmp && rm -rf *
	cd $1
	VER_DST=`cat version.txt`
	rsync -avcR --delete --exclude='.svn' --exclude='*.fla' --write-batch=$TARGET_PATH/tmp/$VER_SRC.$VER_DST.patch . $2
	#rsync -avcR --delete --exclude='.svn' --exclude='install' --exclude='resource' --exclude='sample' --exclude='tests' --exclude='*.fla' --write-batch=$TARGET_PATH/tmp/$VER_SRC.$VER_DST.patch . $2
	cd $TARGET_PATH/tmp && printf "$VER_DST" > version.txt
	rm -rf *.sh && zip -b /tmp $ZIP_FILENAME *
	msg "===================Alice Tencent Update Patch==================\n"
	msg "md5: `md5sum $ZIP_FILENAME | awk '{print $1}'`\n"
	msg "src: $VER_SRC\n"
	msg "dst: $VER_DST\n"
	msg "url: http://192.168.0.222/version-release/tmp/tencent.alice.zip\n"
}


if [ `whoami` != 'user_00' ]; then
	msg 'Please use user_00 to run this script!\n';
	exit -1
elif [ $# != 1 ]; then
	msg 'Usage: ./export_code.sh <version>\n'
	exit -2
fi
sync_by_svn $SVN_URL/branches/2.0.x/src $TARGET_PATH/svn $1
sync_by_rsync $TARGET_PATH/svn $TARGET_PATH/dst
exit 0
