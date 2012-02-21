var FH = FH || {};
(function($$) {
	/**
	 * 记录调试信息
	 * @param string str 字符串
	 */
	$$.log = function(str) {
		if (typeof(console) != 'undefined' && typeof(console.log) == 'function') {
			console.log(str);
		}
	};
	/**
	 * 页面重新加载
	 */
	$$.pageReload = function () {
		self.location.reload(true);
	};
	/**
	 * 弹出警告提示
	 * @param string msg 错误消息
	 * @param int code 错误代码
	 */
	$$.alert = function(str, code) {
		window.alert(str);
	};
	/**
	 * 设置/获取Cookie信息
	 * @param string key 键
	 * @param string val 值
	 * @param int ttl 有效时间(毫秒)
	 * @param string path 有效路径
	 * @param string domain 有效域
	 */
	$$.cookie = function(key, val, ttl, path, domain) {
		if (typeof(val) != 'undefined') {
			var arr = [];
			arr.push(key + '=' + escape(val));
			if (ttl) {
				var date = new Date();
				date.setTime(date.getTime() + ttl);
				arr.push('expires=' + date.toGMTString());
			}
			if (path) {
				arr.push('path=' + path);
			}
			if (domain && domain != 'localhost') {
				arr.push('domain=' + domain);
			}
			document.cookie = arr.join('; ') + ';';
		} else {
			var arr = document.cookie.split("; ");
			for (var i = 0; i < arr.length; ++i) {
				var pair = arr[i].split("=");
				if (key == pair[0]) {
					return unescape(pair[1]);
				}
			}
			return null;
		}
	};
})(FH);