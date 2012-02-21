#!/bin/bash

DOMAIN=app27790.qzoneapp.com
URL=http://$DOMAIN/crossdomain.xml
PORT=80

msg() {
	printf "\033[35m$1\033[0m"
}

dig $DOMAIN | grep '^[^;]' | awk '{print $5}' | while read IP; do
	printf "$IP\t"
	msg "`curl -I $URL -x $IP:$PORT -s | head -n 1`\n"
done

exit 0
