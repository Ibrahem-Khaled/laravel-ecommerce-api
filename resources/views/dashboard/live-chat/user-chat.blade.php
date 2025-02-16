@extends('layouts.app')
<style>
    /* تنسيق رسائل الأدمن */
    .admin-message {
        background-color: #007bff;
        color: white;
        margin-left: auto;
        border-bottom-right-radius: 0 !important;
    }

    /* تنسيق رسائل المستخدم */
    .user-message {
        background-color: #e9ecef;
        color: black;
        margin-right: auto;
        border-bottom-left-radius: 0 !important;
    }

    /* تحسين شكل صندوق الدردشة */
    #chat-box {
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    /* تحسين شكل زر الإرسال */
    .btn-success {
        border-radius: 0 5px 5px 0;
    }

    /* تحسين شكل حقل الإدخال */
    .form-control {
        border-radius: 5px 0 0 5px;
    }
</style>
@section('content')
    <div class="container">
        <h3 class="text-center mb-4">دردشة مع {{ $selectedUser->name }}</h3>
        <div id="chat-box"
            style="height: 500px; overflow-y: scroll; border: 1px solid #ddd; border-radius: 10px; padding: 15px; margin-bottom: 20px; background-color: #f9f9f9;">
            @foreach ($messages as $message)
                <div class="mb-3">
                    <div class="@if ($message->admin_id) admin-message @else user-message @endif p-3 rounded"
                        style="max-width: 70%; word-wrap: break-word;">
                        <strong>{{ $message->user->name ?? 'المستخدم' }}:</strong>
                        <br>
                        <span>{{ $message->message }}</span>
                        <br>
                        <small class="text-dark float-right">{{ $message->created_at->format('H:i:s') }}</small>
                    </div>
                </div>
            @endforeach
        </div>
        <form action="{{ route('dashboard.live-chat.send', $selectedUser->id) }}" method="POST">
            @csrf
            <div class="input-group">
                <input type="text" name="message" id="message" class="form-control" placeholder="أدخل رسالتك..."
                    autocomplete="off">
                <div class="input-group-append">
                    <button type="submit" class="btn btn-success">إرسال</button>
                </div>
            </div>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // دالة لجلب الرسائل الخاصة بالمستخدم المحدد وتحديث صندوق الدردشة
            function fetchMessages() {
                $.ajax({
                    url: "{{ route('dashboard.live-chat.fetch.user', $selectedUser->id) }}",
                    method: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        let chatBox = $('#chat-box');
                        chatBox.empty();
                        $.each(data, function(index, message) {
                            let userName = message.user ? message.user.name : 'المستخدم';
                            let messageClass = message.admin_id ? 'admin-message' :
                                'user-message';
                            chatBox.append(
                                '<div class="mb-3">' +
                                '<div class="' + messageClass +
                                ' p-3 rounded" style="max-width: 70%; word-wrap: break-word;">' +
                                '<strong>' + userName + ':</strong> ' +
                                '<br>' +
                                '<span>' + message.message + '</span>' +
                                '<br>' +
                                '<small class="text-dark float-right">' + new Date(message
                                    .created_at).toLocaleTimeString() + '</small>' +
                                '</div>' +
                                '</div>'
                            );
                        });
                        chatBox.scrollTop(chatBox[0].scrollHeight);
                    },
                    error: function() {
                        console.log('حدث خطأ أثناء جلب الرسائل.');
                    }
                });
            }

            // استدعاء دالة جلب الرسائل كل 5 ثواني
            setInterval(fetchMessages, 5000);

            // جلب الرسائل عند تحميل الصفحة
            fetchMessages();
        });
    </script>
@endsection
