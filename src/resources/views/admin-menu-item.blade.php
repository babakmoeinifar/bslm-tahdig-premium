@if(allowed('reservation_view') || allowed('food_view'))
    <li class="sidebar-item"><a class="sidebar-link has-arrow waves-effect waves-dark"
                                href="javascript:void(0)" aria-expanded="false">
            <i class="bi bi-egg-fried"></i>
            <span class="hide-menu ">ته‌دیگ</span>
        </a>
        <ul aria-expanded="false" class="collapse  first-level">
            @if(allowed('reservation_view'))
                <li class="sidebar-item">
                    <a class="sidebar-link waves-effect waves-dark sidebar-link"
                       href="{{ url('admin/lunch/reservation') }}"
                       aria-expanded="false"><i class="fab fa-bitbucket"></i>
                        <span class="hide-menu ">لیست روز</span></a>
                </li>
            @endif
            @if(allowed('food_view'))
                <li class="sidebar-item">
                    <a class="sidebar-link waves-effect waves-dark sidebar-link"
                       href="{{ url('admin/lunch/reservation/create') }}"
                       aria-expanded="false"><i class="fas fa-dollar-sign"></i>
                        <span class="hide-menu ">افزودن روزغذا</span></a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link waves-effect waves-dark sidebar-link"
                       href="{{ url('admin/lunch/restaurants') }}"
                       aria-expanded="false"><i class="fas fa-dollar-sign"></i>
                        <span class="hide-menu ">لیست رستوران</span></a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link waves-effect waves-dark sidebar-link"
                       href="{{ url('admin/lunch/foods') }}"
                       aria-expanded="false"><i class="fas fa-dollar-sign"></i>
                        <span class="hide-menu ">لیست غذاها</span></a>
                </li>
                @if(allowed('billing_view'))
                    <li class="sidebar-item">
                        <a class="sidebar-link waves-effect waves-dark sidebar-link"
                           href="{{ url('admin/bills/lunch-users') }}"
                           aria-expanded="false"><i class="fab fa-bitbucket"></i>
                            <span class="hide-menu ">حساب غذا</span></a>
                    </li>
                @endif
            @endif
        </ul>
    </li>
@endif
