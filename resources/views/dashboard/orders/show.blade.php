@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h4>تفاصيل الطلب #{{ $order->id }}</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h5>معلومات العميل:</h5>
                        <p>الاسم: {{ $order->user->name }}</p>
                        <p>العنوان: {{ $order->address }}</p>
                        <p>الهاتف: {{ $order->phone }}</p>
                        <p>طريقة الدفع:
                            {{ $order->payment_method == 'cash_on_delivery' ? 'الدفع عند الاستلام' : 'دفع إلكتروني' }}</p>
                    </div>
                    <div class="col-md-6">
                        <h5>حالة الطلب:</h5>
                        <form action="{{ route('orders.update-status', $order->id) }}" method="POST" class="form-inline">
                            @csrf
                            <select name="status" class="form-control mb-2 mr-2">
                                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>معلق</option>
                                <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>جاري
                                    التجهيز</option>
                                <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>تم التسليم
                                </option>
                                <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>ملغي
                                </option>
                            </select>
                            <button type="submit" class="btn btn-primary mb-2">تحديث الحالة</button>
                        </form>
                    </div>
                </div>

                <hr>

                <h5>المنتجات:</h5>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>المنتج</th>
                            <th>الكمية</th>
                            <th>السعر</th>
                            <th>المجموع</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($order->items as $item)
                            <tr>
                                <td>{{ $item->product->name }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>{{ number_format($item->price, 2) }} ر.س</td>
                                <td>{{ number_format($item->quantity * $item->price, 2) }} ر.س</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="3">الإجمالي:</th>
                            <th>{{ number_format(
                                $order->items->sum(function ($item) {
                                    return $item->quantity * $item->price;
                                }),
                                2,
                            ) }}
                                ر.س</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
@endsection
