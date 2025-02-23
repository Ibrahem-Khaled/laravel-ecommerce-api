{{-- resources/views/dashboard/orders/index.blade.php --}}
@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <h1>الكوبونات</h1>
        <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#addCouponModal">
            إضافة كوبون
        </button>

        @include('components.alerts')

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>الكود</th>
                    <th>النوع</th>
                    <th>القيمة</th>
                    <th>مستخدم</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($coupons as $coupon)
                    <tr>
                        <td>{{ $coupon->code }}</td>
                        <td>{{ $coupon->type == 'percentage' ? 'نسبة مئوية' : 'ثابت' }}</td>
                        <td>{{ $coupon->value }}</td>
                        <td>{{ $coupon->is_used ? 'نعم' : 'لا' }}</td>
                        <td>
                            {{-- <button type="button" class="btn btn-warning btn-sm" data-toggle="modal"
                                data-target="#editCouponModal{{ $coupon->id }}">
                                تعديل
                            </button> --}}
                            <form action="{{ route('coupons.destroy', $coupon->id) }}" method="POST"
                                style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">حذف</button>
                            </form>
                        </td>
                    </tr>

                    <!-- نافذة التعديل -->
                    <div class="modal fade" id="editCouponModal{{ $coupon->id }}" tabindex="-1"
                        aria-labelledby="editCouponModalLabel{{ $coupon->id }}" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editCouponModalLabel{{ $coupon->id }}">تعديل الكوبون</h5>
                                    <button type="button" class="btn-close" data-dismiss="modal"
                                        aria-label="إغلاق"></button>
                                </div>
                                <form action="{{ route('coupons.update', $coupon->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label for="type" class="form-label">النوع</label>
                                            <select name="type" id="type" class="form-control">
                                                <option value="percentage"
                                                    {{ $coupon->type == 'percentage' ? 'selected' : '' }}>نسبة مئوية
                                                </option>
                                                <option value="fixed" {{ $coupon->type == 'fixed' ? 'selected' : '' }}>
                                                    ثابت</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="value" class="form-label">القيمة</label>
                                            <input type="number" name="value" id="value" class="form-control"
                                                value="{{ $coupon->value }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="is_used" class="form-label">مستخدم</label>
                                            <input type="checkbox" name="is_used" id="is_used"
                                                {{ $coupon->is_used ? 'checked' : '' }}>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-dismiss="modal">إغلاق</button>
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

    <!-- نافذة الإضافة -->
    <div class="modal fade" id="addCouponModal" tabindex="-1" aria-labelledby="addCouponModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCouponModalLabel">إضافة كوبون</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="إغلاق"></button>
                </div>
                <form action="{{ route('coupons.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="type" class="form-label">النوع</label>
                            <select name="type" id="type" class="form-control">
                                <option value="percentage">نسبة مئوية</option>
                                <option value="fixed">ثابت</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="value" class="form-label">القيمة</label>
                            <input type="number" name="value" id="value" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="quantity" class="form-label">الكمية</label>
                            <input type="number" name="quantity" id="quantity" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
                        <button type="submit" class="btn btn-primary">حفظ التغييرات</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
