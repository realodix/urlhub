      
<nav class="sidebar sidebar-offcanvas" id="sidebar">
  <ul class="nav">
    
    <li class="nav-item">
      <a class="nav-link" data-toggle="collapse" href="#ui-basic" aria-expanded="false" aria-controls="ui-basic">
        <span class="menu-title">User Management</span>
        <i class="menu-arrow"></i>
        <i class="mdi mdi-account-multiple menu-icon"></i>
      </a>
      <div class="collapse" id="ui-basic">
        <ul class="nav flex-column sub-menu">
            <li class="nav-item"> 
              <a class="nav-link" href="{{ route('admin.user_management.user.index') }}">users</a>
            </li>
            <li class="nav-item"> 
              <a class="nav-link" href="{{ route('admin.user_management.role.index') }}">roles</a>
            </li>
            <li class="nav-item"> 
              <a class="nav-link" href="{{ route('admin.user_management.permission.index') }}">permissions</a>
            </li>
            <li class="nav-item"> 
              <a class="nav-link" href="{{ route('admin.user_management.department.index') }}">departments</a>
            </li>
        </ul>
      </div>
    </li>
    <li class="nav-item">
      <a class="nav-link" data-toggle="collapse" href="#notification" aria-expanded="false" aria-controls="ui-basic">
        <span class="menu-title">Notifications (soon...)</span>
        <i class="menu-arrow"></i>
        <i class="mdi mdi-bell-ring menu-icon"></i>
      </a>
      <div class="collapse" id="notification">
        <ul class="nav flex-column sub-menu">
            <li class="nav-item"> 
              <a class="nav-link" href="#">notifications</a>
            </li>
        </ul>
      </div>
    </li>
    <li class="nav-item">
      <a class="nav-link" data-toggle="collapse" href="#translation" aria-expanded="false" aria-controls="ui-basic">
        <span class="menu-title">Translation (soon...)</span>
        <i class="menu-arrow"></i>
        <i class="mdi  mdi-flag menu-icon"></i>
      </a>
      <div class="collapse" id="translation">
        <ul class="nav flex-column sub-menu">
            <li class="nav-item"> 
              <a class="nav-link" href="#">transaltion</a>
            </li>
        </ul>
      </div>
    </li>

    <li class="nav-item sidebar-actions">
      <span class="nav-link">
        <a href="{{ config('laravel_user_management.admin_url') }}" class="btn btn-block btn-lg btn-gradient-primary mt-4"><- Return Back</a>
      </span>
    </li>
  </ul>
</nav>