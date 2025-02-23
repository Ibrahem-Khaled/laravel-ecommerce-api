@extends('layouts.app')
{{-- أو أي ملف Layout خاص بك --}}

@section('content')
    <div class="container">
        <h1>قائمة المتاجر</h1>

        {{-- رسالة نجاح إذا تمت العملية بنجاح --}}
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        {{-- جدول لعرض المتاجر --}}
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>المعرف</th>
                    <th>الاسم</th>
                    <th>الحالة</th>
                    <th>الصورة</th>
                    <th>الرمز</th>
                    <th>Navigation</th>
                    <th>التحكم</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($stores as $store)
                    <tr>
                        <td>{{ $store->id }}</td>
                        <td>{{ $store->name }}</td>
                        <td>{{ $store->status }}</td>
                        <td>
                            @if ($store->image)
                                <img src="{{ $store->image }}" alt="{{ $store->name }}" width="50" height="50">
                            @else
                                لا توجد صورة
                            @endif
                        </td>
                        <td>{{ $store->icon }}</td>
                        <td>{{ $store->navigation }}</td>
                        <td>
                            {{-- زر يفتح الـModal الخاص بالتعديل --}}
                            <button type="button" class="btn btn-sm btn-warning" data-toggle="modal"
                                data-target="#editModal{{ $store->id }}">
                                تعديل
                            </button>
                        </td>
                    </tr>

                    {{-- الـModal الخاص بتعديل بيانات المتجر --}}
                    <div class="modal fade" id="editModal{{ $store->id }}" tabindex="-1"
                        aria-labelledby="editModalLabel{{ $store->id }}" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editModalLabel{{ $store->id }}">
                                        تعديل المتجر ({{ $store->name }})
                                    </h5>
                                    <button type="button" class="btn-close" data-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form action="{{ route('select-stores.update', $store->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')

                                        {{-- اسم المتجر --}}
                                        <div class="mb-3">
                                            <label for="name" class="form-label">اسم المتجر</label>
                                            <input type="text" name="name" class="form-control"
                                                value="{{ old('name', $store->name) }}" required>
                                        </div>

                                        {{-- حالة المتجر --}}
                                        <div class="mb-3">
                                            <label for="status" class="form-label">الحالة</label>
                                            <select name="status" class="form-select">
                                                <option value="active" {{ $store->status == 'active' ? 'selected' : '' }}>
                                                    فعال
                                                </option>
                                                <option value="inactive"
                                                    {{ $store->status == 'inactive' ? 'selected' : '' }}>
                                                    غير فعال
                                                </option>
                                            </select>
                                        </div>

                                        {{-- رابط الصورة --}}
                                        <div class="mb-3">
                                            <label for="image" class="form-label">رابط الصورة</label>
                                            <input type="text" name="image" class="form-control"
                                                value="{{ old('image', $store->image) }}">
                                        </div>

                                        {{-- الرمز --}}
                                        <div class="mb-3">
                                            <label for="icon" class="form-label">الرمز</label>
                                            <input type="text" name="icon" class="form-control"
                                                value="{{ old('icon', $store->icon) }}">
                                        </div>

                                        {{-- Navigation --}}
                                        <div class="mb-3">
                                            <label for="navigation" class="form-label">Navigation</label>
                                            <input type="text" name="navigation" class="form-control"
                                                value="{{ old('navigation', $store->navigation) }}" required>
                                        </div>

                                        {{-- زر الحفظ --}}
                                        <button type="submit" class="btn btn-success">
                                            حفظ التعديلات
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- نهاية الـModal --}}
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
