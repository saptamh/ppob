<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

<!-- Sidebar - Brand -->
<a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
  <div class="sidebar-brand-icon rotate-n-15">
    <i class="fas fa-laugh-wink"></i>
  </div>
  <div class="sidebar-brand-text mx-3">Reka Management</div>
</a>

<!-- Divider -->
<hr class="sidebar-divider my-0">

<!-- Nav Item - Dashboard -->
<li class="nav-item active">
  <a class="nav-link" href="{{ route('home') }}">
    <i class="fas fa-fw fa-tachometer-alt"></i>
    <span>Dashboard</span></a>
</li>

<!-- Divider -->
<hr class="sidebar-divider">
@if(Gate::check('user-list') || Gate::check('role-list'))
<li class="nav-item">
  <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseSeven" aria-expanded="true" aria-controls="collapseTwo">
    <i class="fas fa-fw fa-cog"></i>
    <span>User Management</span>
  </a>
  <div id="collapseSeven" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
    <div class="bg-white py-2 collapse-inner rounded">
      @can('user-list')<a class="collapse-item" href="{{ route('user.main') }}">User</a>@endcan
      @can('role-list')<a class="collapse-item" href="{{ route('role.main') }}">Role</a>@endcan
    </div>
  </div>
</li>

<!-- Divider -->
<hr class="sidebar-divider">
@endcan
@if(Gate::check('employee-list'))
<!-- Nav Item - Pages Collapse Menu -->
<li class="nav-item">
  <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseTwo">
    <i class="fas fa-fw fa-cog"></i>
    <span>Karyawan</span>
  </a>
  <div id="collapseOne" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
    <div class="bg-white py-2 collapse-inner rounded">
      @can('employee-list')<a class="collapse-item" href="{{ route('employee.main') }}">List</a>@endcan
    </div>
  </div>
</li>

<!-- Divider -->
<hr class="sidebar-divider">
@endif
@if(Gate::check('project-list'))
<!-- Nav Item - Pages Collapse Menu -->
<li class="nav-item">
  <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
    <i class="fas fa-fw fa-cog"></i>
    <span>Proyek</span>
  </a>
  <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
    <div class="bg-white py-2 collapse-inner rounded">
      @can('project-list')<a class="collapse-item" href="{{ route('project.main') }}">List</a>@endcan
      <a class="collapse-item" href="{{ route('project.main') }}">Project Estimation</a>
      <a class="collapse-item" href="{{ route('project.main') }}">Project Costing</a>
      <a class="collapse-item" href="{{ route('project.main') }}">Project Management</a>
      <a class="collapse-item" href="{{ route('project.main') }}">Purchase Request</a>
      <a class="collapse-item" href="{{ route('bill.main') }}">Bon Material</a>
      <a class="collapse-item" href="buttons.html">Payment Request</a>
      <a class="collapse-item" href="buttons.html">Invoice Equipment</a>
    </div>
  </div>
</li>

<!-- Divider -->
<hr class="sidebar-divider">
@endif
<!-- Nav Item - Pages Collapse Menu -->
<li class="nav-item">
  <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseThree" aria-expanded="true" aria-controls="collapseTwo">
    <i class="fas fa-fw fa-cog"></i>
    <span>Purchase</span>
  </a>
  <div id="collapseThree" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
    <div class="bg-white py-2 collapse-inner rounded">
      <a class="collapse-item" href="{{ route('purchase.main') }}">Master Supplier</a>
      <a class="collapse-item" href="{{ route('purchase.main') }}">List</a>
      <a class="collapse-item" href="buttons.html">Stok</a>
      <a class="collapse-item" href="{{ route('purchase.main') }}">Barang Rusak</a>
    </div>
  </div>
</li>

<!-- Divider -->
<hr class="sidebar-divider">
@if(Gate::check('purchase-list') || Gate::check('nonpurchase-list') || Gate::check('salaryPayment-list'))
<!-- Nav Item - Pages Collapse Menu -->
<li class="nav-item">
  <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseFour" aria-expanded="true" aria-controls="collapseTwo">
    <i class="fas fa-fw fa-cog"></i>
    <span>Payment Request</span>
  </a>
  <div id="collapseFour" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
    <div class="bg-white py-2 collapse-inner rounded">
      @can('purchase-list')<a class="collapse-item" href="{{ route('purchase.main') }}">Barang</a>@endcan
      @can('salaryPayment-list')<a class="collapse-item" href="{{ route('salary-payment.main') }}">Gaji</a>@endcan
      @can('nonpurchase-list')<a class="collapse-item" href="{{ route('nonpurchase.main') }}">Non Purchase</a>@endcan
    </div>
  </div>
</li>

<!-- Divider -->
<hr class="sidebar-divider">
@endif

@if(Gate::check('pettyCash-list') || Gate::check('payment-list'))
<!-- Nav Item - Pages Collapse Menu -->
<li class="nav-item">
  <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseSix" aria-expanded="true" aria-controls="collapseTwo">
    <i class="fas fa-fw fa-cog"></i>
    <span>Finance</span>
  </a>
  <div id="collapseSix" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
    <div class="bg-white py-2 collapse-inner rounded">
      @can('pettyCash-list')<a class="collapse-item" href="{{ route('petty-cash.main') }}">Cash Flow</a>@endcan
      @can('payment-list')<a class="collapse-item" href="{{ route('payment.main') }}">Payment</a>@endcan
      <a class="collapse-item" href="buttons.html">Invoice</a>
      <a class="collapse-item" href="buttons.html">Investor</a>
      <a class="collapse-item" href="buttons.html">Report</a>
    </div>
  </div>
</li>

<!-- Divider -->
<hr class="sidebar-divider">
@endif
<!-- Nav Item - Pages Collapse Menu -->
<li class="nav-item">
  <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseFive" aria-expanded="true" aria-controls="collapseTwo">
    <i class="fas fa-fw fa-cog"></i>
    <span>Lainnya</span>
  </a>
  <div id="collapseFive" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
    <div class="bg-white py-2 collapse-inner rounded">
      <a class="collapse-item" href="buttons.html">Upload Dokumen</a>
    </div>
  </div>
</li>


<!-- Divider -->
<hr class="sidebar-divider d-none d-md-block">

<!-- Sidebar Toggler (Sidebar) -->
<div class="text-center d-none d-md-inline">
  <button class="rounded-circle border-0" id="sidebarToggle"></button>
</div>

</ul>
<!-- End of Sidebar -->
