@if($notifications->count() > 0)
    @foreach($notifications as $notification)
    <div class="notification-item flex items-start p-4 hover:bg-gray-50 transition-colors {{ $notification->is_read ? 'opacity-75' : '' }}" 
         data-notification-id="{{ $notification->notification_id }}">
        <div class="flex-shrink-0 mr-3">
            <div class="w-10 h-10 rounded-full flex items-center justify-center 
                {{ $notification->is_read ? 'bg-gray-100' : 'bg-' . $notification->color . '-100' }}">
                <i class="{{ $notification->icon }} text-{{ $notification->color }}-600"></i>
            </div>
        </div>
        <div class="flex-1 min-w-0">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-900 {{ $notification->is_read ? 'opacity-75' : '' }}">
                        {{ $notification->title }}
                    </p>
                    <p class="text-sm text-gray-600 mt-1 {{ $notification->is_read ? 'opacity-75' : '' }}">
                        {{ $notification->message }}
                    </p>
                    <p class="text-xs text-gray-500 mt-2">
                        {{ $notification->time_ago }}
                    </p>
                </div>
                <div class="flex items-center space-x-2 ml-2">
                    @if(!$notification->is_read)
                    <button onclick="markAsRead('{{ $notification->notification_id }}')" 
                            class="text-xs text-blue-600 hover:text-blue-800" title="Mark as read">
                        <i class="fas fa-check"></i>
                    </button>
                    @endif
                    <button onclick="deleteNotification('{{ $notification->notification_id }}')" 
                            class="text-xs text-red-600 hover:text-red-800" title="Delete">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
            @if($notification->action_url)
            <div class="mt-2">
                <a href="{{ $notification->action_url }}" 
                   class="text-xs text-blue-600 hover:text-blue-800 font-medium"
                   onclick="markAsRead('{{ $notification->notification_id }}')">
                    View Details →
                </a>
            </div>
            @endif
        </div>
        @if(!$notification->is_read)
        <div class="flex-shrink-0 ml-2">
            <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
        </div>
        @endif
    </div>
    @if(!$loop->last)
    <div class="border-t border-gray-100"></div>
    @endif
    @endforeach
@else
    <div class="p-8 text-center">
        <div class="w-16 h-16 mx-auto mb-4 bg-gray-100 rounded-full flex items-center justify-center">
            <i class="fas fa-bell-slash text-2xl text-gray-400"></i>
        </div>
        <h3 class="text-sm font-medium text-gray-900 mb-1">No notifications</h3>
        <p class="text-sm text-gray-500">You're all caught up! Check back later for updates.</p>
    </div>
@endif
