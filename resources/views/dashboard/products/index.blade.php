@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">قائمة المنتجات</h4>
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addProductModal">
                            إضافة منتج جديد
                        </button>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>الاسم</th>
                                    <th>الوصف</th>
                                    <th>الصور</th>
                                    <th>السعر</th>
                                    <th>السعر بعد الخصم</th>
                                    <th>الكمية</th>
                                    <th>النوع</th>
                                    <th>الحالة</th>
                                    <th>التصنيف الفرعي</th>
                                    <th>المستخدم</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($products as $product)
                                    <tr>
                                        <td>{{ $product->id }}</td>
                                        <td>{{ $product->name }}</td>
                                        <td>{{ $product->description }}</td>
                                        <td>
                                            @if ($product->images)
                                                @foreach (json_decode($product->images) as $image)
                                                    <img src="{{ $image }}" alt="{{ $product->name }}"
                                                        style="width: 50px; height: 50px;">
                                                @endforeach
                                            @else
                                                لا يوجد صور
                                            @endif
                                        </td>
                                        <td>{{ $product->price }}</td>
                                        <td>{{ $product->price_after_discount }}</td>
                                        <td>{{ $product->quantity }}</td>
                                        <td>{{ $product->type }}</td>
                                        <td>{{ $product->status }}</td>
                                        <td>{{ $product->subCategory->name }}</td>
                                        <td>{{ $product->user->name }}</td>
                                        <td>
                                            <button type="button" class="btn btn-info btn-sm" data-toggle="modal"
                                                data-target="#editProductModal{{ $product->id }}">
                                                تعديل
                                            </button>
                                            <button type="button" class="btn btn-danger btn-sm" data-toggle="modal"
                                                data-target="#deleteProductModal{{ $product->id }}">
                                                حذف
                                            </button>
                                        </td>
                                    </tr>

                                    <!-- Edit Product Modal -->
                                    <div class="modal fade" id="editProductModal{{ $product->id }}" tabindex="-1"
                                        role="dialog" aria-labelledby="editProductModalLabel{{ $product->id }}"
                                        aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="editProductModalLabel{{ $product->id }}">
                                                        تعديل المنتج</h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <form action="{{ route('products.update', $product->id) }}" method="POST"
                                                    enctype="multipart/form-data">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <label for="name">الاسم</label>
                                                            <input type="text" class="form-control" id="name"
                                                                name="name" value="{{ $product->name }}" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="description">الوصف</label>
                                                            <textarea class="form-control" id="description" name="description">{{ $product->description }}</textarea>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="status">الحالة</label>
                                                            <select class="form-control" id="status" name="status">
                                                                <option value="active"
                                                                    {{ $product->status == 'active' ? 'selected' : '' }}>
                                                                    نشط</option>
                                                                <option value="inactive"
                                                                    {{ $product->status == 'inactive' ? 'selected' : '' }}>
                                                                    غير نشط</option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="images">الصور</label>
                                                            <input type="file" class="form-control" id="images"
                                                                name="images[]" multiple>
                                                            @if ($product->images)
                                                                @foreach (json_decode($product->images) as $image)
                                                                    <img src="{{ $image }}"
                                                                        alt="{{ $product->name }}"
                                                                        style="width: 50px; height: 50px;">
                                                                @endforeach
                                                            @endif
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="price">السعر</label>
                                                            <input type="number" class="form-control" id="price"
                                                                name="price" value="{{ $product->price }}" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="price_after_discount">السعر بعد الخصم</label>
                                                            <input type="number" class="form-control"
                                                                id="price_after_discount" name="price_after_discount"
                                                                value="{{ $product->price_after_discount }}">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="quantity">الكمية</label>
                                                            <input type="number" class="form-control" id="quantity"
                                                                name="quantity" value="{{ $product->quantity }}" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="type">النوع</label>
                                                            <select class="form-control" id="type" name="type">
                                                                <option value="basic"
                                                                    {{ $product->type == 'basic' ? 'selected' : '' }}>عادي
                                                                </option>
                                                                <option value="hot"
                                                                    {{ $product->type == 'hot' ? 'selected' : '' }}>مميز
                                                                </option>
                                                                <option value="new"
                                                                    {{ $product->type == 'new' ? 'selected' : '' }}>جديد
                                                                </option>
                                                                <option value="special"
                                                                    {{ $product->type == 'special' ? 'selected' : '' }}>خاص
                                                                </option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="sub_category_id">التصنيف الفرعي</label>
                                                            <select class="form-control" id="sub_category_id"
                                                                name="sub_category_id" required>
                                                                @foreach ($subCategories as $subCategory)
                                                                    <option value="{{ $subCategory->id }}"
                                                                        {{ $product->sub_category_id == $subCategory->id ? 'selected' : '' }}>
                                                                        {{ $subCategory->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="user_id">المستخدم</label>
                                                            <select class="form-control" id="user_id" name="user_id"
                                                                required>
                                                                @foreach ($users as $user)
                                                                    <option value="{{ $user->id }}"
                                                                        {{ $product->user_id == $user->id ? 'selected' : '' }}>
                                                                        {{ $user->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-dismiss="modal">إغلاق</button>
                                                        <button type="submit" class="btn btn-primary">حفظ
                                                            التغييرات</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Delete Product Modal -->
                                    <div class="modal fade" id="deleteProductModal{{ $product->id }}" tabindex="-1"
                                        role="dialog" aria-labelledby="deleteProductModalLabel{{ $product->id }}"
                                        aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title"
                                                        id="deleteProductModalLabel{{ $product->id }}">حذف المنتج</h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <form action="{{ route('products.destroy', $product->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <div class="modal-body">
                                                        <p>هل أنت متأكد من أنك تريد حذف هذا المنتج؟</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-dismiss="modal">إلغاء</button>
                                                        <button type="submit" class="btn btn-danger">حذف</button>
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
        </div>
    </div>

    <!-- Add Product Modal -->
    <div class="modal fade" id="addProductModal" tabindex="-1" role="dialog" aria-labelledby="addProductModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addProductModalLabel">إضافة منتج جديد</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">الاسم</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="description">الوصف</label>
                            <textarea class="form-control" id="description" name="description"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="status">الحالة</label>
                            <select class="form-control" id="status" name="status">
                                <option value="active">نشط</option>
                                <option value="inactive">غير نشط</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="images">الصور</label>
                            <input type="file" class="form-control" id="images" name="images[]" multiple>
                        </div>
                        <div class="form-group">
                            <label for="price">السعر</label>
                            <input type="number" class="form-control" id="price" name="price" required>
                        </div>
                        <div class="form-group">
                            <label for="price_after_discount">السعر بعد الخصم</label>
                            <input type="number" class="form-control" id="price_after_discount"
                                name="price_after_discount">
                        </div>
                        <div class="form-group">
                            <label for="quantity">الكمية</label>
                            <input type="number" class="form-control" id="quantity" name="quantity" required>
                        </div>
                        <div class="form-group">
                            <label for="type">النوع</label>
                            <select class="form-control" id="type" name="type">
                                <option value="basic">عادي</option>
                                <option value="hot">مميز</option>
                                <option value="new">جديد</option>
                                <option value="special">خاص</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="sub_category_id">التصنيف الفرعي</label>
                            <select class="form-control" id="sub_category_id" name="sub_category_id" required>
                                @foreach ($subCategories as $subCategory)
                                    <option value="{{ $subCategory->id }}">{{ $subCategory->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="user_id">المستخدم</label>
                            <select class="form-control" id="user_id" name="user_id" required>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
                        <button type="submit" class="btn btn-primary">حفظ</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
