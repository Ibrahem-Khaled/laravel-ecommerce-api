<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('home.dashboard') }}">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-laugh-wink"></i>
        </div>
        <div class="sidebar-brand-text mx-3">لوحة التحكم</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item active">
        <a class="nav-link" href="{{ route('home.dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>لوحة التحكم</span></a>
    </li>

    <!-- Heading -->
    <div class="sidebar-heading">
        الادارات
    </div>

    <!-- Nav Item - Tables -->
    <li class="nav-item">
        <a class="nav-link" href="{{ route('users.index') }}">
            <i class="fas fa-fw fa-users"></i>
            <span>المستخدمين</span></a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('categories.index') }}">
            <i class="fas fa-fw fa-list"></i>
            <span>التصنيفات</span></a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('subCategories.index') }}">
            <i class="fas fa-fw fa-list"></i>
            <span>التصنيفات الفرعية</span></a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('products.index') }}">
            <i class="fas fa-fw fa-box"></i>
            <span> المنتجات</span></a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('notifications.index') }}">
            <i class="fas fa-fw fa-bell"></i>
            <span> الاشعارات</span></a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('orders.index') }}">
            <i class="fas fa-fw fa-box"></i>
            <span> الطلبات</span></a>
    </li>
    <li class="nav-item">

        <a class="nav-link" href="{{ route('live-chat.index') }}">
            <i class="fas fa-fw fa-comments"></i>
            <span> المحادث الحية
                @if ($unreadMessagesCount > 0)
                    <span class="badge bg-danger text-white ">{{ $unreadMessagesCount }}</span>
                @endif
            </span></a>

    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('coupons.index') }}">
            <i class="fas fa-fw fa-tags"></i>
            <span> اعدادات الكوبونات</span></a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('app-settings.index') }}">
            <i class="fas fa-fw fa-cogs"></i>
            <span> اعدادات التطبيق</span></a>
    </li>

</ul>
