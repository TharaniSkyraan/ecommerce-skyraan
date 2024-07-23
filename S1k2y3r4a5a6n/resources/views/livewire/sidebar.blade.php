
    <!-- sidebar -->
    <nav class="sidebar">
      <div class="menu_content">
        <ul class="menu_items">
          <div class="menu_title menu_dahsboard"></div>
          <!-- duplicate or remove this li tag if you want to add or remove navlink with submenu -->
          <!-- start -->
          @php
              $role = Auth::guard('admin')->user()->role;              
              $menus = Auth::guard('admin')->user()->privilegesData();
              $submenus = Auth::guard('admin')->user()->submenuprivilegesData();
          @endphp

          @foreach($menus as $menu)
            @php $key = $menu['key']; @endphp
            @switch($key)
                @case('admin-dashboard')
                    <li class="item">
                      <a href="{{ route('admin.dashboard') }}" class="nav_link submenu_item  {{ (request()->is('admin/dashboard*')) ? 'show_submenu active' : '' }}">
                        <span class="navlink_icon">
                          <i class="bx bx-grid-alt"></i>
                        </span>
                        <span class="navlink">Admin Dashboard</span>
                      </a>
                    </li>
                    @break
                @case('sub-admin')
                    <li class="item">
                      <a href="{{ route('admin.subadmin.index') }}" class="nav_link submenu_item  {{ (request()->is('admin/subadmin*')) ? 'show_submenu active' : '' }}">
                        <span class="navlink_icon">
                          <i class="bx bx-user"></i>
                        </span>
                        <span class="navlink">Sub Admin</span>
                      </a>
                    </li>
                    @break
                @case('ecommerce')
                  <li class="item">
                    <div href="javascript:void(0)" class="nav_link submenu_item  {{ ((request()->is('admin/category*')) ||  
                      (request()->is('admin/coupon*')) || (request()->is('admin/special-product*')) ||
                      (request()->is('admin/brand*')) || (request()->is('admin/collection*')) ||  (request()->is('admin/cacnel_reasons*')) ||
                      (request()->is('admin/banner*')) || (request()->is('admin/label*')) || (request()->is('admin/buying-option*')) || 
                      (request()->is('admin/attribute*')) || (request()->is('admin/product*'))) ? 'show_submenu active' : '' }}">
                      <span class="navlink_icon">
                        <i class="bx bx-shopping-bag"></i>
                      </span>
                      <span class="navlink">Ecommere</span>
                      <i class="bx bx-chevron-right arrow-left"></i>
                    </div>
                    <ul class="menu_items submenu">
                      @foreach($menu->sub_modules as $submenu)
                        @php $submenukey = $submenu->key; @endphp
                        @if(in_array($submenukey,$submenus))
                          @switch($submenukey)
                            @case('product-category')
                              <a href="{{ route('admin.category.index') }}" class="nav_link sublink {{ (request()->is('admin/category*')) ? 'active' : '' }}">Product Category</a>
                              @break
                            @case('product-brands')
                              <a href="{{ route('admin.brand.index') }}" class="nav_link sublink {{ (request()->is('admin/brand*')) ? 'active' : '' }}">Product Brands</a>
                              @break
                            @case('product-labels')
                              <a href="{{ route('admin.label.index') }}" class="nav_link sublink {{ (request()->is('admin/label*')) ? 'active' : '' }}">Product Labels</a>
                              @break
                            @case('product-attributes')
                              <a href="{{ route('admin.attribute.index') }}" class="nav_link sublink {{ (request()->is('admin/attribute*')) ? 'active' : '' }}">Product Attributes</a>
                              @break
                            @case('product-collections')
                              <a href="{{ route('admin.collection.index') }}" class="nav_link sublink {{ (request()->is('admin/collection*')) ? 'active' : '' }}">Product Collections</a>
                              @break
                            @case('product-banners')
                              <a href="{{ route('admin.banner.index') }}" class="nav_link sublink {{ (request()->is('admin/banner*')) ? 'active' : '' }}">Product Banners</a>
                              @break
                            @case('buying-options')
                              <a href="{{ route('admin.buying-option.index') }}" class="nav_link sublink {{ (request()->is('admin/buying-option*')) ? 'active' : '' }}">Buying Options</a>
                              @break
                            @case('coupons')
                              <a href="{{ route('admin.coupon.index') }}" class="nav_link sublink {{ (request()->is('admin/coupon*')) ? 'active' : '' }}">Coupon</a>
                              @break
                            @case('products')
                              <a href="{{ route('admin.product.index') }}" class="nav_link sublink {{ (request()->is('admin/product*')) ? 'active' : '' }}">Products</a>
                              @break
                            @case('special-products')
                              <a href="{{ route('admin.special-product') }}" class="nav_link sublink {{ (request()->is('admin/special-product')) ? 'active' : '' }}">Special Products</a>
                              @break
                            @case('cancel-reasons')
                              <a href="{{ route('admin.cancel_reasons.index') }}" class="nav_link sublink {{ (request()->is('admin/cancel_reasons*')) ? 'active' : '' }}">Cancel Reasons</a>
                              @break
                          @endswitch
                        @endif
                      @endforeach
                    </ul>
                  </li>
                  @break
                @case('orders')
                  <li class="item">
                    <div href="javascript:void(0)" class="nav_link submenu_item  {{ ((request()->is('admin/orders*')) || (request()->is('admin/shipments*')) || (request()->is('admin/returns*')) || (request()->is('admin/invoices*'))) ? 'show_submenu active' : '' }}">
                      <span class="navlink_icon">
                        <i class="bx bx-cart"></i>
                      </span>
                      <span class="navlink">Orders</span>
                      <i class="bx bx-chevron-right arrow-left"></i>
                    </div>
                    <ul class="menu_items submenu">                      
                      @foreach($menu->sub_modules as $submenu)
                          @php $submenukey = $submenu->key; @endphp
                          @if(in_array($submenukey,$submenus))
                          
                          @switch($submenukey)
                              @case('order')
                                <a href="{{ route('admin.orders.index') }}" class="nav_link sublink {{ (request()->is('admin/orders*')) ? 'active' : '' }}">Orders</a>
                              @break
                              @case('shipments')
                                <a href="{{ route('admin.shipments.index') }}" class="nav_link sublink {{ (request()->is('admin/shipments*')) ? 'active' : '' }}">Shipment</a>
                              @break
                              @case('invoices')
                                <a href="{{ route('admin.invoices.index') }}" class="nav_link sublink {{ (request()->is('admin/invoices')) ? 'active' : '' }}">Invoice</a>
                              @break
                          @endswitch
                          @endif
                      @endforeach
                      <!-- <a href="{{ route('admin.returns') }}" class="nav_link sublink {{ (request()->is('admin/returns')) ? 'active' : '' }}">Order Returns</a> -->
                    </ul>
                  </li>
                  @break
                @case('stock-maintenance')
                  <ul class="menu_items">
                    <div class="menu_title menu_stock"></div>                    
                        @foreach($menu->sub_modules as $submenu)
                            @php $submenukey = $submenu->key; @endphp
                            @if(in_array($submenukey,$submenus))                            
                              @switch($submenukey)
                                @case('manage-stock')
                                  <li class="item">
                                    <a href="{{ route('admin.manage-stock.index') }}" class="nav_link submenu_item  {{ (request()->is('admin/manage-stock*')) ? 'show_submenu active' : '' }}">
                                      <span class="navlink_icon">
                                        <i class="bx bx-cube-alt"></i>
                                      </span>
                                      <span class="navlink">Manage Stock</span>
                                    </a>
                                  </li>
                                  @break
                                @case('stock-history')
                                  <li class="item">
                                    <a href="{{ route('admin.stock-history.index') }}" class="nav_link submenu_item  {{ (request()->is('admin/stock-history*')) ? 'show_submenu active' : '' }}">
                                      <span class="navlink_icon">
                                        <i class="bx bx-data"></i>
                                      </span>
                                      <span class="navlink">Stock History</span>
                                    </a>
                                  </li>
                                  @break
                                @case('warehouse')
                                  <li class="item">
                                    <a href="{{ route('admin.warehouses.index') }}" class="nav_link submenu_item  {{ (request()->is('admin/warehouse*')) ? 'show_submenu active' : '' }}">
                                      <span class="navlink_icon">
                                        <i class="bx bx-building-house"></i>
                                      </span>
                                      <span class="navlink">Warehouse</span>
                                    </a>
                                  </li>
                                  @break
                                @case('zones')
                                  <li class="item">
                                    <a href="{{ route('admin.zones.index') }}" class="nav_link submenu_item  {{ (request()->is('admin/zones*')) ? 'show_submenu active' : '' }}">
                                      <span class="navlink_icon">
                                        <i class="bx bx-map"></i>
                                      </span>
                                      <span class="navlink">Delivery Zones</span>
                                    </a>
                                  </li>
                                  @break
                              @endswitch
                            @endif
                        @endforeach
                  </ul>
                  @break
                @case('sales')
                  <ul class="menu_items">
                    <div class="menu_title menu_sales"></div>                    
                        @foreach($menu->sub_modules as $submenu)
                            @php $submenukey = $submenu->key; @endphp
                            @if(in_array($submenukey,$submenus))                            
                              @switch($submenukey)
                                @case('sales-dashboard')
                                  <li class="item">
                                    <a href="{{ route('admin.sales-dashboard.index') }}" class="nav_link submenu_item  {{ (request()->is('admin/sales-dashboard*')) ? 'show_submenu active' : '' }}">
                                      <span class="navlink_icon">
                                        <i class="bx bx-grid-alt"></i>
                                      </span>
                                      <span class="navlink">Sales Dashboard</span>
                                    </a>
                                  </li>
                                  @break
                                @case('sales-report')
                                  <li class="item">
                                    <a href="{{ route('admin.sales-report.index') }}" class="nav_link submenu_item  {{ (request()->is('admin/sales-report*')) ? 'show_submenu active' : '' }}">
                                      <span class="navlink_icon">
                                        <i class="bx bxs-report"></i>
                                      </span>
                                      <span class="navlink">Sales Report</span>
                                    </a>
                                  </li>
                                  @break
                              @endswitch
                            @endif
                        @endforeach
                  </ul>
                  @break
                @case('settings')
                  <ul class="menu_items">
                    <div class="menu_title menu_setting"></div>
                    <li class="item">
                      <div href="javascript:void(0)" class="nav_link submenu_item {{ ((request()->is('admin/tax*'))||(request()->is('admin/pages*'))||(request()->is('admin/settings'))) ? 'show_submenu active' : '' }}">
                        <span class="navlink_icon">
                          <i class="bx bx-cog"></i>
                        </span>
                        <span class="navlink">Settings</span>
                        <i class="bx bx-chevron-right arrow-left"></i>
                      </div>
                      <ul class="menu_items submenu">
                        @foreach($menu->sub_modules as $submenu)
                            @php $submenukey = $submenu->key; @endphp
                            @if(in_array($submenukey,$submenus))                            
                              @switch($submenukey)
                                @case('taxes')
                                  <a href="{{ route('admin.tax.index') }}" class="nav_link sublink {{ (request()->is('admin/tax*')) ? 'active' : '' }}">Taxes</a>
                                  @break
                                @case('setting')
                                  <a href="{{ route('admin.settings') }}" class="nav_link sublink {{ (request()->is('admin/settings')) ? 'active' : '' }}">Settings</a>
                                  @break
                                @case('pages')
                                  <a href="{{ route('admin.pages.index') }}" class="nav_link sublink {{ (request()->is('admin/pages*')) ? 'active' : '' }}">Pages</a>
                                  @break
                              @endswitch
                            @endif
                        @endforeach
                      </ul>
                    </li>
                  </ul>
                  @break
            @endswitch




          @endforeach
          <!-- end -->
        </ul>


        <!-- Sidebar Open / Close -->
        <div class="bottom_content">
          <div class="bottom expand_sidebar">
            <span> Expand</span>
            <i class='bx bx-log-in' ></i>
          </div>
          <div class="bottom collapse_sidebar">
            <span> Collapse</span>
            <i class='bx bx-log-out'></i>
          </div>
        </div>
      </div>
    </nav>