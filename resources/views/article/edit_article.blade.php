@extends('layouts.default')

@section('css')
    <style type="text/css">
        .pic-list li {
            margin-bottom: 5px;
        }
        img{
            display:block;
        }

        button{
            display:block;
        }
        .goods_detail{
            margin-top:20px;
            display:inherit;
        }.goods_detail img{
             style="height:120px;"
         }
        .goods_img{
            margin-top:20px;
            display:inherit;
            float:left;
        }
        .table th {
            vertical-align: top;
        }
        .chose_class select{
            /*display:none;*/
        }
    </style>

@stop

@section('content')

    <div class="box">
        <div class="box-body">
            <div class="wrap js-check-wrap">
                <h4 class="bg-info" style="padding:10px; font-size:14px;">新增文章</h4>
                <form method="post" class="form-horizontal js-mobile-add-form" enctype="multipart/form-data" >
                    <div class="row-fluid">
                        <table class="table table-bordered" style="table-layout:fixed;">
                            <tr>
                                <th  width="10%">标题</th>
                                <td width="90%">
                                    <input type="text" class="form-control"  style="width:400px;" name="title" id="title"  value="{{$data->title or '' }}" />
                                </td>
                            </tr>
                            <tr>
                                <th  width="10%">描述</th>
                                <td width="90%">
                                    <input type="text" class="form-control"  style="width:400px;" name="description" id="description"  value="{{$data->description or '' }}" />
                                </td>
                            </tr>
                            <tr>
                                <th  width="10%">关键词</th>
                                <td width="90%">
                                    <input type="text" class="form-control"  style="width:400px;" name="keywords" id="keywords"  value="{{$data->keywords or '' }}" />
                                </td>
                            </tr>
                            <tr>
                                <th  width="10%">作者</th>
                                <td width="90%">
                                    <input type="text" class="form-control"  style="width:400px;" name="writer" id="writer"  value="{{$data->writer or '' }}" />
                                </td>
                            </tr>
                            <tr>
                                <th  width="10%">来源</th>
                                <td width="90%">
                                    <input type="text" class="form-control"  style="width:400px;" name="tsource" id="tsource"  value="{{$data->tsource or '' }}" />
                                </td>
                            </tr>
                            <tr>
                                <th>状态</th>
                                <td>
                                    <select name="status" class="form-control"  >
                                        <option @if(isset($data->status)&&$data->status == 1) selected @endif  value="1">启用</option>
                                        <option @if(isset($data->status)&&$data->status == 0) selected @endif  value="0">禁用</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th>是否置顶</th>
                                <td>
                                    <select name="istop" class="form-control"  >
                                        <option @if(isset($data->istop)&&$data->istop == 0) selected @endif  value="0">否</option>
                                        <option @if(isset($data->istop)&&$data->istop == 1) selected @endif  value="1">是</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th>文章内容</th>
                                <td>
                                    <script id="container" name="tcontent" type="text/plan">
                                        {!!$data->tcontent or '' !!}
                                    </script>
                                </td>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="form-actions">
                        <input type="hidden" name="article_id" value="{{Request::get('article_id')}}">
                        <input type="hidden" name="_token"  value="{{csrf_token()}}"/>
                        <button class="btn btn-primary js-ajax-submit" type="submit">提交</button>
                        <a href="{{ url('article/article_lists').'?'.http_build_query($_REQUEST) }}" class="btn btn-default">返回</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

@stop

@section('js')
    <script type="text/javascript" src="{{ asset('static/ueditor/1.4.3/ueditor.config.js') }}"></script>
    <script type="text/javascript" src="{{ asset('static/ueditor/1.4.3/ueditor.all.js') }}"> </script>
    <script type="text/javascript" src="{{ asset('static/validate/validate.js') }}"></script>
    <script type="text/javascript" >
        //实例化编辑器
        var ue = UE.getEditor('container',{
            initialFrameWidth :800,
            initialFrameHeight :600
        });
        var _this=document.querySelector('.js-ajax-submit');
        $(function() {
            //表格提交
            $(".js-mobile-add-form").validate({
                rules: {
                    title: {
                        required: true
                    }
                },
                messages: {
                    title: {
                        required: "文章标题必须输入"
                    }
                },
                submitHandler: function () {
                    _this.disable=true;// 禁用按钮
                    var data = $('.js-mobile-add-form').serialize();
                    $.post("{{url('article/article_lists/edit_article')}}",data, function (msg) {
                        if (msg.status == 1) {
                            layer.msg(msg.msg, {icon:1});
                            if(!!document.querySelector('[name=article_id]').value){
                                _this.disable=false;
                            }
                            setTimeout(function (){
                                location.href='{{ url('article/article_lists') }}';
                            },1000);

                        } else {
                            layer.alert(msg.msg, {
                                title: '提示',
                                icon: 2,
                            });
                        }
                    }, 'json');
                    return false;
                }
            });
        });
    </script>
@stop


