@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">قائمة المستخدمين</h4>
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addUserModal">
                            إضافة مستخدم جديد
                        </button>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>الاسم</th>
                                    <th>البريد الإلكتروني</th>
                                    <th>الهاتف</th>
                                    <th>الحالة</th>
                                    <th>الدور</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                    <tr>
                                        <td>{{ $user->id }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->phone }}</td>
                                        <td>{{ $user->status }}</td>
                                        <td>{{ $user->role }}</td>
                                        <td>
                                            <button type="button" class="btn btn-info btn-sm" data-toggle="modal"
                                                data-target="#editUserModal{{ $user->id }}">
                                                تعديل
                                            </button>
                                            <button type="button" class="btn btn-danger btn-sm" data-toggle="modal"
                                                data-target="#deleteUserModal{{ $user->id }}">
                                                حذف
                                            </button>
                                        </td>
                                    </tr>

                                    <!-- Edit User Modal -->
                                    <div class="modal fade" id="editUserModal{{ $user->id }}" tabindex="-1"
                                        role="dialog" aria-labelledby="editUserModalLabel{{ $user->id }}"
                                        aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="editUserModalLabel{{ $user->id }}">
                                                        تعديل المستخدم</h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <form action="{{ route('users.update', $user->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <label for="name">الاسم</label>
                                                            <input type="text" class="form-control" id="name"
                                                                name="name" value="{{ $user->name }}" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="email">البريد الإلكتروني</label>
                                                            <input type="email" class="form-control" id="email"
                                                                name="email" value="{{ $user->email }}">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="phone">الهاتف</label>
                                                            <input type="text" class="form-control" id="phone"
                                                                name="phone" value="{{ $user->phone }}" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="password">كلمة المرور</label>
                                                            <input type="password" class="form-control" id="password"
                                                                name="password">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="status">الحالة</label>
                                                            <select class="form-control" id="status" name="status">
                                                                <option value="active"
                                                                    {{ $user->status == 'active' ? 'selected' : '' }}>نشط
                                                                </option>
                                                                <option value="inactive"
                                                                    {{ $user->status == 'inactive' ? 'selected' : '' }}>غير
                                                                    نشط</option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="role">الدور</label>
                                                            <select class="form-control" id="role" name="role">
                                                                <option value="user"
                                                                    {{ $user->role == 'user' ? 'selected' : '' }}>مستخدم
                                                                </option>
                                                                <option value="admin"
                                                                    {{ $user->role == 'admin' ? 'selected' : '' }}>مدير
                                                                </option>
                                                                <option value="worker"
                                                                    {{ $user->role == 'worker' ? 'selected' : '' }}>عامل
                                                                </option>
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

                                    <!-- Delete User Modal -->
                                    <div class="modal fade" id="deleteUserModal{{ $user->id }}" tabindex="-1"
                                        role="dialog" aria-labelledby="deleteUserModalLabel{{ $user->id }}"
                                        aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="deleteUserModalLabel{{ $user->id }}">
                                                        حذف المستخدم</h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <form action="{{ route('users.destroy', $user->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <div class="modal-body">
                                                        <p>هل أنت متأكد من أنك تريد حذف هذا المستخدم؟</p>
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

    <!-- Add User Modal -->
    <div class="modal fade" id="addUserModal" tabindex="-1" role="dialog" aria-labelledby="addUserModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addUserModalLabel">إضافة مستخدم جديد</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('users.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">الاسم</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="email">البريد الإلكتروني</label>
                            <input type="email" class="form-control" id="email" name="email">
                        </div>
                        <div class="form-group">
                            <label for="phone">الهاتف</label>
                            <input type="text" class="form-control" id="phone" name="phone" required>
                        </div>
                        <div class="form-group">
                            <label for="password">كلمة المرور</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div> 
                        <div class="form-group">
                            <label for="status">الحالة</label>
                            <select class="form-control" id="status" name="status">
                                <option value="active">نشط</option>
                                <option value="inactive">غير نشط</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="role">الدور</label>
                            <select class="form-control" id="role" name="role">
                                <option value="user">مستخدم</option>
                                <option value="admin">مدير</option>
                                <option value="worker">عامل</option>
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
