<?PHP
function xform_element($params){
	if(!is_array($params)) return $params;
	if(!array_key_exists('etype',$params)) return NULL;
	$return=element('return',$params);
	$etype=element('etype',$params);
	remove_element(array('return','etype'),$params);
	$func= 'xform_'.strtolower($etype);
	if(!function_exists($func)){
		$child=element('child',$params);
		remove_element('child',$params);
		$html=xform__tag(array('tag_name'=>$etype,'attr'=>$params,'child'=>$child,'return'=>TRUE));
	}else $html=$func($params);
	if($return) return $html;echo $html;
}

function xform_checkbox($params=NULL){
	$params=xform__trim_param($params,'label');
	if(element('checked',$params)) $params['checked']='checked';
	//else $params['checked'];
	$params['type']='checkbox';
	return xform_input($params);
}

function xform_radio($params=NULL){
	$params=xform__trim_param($params,'label');
	$params['type']='radio';
	$options = element('options',$params);
	$default = element('default', $params);
	unset($params['options']);
	$value = element('value',$params);
	if ($value == ''){
		$value = $default;
	}
	$html = '';
	foreach($options as $key=>$option){
		if($value==$key) $params['checked']='checked';
		else unset($params['checked']);
		$params['value']=$key;
		$params['label']=$option;
		$html.=xform_input($params);
	}
	return $html;
}

function xform_input($params=NULL){
	static $dexLab=1;
	$params=xform__trim_param($params);
	if(!array_key_exists('type',$params)) $params['type']='text';
	$html='';
	if(array_key_exists('label',$params)){
		if(!is_array($params['label'])){
			$labelCfg=array('child'=>$params['label']);
		}else $labelCfg=$params['label'];
		if(!array_key_exists('id',$params)) $params['id']='mki_'.($dexLab++);
		if(!array_key_exists('for',$labelCfg)) $labelCfg['for']=$params['id'];
		$labelCfg['id']='mki_'.($dexLab++);
		$html=xform_label($labelCfg);
		unset($params['label']);
	};
	$return= element('return',$params);
	$html=xform__tag(array('tag_name'=>'input','attr'=>$params,'return'=>TRUE)).$html;
	if($return) return $html;echo $html;
}

function xform_label($params=NULL){
	$params=xform__trim_param($params);
	$child=element('child',$params);
	$return=element('return',$params);
	$html=xform__tag(array('tag_name'=>'label','attr'=>$params,'child'=>$child,'return'=>TRUE));
	if(!$return) return $html;
	echo $html; return "";
}

function xform__tag($params=NULL){
	if(!$params) return;

	$params=xform__trim_param($params,'tag_name');

	$tag=element('tag_name',$params);
	$attr=element('attr',$params);
	$child=element('child',$params);
	$return=element('return',$params);

	remove_element(array('tag_name','child','return','attr'),$attr);

	$attr=xform__wrap($attr);

	if(is_array($attr)) $attr=xform__wrap($attr);
	if(!is_scalar($attr)) return "";

	$html='';
	if(is_array($child)){
		if(!$child['multiple']) $children=array($child);
		else $children=$child;
		remove_element('multiple',$children);
		while($child=array_shift($children)){
			if(is_array($child)) $child['return']=TRUE;
			$html.=xform_element($child);
		}
	}else $html=$child;
	if($attr) $attr=' '.$attr;
	$html='<'.$tag.$attr.'>'.$html.'</'.$tag.'>';

	if($return) return $html;
	echo $html; return "";
}

function xform__wrap($subject,$glue=' '){
	if(is_scalar($subject)) return str_replace('"','&quot;',$subject);
	//just only support array|scalar
	if(!is_array($subject)) return '';
	$html='';
	foreach($subject as $k=>$v){
		$conj=$glue;
		if(!is_numeric($k)) $html.=$k.'="';
		//accept inner-wrapper
		$wp='xform__wrap_'.strtolower($k);
		if(function_exists($wp)) $v=$wp($v);
		$html.=xform__wrap($v,$glue);
		if(!is_numeric($k)) $html.='"';
		$html.=$glue;
	}
	return $html;
}

function xform__wrap_style($value){
	if(!is_array($value)) return $value;
	foreach($value as $k=>$v){
		unset($value[$k]);
		$v=str_replace("'","\\'",$v);
		$v=str_replace('"',"'",$v);
		$value[]=$k.':'.$v;
	}
	return xform__wrap($value,';');
}

function xform__trim_param($param,$default_key='name'){
	if($param===NULL) return $param=array();
	if(is_string($param)) return $param=array($default_key=>$param);
	if(!is_array($param)) return array();
	return $param;
}

/**
 *	日期控件
 *
 *	@param	$config
 *		[key	yearRange:String][default:'c-10:c+10']	候选年区间范围
 *		[key	defaultDate:String]default:@today]			默认日期
 *		[key	other-options...]												其他datepicker的控制参数，详情参照jQuery Widget文档说明
 *
 *		[key	value:mixed][default:'']								显示日期值
 *
 *	@example
 *		xform_date(array('yearRange'=>'2005-2014'));	设置年份范围
 *		xform_date(array('showWeek'=>false,'changeYear'=>false));		不显示周，并且不允许直接修改年份
 *	@return String
 */
function xform_date($config=array()){
	static $date_index=1;
	//检测参数是否合法
	if(is_array($config)===FALSE) return FALSE;

	$options=array();

	//设置datepicker的默认控制参数
	$config=array_merge(array('yearRange'=>'c-5:c+5'),$config);

	//获取显示日期
	$value=element('value',$config);
	$name = element('name',$config);
	$class = element('class', $config);

	remove_element('value',$config);
	remove_element('name',$config);

	//装换ＰＨＰ数组为ＪＳＯＮ编码
	$options = json_encode($config);

	$date_id='mki_date_'.$date_index++;
	$html = xform_input(array('id'=>$date_id,'class'=>$class.' text','value'=>$value,'name'=>$name));

	//绑定脚本
	$html.=js_open();
	$html.= "$(function(){";
	$html.= "$('#".$date_id."').datepicker();";
	$html.= "});";
	$html.=js_close();

	if($date_index===2){
		$html.=javascript_tag(javascript_path('jquery.ui.datepicker.js'));
		$html.=javascript_tag(javascript_path('jquery.ui.core.js'));
		$html.= link_tag(css_path('jquery.ui.theme.css'));
		$html.= link_tag(css_path('jquery.ui.datepicker.css'));
	}
	return $html;
}

/**
 *	导行控件
 *
 *	@param	$data			Array()		列表值
 *	@param	$config		Array()		配置参数
 *		[key	rule:String]	指定$data数组中的Key-Value在控件中的解析规则，如下：
 *			数组的key值引用用'k'，value值引用用'v'，规则的格式为[option的value属性:option的caption属性]，
 *			各属性可以用引用关键字引用数组项的相应属性。
 *		[key	change:String]	选择发生改变时，执行的JS动作，执行函数可接收一个参数，代表当前选择的值
 *		[key	value:mixed]		默认
 */
function xform_itemsList($data=array(), $config=array()){
	static $itemsIndex = 1;
	$config = array_merge(array('rule'=>'k:v','change'=>FALSE),$config);
	$value = element('value',$config);
	$itemsId = 'FH_items_'.$itemsIndex++;
	$html = "<ul class='sidebar-title-sub' id='{$itemsId}'>";
	$val=substr($config['rule'],0,1);
	$cap=substr($config['rule'],2,1);
	if (empty($data)){
		$data = array(
			'新建项目' => 'project/index',
			'积分评定' => 'integral/index',
			'工作分配' => 'task/assignTask',
			'我的任务' => 'task/myTask',
			'公共任务' => 'task/publicTask',
			'特殊任务' => 'task/specialTasks',
			'任务审批' => 'task/approvalTask',
			'任务问题' => 'task/problemTask',
			'完成情况' => 'project/completion',
			'项目文档' => 'project/projectFile',
		);
	}
	foreach ($data as $k => $v){
		$mval=str_replace("'",'&#039;',$$val);
		$mcap=str_replace(array('<','>'),array('&lt;','&gt;'),$$cap);
		$selected=($mcap==$value)?"class='sideBarAhover'":"";
		$html .= '<li><a href="'.site_url($mcap).'" '.$selected.'>'.$mval.'</a></li>';
	}
	$html .= "</ul>";
	return $html;
}

/**
 *	选择控件
 *
 *	@param	$data			Array()		列表值
 *	@param	$config		Array()		配置参数
 *		[key	rule:String]	指定$data数组中的Key-Value在控件中的解析规则，如下：
 *			数组的key值引用用'k'，value值引用用'v'，规则的格式为[option的value属性:option的caption属性]，
 *			各属性可以用引用关键字引用数组项的相应属性。
 *		[key	width:Integer]	宽度，只能为数字，不能为百分比，即不能设置为%xx格式
 *		[key	change:String]	选择发生改变时，执行的JS动作，执行函数可接收一个参数，代表当前选择的值
 *		[key	value:mixed]		默认值
 */
function xform_select($data=array(),$config=array()){
	static $select_index=1;

	$config = array_merge(array('rule'=>'k:v','width'=>'120','change'=>FALSE,'group'=>FALSE),$config);

	$event_change = element('change',$config);
	$value = element('value',$config);

	$config['width'] = preg_replace('/([^\d]*)(\d+)(.*)/','$2',$config['width']);

	$select_id='mki_select_'.$select_index++;

	$html="<select id='{$select_id}' name='{$config['name']}' style='width:{$config['width']}px'>";
	$val=substr($config['rule'],0,1);
	$cap=substr($config['rule'],2,1);
	
	if($config['group'] == TRUE){
		foreach($data as $gk=>$gv){
			$gmval=str_replace("'",'&#039;',$gk);
			$html .= "<optgroup label='{$gmval}'>";
			foreach ($gv as $k => $v){
				$mval=str_replace("'",'&#039;',$$val);
				$mcap=str_replace(array('<','>'),array('&lt;','&gt;'),$$cap);
				$selected=($$val==$value)?"selected":"";
				$html.="<option value='{$mval}' {$selected}>{$mcap}</option>";
			}
			$html .= "</optgroup>";
		}
		$html.='</select>';
	}else{
		if(!empty($data)){
			foreach($data as $k=>$v){
				$mval=str_replace("'",'&#039;',$$val);
				$mcap=str_replace(array('<','>'),array('&lt;','&gt;'),$$cap);
				$selected=($$val==$value)?"selected":"";
				$html.="<option value='{$mval}' {$selected}>{$mcap}</option>";
			}
		}
		$html.='</select>';
	}
	//绑定脚本
	if($event_change){
		$html.=js_open();
		$html.="$(function(){";
		//检测是否需要注册值改变事件
		$html.="$('#".$select_id."').change(function(){var val=$(this).val();location.href='".site_url('main/index')."/platform/'+val});";
		$html.='})';
		$html.=js_close();
	}
	return $html;
}

/**
 * 月份选择
 * Enter description here ...
 * @param $data
 * @param $config
 */
function xform_selectMonth($data=array(),$config=array()){
	static $select_index=1;
	$event_change = 1;
	$config = array_merge(array('rule'=>'k:v','change'=>FALSE,'group'=>FALSE),$config);
	$value = explode("-", element('value',$config));
	$class = element('class',$config);
	$_type = element('_type', $config);
	$valueYear = $value[0];
	$valueMonth = $value[1];
	$select_id='mki_select_'.$select_index++;
	$select_v_id = 'mki_sval_'.$select_index++;
	//保存值的隐藏Input
	$html="<input type ='hidden' name='{$config['name']}' value='{$config['value']}' id='{$select_v_id}' class='{$class}' _type = '{$_type}' />";
	//年
	$html .= "<select id = 'y_".$select_id."' style='width:60px' onchange=setData('".$select_v_id."','y_".$select_id."')>";
	for($i = 0; $i <=(date("Y")-$data['year']);$i++){
		$selected = ($valueYear == ($data['year']+$i))?"selected":'';
		$html .= "<option ".$selected." value='".($data['year']+$i)."'>".($data['year']+$i)."</option>";
	}
	$html.='</select>-';
	//月
	$html .= "<select id='m_".$select_id."' style='width:40px' onchange=setDataM('".$select_v_id."','m_".$select_id."')>";
	for($i = 1; $i <=12; $i++){
		$selected = ($valueMonth == $i)?"selected":'';
		if($i <10){
			$html .= "<option ".$selected." value='0".$i."'>0".$i."</option>";
		}else{
			$html .= "<option ".$selected." value='".$i."'>".$i."</option>";
		}
	}
	$html.='</select>';
	if($event_change == 1){
		$html .= js_open();
		$html.="function setData(id,sid){var sval = $('#'+sid).val();var vid=$('#'+id).val().split('-'); $('#'+id).val(sval+'-'+vid[1]+'-'+vid[2]);}";
		$html.="function setDataM(id,sid){var sval = $('#'+sid).val();var vid=$('#'+id).val().split('-');$('#'+id).val(vid[0]+'-'+sval+'-'+vid[2]);}";
		$html .= js_close();
	}
	$event_change++;
	return $html;
}
