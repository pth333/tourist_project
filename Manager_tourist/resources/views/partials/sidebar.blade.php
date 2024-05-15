    <!-- partial:partials/_sidebar.html -->
    <nav class="sidebar sidebar-offcanvas" id="sidebar">
        <ul class="nav">
            <li class="nav-item nav-profile">
                <a href="#" class="nav-link">
                    <div class="nav-profile-image">
                        <img src="{{ asset('images/faces/face14.jpg')}}" alt="profile">
                        <span class="login-status online"></span>
                        <!--change to offline or busy as needed-->
                    </div>
                    <div class="nav-profile-text d-flex flex-column">
                        <span class="font-weight-bold mb-2">{{ Auth::user()->name}}</span>
                        <span class="text-secondary text-small">Project Manager</span>
                    </div>
                    <i class="mdi mdi-bookmark-check text-success nav-profile-badge"></i>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ url('dashboard')}}">
                    <span class="menu-title">Dashboard</span>
                    <i class="mdi mdi-home menu-icon"></i>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('categories.index')}}">
                    <span class="menu-title">Quản lý danh mục</span>
                    <i class="mdi mdi-format-list-bulleted menu-icon"></i>

                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('destination.index')}}">
                    <span class="menu-title">Quản lý địa điểm</span>
                    <i class="mdi mdi-google-maps menu-icon"></i>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('tour.index')}}">
                    <span class="menu-title">Quản lý tour</span>
                    <i class="mdi mdi-airplane-takeoff menu-icon"></i>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('slider.index')}}">
                    <span class="menu-title">Slider</span>
                    <i class="mdi mdi-hotel menu-icon"></i>
                </a>
            </li>


        </ul>
    </nav>
