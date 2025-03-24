<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="{{ auth()->user()->role=="admin"?route('dashboard'):"#" }}"> Contract Man.</a>
        </div>
        @if (auth()->user()->role=='admin')
            
            <ul class="sidebar-menu">
                
                <li class="dropdown">
                    <a href="#" class="menu-toggle nav-link has-dropdown"><i
                            data-feather="users"></i><span>Users</span></a>
                    <ul class="dropdown-menu">
                        <li><a class="nav-link" href="{{ route('user') }}">All Users</a></li>
                        <li><a class="nav-link" href="{{ route('user.add') }}">New User</a></li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="menu-toggle nav-link has-dropdown">
                        <i data-feather="folder"></i><span>Categories</span></a>
                    <ul class="dropdown-menu">
                        <li><a class="nav-link" href="{{ route('category') }}">All Categories</a></li>
                        <li><a class="nav-link" href="{{ route('category.add') }}">New Category</a></li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="menu-toggle nav-link has-dropdown"><i
                            data-feather="layout"></i><span>Templates</span></a>
                    <ul class="dropdown-menu">
                        <li><a class="nav-link" href="{{ route('template') }}">All Templates</a></li>
                        <li><a class="nav-link" href="{{ route('template.add') }}">New Template</a></li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="menu-toggle nav-link has-dropdown">
                        <i data-feather="clipboard"></i><span>Contracts</span></a>
                    <ul class="dropdown-menu">
                        <li><a class="nav-link" href="{{ route('contract') }}">All Contracts</a></li>
                        <li><a class="nav-link" href="{{ route('contract.add') }}">New Contract</a></li>
                    </ul>
                </li>
            </ul>
            
        @else
        
            <ul class="sidebar-menu">
                
                <li>
                    <a href="{{ route('user.profile') }}" class="nav-link">
                        <i data-feather="user"></i><span>Profile</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('contract.my-contracts') }}" class="nav-link">
                        <i data-feather="briefcase"></i><span>My Contracts</span>
                    </a>
                </li>
            </ul>
            
        @endif
    </aside>
</div>