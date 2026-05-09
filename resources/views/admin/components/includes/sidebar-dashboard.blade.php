@php
    $listMenu = [
        [
            'id' => 'dashboard',
            'idSidebar' => 'Dashboard',
            'label' => 'Dashboard',
            'icon' => 'ri-dashboard-2-line',
            'route' => null,
            'active' => null,
            'permissions' => null,
            'children' => [
                [
                    'id' => 'dashboardpembelian',
                    'label' => 'Pembelian',
                    'icon' => null,
                    'route' => route('admin.dashboard.pembelian'),
                    'active' => 'admin.dashboard.pembelian',
                    'permissions' => ['dashboard.pembelian.pembelian-produk', 'dashboard.pembelian.jumlah-transaksi'],
                    'children' => null,
                ],
                [
                    'id' => 'dashboardpenjualan',
                    'label' => 'Penjualan',
                    'icon' => null,
                    'route' => route('admin.dashboard.penjualan'),
                    'active' => 'admin.dashboard.penjualan',
                    'permissions' => [
                        'dashboard.penjualan.penjualan-produk',
                        'dashboard.penjualan.penjualan-produk-per-jenis-transaksi',
                        'dashboard.penjualan.jumlah-transaksi',
                    ],
                    'children' => null,
                ],
                [
                    'id' => 'dashboardpersediaan',
                    'label' => 'Persediaan',
                    'icon' => null,
                    'route' => route('admin.dashboard.persediaan'),
                    'active' => 'admin.dashboard.persediaan',
                    'permissions' => [
                        'dashboard.persediaan.penyesuaian-persediaan',
                        'dashboard.persediaan.total-nilai-persediaan',
                    ],
                    'children' => null,
                ],
                [
                    'id' => 'dashboardkeuangan',
                    'label' => 'Keuangan',
                    'icon' => null,
                    'route' => route('admin.dashboard.keuangan'),
                    'active' => 'admin.dashboard.keuangan',
                    'permissions' => [
                        'dashboard.keuangan.jumlah-transaksi',
                        'dashboard.keuangan.saldo-kas',
                        'dashboard.keuangan.kas-keluar',
                        'dashboard.keuangan.kas-masuk',
                    ],
                    'children' => null,
                ],
            ],
        ],
    ];
@endphp

<x-admin::utils.menu :list-menu="$listMenu" />
