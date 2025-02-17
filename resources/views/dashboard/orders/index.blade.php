{{-- resources/views/dashboard/orders/index.blade.php --}}
@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">قائمة الطلبات</h4>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>المستخدم</th>
                            <th>الهاتف</th>
                            <th>العمولة</th>
                            <th>طريقة الدفع</th>
                            <th>الحالة</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $order)
                            <tr>
                                <td>{{ $order->id }}</td>
                                <td>{{ $order->user->name }}</td>
                                <td>{{ $order->user->phone }}</td>
                                <td>{{ $order->fee }}</td>
                                <td>{{ $order->payment_method == 'cash_on_delivery' ? 'الدفع عند الاستلام' : 'دفع إلكتروني' }}
                                </td>
                                <td>
                                    <span
                                        class="badge 
                                @if ($order->status == 'pending') badge-warning
                                @elseif($order->status == 'processing') badge-info
                                @elseif($order->status == 'delivered') badge-success
                                @else badge-danger @endif">
                                        {{ $order->status }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('orders.show', $order->id) }}" class="btn btn-info btn-sm">
                                        التفاصيل
                                    </a>
                                    <button class="btn btn-primary btn-sm" data-toggle="modal"
                                        data-target="#statusModal{{ $order->id }}">
                                        تغيير الحالة
                                    </button>
                                </td>
                            </tr>

                            <!-- Status Change Modal -->
                            <div class="modal fade" id="statusModal{{ $order->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form action="{{ route('orders.update-status', $order->id) }}" method="POST">
                                            @csrf
                                            <div class="modal-header">
                                                <h5 class="modal-title">تغيير حالة الطلب #{{ $order->id }}</h5>
                                            </div>
                                            <div class="modal-body">
                                                <select name="status" class="form-control">
                                                    <option value="pending"
                                                        {{ $order->status == 'pending' ? 'selected' : '' }}>معلق</option>
                                                    <option value="processing"
                                                        {{ $order->status == 'processing' ? 'selected' : '' }}>جاري التجهيز
                                                    </option>
                                                    <option value="delivered"
                                                        {{ $order->status == 'delivered' ? 'selected' : '' }}>تم التسليم
                                                    </option>
                                                    <option value="cancelled"
                                                        {{ $order->status == 'cancelled' ? 'selected' : '' }}>ملغي</option>
                                                </select>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-dismiss="modal">إلغاء</button>
                                                <button type="submit" class="btn btn-primary">حفظ التغييرات</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
