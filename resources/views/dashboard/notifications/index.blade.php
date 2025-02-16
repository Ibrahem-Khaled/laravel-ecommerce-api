@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">قائمة الإشعارات</h4>
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addNotificationModal">
                            إضافة إشعار جديد
                        </button>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>العنوان</th>
                                    <th>الرسالة</th>
                                    <th>الصورة</th>
                                    <th>المستخدم</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($notifications as $notification)
                                    <tr>
                                        <td>{{ $notification->id }}</td>
                                        <td>{{ $notification->title }}</td>
                                        <td>{{ $notification->message }}</td>
                                        <td>
                                            @if ($notification->image)
                                                <img src="{{ $notification->image }}" alt="{{ $notification->title }}"
                                                    style="width: 50px; height: 50px;">
                                            @else
                                                لا يوجد صورة
                                            @endif
                                        </td>
                                        <td>{{ $notification->user ? $notification->user->name : 'عام' }}</td>
                                        <td>
                                            <button type="button" class="btn btn-info btn-sm" data-toggle="modal"
                                                data-target="#editNotificationModal{{ $notification->id }}">
                                                تعديل
                                            </button>
                                            <button type="button" class="btn btn-danger btn-sm" data-toggle="modal"
                                                data-target="#deleteNotificationModal{{ $notification->id }}">
                                                حذف
                                            </button>
                                        </td>
                                    </tr>

                                    <!-- Edit Notification Modal -->
                                    <div class="modal fade" id="editNotificationModal{{ $notification->id }}" tabindex="-1"
                                        role="dialog" aria-labelledby="editNotificationModalLabel{{ $notification->id }}"
                                        aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title"
                                                        id="editNotificationModalLabel{{ $notification->id }}">تعديل
                                                        الإشعار</h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <form action="{{ route('notifications.update', $notification->id) }}"
                                                    method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <label for="title">العنوان</label>
                                                            <input type="text" class="form-control" id="title"
                                                                name="title" value="{{ $notification->title }}" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="message">الرسالة</label>
                                                            <textarea class="form-control" id="message" name="message">{{ $notification->message }}</textarea>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="image">الصورة</label>
                                                            <input type="file" class="form-control" id="image"
                                                                name="image">
                                                            @if ($notification->image)
                                                                <img src="{{ $notification->image }}"
                                                                    alt="{{ $notification->title }}"
                                                                    style="width: 50px; height: 50px;">
                                                            @endif
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="user_id">المستخدم</label>
                                                            <select class="form-control" id="user_id" name="user_id">
                                                                <option value="">عام</option>
                                                                @foreach ($users as $user)
                                                                    <option value="{{ $user->id }}"
                                                                        {{ $notification->user_id == $user->id ? 'selected' : '' }}>
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

                                    <!-- Delete Notification Modal -->
                                    <div class="modal fade" id="deleteNotificationModal{{ $notification->id }}"
                                        tabindex="-1" role="dialog"
                                        aria-labelledby="deleteNotificationModalLabel{{ $notification->id }}"
                                        aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title"
                                                        id="deleteNotificationModalLabel{{ $notification->id }}">حذف
                                                        الإشعار</h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <form action="{{ route('notifications.destroy', $notification->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <div class="modal-body">
                                                        <p>هل أنت متأكد من أنك تريد حذف هذا الإشعار؟</p>
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

    <!-- Add Notification Modal -->
    <div class="modal fade" id="addNotificationModal" tabindex="-1" role="dialog"
        aria-labelledby="addNotificationModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addNotificationModalLabel">إضافة إشعار جديد</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('notifications.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="title">العنوان</label>
                            <input type="text" class="form-control" id="title" name="title" required>
                        </div>
                        <div class="form-group">
                            <label for="message">الرسالة</label>
                            <textarea class="form-control" id="message" name="message"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="image">الصورة</label>
                            <input type="file" class="form-control" id="image" name="image">
                        </div>
                        <div class="form-group">
                            <label for="user_id">المستخدم</label>
                            <select class="form-control" id="user_id" name="user_id">
                                <option value="">عام</option>
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
