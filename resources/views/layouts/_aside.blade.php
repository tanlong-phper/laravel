

<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

        <!-- Sidebar user panel (optional) -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="/dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <p>@if(isset($__user_info__)) {{ $__user_info__['name'] }} @endif</p>
                <!-- Status -->
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>

        <!-- search form (Optional) -->
        <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Search...">
          <span class="input-group-btn">
              <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
              </button>
            </span>
            </div>
        </form>
        <!-- /.search form -->

        <!-- Sidebar Menu -->
        <ul class="sidebar-menu" data-widget="tree">
            <li class="header">主导航</li>


            @foreach($__menu_lists__ as $values)

                @if(!empty($values['sub']))


                    <li class="treeview">
                        <a href="{{ $values['url'] }}"><i class="fa {{ $values['icon'] }}"></i> <span>{{ $values['name'] }}</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
                        </a>
                        <ul class="treeview-menu">
                            @foreach($values['sub'] as $child_values)
                                <li class="li_diy_menu"><a href="{{ $child_values['url'] }}"><i class="fa fa-circle-o"></i>{{ $child_values['name'] }}</a></li>
                            @endforeach

                        </ul>
                    </li>

                @else

                    <li class="li_diy_menu"><a href="{{ $values['url'] }}"><i class="fa {{ $values['icon'] }}"></i> <span>{{ $values['name'] }}</span></a></li>

                @endif





            @endforeach


            <li class="header">标签</li>
            <li><a href="#"><i class="fa fa-circle-o text-red"></i> <span>重要</span></a></li>
            <li><a href="#"><i class="fa fa-circle-o text-yellow"></i> <span>警告</span></a></li>
            <li><a href="#"><i class="fa fa-circle-o text-aqua"></i> <span>消息</span></a></li>



        </ul>
        <!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
</aside>