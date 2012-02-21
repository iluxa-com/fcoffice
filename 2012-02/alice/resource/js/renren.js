var FH = FH || {};
(function($$) {
	var appId = 146821;
	var appUrl = 'http://apps.renren.com/dreaming_adventures/';
	var appIcon = 'http://app.xnimg.cn/application/20110727/15/35/LPSH5153o018153.jpg';
	/**
	 * 邀请好友
	 * @link http://wiki.dev.renren.com/wiki/Request_dialog
	 */
	$$.inviteFriend = function () {
		var urlPrefix = 'http://widget.renren.com/dialog/request?',
		paramArr = {
			"app_id": appId,
			"redirect_uri": appUrl + "?ref=invite_send",
			"accept_url": appUrl + "?ref=invite_accept",
			"accept_label": "开通《童话迷城》应用",
			"actiontext": "邀请好友加入《童话迷城》",
			"selector_mode": "naf",
			"app_msg": "快来一起玩《童话迷城》"
		},
		tempArr = [];
		for (var key in paramArr) {
			tempArr.push(key + '=' + encodeURIComponent(paramArr[key]));
		}
		top.location.href = urlPrefix + tempArr.join('&');
	};
	/**
	 * 发送Feed
	 * @param string type 类型
	 * @param array paramArr 参数数组
	 * @link http://wiki.dev.renren.com/wiki/Feed_dialog
	 */
	$$.sendFeed = function (type, paramArr) {
		var feedSettings = {};
		feedSettings.gradeUp = {
			"template_bundle_id": 1,
			"template_data": {
				"images": [{
					"src": appIcon,
					"href": appUrl
				}],
				"grade": paramArr[0]
			},
			"body_general": "《童话迷城》提醒您：分享是一种快乐！",
			"callback": function (data) {
				$$.log(data);
			},
			"user_message_prompt": "分享快乐————发送新鲜事",
			"user_message": "嘿嘿，关卡解谜难度大不是问题，有大神我在，一切就不是问题啦~ "
		};
		feedSettings.exchangeItem = {
			"template_bundle_id": 2,
			"template_data": {
				"images": [{
					"src": appIcon,
					"href": appUrl
				}],
				"name": paramArr[0]
			},
			"body_general": "《童话迷城》提醒您：分享是一种快乐！",
			"callback": function (data) {
				$$.log(data);
			},
			"user_message_prompt": "分享快乐————发送新鲜事",
			"user_message": "这不是开玩笑的，大神我可是来真的，兑换神马的，有木有啊？"
		};
		XN.Connect.showFeedDialog(feedSettings[type]);
	};
})(FH);