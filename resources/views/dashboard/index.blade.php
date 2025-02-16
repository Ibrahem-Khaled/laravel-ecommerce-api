{{-- resources/views/dashboard/index.blade.php --}}
@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- Quick Stats -->
            <div class="col-md-3">
                <div class="card bg-primary text-white mb-4">
                    <div class="card-body">
                        <h5 class="card-title">المستخدمون</h5>
                        <h2>{{ $usersCount }}</h2>
                        <p class="card-text">+{{ $newUsers }} جديد هذا الأسبوع</p>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card bg-success text-white mb-4">
                    <div class="card-body">
                        <h5 class="card-title">الطلبات</h5>
                        <h2>{{ $ordersCount }}</h2>
                        <p class="card-text">{{ number_format($ordersRevenue, 2) }} ر.س إجمالي المبيعات</p>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card bg-warning text-white mb-4">
                    <div class="card-body">
                        <h5 class="card-title">المنتجات</h5>
                        <h2>{{ $productsCount }}</h2>
                        <p class="card-text">{{ $lowStockProducts }} منتج قليل الكمية</p>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card bg-danger text-white mb-4">
                    <div class="card-body">
                        <h5 class="card-title">الدردشة</h5>
                        <h2>{{ $unreadMessages }}</h2>
                        <p class="card-text">رسالة غير مقروءة</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Sales Chart -->
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5>تحليل المبيعات خلال الأسبوع</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="salesChart" height="100"></canvas>
                    </div>
                </div>
            </div>

            <!-- Recent Orders -->
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5>أحدث الطلبات</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-group">
                            @foreach ($recentOrders as $order)
                                <li class="list-group-item">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h6>#{{ $order->id }}</h6>
                                            <small>{{ $order->user->name }}</small>
                                        </div>
                                        <span
                                            class="badge 
                                    @if ($order->status == 'pending') badge-warning
                                    @elseif($order->status == 'processing') badge-info
                                    @elseif($order->status == 'delivered') badge-success
                                    @else badge-danger @endif">
                                            {{ $order->status }}
                                        </span>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Sales Chart
        const salesData = @json($salesData);
        const ctx = document.getElementById('salesChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: salesData.map(data => data.date),
                datasets: [{
                    label: 'إجمالي المبيعات',
                    data: salesData.map(data => data.total),
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 2
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return value + ' ر.س';
                            }
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.raw + ' ر.س';
                            }
                        }
                    }
                }
            }
        });
    </script>
@endpush
