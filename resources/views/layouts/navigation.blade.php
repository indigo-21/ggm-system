<!-- Top Bar -->
<nav class="top_navbar">
    <div class="container">
        <div class="row clearfix">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="navbar-logo">
                        <a href="javascript:void(0);" class="bars"></a>
                        <a class="navbar-brand" href="#"><img src="{{ asset('assets/images/header.png') }}" width="400" alt="Amaze">
                            {{-- <span class="ml-2">Amaze</span> --}}
                        </a>
                    </div>
                    <div class="d-flex justify-content-end flex-grow-1">
                        <ul class="navbar">    
                            <li class="dropdown profile">
                                <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button">
                                    <img class="rounded-circle" src="{{ asset('assets/images/profile_av.png') }}" alt="User">
                                </a>
                                <ul class="dropdown-menu">
                                    <li>
                                        <div class="user-info">
                                            <h5 class="user-name mb-0">{{ Auth::user()->firstname }} {{ Auth::user()->lastname }}</h5>
                                            <p class="user-position font-13">{{ Auth::user()->email }}</p>
                                            <hr>
                                        </div>
                                    </li>                            
                                    <li><a href="{{route('profile.edit')}}"><i class="icon-user mr-2"></i> <span>My Profile</span>  </a></li>
                                    <li><a href="taskboard.html"><i class="icon-notebook mr-2"></i><span>Taskboard</span> <span class="badge badge-info float-right">New</span></a></li>
                                    <li>
                                        {{-- <a href="sign-in.html"><i class="icon-power mr-2"></i><span>Logout Out</span></a> --}}
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                        
                                            <x-responsive-nav-link :href="route('logout')"
                                                    onclick="event.preventDefault();
                                                                this.closest('form').submit();">
                                                <i class="icon-power mr-2"></i><span>Log Out</span>
                                            </x-responsive-nav-link>
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>        
    </div>
</nav>

<aside id="leftsidebar" class="sidebar h_menu">
    <div class="container">
        <div class="row clearfix">
            <div class="col-12">
                <div class="menu text-center">
                    <ul class="list">
                        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                            <a href="javascript:void(0);" class="menu-toggle"><i class="zmdi zmdi-home"></i><span>Dashboard</span></a>
                        </x-nav-link>
                        <x-dropdown-link>
                            <x-slot name="label_anchor">
                                <a href="javascript:void(0);" class="menu-toggle"><i class="zmdi zmdi-folder"></i><span>Orders</span></a>
                            </x-slot>
                            <ul class="ml-menu">
                                <li><a class="text-left" href="{{route('quote.index')}}" class="{{request()->routeIs('quote') ? 'active' : ''}}">Quotes</a></li>
                                <li><a class="text-left" href="#">Orders</a></li>
                                <li><a class="text-left" href="#">Completed</a></li>
                                <li><a class="text-left" href="#">All Orders</a></li>
                            </ul>
                        </x-dropdown-link>
                        <x-nav-link>
                            <a href="javascript:void(0);" class="menu-toggle"><i class="zmdi zmdi-home"></i><span>Schedules</span></a>
                        </x-nav-link>
                        <x-nav-link>
                            <a href="javascript:void(0);" class="menu-toggle"><i class="zmdi zmdi-home"></i><span>Inventory</span></a>
                        </x-nav-link>
                        <x-nav-link>
                            <a href="javascript:void(0);" class="menu-toggle"><i class="zmdi zmdi-home"></i><span>Customers</span></a>
                        </x-nav-link>
                        <x-nav-link>
                            <a href="javascript:void(0);" class="menu-toggle"><i class="zmdi zmdi-home"></i><span>Archives</span></a>
                        </x-nav-link>
                        <x-nav-link>
                            <a href="javascript:void(0);" class="menu-toggle"><i class="zmdi zmdi-home"></i><span>Rylond</span></a>
                        </x-nav-link>
                        <x-dropdown-link>
                            <x-slot name="label_anchor">
                                <a href="javascript:void(0);" class="menu-toggle"><i class="zmdi zmdi-folder"></i><span>Reports</span></a>
                            </x-slot>
                            <ul class="ml-menu">
                                <li><a class="text-left" href="#">Order Reports</a></li>
                                <li><a class="text-left" href="#">Renovations with no Description</a></li>
                                <li><a class="text-left" href="#">Unpaid Jobs</a></li>
                                <li><a class="text-left" href="#">No Grave Number</a></li>
                                <li><a class="text-left" href="#">Report Status</a></li>
                                <li><a class="text-left" href="#">Washdown Report</a></li>
                            </ul>
                        </x-dropdown-link>
                        <x-dropdown-link>
                            <x-slot name="label_anchor">
                                <a href="javascript:void(0);" class="menu-toggle"><i class="zmdi zmdi-folder"></i><span>Masterfiles</span></a>
                            </x-slot>
                            <ul class="ml-menu">
                                <li><a class="text-left" href="{{route('cemetery.index')}}" class="{{request()->routeIs('cemetery') ? 'active' : ''}}">Cemeteries</a></li>
                                <li><a class="text-left" href="{{route('burial_society_organization.index')}}" class="{{request()->routeIs('burial_society_organization') ? 'active' : ''}}">Burial Society Organization</a></li>
                                <li><a class="text-left" href="{{route('grave_space.index')}}" class="{{request()->routeIs('grave_space') ? 'active' : ''}}">Grave Spaces</a></li>
                                <li><a class="text-left" href="{{route('letter_type.index')}}" class="{{request()->routeIs('letter_type') ? 'active' : ''}}">Letter Types</a></li>
                                <li><a class="text-left" href="{{route('material.index')}}" class="{{request()->routeIs('material') ? 'active' : ''}}">Materials</a></li>
                                <li><a class="text-left" href="{{route('accessory.index')}}" class="{{request()->routeIs('accessory') ? 'active' : ''}}">Accessories</a></li>
                                <li><a class="text-left" href="{{route('based_ledger.index')}}" class="{{request()->routeIs('based_ledger') ? 'active' : ''}}">Based Ledgers</a></li>
                                <li><a class="text-left" href="{{route('order_type.index')}}" class="{{request()->routeIs('order_type') ? 'active' : ''}}">Order Types</a></li>
                            </ul>
                        </x-dropdown-link>   
                        @if (Auth::id() == 1): 
                            <x-dropdown-link>
                                <x-slot name="label_anchor">
                                    <a href="javascript:void(0);" class="menu-toggle"><i class="zmdi zmdi-folder"></i><span>Configuration</span></a>
                                </x-slot>
                                <ul class="ml-menu">
                                    <li><a class="text-left" href="{{route('user.index')}}" class="{{request()->routeIs('user') ? 'active' : ''}}">Users</a></li>
                                    <li><a class="text-left" href="{{route('location.index')}}" class="{{request()->routeIs('location') ? 'active' : ''}}">Location</a></li>
                                    <li><a class="text-left" href="{{route('account_level.index')}}" class="{{request()->routeIs('account_level') ? 'active' : ''}}">Account Level</a></li>
                                    <li><a class="text-left" href="{{route('module.index')}}" class="{{request()->routeIs('module') ? 'active' : ''}}">Modules</a></li>
                                </ul>
                            </x-dropdown-link>
                        @endif
                                 
                    </ul>
                </div>
            </div>
        </div>
    </div>
</aside>

