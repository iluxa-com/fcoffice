var QC = function() {
    var EMPTY_FUN = function() {};
    var ieVer = window.ActiveXObject && navigator.userAgent.match(/MSIE\s+(\d+)/)[1];
    var $Toolkit = function() {
        var str2dom = function(_str) {
            var _ret = [],
            _cot = arguments.callee._temp = arguments.callee._temp || document.createElement("div");;
            _cot.innerHTML = _str;
            while (_cot.firstChild) {
                _ret.push(_cot.removeChild(_cot.firstChild));
            }
            return _ret.length > 1 ?
            function() {
                var tmp = document.createDocumentFragment();
                for (var i = 0; i < _ret.length; i++) {
                    tmp.appendChild(_ret[i]);
                }
                return tmp;
            } () : _ret[0];
        };
        var format = function(str, obj) {
            return str.replace(arguments.callee._reg,
            function(_i, _1) {
                return obj[_1] !== null ? obj[_1] : _1;
            });
        }
        format._reg = /\{(\w+)\}/g;
        return {
            str2dom: str2dom,
            format: format,
            extend: function(_Cld, _Prt) {
                var fn = EMPTY_FUN;
                fn.prototype = _Prt.prototype;
                _Cld.prototype = new fn();
                _Cld.constructor = _Cld;
                return _Cld;
            }
        }
    } ();
    var $JSON = function() {
        var escapable = /[\\\"\x00-\x1f\x7f-\x9f\u00ad\u0600-\u0604\u070f\u17b4\u17b5\u200c-\u200f\u2028-\u202f\u2060-\u206f\ufeff\ufff0-\uffff]/g,
        meta = {
            '\b': '\\b',
            '\t': '\\t',
            '\n': '\\n',
            '\f': '\\f',
            '\r': '\\r',
            '"': '\\"',
            '\\': '\\\\'
        };
        function quote(string) {
            escapable.lastIndex = 0;
            return escapable.test(string) ? '"' + string.replace(escapable,
            function(a) {
                var c = meta[a];
                return typeof c === 'string' ? c: '\\u' + ('0000' + a.charCodeAt(0).toString(16)).slice( - 4);
            }) + '"': '"' + string + '"';
        }
        function stringify(obj) {
            var ret = [],
            v = "";
            for (var i in obj) {
                v = obj[i] || "";
                switch (typeof v) {
                case 'string':
                    v = quote(v);
                    break;
                case 'object':
                    v = stringify(v);
                    break;
                case 'function':
                    continue;
                }
                ret.push('"' + i + '":' + v);
            }
            return '{' + ret + '}';
        }
        return {
            stringify: function() {
                return window.JSON && JSON.stringify ? JSON.stringify: stringify
            } (),
            parse: function(str) {
                str = str || "{}";
                var ret = {};
                try {
                    ret = (new Function("return (" + str + ")"))()
                } catch(e) {
                    $Console.error("JSON.parse => parse���ݸ�ʽ����:" + str);
                }
                return ret;
            }
        }
    } ();
    var $XML = function() {
        if (document.implementation.hasFeature("XPath", "3.0")) {
            XMLDocument.prototype.selectNodes = function(cXPathString, xNode) {
                if (!xNode) {
                    xNode = this;
                }
                var oNSResolver = this.createNSResolver(this.documentElement) var aItems = this.evaluate(cXPathString, xNode, oNSResolver, XPathResult.ORDERED_NODE_SNAPSHOT_TYPE, null) var aResult = [];
                for (var i = 0; i < aItems.snapshotLength; i++) {
                    aResult[i] = aItems.snapshotItem(i);
                }
                return aResult;
            }
            Element.prototype.selectNodes = function(cXPathString) {
                if (this.ownerDocument.selectNodes) {
                    return this.ownerDocument.selectNodes(cXPathString, this);
                } else {
                    throw "For XML Elements Only";
                }
            }
        }
        var loadXML = function(xmlString) {
            var XMLDoc = ieVer ? new ActiveXObject("Microsoft.XMLDOM") : document.implementation.createDocument("text/xml", "", null);
            if (ieVer) {
                if (XMLDoc.loadXML(xmlString)) {
                    return XMLDoc;
                } else {
                    return null;
                }
            } else {
                try {
                    var childNodes = XMLDoc.childNodes;
                    for (var i = childNodes.length - 1; i >= 0; i--) XMLDoc.removeChild(childNodes[i]);
                    var dp = new DOMParser();
                    var newDOM = dp.parseFromString(xmlString, "text/xml");
                    var newElt = XMLDoc.importNode(newDOM.documentElement, true);
                    XMLDoc.appendChild(newElt);
                    return XMLDoc;
                } catch(ex) {
                    return null;
                }
            }
        }
        return {
            stringify: function(doc) {
                return doc.xml || new XMLSerializer().serializeToString(doc);
            },
            parse: loadXML
        }
    } ();
    var $Object = function() {
        return {
            extend: function() {
                var args = arguments,
                len = arguments.length,
                deep = false,
                i = 1,
                target = args[0],
                opts,
                src,
                clone,
                copy;
                if (typeof target === "boolean") {
                    deep = target;
                    target = arguments[1] || {};
                    i = 2;
                }
                if (typeof target !== "object" && typeof target !== "function") {
                    target = {};
                }
                if (len === i) {
                    target = {}; --i;
                }
                for (; i < len; i++) {
                    if ((opts = arguments[i]) != null) {
                        for (var name in opts) {
                            src = target[name];
                            copy = opts[name];
                            if (target === copy) {
                                continue;
                            }
                            if (deep && copy && typeof copy === "object" && !copy.nodeType) {
                                if (src) {
                                    clone = src;
                                } else if (copy instanceof Array) {
                                    clone = [];
                                } else if (typeof copy === 'object') {
                                    clone = {};
                                } else {
                                    clone = copy;
                                }
                                target[name] = object.extend(deep, clone, copy);
                            } else if (copy !== undefined) {
                                target[name] = copy;
                            }
                        }
                    }
                }
                return target;
            }
        }
    } ();
    var $QueryString = function() {
        var re = /"/g;
        var tool = {
            genHttpParamString: function(o) {
                return this.commonDictionaryJoin(o, null, null, null, window.encodeURIComponent);
            },
            splitHttpParamString: function(s) {
                return this.commonDictionarySplit(s, null, null, null, window.decodeURIComponent);
            },
            commonDictionarySplit: function(s, esp, vq, eq, valueHandler) {
                var res = {},
                l, ks, vs, t, vv;
                if (!s || typeof(s) != "string") {
                    return res;
                }
                if (typeof(esp) != 'string') {
                    esp = "&";
                }
                if (typeof(vq) != 'string') {
                    vq = "";
                }
                if (typeof(eq) != 'string') {
                    eq = "=";
                }
                l = s.split(esp);
                if (l && l.length) {
                    for (var i = 0,
                    len = l.length; i < len; ++i) {
                        ks = l[i].split(eq);
                        if (ks.length > 1) {
                            t = ks.slice(1).join(eq);
                            vs = t.split(vq);
                            vv = vs.slice(vq.length, vs.length - vq.length).join(vq);
                            res[ks[0]] = (typeof valueHandler == 'function' ? valueHandler(vv) : vv);
                        } else {
                            ks[0] && (res[ks[0]] = true);
                        }
                    }
                }
                return res;
            },
            commonDictionaryJoin: function(o, esp, vq, eq, valueHandler) {
                var res = [],
                t,
                ok;
                if (!o || typeof(o) != "object") {
                    return '';
                }
                if (typeof(o) == "string") {
                    return o;
                }
                if (typeof(esp) != 'string') {
                    esp = "&";
                }
                if (typeof(vq) != 'string') {
                    vq = "";
                }
                if (typeof(eq) != 'string') {
                    eq = "=";
                }
                for (var k in o) {
                    ok = (o[k] + "").replace(re, "\\\"");
                    res.push(k + eq + vq + (typeof valueHandler == 'function' ? valueHandler(ok) : ok) + vq);
                }
                return res.join(esp);
            }
        }
        return {
            stringify: function(obj) {
                return tool.genHttpParamString(obj);
            },
            parse: function(str) {
                return tool.splitHttpParamString(str);
            }
        }
    } ();
    var $String = function() {
        var res = [/&/g, /</g, />/g, /\x27/g, /\x22/g],
        rep = ['&amp;', '&lt;', '&gt;', '&#039;', '&quot;'];
        return {
            escHTML: function(str) {
                var ret = str;
                for (var i = 0,
                l = res.length; i < l; i++) {
                    ret = ret.replace(res[i], rep[i]);
                }
                return ret;
            },
            format: $Toolkit.format
        }
    } ();
    var $Cookie = function() {
        var domainPrefix = document.domain || "";
        return {
            set: function(name, value, domain, path, hour) {
                if (hour) {
                    var expire = new Date();
                    expire.setTime(expire.getTime() + 3600000 * hour);
                }
                document.cookie = name + "=" + value + "; " + (hour ? ("expires=" + expire.toGMTString() + "; ") : "") + (path ? ("path=" + path + "; ") : "path=/; ") + (domain ? ("domain=" + domain + ";") : ("domain=" + domainPrefix + ";"));
                return true;
            },
            get: function(name) {
                var r = new RegExp("(?:^|;+|\\s+)" + name + "=([^;]*)"),
                m = document.cookie.match(r);
                return (!m ? "": m[1]);
            },
            del: function(name, domain, path) {
                document.cookie = name + "=; expires=Mon, 26 Jul 1997 05:00:00 GMT; " + (path ? ("path=" + path + "; ") : "path=/; ") + (domain ? ("domain=" + domain + ";") : ("domain=" + domainPrefix + ";"));
            }
        }
    } ();
    var $Console = function() {
        var _QC_CONSOLE_DEBUG_LEVEL = 2;
        var cons_prefix = ' :: [QQConnect] > ';
        var trace = function(funName) {
            return function(args) {
                window.console && console[funName] && getDebugLevel() >= LEVELS[funName] && console[funName](cons_prefix + args);
            }
        };
        var LEVELS = {
            log: 3,
            info: 2,
            warn: 1,
            error: 0
        };
        var getDebugLevel = function() {
            return~~ (_QC_CONSOLE_DEBUG_LEVEL || LEVELS.info);
        };
        return {
            log: trace("log"),
            info: trace("info"),
            warn: trace("warn"),
            error: trace("error"),
            setLevel: function(lvNm) {
                return _QC_CONSOLE_DEBUG_LEVEL = LEVELS[lvNm] || _QC_CONSOLE_DEBUG_LEVEL;
            }
        }
    } ();
    var Like = function() {
        return {
            _insertButton: EMPTY_FUN
        }
    };
    var Share = function() {
        return {}
    };
    return {
        Like: Like(),
        Share: Share(),
        Toolkit: $Toolkit,
        JSON: $JSON,
        XML: $XML,
        Object: $Object,
        QueryString: $QueryString,
        String: $String,
        Cookie: $Cookie,
        Console: $Console
    }
} (); (function(_qc) {
    var $ = function(_) {
        return typeof(_) == "string" ? document.getElementById(_) : _
    };
    var resDomain = "qzonestyle.gtimg.cn",
    mainDomain = "qzs.qq.com";
    var $Toolkit = _qc.Toolkit,
    $JSON = _qc.JSON,
    $XML = _qc.XML,
    $Object = _qc.Object,
    $QueryString = _qc.QueryString,
    $String = _qc.String,
    $Cookie = _qc.Cookie,
    $Console = _qc.Console;
    var EMPTY_FUN = function() {};
    var ieVer = window.ActiveXObject && navigator.userAgent.match(/MSIE\s+(\d+)/)[1];
    var config = {
        PMCrossPage: 'https://graph.qq.com/jsdkproxy/PMProxy.html',
        FLACrossPage: 'https://graph.qq.com/jsdkproxy/FLAProxy.swf',
        getCrossSolution: function() {
            var solution;
            if (window.postMessage && (!ieVer)) {
                solution = "PMProxy";
            } else if ( !! ((window.ActiveXObject && !!
            function() {
                var ret = true;
                try {
                    new ActiveXObject('ShockwaveFlash.ShockwaveFlash')
                } catch(e) {
                    ret = false;
                }
                return ret;
            } ()) || (navigator.plugins && navigator.plugins["Shockwave Flash"]) || false)) {
                solution = "FLAProxy";
            } else {
                _qc.Console.error("δ�ҵ����õĿ���ͨ�ŷ���");
                solution = "EMPProxy";
            }
            _qc.Console.info("ȷ������������ԣ�" + solution);
            return solution;
        }
    };
    var sequence = 1000;
    _qc.getConfig = function() {
        return config;
    };
    var Request = function(uri, paras, fmt, method) {
        this.uri = uri;
        this.paras = paras || {};
        this.fmt = fmt || "json";
        this.method = (method || "get").toLocaleLowerCase();
        this.successPool = [];
        this.errorPool = [];
        this.completePool = [];
        this.seq = sequence++;
    };
    Request.prototype.success = function(fun) {
        this.successPool.push(fun);
        return this;
    };
    Request.prototype.error = function(fun) {
        this.errorPool.push(fun);
        return this;
    };
    Request.prototype.complete = function(fun) {
        this.completePool.push(fun);
        return this;
    };
    Request.prototype.send = EMPTY_FUN;
    Request.prototype._onCallback = function(xhr, fmt, seq) {
        if (xhr.status == 200 || xhr.status == 204) {
            var responseText = xhr.responseText,
            response = new Response(responseText, xhr.status, fmt, seq); ! ~~response.code ? this.onSuccess(response) : this.onError(response);
        } else {
            this.onError(new Response("", xhr.status, fmt, seq));
        }
    };
    Request.prototype.onSuccess = function(response) {
        var pool = this.successPool;
        for (var i = 0; i < pool.length; i++) {
            pool[i](response);
        }
        this.onComplete(response);
    };
    Request.prototype.onError = function(response) {
        var pool = this.errorPool;
        for (var i = 0; i < pool.length; i++) {
            pool[i](response);
        }
        this.onComplete(response);
    };
    Request.prototype.onComplete = function(response) {
        var pool = this.completePool;
        for (var i = 0; i < pool.length; i++) {
            pool[i](response);
        }
    };
    var Response = function(respData, status, fmt, seq) {
        this.status = status || -1;
        this.fmt = fmt || "json";
        this.code = this.ret = -1;
        this.data = null;
        this.seq = seq || -1;
        this.parseData(respData);
        if (this.code && Response[this.code]) {
            Response[this.code](this.data, this.dataText);
        }
    };
    Response.prototype.parseData = function(rd) {
        this.dataText = rd;
        switch (this.fmt) {
        case "xml":
            this.data = _qc.XML.parse(rd || '<root></root>');
            var node = this.data.selectNodes("//ret")[0];
            this.code = this.ret = (node && node.firstChild.nodeValue) || -1;
            break;
        case "json":
        default:
            this.data = _qc.JSON.parse(rd || '{}');
            this.code = this.ret = this.data.ret !== undefined ? ~~this.data.ret: this.data.data && this.data.data.ret !== undefined ? ~~this.data.data.ret: -1;
            break;
        }
    };
    Response.prototype.stringifyData = function() {
        return this.dataText;
    };
    Response[100013] = function(dt, dtTxt) {
        _qc.Login.signOut();
        _qc.Console.warn("api����tokenʧЧ");
    };
    var XHRRequest = _qc.Toolkit.extend(function() {
        Request.apply(this, arguments);
        this.xhr = XHRRequest.createInstance();
    },
    Request);
    _qc.Object.extend(XHRRequest.prototype, {
        send: function() {
            var xhr = this.xhr,
            method = this.method,
            fmt = this.fmt,
            me = this,
            paras = _qc.QueryString.stringify(me.paras),
            uri = method == "post" ? this.uri: this.uri.indexOf("?") < 0 ? this.uri + '?' + paras: this.uri.replace(/[&?]*/g, "") + '&' + paras;
            xhr.open(method, uri, !!this.async);
            try {
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.setRequestHeader("X-Requested-From", "_TC_QC_jsProxy_");
            } catch(e) {}
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4) {
                    me._onCallback(xhr, fmt, me.seq);
                }
            };
            xhr.send(paras || null);
        }
    });
    XHRRequest.createInstance = window.XMLHttpRequest ?
    function() {
        return new window.XMLHttpRequest();
    }: function() {
        return new window.ActiveXObject("Microsoft.XMLHTTP");
    };
    var Proxy = function() {
        this.requests = [];
        this.invokes = [];
        this.readyPool = [];
        this.isReady = false;
        this.timeStamp = +new Date();
        this.init();
    };
    Proxy.prototype.init = EMPTY_FUN;
    Proxy.prototype.ready = function(fun) {
        this.readyPool.push(fun);
    };
    Proxy.prototype.onReady = function() {
        this.isReady = true;
        var pool = this.readyPool;
        for (var i = 0; i < pool.length; i++) {
            pool[i]();
        }
    };
    Proxy.prototype.send = function(req) {
        var pendingRequest;
        req && (this.requests.push(req));
        while (this.isReady && (pendingRequest = this.requests.shift())) {
            Proxy.pendingRequests.push(pendingRequest);
            QC.Console.log("seq no :" + pendingRequest.seq + "������" + "  ts -> " + ( + new Date()));
            this._doSend(pendingRequest);
        }
    };
    Proxy.prototype._doSend = function(req) {};
    Proxy.prototype._preDispatch = function(_this, cstDt, seq, fmt) {
        var dt = cstDt.data || (cstDt.currentTarget && cstDt.currentTarget.data) || {};
        var cmd = dt.split("@@@");
        switch (cmd[0]) {
        case "invoke":
            this.invoke(cmd[1]);
            break;
        default:
            this.dispatch(cmd[1] || cstDt, seq, fmt);
            break;
        }
    };
    Proxy.prototype.invoke = function(ivk) {
        var pendingInvoke;
        ivk && (this.invokes.push(ivk));
        while (this.isReady && (pendingInvoke = this.invokes.shift())) {
            this._doInvoke(pendingInvoke);
        }
    };
    Proxy.prototype._doInvoke = function(ivk) {};
    Proxy.prototype.dispose = function() {
        _proxy = null;
        this.onDispose();
    };
    Proxy.prototype.onDispose = function() {};
    Proxy.pendingRequests = [];
    Proxy.dispatchReceive = function(seq, resText, status, fmt) {
        var pendingRequests = Proxy.pendingRequests;
        for (var i = 0; i < pendingRequests.length; i++) {
            if (pendingRequests[i].seq == seq) {
                QC.Console.log("seq no :" + seq + "��Ӧ�յ�" + "  ts -> " + ( + new Date()));
                pendingRequests[i]._onCallback({
                    status: status,
                    responseText: resText
                },
                fmt, seq);
                pendingRequests.splice(i, 1);
                return;
            }
        }
    };
    Proxy.invoke = function() {
        var _pendingPool = [];
        return function(cmdStr) {
            cmdStr && _pendingPool.push(cmdStr);
            if (!_proxy) {
                _qc.Console.info("Proxyδ��ʼ����invoke��ջ");
                Proxy.generateProxy();
                return;
            }
            var _crtIvk;
            while (_crtIvk = _pendingPool.shift()) {
                _proxy._doInvoke(_crtIvk);
            }
        }
    } ();
    var _proxy;
    Proxy.generateProxy = function() {
        var _solutions = {
            PMProxy: PMProxy,
            FLAProxy: FLAProxy,
            EMPProxy: EMPProxy
        };
        if (!_proxy) {
            _proxy = new _solutions[config.getCrossSolution()]();
        }
        return _proxy;
    };
    Proxy.getFunction = function(cmdP) {
        var cmd;
        cmdP = cmdP.split(".");
        for (var i = 0; i < cmdP.length; i++) {
            cmd = cmd ? cmd[cmdP[i]] : window[cmdP[i]];
        }
        return cmd;
    };
    var FLAProxy = _qc.Toolkit.extend(function() {
        Proxy.apply(this, arguments);
    },
    Proxy);
    _qc.Object.extend(FLAProxy.prototype, {
        prefix: '_TC_QC_flaProxy_',
        init: function() {
            var _me = this,
            conId = function() {
                var mat = window.name.match(/oauth2Login_(\d+)/),
                __qc_wId = ~~_qc.Cookie.get("__qc_wId"),
                ret = ~~ ((mat && mat[1]) || ( + new Date() % 1000)); ! mat && (document.cookie = ["__qc_wId=" + ret, "; path=/"].join(";"));
                _qc.Console.info("���򴰿ڱ�ʶ�� __qc_wId : " + __qc_wId);
                return ret;
            } (),
            receiveId = conId < 10000 ? conId + 10000 : conId - 10000;
            var flaStr = FLAProxy.getFlashHtml({
                "src": config.FLACrossPage,
                "width": "100%",
                "height": "100%",
                "allowScriptAccess": "always",
                "id": "_qc_cross_request_flash_proxy",
                "name": "_qc_cross_request_flash_proxy",
                "flashVars": "suffix=" + (this.timeStamp) + "&conId=" + conId + "&conId_receive=" + receiveId
            });
            var cot = this.cot = document.createElement("div");
            cot.style.cssText = "position:fixed; _position:absolute; top:30px; left:30px; width:3px; height:3px; margin:0; padding:0; display:none;";
            cot.innerHTML = flaStr;
            document.body.appendChild(cot);
            cot.style.display = "block";
            window[this.prefix + 'onFlashReady_' + this.timeStamp] = function() {
                _qc.Console.info("FLAProxy���������ɹ�����ʱ" + (new Date() - _me.timeStamp));
                setTimeout(function() {
                    _me.isReady = true;
                    _me.send();
                    _me.invoke();
                });
                if (!_qc.Login._check()) {
                    document['_qc_cross_request_flash_proxy'].initConn();
                }
            };
            window[this.prefix + 'onFlashRequestComplete_' + this.timeStamp] = function(cstDt, seq, fmt) {
                setTimeout(function() {
                    _me._preDispatch(_me, cstDt, seq, fmt);
                });
            };
            window[this.prefix + 'onFlashInvokeBack_' + this.timeStamp] = function() {
                var arg = arguments;
                setTimeout(function() {
                    var fun = Proxy.getFunction(arg[0]);
                    var paras = arg[1];
                    arg[0].indexOf(".") > -1 ? fun.apply(null, paras) : fun(paras);
                });
            };
        },
        _doSend: function(regObj) {
            var uri = regObj.uri,
            paras = _qc.QueryString.stringify(regObj.paras),
            seq = regObj.seq,
            fmt = regObj.fmt,
            method = regObj.method;
            var proxyFun = document['_qc_cross_request_flash_proxy'].httpRequest;
            proxyFun ? proxyFun(uri, paras, method, fmt, seq) : (!
            function() {
                throw new Error("flash proxy ��ʼ��ʧ��")
            } ());
        },
        dispatch: function(cstDt, seq, fmt) {
            var data = cstDt.currentTarget.data,
            status = cstDt.type != "complete" ? 404 : 200;
            Proxy.dispatchReceive(seq, data, status, fmt);
        },
        _doInvoke: function(args) {
            var fun = document['_qc_cross_request_flash_proxy'].jsCallSwf;
            fun && fun.apply(null, args);
        }
    });
    FLAProxy.getFlashHtml = function(flashArguments, requiredVersion, flashPlayerCID) {
        var _attrs = [],
        _params = [],
        _isIE = !!window.ActiveXObject;
        requiredVersion = requiredVersion || 9;
        for (var k in flashArguments) {
            switch (k) {
            case "noSrc":
            case "movie":
                continue;
                break;
            case "id":
            case "name":
            case "width":
            case "height":
            case "style":
                if (typeof(flashArguments[k]) != 'undefined') {
                    _attrs.push(' ', k, '="', flashArguments[k], '"');
                }
                break;
            case "src":
                if (_isIE) {
                    _params.push('<param name="movie" value="', (flashArguments.noSrc ? "": flashArguments[k]), '"/>');
                } else {
                    _attrs.push(' data="', (flashArguments.noSrc ? "": flashArguments[k]), '"');
                }
                break;
            default:
                _params.push('<param name="', k, '" value="', flashArguments[k], '" />');
            }
        }
        if (_isIE) {
            _attrs.push(' classid="clsid:', flashPlayerCID || 'D27CDB6E-AE6D-11cf-96B8-444553540000', '"');
        } else {
            _attrs.push(' type="application/x-shockwave-flash"');
        }
        if (location && location.protocol.indexOf("https") < 0) {
            _attrs.push(' codeBase="http://fpdownload.macromedia.com/get/flashplayer/current/swflash.cab#version=', requiredVersion, '"');
        }
        return "<object" + _attrs.join("") + ">" + _params.join("") + "</object>";
    };
    var PMProxy = _qc.Toolkit.extend(function() {
        Proxy.apply(this, arguments);
    },
    Proxy);
    _qc.Object.extend(PMProxy.prototype, {
        init: function() {
            var _me = this;
            _me._connFrame = document.createElement("iframe");
            _me._connFrame.style.cssText = "width:0px; height:0px; display:none; overflow:hidden;";
            _me._connFrame.src = config.PMCrossPage;
            _me._connFrame.onload = function() {
                _qc.Console.info("PMProxy���������ɹ�����ʱ" + (new Date() - _me.timeStamp));
                _me.isReady = true;
                _me.send();
                _me.invoke();
            };
            document.body.appendChild(_me._connFrame);
            var _preDispatchHandler = function(crsDt) {
                _me._preDispatch(_me, crsDt);
            };
            window.addEventListener ? window.addEventListener("message", _preDispatchHandler, false) : window.attachEvent("onmessage", _preDispatchHandler);
        },
        _doSend: function(regObj) {
            var str = _qc.QueryString.stringify({
                uri: regObj.uri,
                paras: _qc.QueryString.stringify(regObj.paras),
                fmt: regObj.fmt,
                method: regObj.method
            });
            this._connFrame.contentWindow.postMessage(str, "*");
        },
        dispatch: function(crsDt) {
            var dt = crsDt.data,
            obj = dt.split("#"),
            seq = obj[0],
            status = obj[1],
            fmt = obj[2],
            data = obj[3];
            _qc.Console.log("data:\t" + data);
            Proxy.dispatchReceive(seq, data, status, fmt);
        },
        _doInvoke: function(cmdStr) {
            _qc.Console.log("invoke:\t" + cmdStr);
            if (typeof cmdStr != "string") {
                return;
            }
            var pas = cmdStr.split("#"),
            cmdP = pas[0],
            args = pas[1] && pas[1].split(","),
            cmd;
            cmd = Proxy.getFunction(cmdP);
            cmd.apply(null, args);
        },
        onDispose: function() {
            this._connFrame.parentNode.removeChild(this._connFrame);
            this._connFrame = null;
        }
    });
    var EMPProxy = _qc.Toolkit.extend(function() {
        Proxy.apply(this, arguments);
    },
    Proxy);
    _qc.Object.extend(EMPProxy.prototype, {
        init: function() {
            _qc.Console.info("init:" + arguments)
        },
        _doSend: function(regObj) {
            _qc.Console.info("_doSend:" + arguments)
        },
        dispatch: function(crsDt) {
            _qc.Console.info("dispatch:" + arguments)
        }
    });
    _qc.XHRRequest = XHRRequest;
    _qc.request = function(uri, paras, fmt, method) {
        return new XHRRequest(uri, paras, fmt, method);
    };
    var fun_ready_pool = [];
    _qc.api = function() {
        var _pendingPool = [];
        var bindTokenPara = function(req) {
            var TKObj = _qc.Login._getTokenKeys();
            if (appId <= 0) throw new Error("����ĵ����˰�token��req�ķ��� bindTokenPara");
            req.paras.oauth_consumer_key = appId;
            req.paras.access_token = TKObj.accessToken;
            req.paras.openid = TKObj.openid;
            req.paras.format = req.fmt;
            return req;
        };
        var fun = function(api, paras, fmt, method) {
            _proxy = Proxy.generateProxy();
            var defAPICfg = getAPIConfig(api);
            api = defAPICfg.api || api;
            paras = paras || {};
            method = method || defAPICfg.method;
            var req = new Request(api, paras, fmt, method);
            if (appId > 0) {
                setTimeout(function() {
                    var aToken = _qc.Login._getTokenKeys();
                    if (aToken.openid && aToken.accessToken) {
                        _proxy.send(bindTokenPara(req));
                    } else {
                        _pendingPool.push(req);
                        _qc.Console.warn("openid��accessToken��ʧ������������ջ : [" + api + "]��ջ��С��" + _pendingPool.length);
                    }
                },
                10);
            } else {
                _pendingPool.push(req);
                _qc.Console.warn((_proxy.isReady && appId < 0 ? "token��ȡʧ�ܣ�������û���¼����": "api������δ��ʼ���ɹ�") + "������������ջ : [" + api + "]��ջ��С��" + _pendingPool.length);
            }
            return req;
        };
        fun._ready = function() {
            _qc.Console.info("init�ɹ�����ʼ����api���ö�ջ");
            var _crtReq;
            while (_crtReq = _pendingPool.shift()) {
                _proxy.send(bindTokenPara(_crtReq));
            }
        };
        fun.getDoc = function() {
            var DOCS = null,
            loadDocJS = function(key, fun) {
                var script = document.createElement("script");
                script.type = "text/javascript";
                script.src = "http://qzonestyle.gtimg.cn/qzone/openapi/qc_jsdkdoc.js";
                document.body.appendChild(script);
                window.on_qc_jsdkdoc_loaded = function(_docs) {
                    DOCS = _docs;
                    fun && fun(DOCS[key]);
                    document.body.removeChild(script);
                    script = null;
                };
            };
            return function(key, fun) { (DOCS && fun) ? fun(DOCS[key]) : loadDocJS(key, fun || EMPTY_FUN);
            };
        } ();
        return fun;
    } ();
    var Login = function() {
        var _openId, access_token, dt_cache;
        var BUTTON_STYLE = {
            A_XL: {
                styleId: 5,
                size: '230*48'
            },
            A_L: {
                styleId: 4,
                size: '170*32'
            },
            A_M: {
                styleId: 3,
                size: '120*24'
            },
            A_S: {
                styleId: 2,
                size: '105*16'
            },
            B_M: {
                styleId: 7,
                size: '63*24'
            },
            B_S: {
                styleId: 6,
                size: '50*16'
            },
            C_S: {
                styleId: 1,
                size: '16*16'
            }
        };
        function _insertButton(opts) {
            if (opts.clientId) {
                QC.init({
                    appId: opts.clientId
                });
            }
            var appid = QC.getAppId();
            if (appid < 0) {
                QC.getAppId(arguments);
                return;
            }
            opts.size = opts['size'] || 'B_M';
            var btn = $(opts['btnId']),
            sizeObj = BUTTON_STYLE[opts['size']] || BUTTON_STYLE['B_M'],
            size = sizeObj['styleId'],
            fullWindow = opts['fullWindow'] || false,
            btnMode = opts['btnMode'] || 'standard';
            var url = arguments.callee._getPopupUrl(opts);
            var parasObj = {
                size: size,
                fullWindow: fullWindow,
                url: url
            };
            if (opts && opts['btnId']) {
                if (btn) {
                    btn.innerHTML = arguments.callee.getBtnHtml(parasObj, btnMode, opts);
                    var onclick = btn.firstChild.onclick; (btn.firstChild.onclick = function(_a) {
                        var crtPop, _close = function() {
                            crtPop && crtPop.close();
                        };
                        window.addEventListener ? window.addEventListener("unload", _close, false) : window.attachEvent("onunload", _close);
                        return function() {
                            if (crtPop) {
                                crtPop.close();
                            }
                            crtPop = _a();
                        }
                    } (onclick));
                } else {
                    throw new Error('δ�ҵ�����ڵ�:')
                }
            }
        }
        _insertButton.TEMPLATE = ['<a href="javascript:;" onclick="{onclick}"><img src="{src}" alt="{alt}" border="0"/></a>'].join('');
        _insertButton.getBtnHtml = function(parasOpts, btnMode, opts) {
            return arguments.callee.MODE[btnMode] && arguments.callee.MODE[btnMode](parasOpts, opts);
        };
        _insertButton.getBtnHtml.MODE = {
            "standard": function(parasObj) {
                var windowId = ~~$Cookie.get("__qc_wId") + 10000;
                var baseStr = $Toolkit.format(_insertButton.TEMPLATE, {
                    src: 'http://' + resDomain + '/qzone/vas/opensns/res/img/Connect_logo_' + parasObj.size + '.png',
                    onclick: parasObj.fullWindow ? 'return window.open(\'' + parasObj.url + '\', \'oauth2Login_' + windowId + '\');': 'return window.open(\'' + parasObj.url + '\', \'oauth2Login_' + windowId + '\' ,\'height=525,width=585, toolbar=no, menubar=no, scrollbars=no, status=no, location=yes, resizable=yes\')',
                    alt: 'QQ��¼'
                });
                return baseStr;
            },
            "showUserAfterLogin": function(parasObj, opts) {
                var baseStr = $JSON.stringify(opts),
                sizeObj = BUTTON_STYLE[opts['size']] || BUTTON_STYLE['B_M'],
                size = sizeObj['size'].split("*"),
                outerStr = '<iframe frameBorder="0" scrolling="no" src="http://' + mainDomain + '/qzone/openapi/frames/login_button.html#para=' + encodeURIComponent(baseStr) + '" width="' + Math.max(200, size[0]) + '" height="' + size[1] + '" allowTransparency="true"></iframe>';
                return outerStr;
            }
        };
        _insertButton._getPopupUrl = function(opts) {
            var scope = opts['scope'] || 'all',
            redirectURI = opts['redirectURI'] || (ieVer <= 6 ? 'https%3A%2F%2Fgraph.qq.com%2Fjsdkproxy%2Fredirect.html': 'http%3A%2F%2Fqzonestyle.gtimg.cn%2Fqzone%2Fopenapi%2Fredirect.html'),
            display = opts['display'] || '',
            clientId = QC.getAppId();
            var paras = ['client_id=' + clientId, 'response_type=token'],
            url = 'https://graph.qq.com/oauth2.0/authorize';
            if (scope) {
                paras.push('scope=' + scope);
            }
            if (redirectURI) {
                if (redirectURI.indexOf('?') > 0) {
                    redirectURI = encodeURIComponent(redirectURI);
                }
                paras.push('redirect_uri=' + redirectURI);
            }
            if (display == 'mobile') {
                paras.push('display=' + display);
            }
            url = url + "?" + paras.join("&");
            return url;
        };
        var _getACToken = function() {
            return access_token ||
            function() {
                var matcher = location && location.hash.match(/access_token=([^&]*)/i),
                _timer,
                _cbPool = [],
                _keys;
                var __qc__k;
                if ((__qc__k = $Cookie.get("__qc__k"))) {
                    _keys = __qc__k.split("=");
                    if (__qc__k.length && _keys.length != 2) throw new Error("QQConnect -> cookie : __qc__k ��ʽ�Ƿ�");
                }
                access_token = (matcher && matcher[1]) || (_keys && _keys[1]);
            } ();
        };
        var _getMe = function() {
            var _timer, _cbPool = [];
            _getACToken();
            var _fireCallBack = function(openId, accessToken, _dt) {
                var _crtReq;
                while (_crtReq = _cbPool.shift()) {
                    setTimeout(function(_rq) {
                        return function() {
                            _rq(openId, accessToken, _dt);
                        }
                    } (_crtReq));
                }
            };
            var _getMeError = function(error_description) {
                $Console.error(error_description + " : _getMeError");
                window.callback({
                    error_description: error_description
                });
            };
            var script, fn;
            var getOpenId = function(_cb) { ! fn && (fn = window.callback);
                _cb && getOpenId.cbPool.push(_cb);
                window.callback = function(_dt) {
                    clearTimeout(_timer);
                    if (!_dt.openid) {
                        return;
                    }
                    dt_cache = _dt;
                    var openId = _openId = _dt.openid;
                    var aToken = access_token;
                    $Console.log(" getMe => openId & accessToken " + [openId, aToken, script ? 'ͨ��me�ӿ�': 'ͨ������']);
                    var cCb;
                    while (cCb = getOpenId.cbPool.shift()) {
                        cCb(openId, access_token, _dt);
                    }
                    script = null;
                    window.callback = fn;
                };
                if (!_getACToken()) {
                    _getMeError("access_token��ʧ");
                    return;
                }
                if (dt_cache) {
                    window.callback(dt_cache);
                } else {
                    if (script) return;
                    script = document.createElement("script");
                    script.type = "text/javascript";
                    script.src = "https://graph.qq.com/oauth2.0/me?access_token=" + access_token;
                    script.onerror = function() {
                        _getMeError("me�ӿڷ��ظ�ʽ����");
                    };
                    document.body.appendChild(script);
                    _timer = setTimeout(function() {
                        _getMeError("me�ӿڳ�ʱ");
                    },
                    5000);
                }
            };
            getOpenId.cbPool = [];
            return function(_cb, _isInitFn) {
                _isInitFn ? _cbPool.unshift(_cb) : _cbPool.push(_cb);
                getOpenId(_fireCallBack);
            }
        } ();
        var _signOut = function() {
            dt_cache = null;
            access_token = undefined;
            _user_info = null;
            var logoutFun;
            for (var i = 0; i < _logoutFuns.length; i++) {
                logoutFun = _logoutFuns[i];
                logoutFun();
            }
        };
        var _showPopup = function(opts) {
            var url = _insertButton._getPopupUrl(opts || {});
            return window.open(url);
        };
        var _DEF_LOGIN_FUN = function(dt, opts) {
            QC.Login.fillUserInfo(opts['btnId'], dt);
        };
        var _loginFuns = [],
        _logoutFuns = [],
        _user_info;
        var retFun = function(opts, loginFun, logoutFun, __outCallFlag) {
            var args = arguments; ! __outCallFlag && logoutFun !== null && _logoutFuns.push(function(__opts) {
                return function() {
                    var argPara = [args[0], null, null, 1]; (logoutFun || EMPTY_FUN)(__opts);
                    args.callee.apply(null, argPara);
                }
            } (opts));
            var _loginFun, loginFunFire = function() {
                for (var i = 0; i < _loginFuns.length; i++) {
                    _loginFun = _loginFuns[i];
                    _loginFun(_user_info);
                }
            }; ! __outCallFlag && loginFun !== null && _loginFuns.push(function(__opts) {
                return function(dt) { (loginFun || _DEF_LOGIN_FUN)(dt, __opts);
                }
            } (opts));
            if (!_user_info) {
                access_token ? _getMe(function(openId) {
                    if (openId) {
                        QC.api("get_user_info").success(function(req) {
                            _user_info = req.data;
                            loginFunFire();
                        }).error(function(req) {
                            QC.Console.error("Login => getMe ��ȡ����ʧ��" + req);
                        });
                    } else {
                        _insertButton(opts);
                    }
                }) : _insertButton(opts);;
            } else {
                _user_info && loginFunFire();
            }
        };
        $Object.extend(retFun, {
            insertButton: _insertButton,
            getMe: _getMe,
            showPopup: _showPopup,
            signOut: function() {
                _openId = "";
                document.cookie = ["__qc__k=", "path=/;"].join(";");
                _signOut();
            },
            _getTokenKeys: function() {
                return {
                    openid: _openId,
                    accessToken: access_token
                }
            },
            check: function() {
                return !! access_token;
            },
            _check: function() {
                return !! (dt_cache && access_token && _user_info);
            },
            _onLoginBack: function(openId, aToken) {
                if (openId && aToken) {
                    _openId = openId;
                    document.cookie = ['__qc__k=' + ["TC_MK", aToken].join("="), 'path=/'].join(";");
                }
                dt_cache = {
                    client_id: -1,
                    openid: _openId
                };
                _getACToken();
                QC.init();
                QC.Login({},
                null, null, 1);
                QC.invoke("close");
            },
            reset: function() {
                _loginFuns = [];
                _logoutFuns = [];
            },
            fillUserInfo: function(btnId, reqData) {
                var dom = $(btnId),
                _logoutTemplate = ['<span class="qc_item figure"><img src="{figureurl}" class="{size_key}"/></span>', '<span class="qc_item nickname" style="margin-left:6px;">{nickname}</span>', '<span class="qc_item logout"><a href="javascript:QC.Login.signOut();" style="margin-left:6px;">�˳�</a></span>'].join("");
                dom && (dom.innerHTML = QC.String.format(_logoutTemplate, {
                    nickname: QC.String.escHTML(reqData.nickname),
                    figureurl: reqData.figureurl
                }));
            }
        });
        return retFun;
    };
    _qc.Login = Login();
    var appId = -1,
    tmpCfg = null;
    _qc.init = function(cfg) {
        cfg = cfg || tmpCfg || {};
        tmpCfg = cfg;
        var TKObj = _qc.Login._getTokenKeys();
        appId = cfg.appId;
        if (!TKObj.openid) {
            Proxy.invoke();
            if (fun_ready_pool.length && cfg.appId > -1) {
                for (var i = 0; i < fun_ready_pool.length; i++) {
                    fun_ready_pool[i]();
                }
            }
            return;
        }
        _qc.Login.getMe(function(oid, token, dt) {
            if (!~~dt.error && (dt.client_id <= 0 || dt.client_id % 1000000 == cfg.appId % 1000000)) {
                appId = dt.client_id = cfg.appId || cfg.clientId || cfg.app_id || cfg.client_id;
                _qc.api._ready && _qc.api._ready();
            } else {
                _qc.Console.error(dt.error_description || "appId/client_id ��ƥ��");
            }
        },
        true);
    };
    _qc.getAppId = function(ars) {
        if (ars) {
            fun_ready_pool.push(function() {
                ars.callee.apply(null, ars);
            });
        }
        return appId;
    };
    _qc.invoke = function() {
        var pxy = Proxy.generateProxy();
        pxy.invoke(arguments);
    };
    var getAPIConfig = function() {
        var API_DICT = {
            "get_user_info": {
                api: "https://graph.qq.com/user/get_user_info",
                method: "get"
            },
            "add_topic": {
                api: "https://graph.qq.com/shuoshuo/add_topic",
                method: "post"
            },
            "add_one_blog": {
                api: "https://graph.qq.com/blog/add_one_blog",
                method: "post"
            },
            "add_album": {
                api: "https://graph.qq.com/photo/add_album",
                method: "post"
            },
            "upload_pic": {
                api: "https://graph.qq.com/photo/upload_pic",
                method: "post"
            },
            "list_album": {
                api: "https://graph.qq.com/photo/list_album",
                method: "get"
            },
            "add_share": {
                api: "https://graph.qq.com/share/add_share",
                method: "post"
            },
            "check_page_fans": {
                api: "https://graph.qq.com/user/check_page_fans",
                method: "get"
            },
            "add_t": {
                api: "https://graph.qq.com/t/add_t",
                method: "post"
            },
            "add_pic_t": {
                api: " https://graph.qq.com/t/add_pic_t",
                method: "post"
            },
            "del_t": {
                api: "https://graph.qq.com/t/del_t",
                method: "post"
            },
            "get_repost_list": {
                api: "https://graph.qq.com/t/get_repost_list",
                method: "get"
            },
            "get_info": {
                api: "https://graph.qq.com/user/get_info",
                method: "get"
            },
            "get_other_info": {
                api: "https://graph.qq.com/user/get_other_info",
                method: "get"
            },
            "get_fanslist": {
                api: "https://graph.qq.com/relation/get_fanslist",
                method: "get"
            },
            "get_idollist": {
                api: "https://graph.qq.com/relation/get_idollist",
                method: "get"
            },
            "add_idol": {
                api: "https://graph.qq.com/relation/add_idol",
                method: "get"
            },
            "del_idol": {
                api: "https://graph.qq.com/relation/del_idol",
                method: "post"
            },
            "get_tenpay_addr": {
                api: "https://graph.qq.com/cft_info/get_tenpay_addr",
                method: "get"
            }
        };
        return function(apiKey) {
            return API_DICT[apiKey] || {};
        }
    } (); (function() {
        var reg = /\/qzone\/openapi\/qc\.js[?#]appId=(\d+)/i;
        var scripts = document.getElementsByTagName("script");
        for (var i = 0,
        l = scripts.length; i < l; i++) {
            var src = scripts[i].src || "";
            var mat = src.match(reg);
            if (mat && mat[1]) {
                _qc.Console.info("��⵽�Զ���ʼ������[appId]" + mat[1]);
                _qc.init({
                    appId: mat[1]
                });
                return;
            }
        }
    })();
})(QC); (function() {
    if (typeof Object.freeze == "function") {
        Object.freeze(QC);
    }
})();
/*  |xGv00|873edb055bee3cfb150001210a0a5582 */