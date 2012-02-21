<script type="text/javascript">
$(function() {
	function log(str) {
		if (typeof(console) != 'undefined' && typeof(console.log) == 'function') {
			console.log(str);
		}
	}
	var swfUrl = '<?php echo GATEWAY_URL; ?>admin/flash/open-flash-chart.swf',
	id = 'flashDiv',
	width = '100%',
	height = '480',
	version = '9.0.0',
	expressInstallSwfUrl = '',
	flashVars = {
		'data-file': encodeURIComponent('<?php echo $D['data_file_url']?>')
	},
	params = {
		allowScriptAccess: 'always',
		allowFullScreen: 'true',
		quality: 'high',
		wmode: 'opaque',
		bgcolor: '#000',
		menu: 'false'
	},
	attributes = {},
	callbackFn = function(data) {
		if (data.success) {
			log('flash loading success!');
		} else {
			log('flash loading fail!');
		}
	};
	swfobject.embedSWF(swfUrl, id, width, height, version, expressInstallSwfUrl, flashVars, params, attributes, callbackFn);
});
</script>