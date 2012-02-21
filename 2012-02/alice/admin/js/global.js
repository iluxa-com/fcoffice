$(function () {
    $.extend({
        ajaxGoBack: function(step) {
            var ajaxHistory = $(window).data('ajaxHistory');
            if (ajaxHistory.length >= step) {
                while (step >= 1) {
                    ajaxHistory.pop();
                    --step;
                }
                var obj = ajaxHistory.pop();
                obj.trigger('click');
            }
        },
        ajaxSuccessCallback: function (data, textStatus, xhr) {
            if (data.callback) {
                if (data.params) {
                    eval(data.callback + '(' + data.params + ')');
                } else {
                    eval(data.callback + '()');
                }
            } else if (data.msg) {
                alert(data.msg);
                if (data.back) {
                    $.ajaxGoBack(1);
                }
            } else {
                alert(xhr.responseText);
            }
            if (data.refresh) {
                $.ajaxGoBack(0);
            }
        },
        applyInputFilter: function () {
            $('input').die('keyup').live('keyup', function () {
                if ($(this).hasClass('positiveIntegerFilter')) {
                    var regExp = /[^0-9]/g; // 匹配非数字字符
                } else if ($(this).hasClass('negativeIntegerFilter')) {
                    var regExp = /[^0-9\-]/g; // 匹配非数字字符和非"-"字符
                } else if ($(this).hasClass('positiveFloatFilter')) {
                    var regExp = /[^0-9\.]/g; // 匹配非数字和非"."字符
                } else if ($(this).hasClass('negativeFloatFilter')) {
                    var regExp = /[^0-9\.\-]/g; // 匹配非数字、非"."和非"-"字符
                } else {
                    return;
                }
                var oldVal = $(this).val();
                var newVal = oldVal.replace(regExp, ''); // 将匹配的字符都替换成空串
                if (oldVal != newVal) {
                    $(this).val(newVal);
                }
            });
        },
        applyTableSorter: function(urlPrefix, orderColumn, orderMethod) {
            $('.tableSorter th').each(function () {
                if ($(this).hasClass('hack')) {
                    return true;
                }
                if ($(this).attr('class') == orderColumn) {
                    var arrow = (orderMethod == 'ASC') ? '↓' : '↑';
                    $(this).text($(this).text() + arrow);
                }
                $(this).attr('title', '点击进行排序');
                $(this).click(function () {
                    var column = $(this).attr('class');
                    orderColumn = (orderColumn == column) ? orderColumn : column;
                    orderMethod = (orderMethod == 'ASC') ? 'DESC' : 'ASC';
                    $('#ajaxContainer').load(urlPrefix + '&orderColumn=' + orderColumn + '&orderMethod=' + orderMethod);
                });
            });
        },
        getJsonData: function(d) {
            var tmp = {};
            var obj = {};
            var ret = {};
            $(d).each(function(){
                if (!$(this).find('input[type=checkbox]').attr('checked')) {
                    return true;
                }
                if ($(this).hasClass('item')) {
                    var o = $(this).find('select[name=item_id]');
                    if (o.val() == '') {
                        ret.msg = '请选择道具！';
                        o.focus();
                        return false;
                    }
                    if (tmp[o.val()] != null) {
                        ret.msg = '道具[' + o.find('option:selected').text() + ']重复，请检查！';
                        o.focus();
                        return false;
                    }
                    tmp[o.val()] = 1;
                    var item_id = o.val();
                    var o = $(this).find('input[name=num]');
                    if (o.val() == '') {
                        ret.msg = '请输入道具数量！';
                        o.focus();
                        return false;
                    }
                    if(obj.items == null) {
                        obj.items = [];
                    }
                    obj.items.push({
                        id:item_id,
                        num:o.val()
                    });
                } else if ($(this).hasClass('silver')) {
                    var o = $(this).find('input[name=silver]');
                    if(o.val() == '') {
                        ret.msg = '请输入银币数量！';
                        o.focus();
                        return false;
                    }
                    obj.silver = o.val();
                } else if ($(this).hasClass('energy')) {
                    var o = $(this).find('input[name=energy]');
                    if(o.val() == '') {
                        ret.msg = '请输入能量点数！';
                        o.focus();
                        return false;
                    }
                    obj.energy = o.val();
                } else if ($(this).hasClass('exp')) {
                    var o = $(this).find('input[name=exp]');
                    if(o.val() == '') {
                        ret.msg = '请输入经验值！';
                        o.focus();
                        return false;
                    }
                    obj.exp = o.val();
                } else if ($(this).hasClass('part')) {
                    var o = $(this).find('select[name=part]');
                    if (o.val() == '') {
                        ret.msg = '请选择部位！';
                        o.focus();
                        return false;
                    }
                    obj.part = o.val();
                } else if ($(this).hasClass('friend')) {
                    var o = $(this).find('textarea[name=friend]');
                    if (o.val() == '') {
                        ret.msg = '请填写好友奖励！';
                        o.focus();
                        return false;
                    }
                    obj.friend = $.evalJSON(o.val());
                }
            });
            if(!ret.msg) {
                ret.data = $.toJSON(obj);
            }
            return ret;
        },
        showJsonMaker: function(g, s, d, h) { // g为对话框,s为输入框,d为jsonMaker模板"行"选择器,h为是否隐藏好友奖励
            $(g).dialog('close');
            $(g).dialog({
                title: 'JSON数据生成器',
                width: 518,
                minWidth: 518,
                maxWidth: 518,
                height: (h ? 320 : 410),
                minHeight: (h ? 270 : 354),
                open: function(event, ui) {
                    if (h) {
                        $(g).find('tr.friend').hide();
                    }
                    var oldVal = $(s).val();
                    if (oldVal != '') {
                        $(g).hide();
                        var obj = $.evalJSON(oldVal);
                        for (var k in obj) {
                            switch(k) {
                                case 'items':
                                    for (var i = 0; i < obj[k].length - 1; ++i) {
                                        $('.addLink').last().trigger('click');
                                    }
                                    var t = $(d).filter('.item');
                                    t.each(function(j){
                                        var item = obj[k][j];
                                        $(this).find('[name=item_id]').val(item.id);
                                        $(this).find('[name=num]').val(item.num);
                                        $(this).find(':checkbox').attr('checked', 'checked');
                                    });
                                    break;
                                case 'silver':
                                case 'energy':
                                case 'exp':
                                case 'part':
                                    var t = $(d).filter('.' + k);
                                    t.find('[name=' + k + ']').val(obj[k]);
                                    t.find(':checkbox').attr('checked', 'checked');
                                    break;
                                case 'friend':
                                    var t = $(d).filter('.' + k);
                                    t.find('[name=' + k + ']').val($.toJSON(obj[k]));
                                    t.find(':checkbox').attr('checked', 'checked');
                                    break;
                            }
                        }
                        $(g).show();
                    }
                },
                buttons: [{
                    text: '确定',
                    click: function() {
                        var ret = $.getJsonData(d);
                        if (ret.msg) {
                            alert(ret.msg);
                        } else if (ret.data && ret.data != '{}') {
                            $(s).val(ret.data);
                            $(this).dialog('close');
                        } else {
                            alert('尚未选择任何项！');
                        }
                    }
                },{
                    text: '查看JSON数据',
                    click: function() {
                        var ret = $.getJsonData(d);
                        if (ret.msg) {
                            alert(ret.msg);
                        } else if (ret.data && ret.data != '{}') {
                            alert(ret.data);
                        } else {
                            alert('尚未选择任何项！');
                        }
                    }
                },{
                    text: '清空',
                    click: function() {
                        $(s).val('');
                    }
                },{
                    text: '放弃',
                    click: function() {
                        $(this).dialog('close');
                    }
                }]
            });
        }
    });
    $.fn.setupSubmit = function () {
        return this.each(function () {
            // 检查submit绑定标志,如果已经设置,直接返回true跳过
            if ($(this).data('submitBind')) {
                return true;
            }
            $(this).submit(function () {
                // 如果设置了验证回调函数,则先执行验证回调函数,验证回调函数返回true表示验证通过
                if ($(this).data('validateCallback') && typeof($(this).data('validateCallback')) == 'function' && !$(this).data('validateCallback')()) {
                    return false;
                }
                var ajaxTarget = $(this).attr('ajaxTarget');
                if (ajaxTarget == undefined) {
                    if ($(this).attr('method') && ($(this).attr('method').toLowerCase() == 'post')) {
                        $.post($(this).attr('action'), $(this).serialize(), $.ajaxSuccessCallback, 'json');
                    } else {
                        $.get($(this).attr('action'), $(this).serialize(), $.ajaxSuccessCallback, 'json');
                    }
                } else {
                    swfobject.removeSWF('flashDiv');
                    $(ajaxTarget).load($(this).attr('action'), $(this).serialize());
                }
                return false;
            });
            // 设置submit事件绑定标志
            $(this).data('submitBind', true);
        });
    };
    // 初始化Ajax历史记录
    $(window).data('ajaxHistory', []);
    // hover样式应用
    $('table.gHover tr, ul.gHover li, ol.gHover li, dl.gHover dd').die('mouseover').live('mouseover', function () {
        $(this).addClass('hover');
    });
    $('table.gHover tr, ul.gHover li, ol.gHover li, dl.gHover dd').die('mouseout').live('mouseout', function () {
        $(this).removeClass('hover');
    });
    // Ajax加载链接
    $('a[ajaxTarget]').die('click').live('click', function() {
        swfobject.removeSWF('flashDiv');
        $($(this).attr('ajaxTarget')).load($(this).attr('href'));
        var ajaxHistory = $(window).data('ajaxHistory');
        ajaxHistory.push($(this));
        $(window).data('ajaxHistory', ajaxHistory);
        return false;
    });
    // Ajax完成处理事件
    $('body').ajaxComplete(function() {
        // 表单ajax提交应用
        $('form.ajaxSubmit').setupSubmit();
        // 日期选择控件应用
        $('.datepicker').datepicker({
            dateFormat: 'yy-mm-dd',
            showButtonPanel: true,
            changeMonth: true,
            changeYear: true
        });
    });
    // 返回按钮单击事件绑定
    $('button.gGoBack').die('click').live('click', function () {
        $.ajaxGoBack(1);
    });
    // 输入框应用输入过滤
    $.applyInputFilter();
    // Ajax删除链接
    $('.ajaxDelLink').die('click').live('click', function() {
        if (window.confirm('确定要执行此操作吗？')) {
            $.get($(this).attr('href'), {}, $.ajaxSuccessCallback, 'json');
        }
        return false;
    });
    // jsonMaker增加链接单击事件绑定
    $('.addLink').die('click').live('click', function(){
        var obj = $(this).closest('tr');
        obj.clone().removeClass('hover').insertAfter(obj);
    });
    // jsonMaker删除链接单击事件绑定
    $('.delLink').die('click').live('click', function(){
        if ($('.delLink').size() == 1) {
            alert('最后一个不能删除！');
            return;
        }
        if (!window.confirm('确定要删除吗？')) {
            return;
        }
        $(this).closest('tr').remove();
    });
    $('.jsonMaker').die('click').live('click', function(){
        var h = $(this).hasClass('hack');
        var g = h ? '#gDialog2' : '#gDialog';
        var s = $(this); // 临时存储,以防止变更
        var d = g + ' .jsonMakerTemplate tr'; // 不要在这里就取jQuery对象,因为有可能模板还没加载
        if (!$(window).data('jsonMakerTemplate')) {
            $(g).load('gateway.php?service=AdminService&action=getJsonMakerTemplate', function(data) {
                $(window).data('jsonMakerTemplate', data);
                $.showJsonMaker(g, s, d, h);
            });
        } else {
            $(g).html($(window).data('jsonMakerTemplate'));
            $.showJsonMaker(g, s, d, h);
        }
    });
});