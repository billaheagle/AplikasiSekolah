<div id="left-sidebar" class="sidebar">
    <div class="sidebar-scroll">
        <div class="user-account">
            <img src="{{ asset('assets/img/user.png') }}" class="rounded-circle user-photo" alt="User Profile Picture">
            <div class="dropdown">
                <span>Welcome,</span>
                <a href="javascript:void(0);" class="dropdown-toggle user-name" data-toggle="dropdown">
                    <strong>{{ auth()->user()->name }}</strong>
                </a>
                <ul class="dropdown-menu dropdown-menu-right account">
                    <li><a href="javascript:void(0);"><i class="icon-settings"></i>Settings</a></li>
                    <li class="divider"></li>
                    <li>
                        <a href="{{ route('logout') }}" style="cursor: pointer" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="icon-power"></i>Logout</a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </li>
                </ul>
            </div>
            <hr>
        </div>
        <div class="tab-content p-l-0 p-r-0">
            <div class="tab-pane active" id="menu">
                <nav id="left-sidebar-nav" class="sidebar-nav">
                    <ul id="main-menu" class="metismenu">
                    @foreach($global_menu as $menuItem)
                        @if( $menuItem->parent_id == 0 )
                            @can($menuItem->model_name . '-access')
                            <li class="{{ Request::segment(1) === $menuItem->model_name ? 'active' : null }}">
                            <a href="{{ $menuItem->url }}" class="{{ $menuItem->children->isEmpty() ? "" : "has-arrow" }}"><i class="{{ $menuItem->icon }}"></i> <span>
                                    {{ $menuItem->title }}</span>
                                </a>
                            @endcan
                        @endif

                        @if( ! $menuItem->children->isEmpty() )
                            <ul>
                                @foreach($menuItem->children as $subMenuItem)
                                    @can($subMenuItem->model_name . '-access')
                                    <li class="{{ Request::segment(2) === $subMenuItem->model_name ? 'active' : null }}">
                                        <a href="{{ $subMenuItem->url }}">{{ $subMenuItem->title }}</a>
                                    </li>
                                    @endcan
                                @endforeach
                            </ul>
                        @endif
                        </li>
                    @endforeach
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>
