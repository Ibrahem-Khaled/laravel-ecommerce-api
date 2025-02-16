{{-- resources/views/dashboard/live-chat/index.blade.php --}}
@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h4>الدردشة الحية</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="list-group">
                            @foreach ($users as $user)
                                <a href="#" class="list-group-item list-group-item-action user-chat"
                                    data-user-id="{{ $user->id }}">
                                    {{ $user->name }}
                                    <span class="badge badge-primary float-right unread-count"
                                        id="unread-{{ $user->id }}">0</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="chat-box" id="chat-box">
                            <!-- Messages will be loaded here via AJAX -->
                        </div>
                        <form id="chat-form">
                            @csrf
                            <input type="hidden" name="user_id" id="chat-user-id">
                            <div class="input-group">
                                <input type="text" name="message" class="form-control" placeholder="اكتب رسالتك..."
                                    required>
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-primary">إرسال</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            let currentUserId = null;

            // Load chat messages
            function loadChat(userId) {
                $.get(`/dashboard/live-chat/${userId}/messages`, function(data) {
                    $('#chat-box').html(data);
                    $('#chat-box').scrollTop($('#chat-box')[0].scrollHeight);
                    $('#chat-user-id').val(userId);
                    currentUserId = userId;
                    updateUnreadCount(userId);
                });
            }

            // Update unread count
            function updateUnreadCount(userId) {
                $.get(`/dashboard/live-chat/${userId}/unread-count`, function(data) {
                    $(`#unread-${userId}`).text(data.count);
                });
            }

            // User click event
            $('.user-chat').click(function(e) {
                e.preventDefault();
                const userId = $(this).data('user-id');
                loadChat(userId);
            });

            // Form submission
            $('#chat-form').submit(function(e) {
                e.preventDefault();
                const formData = $(this).serialize();

                $.post('/dashboard/live-chat', formData, function() {
                    loadChat(currentUserId);
                    $('#chat-form')[0].reset();
                });
            });

            // Auto refresh every 5 seconds
            setInterval(function() {
                if (currentUserId) {
                    loadChat(currentUserId);
                }
            }, 5000);
        });
    </script>
@endpush
