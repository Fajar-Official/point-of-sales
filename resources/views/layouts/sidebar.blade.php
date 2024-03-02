<nav class="mt-2">
    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <!-- Add icons to the links using the Font Awesome class names -->
        <li class="nav-header nav-item">
            <a href="{{ route('dashboard') }}" class="nav-link">
                <i class="nav-icon fas fa-tachometer-alt"></i>
                <p>
                    Dashboard
                </p>
            </a>
        </li>

        <li class="nav-header">Master</li>
        <li class="nav-item">
            <a href="{{ route('categories.index') }}" class="nav-link">
                <i class="nav-icon fas fa-calendar-alt"></i>
                <p>
                    Kategori
                </p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('products.index') }}" class="nav-link">
                <i class="nav-icon fas fa-image"></i>
                <p>
                    Product
                </p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('members.index') }}" class="nav-link">
                <i class="nav-icon fas fa-users"></i>
                <p>
                    Member
                </p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('suppliers.index') }}" class="nav-link">
                <i class="nav-icon fas fa-truck"></i>
                <p>
                    Supplier
                </p>
            </a>
        </li>

        <li class="nav-header">Transaksi</li>
        <li class="nav-item">
            <a href="{{ route('expenses.index') }}" class="nav-link">
                <i class="nav-icon fas fa-money-bill-alt"></i>
                <p>
                    Pengeluaran
                </p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('purchases.index') }}" class="nav-link">
                <i class="nav-icon fas fa-shopping-cart"></i>
                <p>
                    Pembelian
                </p>
            </a>
        </li>
        <li class="nav-item">
            <a href="pages/kanban.html" class="nav-link">
                <i class="nav-icon fas fa-chart-line"></i>
                <p>
                    Penjualan
                </p>
            </a>
        </li>
        <li class="nav-item">
            <a href="pages/kanban.html" class="nav-link">
                <i class="nav-icon fas fa-clock"></i>
                <p>
                    Transaksi Lama
                </p>
            </a>
        </li>
        <li class="nav-item">
            <a href="pages/kanban.html" class="nav-link">
                <i class="nav-icon fas fa-plus"></i>
                <p>
                    Transaksi Baru
                </p>
            </a>
        </li>

        <li class="nav-header">Report</li>
        <li class="nav-item">
            <a href="pages/calendar.html" class="nav-link">
                <i class="nav-icon far fa-file-alt"></i>
                <p>
                    Laporan
                </p>
            </a>
        </li>

        <li class="nav-header">Sistem</li>
        <li class="nav-item">
            <a href="pages/calendar.html" class="nav-link">
                <i class="nav-icon fas fa-user"></i>
                <p>
                    User
                </p>
            </a>
        </li>
        <li class="nav-item">
            <a href="pages/gallery.html" class="nav-link">
                <i class="nav-icon fas fa-cogs"></i>
                <p>
                    Pengaturan
                </p>
            </a>
        </li>
    </ul>
</nav>
<!-- /.sidebar-menu -->
