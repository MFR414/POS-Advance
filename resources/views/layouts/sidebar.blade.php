<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    {{-- <a href="index3.html" class="brand-link">
      <img src="{{asset('AdminLTE')}}/dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">AdminLTE 3</span>
    </a> --}}

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="{{asset('AdminLTE')}}/dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block">{{ Auth::user()->name }}</a>
        </div>
      </div>

      <!-- SidebarSearch Form -->
      {{-- <div class="form-inline">
        <div class="input-group" data-widget="sidebar-search">
          <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
          <div class="input-group-append">
            <button class="btn btn-sidebar">
              <i class="fas fa-search fa-fw"></i>
            </button>
          </div>
        </div>
      </div> --}}

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <!-- Add icons to the links using the .nav-icon class
                with font-awesome or any other icon font library -->
              <li class="nav-item">
                <a href="{{ route('application.dashboard') }}" class="nav-link {{ ( !empty($active_page) && $active_page == 'dashboard') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-home"></i>
                    <p>Dashboard</p>
                </a>
              </li>
              <li class="nav-item">
                  <a href="{{ route('application.transactions.create') }}" class="nav-link {{ ( !empty($active_page) && $active_page == 'transactions-create') ? 'active' : '' }}">
                      <i class="nav-icon fas fa-cash-register"></i>
                      <p>Buat Transaksi</p>
                  </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('application.transactions.index') }}" class="nav-link {{ ( !empty($active_page) && $active_page == 'transactions') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-balance-scale"></i>
                    <p>Transaksi</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('application.products.index') }}" class="nav-link {{ ( !empty($active_page) && $active_page == 'products') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-box"></i>
                    <p>Produk</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('application.stocks.index') }}" class="nav-link {{ ( !empty($active_page) && $active_page == 'stocks') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-warehouse"></i>
                    <p>Stock</p>
                </a>
              </li>
              
            @if( auth()->user()->hasAnyRole(['Admin','Super Admin','Owner User']))
                <li class="nav-item">
                  <a href="{{ route('application.reports.index') }}" class="nav-link {{ ( !empty($active_page) && $active_page == 'reports') ? 'active' : '' }}"">
                    <i class="nav-icon fa fa-book"></i>
                    <p>Laporan</p>
                  </a>
                </li>
                <li class="nav-item {{ ( !empty($active_page) && $active_page == 'users') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ ( !empty($active_page) && $active_page == 'users') ? 'active' : '' }}"">
                        <i class="nav-icon fa fa-list"></i><p>Daftar Pengguna</p>
                        <p>
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                      <li class="nav-item">
                          <a class="nav-link {{ ( !empty($active_subpage) && $active_subpage == 'admins') ? 'active' : '' }}" href="{{ route('application.users.admins.index') }}"> <i class="nav-icon fa fa-user"></i>
                              <p>Admin</p>
                          </a>
                      </li>
                      <li class="nav-item">
                          <a class="nav-link {{ ( !empty($active_subpage) && $active_subpage == 'cashiers') ? 'active' : '' }}" href="{{ route('application.users.cashiers.index') }}"> <i class="nav-icon fa fa-user"></i>
                              <p>Kasir</p>
                          </a>
                      </li>
                    </ul>
                <li>
            @endif

            <li class="nav-item">
                <a href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();" class="nav-link">
                    <i class="nav-icon fas fa-door-open"></i>
                    <p>Logout</p>
                </a>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                  @csrf
                </form>             
            </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
    
  </aside>