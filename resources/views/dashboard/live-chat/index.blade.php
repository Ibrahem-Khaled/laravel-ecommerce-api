@extends('layouts.app')

@section('content')
    <div class="container">
        <h3 class="mb-4">اختر مستخدم للبدء بالدردشة</h3>
        <ul class="list-group">
            @foreach ($users as $user)
                <li class="list-group-item">
                    <a href="{{ route('dashboard.live-chat.user', $user->id) }}">{{ $user->name }}</a>
                    <i class="fas fa-user"></i>
                    <p class="badge badge-danger" >{{ $user->chats->where('status','unread')->count() }}</p>
                </li>
            @endforeach
        </ul>
    </div>
@endsection
