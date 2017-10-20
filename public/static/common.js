//dom加载完成后执行的js
;$(function(){


    $('.js-ajax-form').submit(function (){
//                var waiting = layer.msg('请求中......',{'time':0,'shade':0.3,'icon':16});
        $(this).ajaxSubmit({
            success:function (data){

//                        layer.close(waiting);
                var message = data.info;

                if (data.status==1) {
                    message = message === undefined ? '操作成功！' : message;
                    if (data.url) {
                        layer.msg(message + ' 页面即将自动跳转~', {icon: 1});
                        setTimeout(function (){
                            location.href = data.url;
                        },1000);
                    }else{
                        layer.msg(message, {icon: 1});
                    }
                }else{
                    message = message == undefined ? '操作失败！' : message;
                    layer.msg(message, {icon: 2});
                }
            }
        });
        return false;
    });

	//全选的实现
	$(".check-all").click(function(){
		$(".ids").prop("checked", this.checked);
	});
	$(".ids").click(function(){
		var option = $(".ids");
		option.each(function(i){
			if(!this.checked){
				$(".check-all").prop("checked", false);
				return false;
			}else{
				$(".check-all").prop("checked", true);
			}
		});
	});

    //确认框封装 (ajax-get confirm)    liuwei
    $('body').on("click",".layer-get",function(){
        var title = $(this).attr('title');
        title = title?title:'是否执行此操作?';
        var url = $(this).attr('url');
        var href = $(this).attr('href');
        var target = url?url:href;
        var waiting_msg = $(this).attr('waiting_msg')?$(this).attr('waiting_msg'):'加载中...';
        var that = this;
        layer.confirm(title, {
            btn: ['确定','取消'] //按钮
        }, function(index){
            layer.close(index);
            //layer.msg('已确定', {icon: 1});
            var waiting = layer.msg(waiting_msg,{'time':0,'shade':0.3,'icon':16});

            $.get(target, function(data){
                layer.close(waiting);
                var message = data.info;
                if (data.status==1) {
                    message = message === undefined ? '操作成功！' : message;
                    if (data.url) {
                        layer.msg(message + ' 页面即将自动跳转~', {icon: 1});
                        setTimeout(function () {
                            location.href = data.url;
                        }, 1000);
                    }else{
                        layer.msg(message, {icon: 1});
                    }
                }else{
                    message = message == undefined ? '操作失败！' : message;
                    layer.msg(message, {icon: 2});
                }
            });


        }, function(){
            layer.msg('已取消', {icon: 0});
        });
        return false;
    })

    //确认框封装 (ajax-post confirm)    liuwei
    $('body').on("click",".layer-post",function(){
        var target,query,form;
        var title = $(this).attr('title');
        title = title?title:'是否执行此操作?';
        var nead_confirm = false;
        var target_form = $(this).attr('target-form');
        var waiting_msg = $(this).attr('waiting_msg')?$(this).attr('waiting_msg'):'加载中...';
        var that = this;

        if( ($(this).attr('type')=='submit') || (target = $(this).attr('href')) || (target = $(this).attr('url')) ){
            form = $('.'+target_form);

            if ( form.get(0).nodeName=='FORM' ){        //表单提交
                if($(this).attr('url') !== undefined){
                    target = $(this).attr('url');
                }else{
                    target = form.get(0).action;
                }
                //***表单验证***
                var num = check_form('.'+target_form);
                if(num){
                    layer.msg('有'+num+'项不符合规则', {icon: 0});
                    return false;
                }
                //******
                query = form.serialize();
            }else if( form.get(0).nodeName=='INPUT' || form.get(0).nodeName=='SELECT' || form.get(0).nodeName=='TEXTAREA') {//批量操作
                var arr = Array();
                form.each(function(k,v){
                    if(v.type=='checkbox' && v.checked==true){
                        nead_confirm = true;
                    }
                })
                if(!nead_confirm){
                    var warn = $(this).attr('warn')?$(this).attr('warn'):'请选择信息';
                    warning(warn);
                    return false;
                }
                query = form.serialize();
            }else{
                query = form.find('input,select,textarea').serialize();
            }
            layer.confirm(title, {
                btn: ['确定','取消'] //按钮
            }, function(index){
                layer.close(index);
                //layer.msg('已确定', {icon: 1});
                var waiting = layer.msg(waiting_msg,{'time':0,'shade':0.3,'icon':16});
                $.post(target,query).success(function(data){
                    layer.close(waiting);
                    if (data.status==1) {
                        if (data.url) {
                            updateAlert(data.info + ' 页面即将自动跳转~','alert-success');
                        }else{
                            updateAlert(data.info ,'alert-success');
                        }
                        setTimeout(function(){
                            $(that).removeClass('disabled').prop('disabled',false);
                            if (data.url) {
                                location.href=data.url;

                            }else if( $(that).hasClass('no-refresh')){
                                $('#top-alert').find('button').click();
                            }else{
                                location.reload();
                            }
                        },1500);
                    }else{
                        updateAlert(data.info);
                        setTimeout(function(){
                            $(that).removeClass('disabled').prop('disabled',false);
                            if (data.url) {
                                location.href=data.url;
                            }else{
                                $('#top-alert').find('button').click();
                            }
                        },1500);
                    }
                });
            }, function(){
                layer.msg('已取消', {icon: 0});
            })
        }
        return false;
    })
    
    /**
     * 商品关联信息删除操作
     * @author liuwei
     * @dateTime 2015-12-29T10:57:16+0800
     * @param    {[type]}                 ){                                   var title [description]
     * @param    {[type]}                 function(index){                                              layer.close(index);            $.ajax({                url : url,                type: 'get',                success:function(data){                    if(data.exist [description]
     * @return   {[type]}                                   [description]
     */
    $('body').on("click",".layer-delete",function(){
        var _this = $(this);
        var title = $(this).attr('title');
        title = title?title:'是否执行此操作?';
        var url = $(this).attr('href');
        layer.confirm(title, {
            btn: ['确定','取消'] //按钮
        }, function(index){
            layer.close(index);
            $.ajax({
                headers: { 'X-CSRF-TOKEN' : _this.attr('token') },
                url : url,
                type: 'delete',
                success:function(data){
                    var message = data.info;
                    if (data.status==1) {
                        message = message === undefined ? '操作成功！' : message;
                        if (data.url) {
                            layer.msg(message + ' 页面即将自动跳转~', {icon: 1});
                            setTimeout(function () {
                                location.href = data.url;
                            }, 1000);
                        }else{
                            layer.msg(message, {icon: 1});
                        }
                    }else{
                        message = message == undefined ? '操作失败！' : message;
                        layer.msg(message, {icon: 2});
                    }
                }

            })
        })
        return false;
    })

    //ajax get请求
    $('body').on("click",'.ajax-get',function(){
        var waiting_msg = $(this).attr('waiting_msg')?$(this).attr('waiting_msg'):'加载中...';
        var target;
        var that = this;
        if ( $(this).hasClass('confirm') ) {
            if(!confirm('确认要执行该操作吗?')){
                return false;
            }
        }
        if ( (target = $(this).attr('href')) || (target = $(this).attr('url')) ) {
            var waiting = layer.msg(waiting_msg,{'time':0,'shade':0.3,'icon':16});
            $.get(target).success(function(data){
                layer.close(waiting);
                if (data.status==1) {
                    if (data.url) {
                        updateAlert(data.info + ' 页面即将自动跳转~','alert-success');
                    }else{
                        updateAlert(data.info,'alert-success');
                    }
                    setTimeout(function(){
                        if (data.url) {
                            location.href=data.url;
                        }else if( $(that).hasClass('no-refresh')){
                            $('#top-alert').find('button').click();
                        }else{
                            location.reload();
                        }
                    },1500);
                }else{
                    updateAlert(data.info);
                    setTimeout(function(){
                        if (data.url) {
                            location.href=data.url;
                        }else{
                            $('#top-alert').find('button').click();
                        }
                    },1500);
                }
            });

        }
        return false;
    });

    //ajax post submit请求
    $('body').on("click",'.ajax-post',function(){
        var waiting_msg = $(this).attr('waiting_msg')?$(this).attr('waiting_msg'):'加载中...';
        var target,query,form;
        var target_form = $(this).attr('target-form');
        var that = this;
        var nead_confirm=false;
        if( ($(this).attr('type')=='submit') || (target = $(this).attr('href')) || (target = $(this).attr('url')) ){
            form = $('.'+target_form);

            if ($(this).attr('hide-data') === 'true'){//无数据时也可以使用的功能
            	form = $('.hide-data');
            	query = form.serialize();
            }else if (form.get(0)==undefined){
            	return false;
            }else if ( form.get(0).nodeName=='FORM' ){
                if ( $(this).hasClass('confirm') ) {
                    if(!confirm('确认要执行该操作吗?')){
                        return false;
                    }
                }
                if($(this).attr('url') !== undefined){
                	target = $(this).attr('url');
                }else{
                	target = form.get(0).action;
                }
                //***表单验证***
                var num = check_form('.'+target_form);
                if(num){
                    layer.msg('有'+num+'项不符合规则', {icon: 0});
                    return false;
                }
                //******
                query = form.serialize();
            }else if( form.get(0).nodeName=='INPUT' || form.get(0).nodeName=='SELECT' || form.get(0).nodeName=='TEXTAREA') {
                form.each(function(k,v){
                    if(v.type=='checkbox' && v.checked==true){
                        nead_confirm = true;
                    }
                })
                if ( nead_confirm && $(this).hasClass('confirm') ) {
                    if(!confirm('确认要执行该操作吗?')){
                        return false;
                    }
                }
                query = form.serialize();
            }else{
                if ( $(this).hasClass('confirm') ) {
                    if(!confirm('确认要执行该操作吗?')){
                        return false;
                    }
                }
                query = form.find('input,select,textarea').serialize();
            }
            $(that).addClass('disabled').attr('autocomplete','off').prop('disabled',true);
            var waiting = layer.msg(waiting_msg,{'time':0,'shade':0.3,'icon':16});
            $.post(target,query).success(function(data){
                layer.close(waiting);
                if (data.status==1) {
                    if (data.url) {
                        updateAlert(data.info + ' 页面即将自动跳转~','alert-success');
                    }else{
                        updateAlert(data.info ,'alert-success');
                    }
                    setTimeout(function(){
                        if (data.url) {
                            if(data.url == 5)
                            {
                                parent.window.location.reload();
                            }else
                            {
                                location.href=data.url;
                            }
                        }else if( $(that).hasClass('no-refresh')){
                            $('#top-alert').find('button').click();
                            $(that).removeClass('disabled').prop('disabled',false);
                        }else{
                            location.reload();
                        }
                    },1500);
                }else{
                    updateAlert(data.info);
                    setTimeout(function(){
                        if (data.url) {
                            location.href=data.url;
                        }else{
                            $('#top-alert').find('button').click();
                            $(that).removeClass('disabled').prop('disabled',false);
                        }
                    },1500);
                }
            });
        }
        return false;
    });


});



//分页跳转带查询条件
$('body').on('click',".first,.num,.prev,.next,.end",function(){
    var obj = $(this).parents('.page:first').parent().parent().find('.search_form').find('form');
    if(obj.length==0){  //无筛选框        
    }else{
        var href = $(this).attr("href");
        obj.attr("action",href);
        obj.submit();
        return false;     
    }    
});
$('body').on('change',"[name='listRows']",function(){
    var html = '<input type="hidden" name="r" value="'+$(this).val()+'">';
    var obj = $(this).parents('.page:first').parent().parent().find('.search_form').find('form');
    if(obj.length==0){  //无筛选框
        var url = $(this).attr('url');
        if(url){
            location.href = url+'&r='+$(this).val();
        }
    }else{
        obj.append(html);
        obj.submit();
        obj.find('[name="r"]').remove();
    }
})

$('body').on('click','.search_form .search_btn',function(){
    $(this).parents('form:first').submit();
})


//dialog弹出框(链接)
$('body').on('click',"[target=dialog]",function(){
    var width  = $(this).attr('width')?$(this).attr('width'):'300px';
    var height = $(this).attr('height')?$(this).attr('height'):'193px';
    var title  = $(this).attr('title')?$(this).attr('title'):'信息';
    var href   = $(this).attr('href');
    var flow   = $(this).attr('flow')?$(this).attr('flow'):'yes';
    var maxmin = $(this).attr('maxmin')?$(this).attr('maxmin'):false;
    var shade  = $(this).attr('shade')?parseFloat($(this).attr('shade')):0.8;
    var offset = $(this).attr('offset')?$(this).attr('offset'):'auto';
    var move   = $(this).attr('move')?$(this).attr('move'):'.layui-layer-title';
    var btn    = $(this).attr('btn');   //按钮

    var config = {      //弹出框初始化
            type: 2,
            title: title,
            shadeClose: false,
            shade: shade,
            area: [width, height],
            maxmin: maxmin,
            move: move,
            content: [href , flow],
        }
    switch(offset){
        case 'auto':
            break;
        case 'rb':
            break;
        default:
            if(offset.indexOf(',')>-1){
                if(offset.indexOf('[')>-1){
                    offset = offset.substr(1,offset.length-2);
                }
                offset = offset.split(',');                
                offset = [offset[0],offset[1]];
            }
            break;
    }    
    config['offset'] = offset;

    if(btn){
        var btn_info = btn.split(',');  //按钮信息
        var btn_name = new Array;       //按钮名称
        for(i in btn_info){
            btn_info[i] = btn_info[i].split(':');
            btn_name.push(btn_info[i][0]);
        }
        config.btn = btn_name;
        var fun_name;
        for(j in btn_name){
            switch(j){
                case '0':
                    fun_name = 'yes';
                    break;
                case '1':
                    fun_name = 'no';
                    break;
                default:
                    fun_name = 'btn'+(parseInt(j)+1);
                    break;
            }                
            if(btn_info[j][1]){     //存在回调函数
                try {
                    var fn = eval(btn_info[j][1]);
                } catch(e) {
                }
                if (typeof fn === 'function'){
                    config[fun_name] = eval(btn_info[j][1]);
                }                
            }            
        }
    }
    var index = layer.open(config);
    return false;
})

//关闭dialog
function close_dialog(){
    var index = parent.layer.getFrameIndex(window.name); //先得到当前iframe层的索引
    parent.layer.close(index); //再执行关闭  
}

$('body').on('mouseout','a[rel=mouseout]',function(){
    layer.closeAll();
})

//提示框 tips
$('body').on('mouseenter','.tips',function(){
    var msg = $(this).attr('msg');
    var width  = $(this).attr('width')?$(this).attr('width'):'auto';
    var eventtype = $(this).attr('eventtype');//触发事件
    var typenum = $(this).attr('typenum')?$(this).attr('typenum'):2;
    var color = $(this).attr('color')?$(this).attr('color'):'#ff9900';
    var time = $(this).attr('time')?$(this).attr('time'):3000;

    if(msg){    //msg不为空时显示
        if(eventtype && eventtype!='mouseenter'){
            $(this).on(eventtype,function(event){
                event.stopPropagation();
                var index = layer.tips(msg,this,{
                    tips: [typenum, color],
                    area: width,
                    time: time,
                });
                $('body').click(function(e){

                    layer.close(index);
                })
            })
        }else{
            var index= layer.tips(msg,this,{
                guide: 1,
                tips: [typenum, color],
                area: width,
                time: time,
            });
            $('body').click(function(e){                
                layer.close(index);
            })
        } 
    }
    $('body').on('click','.layui-layer-content',function(e){
        e.stopPropagation();
    })   
})


//表单js验证    liuwei
function check_form(form){ 
    var num = 0;
    var msgH = '<font class="error_msg">';
    var msgF = '</font>';
    $(form+' :input').each(function(){
        var obj = $(this);
        var msg = obj.attr('msg');
        if(obj.hasClass('error')){
            obj.removeClass('error');
            obj.nextAll('.error_msg').remove();
        }
        var all_class = obj.attr('class');
        if(typeof(all_class) != "undefined"){
            var every_class = all_class.split(' ');
            var result;
            $.each(every_class,function(n,value) {
                try {
                    var fn = eval('check_'+value);
                } catch(e) {
                }
                if (typeof fn === 'function'){
                    result = fn(obj);
                }else{
                    result = '';
                }

                if(result.code == 2){
                    obj.addClass('error');
                    if(msg){
                        result.msg = msg;
                    }
                    obj.after(msgH+result.msg+msgF);
                    num++;
                    return false;
                }
            })
        }      
    })
    return num;
}

//表单项变动，去掉验证错误标识
$('body').on('input propertychange , change','.error',function(){
    $(this).removeClass('error');
    $(this).nextAll('.error_msg').remove();
})

/**
 * 列表移动排序
 * @author liuwei
 * @dateTime 2015-12-07T17:19:15+0800
 */
$('body').on('click','.sortmove',function(){
    var self = $(this);
    var url = 'admin.php?s=MoveSort/sortMove';
    var data = $(this).attr('data');
    data = JSON.parse(data);
    var order = $(this).attr('order');
    var sort  = $(this).attr('sort');
    $.ajax({
        type:'POST',
        url:url,
        data:{"table":data.table,"id":data.id,"type":data.type,"map":data.map,'order':order,'sort':sort},
        success:function(data){//debugger;
            if(data.status>0){
                var tr = self.parents('tr:first');
                if(tr.length>0){
                    var name = 'tr:first';
                }else{  //菜单页
                    tr = self.parents('dl:first');
                    var name = 'dl:first';
                }
                switch(data.type){
                    case 'go_top':
                        location.reload();
                        break;
                    case 'go_up':
                        var up_tr = tr.prev(name);
                        if(up_tr.length>0){
                            tr.insertBefore(up_tr);
                        }else{
                            location.reload();
                        }
                        break;
                    case 'go_down':
                        var down_tr = tr.next(name);
                        if(down_tr.length>0){
                            tr.insertAfter(down_tr);
                        }else{
                             location.reload();
                        }
                        break;
                    case 'go_bottom':
                        location.reload();
                        break;
                }
            }
        }
    })
})

//页面的三级菜单显示 纯js处理  但没办法显示页面的按键的url
/*window.onload = function() {
    var current_a = $(".current").find('a'); 
    var html = '';
    var len = $(".current").find('a').length;    
    $.each(current_a, function(i,val){
        if(i === len - 1){
            html += '<a style="color:#929A8C;">'+$(this).text()+'</a>';
        }else{
            html += '<h2><a class="urlTree" href="'+val+'">'+$(this).text()+'</a><i class="ca"></i>'; 
        }
        if(i === 0){
            var type_2_title  = $(".current").eq(1).parent().prev().text();
            html += '<a>'+type_2_title+'</a><i class="ca"></i>';    
        }
    });  
  
    html += '</h2/><br/><br/><hr style="margin-top: 20px;border: 0;border-top: 1px solid #eee;" />';
    
    $(".main-title").append(html);
};*/
//urltree
$(function(){
    $(".main-title").find('.urlTree').eq(1).attr('href','javascript:void(0);');
  var url_tree = $('a').hasClass('urlTree');
  if(url_tree){
    var url_last = $(".urlTree:last").text();
    $(".urlTree:last").replaceWith(url_last);
    $(".ca:last").remove();
  } 
});

/**
 * 批量操作封装
 * 同时执行多个id 
 * 批量操作ajax调用封装 
 * title_null:为空时执行的提示
 * title:提示信息
 * contro：ajax控制器
 * group_arr需要操作的数组
 * meg:ajax后的提示信息
 */
function group_ajax(title_null,title,contro,meg){
	var arr = Array();
	$('.ids').each(function (){
		$check_val = $(this).is(':checked');
		if($check_val){
			arr.push($(this).val());
		}
	});
	//没有选中的项
	if(arr == ''){
		layer.msg(title_null, {icon: 0});
		return false;
	}else{
		var group_arr = eval(arr);
		layer.confirm(title, {
			btn: ['确定','取消'] //按钮
		}, function(){
			var json_arr = group_arr.join(',');
			$.ajax({
				type:'POST',
				url:contro,
				data:{
					'ids':json_arr,
				},
				success: function(datamsg){
					if(datamsg == '1' || datamsg.info == '删除成功！'){
						layer.msg(meg+'成功！', {icon: 1});
						window.parent.location.reload();
						return true;
					}else{
						layer.msg(meg+'失败！', {shift: 6});
						return false;
					}
				}
			});
		}, function(){
			layer.msg('已取消', {icon: 0});
		});
		return false;
	}
}

$(function(){
    var obj = $('body').find('input[type=text]').eq(0);
    var one_input=obj.val();
    obj.not('.date_time').val("").focus().val(one_input);
})

//商家，商圈，门店 名称 简称  不能输入分号
$('input[rel=check_sem]').on('keyup',function(){
    var val = $(this).val();
    
    if(val.match(/[;]/g)){
        val = val.replace(/[;]/g,"");
        $(this).val(val);
    }
})
    

//优惠卷 会员选择
$("input[rel=member_rel]").on('click',function(){
	$("input[rel=member_rel]").prop("checked", false);
	$(this).parent().nextAll().andSelf().find("input[rel=member_rel]").prop("checked", true);
})

//限制输入特殊字符
$('body').on('keyup',"input[type=text]",function(){
    var val = $(this).val();
    
    if(val.match(/["']/g)){
        val = val.replace(/["']/g,"");
        $(this).val(val);
    }
})
$('body').on('keyup',"textarea",function(){
    var val = $(this).val();
    
    if(val.match(/["']/g)){
        val = val.replace(/["']/g,"");
        $(this).val(val);
    }
})

/**
 * [warning 弹出警告窗]
 * @author 黄开旺
 * @dateTime 2015-12-01T13:56:47+0800
 * @param    {[type]}                 warning_msg [description]
 * @return   {[type]}                             [description]
 */
function warning(warn_msg){
    layer.msg(warn_msg,{time:2000,offset:100,shift:6});
}

/**
 * [alert_success 弹出成功窗]
 * @author 黄开旺
 * @dateTime 2015-12-01T13:59:59+0800
 * @param    {[type]}                 alert_msg [description]
 * @return   {[type]}                           [description]
 */
function alert_success(alert_msg){
    layer.msg(alert_msg, {time:2000,icon: 6});
}

/**
 * [alert_error 弹出错误框]
 * @author 黄开旺
 * @dateTime 2015-12-01T14:00:09+0800
 * @param    {[type]}                 alert_msg [description]
 * @return   {[type]}                           [description]
 */
function alert_error(alert_msg){
    layer.msg(alert_msg, {time:2000,icon: 5});
}

/**
 * [按下Enter，表单提交，按下esc关闭dialog]
 * @author 黄开旺
 * @dateTime 2015-12-18T09:25:51+0800
 * @param    {[type]}                 event) {               if(event.keyCode [description]
 * @return   {[type]}                        [description]
 */
$("body").keydown(function(event) {
    if(event.keyCode == 27){
        close_dialog();
        if($(".imgViewBox").length > 0){
            $(".imgViewBox").remove();
        }

    }
    if(event.keyCode == 13){
        $(".submit-btn").click();
    }
});

/**
 * 后台列表 多余部分 显示...
 * @author liuwei
 * @dateTime 2016-01-07T11:14:34+0800
 * @param    {[type]}                 obj  [description]
 * @param    {[type]}                 size [description]
 * @return   {[type]}                      [description]
 */
function brief_content(obj,size){
    var title,text;
    obj.each(function(){
        title = $.trim($(this).text());
        if(title.length > size){
            text = title.substr(0,size) + " ...";
            $(this).text(text);
            $(this).attr('title',title);
        }
    })
}

/**
 * [viewIMG description]
 * @param  {[type]} url      图片链接
 * @param  {Number} width    图片宽度，默认400
 * @param  {String} position Position属性默认absolute
 * @param  {Number} top      调整position属性，默认为屏幕高度的一半
 * @param  {Number} left     调整position属性，默认为屏幕宽度的一半
 * @return {[type]}          [description]
 */
function viewIMG(url, width, position, top, left){
    var view = '<div id="view_img" style="display:none;"><img src="'+url+'"></div>';
    $("#view_img").remove();
    $("body").append(view);
    var width = arguments[1] ? arguments[1] : 0;
    var position = arguments[2] ? arguments[2] : 'fixed';
    var img_height = parseFloat($("#view_img").css('height'));
    var img_width  = parseFloat($("#view_img").css('width'));
    if(img_width>$("#main-content").width()*0.9){
        width = img_width = $("#main-content").width()*0.9;
    }
    var top = arguments[3] ? arguments[3] : ($(window).height())/2-img_height/2;
    var left = arguments[4] ? arguments[4] : ($(window).width())/2-img_width/2;
    if(width == 0){
        var html = '<div class="imgViewBox" style="border: 4px solid rgb(0, 0, 0); padding: 2px; background: none repeat scroll 0% 0% rgb(255, 255, 255); position: '+position+'; z-index: 65535; top: '+top+'px; left: '+left+'px;"><img src="'+url+'" title="按ESC关闭"><div style="padding:5px;"><span onclick="$(&quot;.imgViewBox&quot;).remove()" style="display:block;text-align:right;cursor:pointer;border-top:1px #ccc solid;">关闭</span></div></div>';
    }else{
        var html = '<div class="imgViewBox" style="border: 4px solid rgb(0, 0, 0); padding: 2px; background: none repeat scroll 0% 0% rgb(255, 255, 255); position: '+position+'; z-index: 65535; top: '+top+'px; left: '+left+'px;"><img width="'+width+'" src="'+url+'" title="按ESC关闭"><div style="padding:5px;"><span onclick="$(&quot;.imgViewBox&quot;).remove()" style="display:block;text-align:right;cursor:pointer;border-top:1px #ccc solid;">关闭</span></div></div>';
    }
    $(".imgViewBox").remove();
    $("body").append(html);
}

/**
 * js求2数组交集
 * @author liuwei
 * @dateTime 2016-01-13T11:06:25+0800
 * @param    {[type]}                 a [description]
 * @param    {[type]}                 b [description]
 * @return   {[type]}                   [description]
 */
function arrayIntersection(a, b){
    var ai=0, bi=0;
    var result = new Array();
    while( ai < a.length && bi < b.length ){
        if(a[ai] < b[bi] ){
            ai++; 
        }else if (a[ai] > b[bi] ){ 
            bi++; 
        }else{
            result.push(a[ai]);
            ai++;
            bi++;
        }
    }
    return result;
}

/**
 * js求2数组差集
 * @author liuwei
 * @dateTime 2016-01-13T11:06:38+0800
 * @param    {[type]}                 a [description]
 * @param    {[type]}                 b [description]
 * @return   {[type]}                   [description]
 */
function arrayDiff(a,b){
    var result = new Array();
    for(var i=0; i < a.length; i++){   
        var flag = true;   
        for(var j=0; j < b.length; j++){   
            if(a[i] == b[j])   
            flag = false;   
        }   
        if(flag)   
        result.push(a[i]);   
    }
    return result;
}

