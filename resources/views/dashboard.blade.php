<x-app-layout>
    <x-slot name="header">
        <div class="block-header">
            <div class="container">
                <div class="row clearfix">
                    <div class="col-lg-6 col-md-12">
                        <ul class="breadcrumb pl-0 pb-0 ">
                            <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                            <li class="breadcrumb-item active">Dashboard</li>
                        </ul>
                        <h1 class="mb-1 mt-1">Welcome, {{ Auth::user()->firstname }} {{ Auth::user()->lastname }}</h1>
                        <span>Welcome back to your dashboard, if need a help <a href="javascript:void(0);" class="text-secondary">Contact</a> us.</span>
                    </div>            
                    <div class="col-lg-6 col-md-12 text-md-right">
                        <button class="btn btn-default hidden-xs ml-2">Download Report</button>
                        <button class="btn btn-secondary hidden-xs ml-2">New Report</button>
                    </div>
                </div>
                <div class="bh_divider"></div>
            </div>
        </div>
    </x-slot>

    <div class="container">
        <div class="row clearfix row-deck">
            <div class="col-12">
                <div class="card top_widget">
                    <div class="header">
                        <h2><strong>Blank</strong> Page</h2>                        
                        <ul class="header-dropdown">
                            <li class="dropdown"> <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"> <i class="zmdi zmdi-more"></i> </a>
                                <ul class="dropdown-menu dropdown-menu-right">
                                    <li><a href="javascript:void(0);">Action</a></li>
                                    <li><a href="javascript:void(0);">Another action</a></li>
                                    <li><a href="javascript:void(0);">Something else</a></li>
                                </ul>
                            </li>
                            <li class="remove">
                                <a role="button" class="boxs-close"><i class="zmdi zmdi-close"></i></a>
                            </li>
                        </ul>
                    </div>
                    <div class="body">
                        <h4>Stater page</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
