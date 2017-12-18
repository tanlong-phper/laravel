

<ul id="myTab" class="nav nav-tabs">
    <li @if($index == 1) class="active" @endif><a href="{{ route('app/ad') }}">首页</a></li>
    <li @if($index == 2) class="active" @endif><a href="" >商城</a></li>
    <li @if($index == 3) class="active" @endif><a href="" >附近</a></li>
    <li @if($index == 4) class="active" @endif><a href="" >商品详情页</a></li>
    <li @if($index == 5) class="active" @endif><a href="" >购物车</a></li>
    <li @if($index == 6) class="active" @endif><a href="" >覆盖植入</a></li>
</ul>