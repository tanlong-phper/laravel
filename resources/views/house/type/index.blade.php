@extends('layouts/default')

@section('css')

    <link rel="stylesheet" href="{{ asset('css/base.css') }}">
    <link rel="stylesheet" href="{{ asset('css/module.css') }}">


@stop

@section('content')

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif
                <!-- 表格列表 -->
        <div class="tb-unit posr">
            <div class="tb-unit-bar" style="overflow: hidden;">
                <a class="btn all-fold" href="javascript:;" style="float:left;">全部折叠</a>
                <a class="btn all-open" href="javascript:;" style="float:left; margin-right:30px;">全部打开</a>
                <a class="btn" href="{{ url('house/type/add') }}" style="float:left; margin-right:30px;">添加类型</a>
                {{--<form action="{{ url('category/column') }}" method="post">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <label>状态：</label>
                    <select name="status">
                        <option value="-1">不限</option>
                        <option value="0" @if(isset($_REQUEST['status']) && $_REQUEST['status'] == 0) selected @endif>禁用</option>
                        <option value="1" @if(isset($_REQUEST['status']) && $_REQUEST['status'] == 1) selected @endif>启用</option>
                        <option value="2" @if(isset($_REQUEST['status']) && $_REQUEST['status'] == 2) selected @endif>删除</option>
                    </select>
                    <label>分类名字：<input type="text" name="class_name" class="text" style="height:30px;" value="@if(isset($_REQUEST['class_name'])){{ $_REQUEST['class_name'] }}@endif"></label>
                    <input type="submit" class="btn" value="查询" name="search" style="margin-left:20px;">
                    <a href="{{ url('category/column') }}" class="btn">显示全部</a>
                </form>--}}

            </div>

            <div class="category">
                <div class="hd cf">
                    <div class="fold">折叠</div>
                    <div class="order">ID</div>
                    <div class="order">排序</div>
                    <div class="order" style="    width: 200px; text-align: left;">名称</div>
                </div>
                @include('house.type.tree', ['tree' => $tree])
            </div>
        </div>

@stop



@section('js')
    <script type="text/javascript">
        $(function (){

            $('.all-open').click(function (){
                $('.category').find('.fold i.icon-fold').removeClass('icon-fold').addClass('icon-unfold');
                $('.category').find('dd').show();
            });
            $('.all-fold').click(function (){
                $('.category').find('.fold i.icon-unfold').removeClass('icon-unfold').addClass('icon-fold');
                $('.category').find('dd').hide();
            });
            /* 分类展开收起 */
            $(".category dd").prev().find(".fold i").addClass("icon-fold")
                    .click(function(){
                        var self = $(this);
                        if(self.hasClass("icon-unfold")){
                            self.closest("dt").next().slideUp("fast", function(){
                                self.removeClass("icon-unfold").addClass("icon-fold");
                            });
                        } else {
                            self.closest("dt").next().slideDown("fast", function(){
                                self.removeClass("icon-fold").addClass("icon-unfold");
                            });
                        }
                    });

            /* 三级分类删除新增按钮 */
            $(".category dd dd .add-sub").remove();



            /* 实时更新分类信息 */
            $(".category")
                    .on("submit", "form", function(){
                        var self = $(this);
                        $.post(
                                self.attr("action"),
                                self.serialize(),
                                function(data){
                                    /* 提示信息 */
                                    var name = data.status ? "success" : "error", msg;
                                    msg = self.find(".msg").addClass(name).text(data.info)
                                            .css("display", "inline-block");
                                    setTimeout(function(){
                                        msg.fadeOut(function(){
                                            msg.text("").removeClass(name);
                                        });
                                    }, 2000);
                                },
                                "json"
                        );
                        return false;
                    })
                    .on("focus","input",function(){
                        $(this).data('param',$(this).closest("form").serialize());

                    })
                    .on("blur", "input", function(){
                        if($(this).data('param')!=$(this).closest("form").serialize()){
                            $(this).closest("form").submit();
                        }
                    })
                    .on('click','.update_status',function (){
                        var self = $(this);
                        $.post(
                                self.attr("href"),
                                '',
                                function(data){
                                    if(data.status){
                                        if(data.data.status == 1){
                                            self.html('<i style="color:#ccc;">禁用</i>').attr('href',"/category/menu/updateStatus?status=0&id="+data.data.id);
                                        }else{
                                            self.html('启用').attr('href',"/category/menu/updateStatus?status=1&id="+data.data.id);
                                        }
                                    }
                                },
                                "json"
                        );
                        return false;
                    });
        });
    </script>
@stop