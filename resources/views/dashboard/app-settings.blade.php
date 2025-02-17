@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>اعدادات التطبيق</h1>
        <!-- Button trigger modal for Create -->
        @if ($settings->count() == 0)
            <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#createModal">
                اضافة اعداد
            </button>
        @endif

        @include('components.alerts')

        <table class="table">
            <thead>
                <tr>
                    <th>اسم التطبيق</th>
                    <th>اللوجو</th>
                    <th>الفافون</th>
                    <th>العنوان</th>
                    <th>الهاتف</th>
                    <th>البريد الإلكتروني</th>
                    <th>العمولة</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($settings as $setting)
                    <tr>
                        <td>{{ $setting->name }}</td>
                        <td>{{ $setting->logo }}</td>
                        <td>{{ $setting->favicon }}</td>
                        <td>{{ $setting->address }}</td>
                        <td>{{ $setting->phone }}</td>
                        <td>{{ $setting->email }}</td>
                        <td>{{ $setting->commission }}</td>
                        <td>
                            <!-- Button trigger modal for Edit -->
                            <button type="button" class="btn btn-warning" data-toggle="modal"
                                data-target="#editModal{{ $setting->id }}">
                                Edit
                            </button>
                            <!-- Delete Form -->
                            {{-- <form action="{{ route('app-settings.destroy', $setting->id) }}" method="POST"
                                style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger"
                                    onclick="return confirm('Are you sure you want to delete this item?');">Delete</button>
                            </form> --}}
                        </td>
                    </tr>

                    <!-- Edit Modal for each item -->
                    <div class="modal fade" id="editModal{{ $setting->id }}" tabindex="-1" role="dialog"
                        aria-labelledby="editModalLabel{{ $setting->id }}" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editModalLabel{{ $setting->id }}">Edit App Setting</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form action="{{ route('app-settings.update', $setting->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="form-group">
                                            <label for="name">Name</label>
                                            <input type="text" name="name" class="form-control"
                                                value="{{ $setting->name }}" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="logo">Logo</label>
                                            <input type="text" name="logo" class="form-control"
                                                value="{{ $setting->logo }}">
                                        </div>
                                        <div class="form-group">
                                            <label for="favicon">Favicon</label>
                                            <input type="text" name="favicon" class="form-control"
                                                value="{{ $setting->favicon }}">
                                        </div>
                                        <div class="form-group">
                                            <label for="address">Address</label>
                                            <input type="text" name="address" class="form-control"
                                                value="{{ $setting->address }}">
                                        </div>
                                        <div class="form-group">
                                            <label for="phone">Phone</label>
                                            <input type="text" name="phone" class="form-control"
                                                value="{{ $setting->phone }}">
                                        </div>
                                        <div class="form-group">
                                            <label for="email">Email</label>
                                            <input type="email" name="email" class="form-control"
                                                value="{{ $setting->email }}">
                                        </div>
                                        <div class="form-group">
                                            <label for="commission">Commission</label>
                                            <input type="number" step="0.01" name="commission" class="form-control"
                                                value="{{ $setting->commission }}">
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary">Save changes</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Create Modal -->
    <div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createModalLabel">Create New App Setting</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('app-settings.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="logo">Logo</label>
                            <input type="text" name="logo" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="favicon">Favicon</label>
                            <input type="text" name="favicon" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="address">Address</label>
                            <input type="text" name="address" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="phone">Phone</label>
                            <input type="text" name="phone" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" name="email" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="commission">Commission</label>
                            <input type="number" step="0.01" name="commission" class="form-control">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Create</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
