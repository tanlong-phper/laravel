@extends('layouts.default')

@section('css')


@stop

@section('content')
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <div class="box">
        <div class="box-body">
            <form action="{{url('nation/add/state')}}" method="post">
                {{ csrf_field() }}
                <label>国家中文名称：</label> <input type="text" name="chinese_n_name" value=""><br>
                <label>国家英文名称：</label> <input type="text" name="english_n_name" value=""><br>
                <label>国家英文缩写：</label> <input type="text" name="abbreviation" value="">
                <input type="submit" value="添加">
            </form>

            <div class="alert"></div>

            <form action="{{url('nation/add/province')}}" method="post">
                {{ csrf_field() }}
                <label>所属国家：</label>
                <select name="p_nation_ID" id="" >
                    @foreach($nationArr as $value)
                        <option value="{{$value->n_ID}}">{{$value->chinese_n_name}}&nbsp;&nbsp;&nbsp;{{$value->english_n_name}}</option>
                    @endforeach
                </select><br>
                <label>省份中文名称：</label> <input type="text" name="chinese_p_name" value=""><br>
                <label>省份英文名称：</label> <input type="text" name="english_p_name" value="">
                <input type="submit" value="添加">
            </form>

            <div class="alert"></div>

            <form action="{{url('nation/add/city')}}" method="post">
                {{ csrf_field() }}
                <label>所属省份：</label>
                <select name="c_province_ID" id="">
                    @foreach($provinceArr as $val)
                        <option value="{{$val->p_ID}}">{{$val->chinese_p_name}}&nbsp;&nbsp;&nbsp;{{$val->english_p_name}}</option>
                    @endforeach
                </select><br>
                <label>市区中文名称：</label> <input type="text" name="chinese_c_name" value=""><br>
                <label>市区英文名称：</label> <input type="text" name="english_c_name" value="">
                <input type="submit" value="添加">
            </form>
        </div>
    </div>

@stop

@section('js')


@stop


