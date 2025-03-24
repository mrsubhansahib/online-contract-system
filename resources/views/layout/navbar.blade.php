<nav class="navbar navbar-expand-lg main-navbar sticky">
    <div class="form-inline mr-auto">
        <ul class="navbar-nav mr-3">
            <li><a href="#" data-toggle="sidebar"
                    class="nav-link nav-link-lg
                                collapse-btn"> <i
                        data-feather="align-justify"></i></a></li>
            <li><a href="#" class="nav-link nav-link-lg fullscreen-btn">
                    <i data-feather="maximize"></i>
                </a></li>
            <li>
                <form class="form-inline mr-auto">
                    <div class="search-element">
                        <input class="form-control" type="search" placeholder="Search" aria-label="Search"
                            data-width="200">
                        <button class="btn" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
            </li>
        </ul>
    </div>
    <ul class="navbar-nav navbar-right">
        @php
            $notificationCount = App\Models\ContractNotification::where('user_id',auth()->user()->id)->where('status','unreaded')->count();
        @endphp
        @if ($notificationCount)
        <li class="dropdown dropdown-list-toggle show">
          <a href="#" data-toggle="dropdown" class="nav-link notification-toggle nav-link-lg message-toggle" aria-expanded="true">
                <svg
                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="feather feather-bell bell">
                    <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                    <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                </svg>
                  <span class="badge headerBadge1">   
                    {{ $notificationCount }}
                  </span>
            </a>
            <div class="dropdown-menu dropdown-list dropdown-menu-right pullDown">
                <div class="dropdown-header">
                    Notifications 
                </div>
                <div class="dropdown-list-content dropdown-list-icons" style="overflow: hidden; outline: none;height:auto;max-height:250px;"
                    tabindex="4">
                    @foreach (App\Models\ContractNotification::where('user_id',auth()->user()->id)->where('status','unreaded')->orderBy('created_at','desc')->get(['content','created_at']) as $item)
                    <a href="#" class="dropdown-item dropdown-item-unread"> 
                      <span class="dropdown-item-icon bg-primary text-white"> 
                        <i class="fas fa-clipboard"></i>
                      </span> 
                      <span class="dropdown-item-desc text-capitalize">
                        {{ $item->content }}
                        <span class="time">{{ \Carbon\Carbon::parse( $item->created_at )->diffForHumans( \Carbon\Carbon::now() ) }}</span>
                      </span>
                    </a>
                    @endforeach
                </div>
                <div class="dropdown-footer text-center">
                  <a href="{{ route('mark-as-readed') }}" class="badge bg-primary text-white">Mark All As Read</a>
                </div>                
            </div>
        </li>
        @endif

        <li class="dropdown">
            <a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user"> 
              <img alt="image" src="{{ asset('assets/img/user.png') }}" class="user-img-radious-style"> 
              <span class="d-sm-none d-lg-inline-block"></span>
            </a>
            <div class="dropdown-menu dropdown-menu-right pullDown">
                <div class="dropdown-title">Hello {{ auth()->user()->name }}</div>
                <div class="dropdown-divider"></div>
                <a href="{{ route('logout') }}" class="dropdown-item has-icon text-danger"> <i
                        class="fas fa-sign-out-alt"></i>
                    Logout
                </a>
            </div>
        </li>
    </ul>
</nav>
