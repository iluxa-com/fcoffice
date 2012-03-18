function $(objID) {
    return document.getElementById(objID)
};
function openWindow(url, w, h) {
    var iTop = (window.screen.availHeight - 30 - h) / 2;
    var iLeft = (window.screen.availWidth - 10 - w) / 2;
    window.open(url, '', 'height=' + h + ',,innerHeight=' + h + ',width=' + w + ',innerWidth=' + w + ',top=' + iTop + ',left=' + iLeft + ',toolbar=no,menubar=no,scrollbars=no,resizeable=no,location=no,status=no');
    return false
}
function changeTab(a, b) {
    var l = a - 1;
    var o = $('tabList').getElementsByTagName('li');
    var p = $('comicList').getElementsByTagName('ul');
    for (var i = 0; i < a; i++) {
        p[i].style.display = 'none';
        o[i].className = (i == l) ? 'end': ''
    }
    o[b].className = (l == b) ? 'end on': 'on';
    p[b].style.display = 'block'
}
function getRequest() {
    var args = new Object();
    var query = location.search.substring(1);
    var pairs = query.split("&");
    for (var i = 0; i < pairs.length; i++) {
        var pos = pairs[i].indexOf('=');
        if (pos == -1) continue;
        var argname = pairs[i].substring(0, pos);
        var value = pairs[i].substring(pos + 1);
        args[argname] = unescape(value)
    }
    return args
}

