#!/bin/bash

#global variables
PHP_SVN_URL=svn://122.11.57.40:6661
AS_SVN_URL=svn://122.11.57.40:6661
PROJECT_DIR=/data/www/alice
CUR_DIR=`pwd`

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
}

sync_by_rsync() {
	msg "Start rsync file ...\n"
	msg "SRC: $1\n"
	msg "DST: $2\n"
	cd $1 && rsync -avcR --delete --exclude='.svn' --exclude='*.fla' . $2
}

rename_resource() {
	msg "Start rename resource ...\n"
	msg "SRC: $1\n"
	msg "DST: $2\n"
	cd $CUR_DIR && ./rename_resource.php $1 $2
}

get_key() {
	echo $1 | awk -F - '{print $2}'
}

replace_key() {
	echo $1 | sed s/-`get_key $1`-/-res-/
}

#check user
if [ `whoami` != 'root' ]; then
	msg "Please use root account to run this script!\n"
	exit -1
fi

#check argument count
if [ $# != 2 ]; then
	msg "Usage: ./`basename $0` <platform-php|flash|js|css|images-test|stable> <version>\n"
	exit -2
fi

#sync
case $1 in
	renren-php-test | renren-php-stable)
		SRC=$PHP_SVN_URL/trunk/src
		DST=$PROJECT_DIR/$1
		sync_by_svn $SRC $DST $2
	;;
	renren-flash-test | renren-flash-stable)
		SRC=$AS_SVN_URL/trunk/src
		TMP=$PROJECT_DIR/.$1
		DST=$PROJECT_DIR/`replace_key $1`/`get_key $1`
		sync_by_svn $SRC $TMP $2
		rename_resource $TMP $DST
	;;
	renren-js-test | renren-js-stable | renren-css-test | renren-css-stable | renren-images-test | renren-images-stable)
		SRC=$PHP_SVN_URL/trunk/src/resource/`get_key $1`
		DST=$PROJECT_DIR/`replace_key $1`/`get_key $1`
		sync_by_svn $SRC $DST $2
	;;
####################################################################################################################################
	renren2-php-test | renren2-php-stable)
		SRC=$PHP_SVN_URL/branches/2.0.x/src
		DST=$PROJECT_DIR/$1
		sync_by_svn $SRC $DST $2
	;;
	renren2-flash-test | renren2-flash-stable)
		SRC=$AS_SVN_URL/trunk/src
		TMP=$PROJECT_DIR/.$1
		DST=$PROJECT_DIR/`replace_key $1`/`get_key $1`
		sync_by_svn $SRC $TMP $2
		rename_resource $TMP $DST
	;;
	renren2-js-test | renren2-js-stable | renren2-css-test | renren2-css-stable | renren2-images-test | renren2-images-stable)
		SRC=$PHP_SVN_URL/branches/2.0.x/src/resource/`get_key $1`
		DST=$PROJECT_DIR/`replace_key $1`/`get_key $1`
		sync_by_svn $SRC $DST $2
	;;
####################################################################################################################################
	pengyou-php-test | pengyou-php-stable)
		SRC=$PHP_SVN_URL/branches/2.0.x/src
		DST=$PROJECT_DIR/$1
		sync_by_svn $SRC $DST $2
	;;
	pengyou-flash-test | pengyou-flash-stable)
		SRC=$AS_SVN_URL/trunk/src
		TMP=$PROJECT_DIR/.$1
		DST=$PROJECT_DIR/`replace_key $1`/`get_key $1`
		sync_by_svn $SRC $TMP $2
		rename_resource $TMP $DST
	;;
	pengyou-js-test | pengyou-js-stable | pengyou-css-test | pengyou-css-stable | pengyou-images-test | pengyou-images-stable)
		SRC=$PHP_SVN_URL/branches/2.0.x/src/resource/`get_key $1`
		DST=$PROJECT_DIR/`replace_key $1`/`get_key $1`
		sync_by_svn $SRC $DST $2
	;;
####################################################################################################################################
	*)
		msg "Unknown case $1!\n"
		exit -3
	;;
esac
exit 0;
