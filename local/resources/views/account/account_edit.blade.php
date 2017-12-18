@extends('layouts.default')

<style>
    .checkbox {vertical-align: middle; margin-top: -3px!important; margin-right: 6px!important;}
    .switch { margin-right:10px; display:inline-block; width:20px; height:20px; border:1px solid #ccc; text-align:center; line-height:20px; cursor: pointer;}
    .area_box li { list-style:none;}
</style>

@section('content')

    <div class="box">


        <!-- /.box-header -->
        <div class="box-body">
            <h4 class="bg-info" style="padding:5px 10px; font-size:14px; overflow:hidden;">
                <span style="line-height:34px;">编辑账号</span>
            </h4>
            <form action="{{ url('account/user/update') }}" method="put" class="js-ajax-form form-horizontal">
                {{ csrf_field() }}
                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">姓名：</label>
                    <div class="col-sm-4">
                        <input type="text" name="name" value="{{ $lists->name }}" class="form-control" id="inputEmail3" placeholder="">
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">账号：</label>
                    <div class="col-sm-4">
                        <input type="text" name="username" value="{{ $lists->username }}" class="form-control" id="inputEmail3" placeholder="">
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">账号状态：</label>
                    <div class="col-sm-4">
                        <select class="form-control" name="status">
                            <option value="1" @if($lists->status == 1) selected  @endif>启用</option>
                            <option value="0" @if($lists->status == 0) selected  @endif>不启用</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">联系方式：</label>
                    <div class="col-sm-4">
                        <input type="text" name="tel" value="{{ $lists->tel }}"  class="form-control" id="inputEmail3" placeholder="">
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">区域：</label>
                    <div class="col-sm-4">
                        <input type="text" name="area" value="{{ $lists->area }}"  class="form-control" id="inputEmail3" placeholder="">
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">选择角色：</label>
                    <div class="col-sm-4">
                        <select class="form-control" name="role_id">

                            @if(empty($roleList))
                                <option value="0">无</option>
                            @endif

                            @foreach($roleList as $values)
                                <option value="{{ $values['id'] }}"  @if($lists->role_id == 1) selected  @endif>{{ $values['name'] }}</option>
                            @endforeach

                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4">
                        <input type="hidden" name="id" value="{{ $lists->id }}">
                        <a href="javascript:window.history.go(-1);" type="button" class="btn btn-default">取消</a>
                        <button type="submit" class="btn btn-primary js-ajax-submit">确定</button>
                    </div>
                </div>

            </form>


        </div>




        <!-- /.box-body -->
    </div>

@stop

@section('js')

    <script>
        $(function (){

            $('.switch').click(function (){
                if ($(this).siblings('ul').css('display') == 'none') {
                    $(this).siblings('ul').slideDown(100).children('li');
                    if ($(this).parents('li').siblings('li').children('ul').css('display') == 'block') {
                        $(this).parents('li').siblings('li').children('ul').slideUp(100);
                    }
                    $(this).text('-');
                } else {
                    //控制自身菜单下子菜单隐藏
                    $(this).siblings('ul').slideUp(100);
                    //控制自身菜单下子菜单隐藏
                    $(this).siblings('ul').children('li').children('ul').slideUp(100);
                    $(this).text('+');
                }
            });

            $('.checkbox-all').click(function (){
                if($(this).is(':checked')){
                    $('.area_box .checkbox').attr('checked',true);
                }else{
                    $('.area_box .checkbox').attr('checked',false);
                }

            });

            $('.checkbox').change(function (){
                var checkout = $(this).parents('label').siblings('ul').children('li').children('label').children('.checkbox');

                if($(this).is(':checked')){
                    checkout.attr('checked',true);
                    checkout.parents('label').siblings('ul').children('li').children('label').children('.checkbox').attr('checked',true);
                }else{
                    checkout.attr('checked',false);
                    checkout.parents('label').siblings('ul').children('li').children('label').children('.checkbox').attr('checked',false);
                }
            });




            /*$('.a-link-menu').click(function() {
                if ($(this).siblings('ul').css('display') == 'none') {
                    $(this).siblings('ul').slideDown(100).children('li');
                    if ($(this).parents('li').siblings('li').children('ul').css('display') == 'block') {
                        $(this).parents('li').siblings('li').children('ul').slideUp(100);

                    }
                } else {
                    //控制自身菜单下子菜单隐藏
                    $(this).siblings('ul').slideUp(100);
                    //控制自身菜单下子菜单隐藏
                    $(this).siblings('ul').children('li').children('ul').slideUp(100);
                }
            });*/
        })
    </script>


    @stop



