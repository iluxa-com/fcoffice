<div class="jsonMakerTemplate">
    <table class="gTable gHover" width="100%" border="0" cellpadding="0" cellspacing="1">
        <tr class="item">
            <td class="td1"><input type="checkbox" /></td>
            <td class="td2">道具</td>
            <td class="td3">
                <select name="item_id">
                    <option value="">＝＝＝请选择＝＝＝</option>
                    <optgroup label="－－服装道具－－">
                        <option value="1001">道具1001</option>
                        <option value="1002">道具1002</option>
                        <option value="1003">道具1003</option>
                    </optgroup>
                    <optgroup label="－－收藏道具－－">
                        <option value="2001">道具2001</option>
                        <option value="2002">道具2002</option>
                        <option value="2003">道具2003</option>
                    </optgroup>
                    <optgroup label="－－任务道具－－">
                        <option value="7001">道具7001</option>
                        <option value="7002">道具7002</option>
                        <option value="7003">道具7003</option>
                    </optgroup>
                </select>
                <input type="text" name="num" class="positiveIntegerFilter" maxlength="2" /></td>
            <td class="td4"><a href="javascript:void(0);" class="addLink">增加</a> <a href="javascript:void(0);" class="delLink">删除</a></td>
        </tr>
        <tr class="silver">
            <td class="td1"><input type="checkbox" /></td>
            <td class="td2">银币</td>
            <td class="td3"><input type="text" name="silver" class="positiveIntegerFilter" maxlength="6" /></td>
            <td class="td4">&nbsp;</td>
        </tr>
        <tr class="energy">
            <td class="td1"><input type="checkbox" /></td>
            <td class="td2">能量</td>
            <td class="td3"><input type="text" name="energy" class="positiveIntegerFilter" maxlength="4" /></td>
            <td class="td4">&nbsp;</td>
        </tr>
        <tr class="exp">
            <td class="td1"><input type="checkbox" /></td>
            <td class="td2">经验</td>
            <td class="td3"><input type="text" name="exp" class="positiveIntegerFilter" maxlength="6" /></td>
            <td class="td4">&nbsp;</td>
        </tr>
        <tr class="part">
            <td class="td1"><input type="checkbox" /></td>
            <td class="td2">部位</td>
            <td class="td3">
                <select name="part">
                    <option value="">＝＝＝请选择＝＝＝</option>
                    <option value="head">头部</option>
                    <option value="body_up">上身</option>
                    <option value="hand">手套</option>
                    <option value="body_down">下身</option>
                    <option value="socks">袜子</option>
                    <option value="socks">鞋子</option>
                    <option value="other">其他</option>
                </select>
            </td>
            <td class="td4">&nbsp;</td>
        </tr>
        <tr class="friend">
            <td class="td1"><input type="checkbox" /></td>
            <td class="td2">好友<br />奖励</td>
            <td class="td3"><textarea name="friend" cols="39" rows="4" class="jsonMaker hack"></textarea></td>
            <td class="td4">&nbsp;</td>
        </tr>
    </table>
</div>
