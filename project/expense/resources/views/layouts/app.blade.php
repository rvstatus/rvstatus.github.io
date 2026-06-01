<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="{{ asset('resources/assets/image/wallet.png') }}" sizes="16x16" type="image/png">
    <title>Expense Management</title>
    <!-- date picker css start -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/css/bootstrap-datepicker.css" rel="stylesheet">
    <!-- date picker css end -->


    <!-- Fonts -->
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css" integrity="sha384-XdYbMnZ/QjLh6iI4ogqCTaIjrFk87ip+ekIjefZch0Y+PvJ8CDYtEs1ipDmPorQ+" crossorigin="anonymous"> -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.css">
    <!-- <link rel="stylesheet" href="{{ asset('resources/assets/css/font_awesome/all.css') }}"> -->

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato:100,300,400,700">

    <!-- Styles -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
    {{-- <link href="{{ elixir('css/app.css') }}" rel="stylesheet"> --}}
    <!-- <script src="{{ asset('resources/assets/js/pages.js') }}"></script> -->
    <!-- <link rel="stylesheet" href="{{ asset('resources/assets/css/main.css') }}"> -->
    <style>
        body {
            font-family: 'Lato';
        }

        .fa-btn {
            margin-right: 6px;
        }
    </style>
    <!-- JavaScripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.3/jquery.min.js" integrity="sha384-I6F5OKECLVtK/BL+8iSLDEHowSAfUo76ZL9+kGAgTRdiByINKJaqTPH/QVNS1VDb" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
    {{-- <script src="{{ elixir('js/app.js') }}"></script> --}}

    <!-- date picker js start -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/js/bootstrap-datepicker.js"></script>
    <!-- date picker js end -->

    <!-- jQuery time picker plugin start -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>
    <!-- jQuery time picker plugin end -->

    @if(Auth::check())
    <link rel="stylesheet" href="{{ asset('resources/assets/css/main_login.css') }}">
    @else
    <link rel="stylesheet" href="{{ asset('resources/assets/css/main.css') }}">
    @endif
</head>

<body id="app-layout">
    @if (Auth::guest())
    <nav class="navbar navbar-default navbar-static-top">
        <div class="container">
            <div class="navbar-header">

                <!-- Collapsed Hamburger -->
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                    <span class="sr-only">Toggle Navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

                <!-- Branding Image -->
                <!-- <a class="navbar-brand" href="{{ url('/') }}">
                        Expense Management
                    </a> -->
            </div>

            <div class="collapse navbar-collapse" id="app-navbar-collapse">
                <!-- Left Side Of Navbar -->
                <ul class="nav navbar-nav">
                    <li><a href="{{ route('home') }}">Expense Management</a></li>
                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="nav navbar-nav navbar-right">
                    <!-- Authentication Links -->
                    @if (Auth::guest())
                    <li><a href="{{ url('/login') }}">Login</a></li>
                    <li><a href="{{ url('/register') }}">Register</a></li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>
    @yield('content')
    @else

    <div id="viewport">
        <!-- navbar start -->
        <div class="navbar">
            <!-- nav-left -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class='nav-link' style="padding-top : 20px !important;">
                        <i class="fas fa-bars" onclick="collapseSidebar()"></i>
                    </a>
                </li>
                <li class="nav-item">
                    <img src="{{ asset('resources/assets/image/expenseic.png') }}" alt="" class="logo">

                </li>
            </ul>

            <!-- end nav left  -->
            <h1 class="navbar-text">Expense Management System</h1>
            <!-- nav right  -->
            <ul class="navbar-nav nav-right">
                <!-- <li class="nav-item">
                        <a class="nav-link" href="#" onclick = "switchTheme()">
                            <i class="fas fa-moon dark-icon"></i>
                            <i class="fas fa-sun light-icon"></i>
                        </a>
                    </li> -->
                <li class="dropdown avt">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                        <img src="{{ asset('resources/assets/image/userlogo.png') }}" alt="User Avatar" class="img-circle" width="35" height="35">
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <!-- <a href="10-Profile.php"> -->
                            <a href="javascript:alert('under construction')">
                                <i class="fas fa-user-tie"></i> Profile
                            </a>
                        </li>
                        <li role="separator" class="divider"></li>
                        <li>
                            <a href="{{ url('/logout') }}">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- <li class="nav-item">
                    <div class="avt dropdown">
                        <img src="{{ asset('resources/assets/image/userlogo.png') }}" alt="" class="dropdown-toggle" data-toggle="user-menu">
                        <ul id="user-menu" class="dropdown-menu">
                            <li class="dropdown-menu-item">
                                <a href="10-Profile.php" class="dropdown-menu-link">
                                    <div>
                                        <i class="fas fa-user-tie"></i>
                                    </div>
                                    <span>Profile</span>
                                </a>
                            </li>
                            <li class="dropdown-menu-item">
                                <a href="{{ url('/logout') }}" class="dropdown-menu-link">
                                    <div>
                                        <i class="fas fa-sign-out-alt"></i>
                                    </div>
                                    <span>Logout</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li> -->
            </ul>
            <!-- sidebar start -->
            <div class="sidebar">
                <ul class="sidebar-nav" id="sidebar-nav">
                    <li class="sidebar-nav-item">
                        <a href="{{ url('/expense_dashboard') }}" class="sidebar-nav-link">
                            <div>
                                <i class="fas fa-tachometer-alt"></i>
                            </div>
                            <span>
                                Dashboard
                            </span>
                        </a>
                    </li>
                    <li class="sidebar-nav-item">
                        <a href="{{ url('/expense_list') }}" class="sidebar-nav-link">
                            <div>
                                <i class="fas fa-coins"></i>
                            </div>
                            <span>
                                Expanse List
                            </span>
                        </a>
                    </li>
                    <li class="sidebar-nav-item" id="Expense" onclick="open1()">
                        <a href="#" class="sidebar-nav-link">
                            <div>
                                <i class="fa fa-plus-circle"></i>
                            </div>
                            <span>
                                Expenses
                            </span>
                        </a>
                    </li>
                    <li class="sidebar-nav-item" style="display: none;">
                        <a href="5-Add-Expenses.php" class="sidebar-nav-link">
                            <div>
                                <i class="fas fa-arrow-right" aria-hidden="true"></i>
                            </div>
                            <span>
                                Add Expenses
                            </span>
                        </a>
                    </li>
                    <li class="sidebar-nav-item" style="display: none">
                        <a href="6-Manage-Expense.php" class="sidebar-nav-link" style="display: none">
                            <div>
                                <i class="fas fa-arrow-right" aria-hidden="true"></i>
                            </div>
                            <span>
                                Manage Expenses
                            </span>
                        </a>
                    </li>
                    <li class="sidebar-nav-item" id="ER" onclick="open2()">
                        <a href="#" class="sidebar-nav-link">
                            <div>
                                <i class="fas fa-calendar-day"></i>
                            </div>
                            <span>
                                Expense Report
                            </span>
                        </a>
                    </li>
                    <li class="sidebar-nav-item" style="display:none;">
                        <a href="7-Datewise.php" class="sidebar-nav-link">
                            <div>
                                <i class="fas fa-calendar-day"></i>
                            </div>
                            <span>
                                Datewise Report
                            </span>
                        </a>
                    </li>
                    <li class="sidebar-nav-item" style="display: none;">
                        <a href="8-Monthly.php" class="sidebar-nav-link">
                            <div>
                                <i class="fas fa-calendar-week"></i>
                            </div>
                            <span>
                                Monthly Report
                            </span>
                        </a>
                    </li>
                    <li class="sidebar-nav-item" style="display:none;">
                        <a href="9-Yearly.php" class="sidebar-nav-link">
                            <div>
                                <i class="fas fa-calendar"></i>
                            </div>
                            <span>
                                Yearly Report
                            </span>
                        </a>
                    </li>
                    <li class="sidebar-nav-item" id="Settings" onclick="openSettingsMenu()">
                        <a href="#" class="sidebar-nav-link">
                            <div>
                                <i class="fas fa-cog"></i>
                            </div>
                            <span>
                                Setting
                            </span>
                        </a>
                    </li>
                    <li class="sidebar-nav-item" style="display:none;">
                        <a href="{{ url('/project_type_list') }}" class="sidebar-nav-link">
                            <div>
                                <i class="fas fa-arrow-right"></i>
                            </div>
                            <span>Project Type</span>
                        </a>
                    </li>

                    <li class="sidebar-nav-item" style="display:none;">
                        <a href="{{ url('/work_category_list') }}" class="sidebar-nav-link">
                            <div>
                                <i class="fas fa-arrow-right"></i>
                            </div>
                            <span>Work Category</span>
                        </a>
                    </li>

                    <li class="sidebar-nav-item" style="display:none;">
                        <a href="{{ url('/work_type_list') }}" class="sidebar-nav-link">
                            <div>
                                <i class="fas fa-arrow-right"></i>
                            </div>
                            <span>Work Type</span>
                        </a>
                    </li>
                    @if(Auth::user()->is_admin == 1)
                    <li class="sidebar-nav-item">
                        <a href="{{ url('/user_approval_list') }}" class="sidebar-nav-link">
                            <div> <i class="fas fa-users"></i> </div>
                            <span> User Approval </span>
                        </a>
                    </li>
                    @endif
                </ul>
            </div>
            <!-- sidebar end -->
        </div>
        <!-- <nav class="navbar navbar-default">
                <div class="container-fluid">
                    <ul class="nav navbar-nav navbar-right">
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                            {{ Auth::user()->name }} <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu" role="menu">
                                <li>
                                    <a href="{{ url('/logout') }}"><i class="fa fa-btn fa-sign-out"></i>Logout</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav> -->
    </div>
    <!-- content -->
    <div id="content">
        <div class="container-fluid" style="margin-top: 70px;">
            @yield('content')
        </div>
    </div>
    @endif
    <script src="{{ asset('resources/assets/js/expense/expense.js') }}"></script>
    <script src="{{ asset('resources/assets/js/main.js') }}"></script>
    <!-- SweetAlert (GLOBAL) -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('resources/lang/' . app()->getLocale() . '.js') }}"></script>
</body>

</html>