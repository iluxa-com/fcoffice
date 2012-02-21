var FH = FH || {};
(function($$) {
	var appId = 27790;
	/**
	 * 邀请好友
	 * @link http://wiki.opensns.qq.com/wiki/fusion.dialog.inviteFriend
	 */
	$$.inviteFriend = function () {
		fusion.dialog.inviteFriend({"appid": appId});
	};
	/**
	 * 发送Feed
	 * @param string type 类型
	 * @param array paramArr 参数数组
	 * @link http://wiki.opensns.qq.com/wiki/fusion.dialog.tweet
	 */
	$$.sendFeed = function (type, paramArr) {
		var msg;
		switch(type) {
			case '1':
				msg = '在迷城历险记中得到了新手导师的高度赞扬，去强势围观一下吧！';
				break;
			case '2':
				msg = '在迷城历险记中开启了营救苏菲娅的冒险之旅，一个人的旅程多孤单啊！来迷城历险记陪TA一块冒险吧！';
				break;
			case '3':
				msg = '成功解锁了' + paramArr[0] + '（关卡区域）的所有关卡，真是给力了！还等什么？赶快来加入迷城历险记吧！';
				break;
			case '4':
				msg = '成功进入了' + paramArr[0] + '，拯救好友苏菲娅之路又前进了一大步，快来迷城历险记看看吧，为TA撒撒鲜花，鼓鼓掌！';
				break;
			case '5':
				msg = '在迷城历险记升到了' + paramArr[0] + '级，好给力啊！你想比TA更给力吗？来迷城历险记比试比试吧！';
				break;
			case '6':
				msg = '家的' + paramArr[0] + '升级了，赶快去看看吧，给力新功能等你来体验啊！';
				break;
			case '7':
				msg = '在迷城历险记捡到了' + paramArr[0] + '，这是什么东东呢？难道这就是传说中的，传说中的什么！想知道答案吗？亲自来迷城历险记看看吧！';
				break;
			case '8':
				msg = '在迷城历险记中通过翻牌得到了' + paramArr[0] + '，难道TA踩了天使运，还等什么，赶快来试试自己的运气吧！';
				break;
			case '9':
				msg = '你们还在睡懒觉的时候，我已经来迷城历险记签到了！功夫不负有心人，哈哈哈，我终于得到了签到大礼包！哇塞！奖励太给力了！';
				break;
			case '10':
				msg = '收集到了一套完整图册，里面藏着什么秘密呢？赶快来迷城历险记窥视窥视吧！';
				break;
			case '11':
				msg = '在挑战关卡中获得了满级评价，纳尼？纳尼？这么牛牛啊！一块来加入挑战吧！说不定你比TA更牛牛！';
				break;
			case '12':
				msg = '幸运地获得了一枚' + paramArr[0] + '魔法棒，难道TA踩狗屎运了，赶快加入迷城历险记吧，试试你的运气如何！';
				break;
			case '13':
				msg = '在合成的时候爆击了，额外获得了很多神秘的东东，快来去一下经吧！说不定下次爆击的就是你啊！';
				break;
			case '14':
				msg = '闯关的时候获得高额的评分，简直是太牛牛了！赶快来迷城历险记跟TAPK一下吧！';
				break;
			case '15':
				msg = '在迷城历险记中获得了一个奖杯，好给力啊！赶快来强势围观吧！';
				break;
			case '16':
				msg = '在迷城历险记中开启了每日礼包，给力的奖励获得了一堆，羡慕吧！赶快加入，开启大礼包吧！';
				break;
			case '17':
				msg = '成功开启了给力大宝箱，给力大宝箱果然够给力，想体验一下给力的感觉吗？赶快来迷城历险记体验一下吧！';
				break;
			case '18':
				msg = '成功开启了' + paramArr[0] + '（地点）的隐藏关卡，里面藏着什么神秘东东，赶快来迷城历险记看看吧！';
				break;
			case '19':
				msg = '在迷城历险记中开启了新手礼盒，得到了相当相当给力的东东！想要吗？赶快来开启吧！';
				break;
			case '20':
				msg = '成功完成了隐藏关卡的冒险，获得了灰常灰常给力的奖励！别独自一人在旁边羡慕嫉妒恨了，赶快加入迷城历险记吧！';
				break;
			case '21':
				msg = '今天帮助NPC完成了20个任务，新时代的雷锋诞生了！赶快来瞧瞧吧！';
				break;
			case '22':
				msg = '今天帮助20个好友完成了任务，新一代的童城雷锋诞生了！赶快来强势围观一下吧！';
				break;
			case '23':
				msg = '精灵工坊升到1级了，可以合出很多给力的东东，真是羡慕嫉妒恨，赶快加入迷城历险记，也来体验一把吧！';
				break;
			case '24':
				msg = '贸易马车升到1级了，赶快去和好友做交易吧！还等什么？赶快来迷城历险记体验一夜暴富的感觉吧！';
				break;
			case '25':
				msg = '磨坊升到1级了，金币呼呼地往外冒，真是令人羡慕啊！赶快加入迷城历险记，去TA家强势围观一下吧！';
				break;
			case '26':
				msg = '魔法果树升到1级了，可以生长出很多神奇果子啊！来迷城历险记看看那棵神奇的魔法果树吧！';
				break;
			case '27':
				msg = '蜂巢升到1级了，蜂胶都溢出来了，真是太给力了！赶快去TA家强势围观一下吧！';
				break;
			case '28':
				msg = '精灵屋升到1级了，快点来强势围观一下里面藏着什么奇异的小精灵吧！';
				break;
			case '29':
				msg = '可以发布分享任务了，赶快去TA家瞧瞧吧，说不定能发现一些高奖励的任务啊！';
				break;
			case '30':
				msg = '小卖部开张了，赶快去逛逛吧！说不定能发现什么稀奇的东东呢！';
				break;
			case '31':
				msg = '通过收藏图鉴获得小装饰物达到了2件，赶紧来迷城历险记，去TA家瞧瞧吧！';
				break;
			case '32':
				msg = '通过收藏图鉴获得小装饰物达到了4件，赶紧来迷城历险记，去TA家瞧瞧吧！';
				break;
			case '33':
				msg = '通过收藏图鉴获得小装饰物达到了6件，赶紧来迷城历险记，去TA家瞧瞧吧！';
				break;
			case '34':
				msg = '通过收藏图鉴获得小装饰物达到了8件，赶紧来迷城历险记，去TA家瞧瞧吧！';
				break;
			case '35':
				msg = '通过收藏图鉴获得小装饰物达到了10件，赶紧来迷城历险记，去TA家瞧瞧吧！';
				break;
			case '36':
				msg = '在等级排行榜上冲到了' + paramArr[0] + '名，羡慕吧？嫉妒吧？还等什么，赶快来迷城历险记超越TA吧！';
				break;
			case '37':
				msg = '在迷城历险记中，摇身一变成了小富翁，竟然冲到了金币排行榜的' + paramArr[0] + '名，赶快加入吧，体验一下富翁的感觉吧！';
				break;
			case '38':
				msg = '真是新时代的大好人，竟然冲到了爱心排行的' + paramArr[0] + '名，还等什么？来迷城历险记，一同体验互助的乐趣吧！';
				break;
			default:
				$$.log('Invalid type ' + type);
				return;
				break;
		}
		fusion2.dialog.tweet({"msg":msg});
	};
	/**
	 * 弹出警告提示
	 * @param string msg 错误消息
	 * @param int code 错误代码
	 */
	$$.alert = function(str, code) {
		if (code && code == 115) { // ERROR_SNS_SESSION_EXPIRED
			fusion.dialog.showLoginBox({appid:appId});
		} else {
			window.alert(str);
		}
	};
	/**
	 * 打开充值页面
	 */
	$$.openPayPage = function() {
		alert("对不起，此功能暂未开通，敬请关注！");
	}
})(FH);