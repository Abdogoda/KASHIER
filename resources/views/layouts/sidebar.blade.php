<aside class="sidebar-left border-right bg-white shadow" id="leftSidebar" data-simplebar>
 <a href="#" class="btn collapseSidebar toggle-btn d-lg-none text-muted ml-2 mt-3" data-toggle="toggle">
   <i class="fe fe-x"><span class="sr-only"></span></i>
 </a>
 <nav class="vertnav navbar navbar-light">
   <!-- nav bar -->
   <div class="w-100 mb-2 d-flex border-bottom">
     <a class="navbar-brand mx-auto mt-2 flex-fill text-center" href="{{route('dashboard')}}">
       <img src="{{$siteSettings['لوجو_الشركة'] ? asset('storage/'.$siteSettings['لوجو_الشركة']) : asset('assets/images/logo.png')}}"  loading="lazy" id="logo" class="navbar-brand-img brand-sm" alt="website logo">
     </a>
   </div>
   <ul class="navbar-nav flex-fill w-100 mb-2">
     <li class="nav-item mb-1 w-100">
       <a class="nav-link" href="{{route('dashboard')}}">
         <img src="{{asset('assets/images/icons/dashboard.png')}}" loading="lazy" class="avatar-img" width="22px"  height="22px" alt="dashboard-image">
         <span class="ml-3 item-text">لوحة التحكم</span><span class="sr-only">(current)</span>
       </a>
     </li>
   </ul>
   <ul class="navbar-nav flex-fill w-100 mb-2">
     @can('view_roles')
      <li class="nav-item mb-1 dropdown">
        <a href="#roles" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle nav-link">
          <img src="{{asset('assets/images/icons/jobs.png')}}" loading="lazy" class="avatar-img" width="22px"  height="22px" alt="roles-image">
          <span class="ml-3 item-text">الوظائف</span>
        </a>
        <ul class="collapse list-unstyled pl-4 w-100" id="roles">
          <li class="nav-item mb-1">
            <a class="nav-link pl-3" href="{{route('roles.index')}}"><span class="ml-1 item-text">ادارة الوظائف</span>
            </a>
          </li>
          @can('add_role')
            <li class="nav-item mb-1">
              <a class="nav-link pl-3" href="{{route('roles.create')}}"><span class="ml-1 item-text">اضافة وظيفة جديدة</span></a>
            </li>
          @endcan
        </ul>
      </li>
      @endcan
      @can('view_employees')
        <li class="nav-item mb-1 dropdown">
          <a href="#employees" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle nav-link">
            <img src="{{asset('assets/images/icons/employees.png')}}" loading="lazy" class="avatar-img" width="22px"  height="22px" alt="employees-image">
            <span class="ml-3 item-text">الموظفين</span>
          </a>
          <ul class="collapse list-unstyled pl-4 w-100" id="employees">
            <li class="nav-item mb-1">
              <a class="nav-link pl-3" href="{{route('employees.index')}}"><span class="ml-1 item-text">ادارة الموظفين</span></a>
            </li>
            @can('add_employee')
              <li class="nav-item mb-1">
                <a class="nav-link pl-3" href="{{route('employees.create')}}"><span class="ml-1 item-text">اضافة موظف جديد</span></a>
              </li>
            @endcan
          </ul>
        </li>
      @endcan
      @can('view_customers')
        <li class="nav-item mb-1 dropdown">
          <a href="#customers" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle nav-link">
            <img src="{{asset('assets/images/icons/customers.png')}}" loading="lazy" class="avatar-img" width="22px"  height="22px" alt="customers-image">
          <span class="ml-3 item-text">العملاء</span>
          </a>
          <ul class="collapse list-unstyled pl-4 w-100" id="customers">
            <li class="nav-item mb-1">
              <a class="nav-link pl-3" href="{{route('customers.index')}}"><span class="ml-1 item-text">ادارة العملاء</span>
              </a>
            </li>
            @can('add_customer')
              <li class="nav-item mb-1">
                <a class="nav-link pl-3" href="{{route('customers.create')}}"><span class="ml-1 item-text">اضافة عميل جديد</span></a>
              </li>
            @endcan
          </ul>
        </li>
      @endcan
      @can('view_suppliers')
        <li class="nav-item mb-1 dropdown">
          <a href="#suppliers" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle nav-link">
            <img src="{{asset('assets/images/icons/suppliers.png')}}" loading="lazy" class="avatar-img" width="22px"  height="22px" alt="suppliers-image">
            <span class="ml-3 item-text">الموردين</span>
          </a>
          <ul class="collapse list-unstyled pl-4 w-100" id="suppliers">
            <li class="nav-item mb-1">
              <a class="nav-link pl-3" href="{{route('suppliers.index')}}"><span class="ml-1 item-text">ادارة الموردين</span>
              </a>
            </li>
            @can('add_supplier')
              <li class="nav-item mb-1">
                <a class="nav-link pl-3" href="{{route('suppliers.create')}}"><span class="ml-1 item-text">اضافة مورد جديد</span></a>
              </li>
            @endcan
          </ul>
        </li>
      @endcan
      @can('view_products')
        <li class="nav-item mb-1 dropdown">
          <a href="#products" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle nav-link">
            <img src="{{asset('assets/images/icons/products.png')}}" loading="lazy" class="avatar-img" width="22px"  height="22px" alt="products-image">
            <span class="ml-3 item-text">المنتجات</span>
          </a>
          <ul class="collapse list-unstyled pl-4 w-100" id="products">
            <li class="nav-item mb-1">
              <a class="nav-link pl-3" href="{{route('products.index')}}"><span class="ml-1 item-text">ادارة المنتجات</span>
              </a>
            </li>
            @can('add_product')
              <li class="nav-item mb-1">
                <a class="nav-link pl-3" href="{{route('products.create')}}"><span class="ml-1 item-text">اضافة منتج جديد</span></a>
              </li>
            @endcan
          </ul>
        </li>
      @endcan
      @can('view_invoices')
        <li class="nav-item mb-1 dropdown">
          <a href="#invoices" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle nav-link">
            <img src="{{asset('assets/images/icons/invoice3.png')}}" loading="lazy" class="avatar-img" width="22px"  height="22px" alt="employees-image">
            <span class="ml-3 item-text">ادارة السندات</span>
          </a>
          <ul class="collapse list-unstyled pl-4 w-100" id="invoices">
            <li class="nav-item mb-1">
              <a class="nav-link pl-3" href="{{route('invoices.index')}}"><span class="ml-1 item-text">ادارة السندات</span>
              </a>
            </li>
            <li class="nav-item mb-1">
              @can('add_invoice')
                <a class="nav-link pl-3" href="{{route('invoices.create', ['type' => 'صرف'])}}"><span class="ml-1 item-text">اضافة سند صرف</span></a>
                <a class="nav-link pl-3" href="{{route('invoices.create', ['type' => 'قبض'])}}"><span class="ml-1 item-text">اضافة سند قبض</span></a>
              @endcan
            </li>
          </ul>
        </li>
      @endcan
      @can('view_selling_invoices')
        <li class="nav-item mb-1 dropdown">
          <a href="#selling_invoices" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle nav-link">
            <img src="{{asset('assets/images/icons/invoice1.png')}}" loading="lazy" class="avatar-img" width="22px"  height="22px" alt="employees-image">
            <span class="ml-3 item-text">ادارة المبيعات</span>
          </a>
          <ul class="collapse list-unstyled pl-4 w-100" id="selling_invoices">
            <li class="nav-item mb-1">
              <a class="nav-link pl-3" href="{{route('selling_invoices.index')}}"><span class="ml-1 item-text">ادارة المبيعات</span>
              </a>
            </li>
            <li class="nav-item mb-1">
              <a class="nav-link pl-3" href="{{route('selling_invoices.create')}}"><span class="ml-1 item-text">اضافة فاتوره بيع</span></a>
            </li>
          </ul>
        </li>
      @endcan
      @can('view_purchasing_invoices')
        <li class="nav-item mb-1 dropdown">
          <a href="#purchasing_invoices" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle nav-link">
            <img src="{{asset('assets/images/icons/invoice2.png')}}" loading="lazy" class="avatar-img" width="22px"  height="22px" alt="employees-image">
            <span class="ml-3 item-text">ادارة المشتريات</span>
          </a>
          <ul class="collapse list-unstyled pl-4 w-100" id="purchasing_invoices">
            <li class="nav-item mb-1">
              <a class="nav-link pl-3" href="{{route('purchasing_invoices.index')}}"><span class="ml-1 item-text">ادارة المشتريات</span>
              </a>
            </li>
            <li class="nav-item mb-1">
              <a class="nav-link pl-3" href="{{route('purchasing_invoices.create')}}"><span class="ml-1 item-text">اضافة فاتوره شراء</span></a>
            </li>
          </ul>
        </li>
      @endcan
      <li class="nav-item mb-1 dropdown">
        <a href="#accounts" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle nav-link">
          <img src="{{asset('assets/images/icons/account_statement.png')}}" loading="lazy" class="avatar-img" width="22px"  height="22px" alt="account-statement-image">
          <span class="ml-3 item-text">ادارة الحسابات</span>
        </a>
        <ul class="collapse list-unstyled pl-4 w-100" id="accounts">
            @can('safe')
              <li class="nav-item mb-1">
                <a class="nav-link pl-3" href="{{route('safe')}}"><span class="ml-1 item-text">ادارة الخزنة</span></a>
              </li>
            @endcan
            @can('view_account')
              <li class="nav-item mb-1 w-100">
                <a class="nav-link" href="{{route('dashboard')}}"><span class="ml-3 item-text">كشف حساب</span></a>
              </li>
            @endcan
          </ul>
        </li>
      @can('view_shifts')
        <li class="nav-item mb-1 w-100">
          <a class="nav-link" href="{{route('shifts.index')}}">
            <img src="{{asset('assets/images/icons/shifts.png')}}" loading="lazy" class="avatar-img" width="22px"  height="22px" alt="shifts-image">
            <span class="ml-3 item-text">ادارة الورديات</span>
          </a>
        </li>
      @endcan
      @can('view_warehouse')
        <li class="nav-item mb-1 w-100">
          <a class="nav-link" href="{{route('warehouse.index')}}">
            <img src="{{asset('assets/images/icons/warehouse.png')}}" loading="lazy" class="avatar-img" width="22px"  height="22px" alt="warehouse-image">
            <span class="ml-3 item-text">ادارة المخزن</span>
          </a>
        </li>
      @endcan
      @can('view_activities')
        <li class="nav-item mb-1 w-100">
          <a class="nav-link" href="{{route('activities.index')}}">
            <img src="{{asset('assets/images/icons/operations.png')}}" loading="lazy" class="avatar-img" width="22px"  height="22px" alt="operations-image">
            <span class="ml-3 item-text">العمليات</span>
          </a>
        </li>
      @endcan
      @can('view_settings')
        <li class="nav-item mb-1 w-100">
          <a class="nav-link" href="{{route('settings.index')}}">
            <img src="{{asset('assets/images/icons/settings.png')}}" loading="lazy" class="avatar-img" width="22px"  height="22px" alt="settings-image">
            <span class="ml-3 item-text">الاعدادات</span>
          </a>
        </li>
      @endcan
   </ul>
 </nav>
</aside>