@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">قائمة الفئات</h4>
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addCategoryModal">
                            إضافة فئة جديدة
                        </button>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>الاسم</th>
                                    <th>الوصف</th>
                                    <th>الحالة</th>
                                    <th>الصورة</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($categories as $category)
                                    <tr>
                                        <td>{{ $category->id }}</td>
                                        <td>{{ $category->name }}</td>
                                        <td>{{ $category->description }}</td>
                                        <td>{{ $category->status }}</td>
                                        <td>
                                            @if ($category->image)
                                                <img src="{{ $category->image }}" alt="{{ $category->name }}"
                                                    style="width: 50px; height: 50px;">
                                            @else
                                                لا يوجد صورة
                                            @endif
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-info btn-sm" data-toggle="modal"
                                                data-target="#editCategoryModal{{ $category->id }}">
                                                تعديل
                                            </button>
                                            <button type="button" class="btn btn-danger btn-sm" data-toggle="modal"
                                                data-target="#deleteCategoryModal{{ $category->id }}">
                                                حذف
                                            </button>
                                        </td>
                                    </tr>

                                    <!-- Edit Category Modal -->
                                    <div class="modal fade" id="editCategoryModal{{ $category->id }}" tabindex="-1"
                                        role="dialog" aria-labelledby="editCategoryModalLabel{{ $category->id }}"
                                        aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="editCategoryModalLabel{{ $category->id }}">
                                                        تعديل الفئة</h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <form action="{{ route('categories.update', $category->id) }}"
                                                    method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <label for="name">الاسم</label>
                                                            <input type="text" class="form-control" id="name"
                                                                name="name" value="{{ $category->name }}" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="description">الوصف</label>
                                                            <textarea class="form-control" id="description" name="description">{{ $category->description }}</textarea>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="status">الحالة</label>
                                                            <select class="form-control" id="status" name="status">
                                                                <option value="active"
                                                                    {{ $category->status == 'active' ? 'selected' : '' }}>
                                                                    نشط</option>
                                                                <option value="inactive"
                                                                    {{ $category->status == 'inactive' ? 'selected' : '' }}>
                                                                    غير نشط</option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="image">الصورة</label>
                                                            <input type="file" class="form-control" id="image"
                                                                name="image">
                                                            @if ($category->image)
                                                                <img src="{{ $category->image }}"
                                                                    alt="{{ $category->name }}"
                                                                    style="width: 50px; height: 50px;">
                                                            @endif
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

                                    <!-- Delete Category Modal -->
                                    <div class="modal fade" id="deleteCategoryModal{{ $category->id }}" tabindex="-1"
                                        role="dialog" aria-labelledby="deleteCategoryModalLabel{{ $category->id }}"
                                        aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title"
                                                        id="deleteCategoryModalLabel{{ $category->id }}">حذف الفئة</h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <form action="{{ route('categories.destroy', $category->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <div class="modal-body">
                                                        <p>هل أنت متأكد من أنك تريد حذف هذه الفئة؟</p>
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

    <!-- Add Category Modal -->
    <div class="modal fade" id="addCategoryModal" tabindex="-1" role="dialog" aria-labelledby="addCategoryModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCategoryModalLabel">إضافة فئة جديدة</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('categories.store') }}" method="POST" enctype="multipart/form-data">
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
                            <label for="image">الصورة</label>
                            <input type="file" class="form-control" id="image" name="image">
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
