<!-- Sidebar -->
      <ul
        class="navbar-nav bg-gradient-success sidebar sidebar-dark accordion"
        id="accordionSidebar"
      >
        <!-- Sidebar - Brand -->
        <a
          class="sidebar-brand d-flex align-items-center justify-content-center"
          href="index.php"
        >
          <div class="sidebar-brand-icon">
            <!-- <i class="fas fa-laugh-wink"></i> -->
            <!-- <i style="color: #fff; font-weight: 500; font-size: 17px">La Gloire Pharma</i> -->
          </div>
        </a>

        <!-- Divider -->
        <!-- <hr class="sidebar-divider my-0" /> -->

        <!-- Nav Item - Dashboard -->
        <li class="nav-item">
          <a class="nav-link <?= active_menu('index.php') ?>" href="index.php">
            <i class="fas fa-fw fa-tachometer-alt" style="font-size: 18px"></i>
            <span style="font-size: 17px; font-weight: 500; color: #fff;">Dashboard</span></a
          >
        </li>

        <!-- Divider -->
        <hr class="sidebar-divider" />

        <!-- Nav Item - Charts -->
        <?php if($_SESSION['user_type'] == 'admin'): ?>
        <li class="nav-item">
          <a class="nav-link <?= active_menu('users.php') ?>" href="users.php">
            <i class="fas fa-fw fa-chart-area fa-2x" style="font-size: 18px"></i>
            <span style="font-size: 17px; font-weight: 500; color: #fff;">Users</span></a
          >
          </li>

        <!-- Nav Item - Tables -->
        <li class="nav-item">
          <a class="nav-link <?= active_menu('stock.php') ?>" href="stock.php">
            <i class="fas fa-fw fa-table fa-2x" style="font-size: 18px"></i>
            <span style="font-size: 17px; font-weight: 500; color: #fff;">Stock</span></a
          >
        </li>
        <?php endif; ?>

        <!-- Nav Item - Tables -->
        <li class="nav-item">
          <a class="nav-link <?= active_menu('sales.php') ?>" href="sales.php">
            <i class="fas fa-dollar-sign fa-2x" style="font-size: 18px"></i>
            <span style="font-size: 17px; font-weight: 500; color: #fff;">Sales</span></a
          >
        </li>

        <!-- Divider -->
        <hr class="sidebar-divider d-none d-md-block" />

        <!-- Sidebar Toggler (Sidebar) -->
        <div class="text-center d-none d-md-inline">
          <button class="rounded-circle border-0" id="sidebarToggle"></button>
        </div>

      </ul>
      <!-- End of Sidebar -->