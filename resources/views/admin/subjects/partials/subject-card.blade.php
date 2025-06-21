<div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow border">
    <div class="p-4">
        <div class="flex items-start justify-between mb-3">
            <div class="flex-1">
                <div class="flex items-center mb-2">
                    <h3 class="text-lg font-semibold text-gray-900">{{ $subject->name }}</h3>
                    @if($subject->subject_code)
                    <span class="ml-2 px-2 py-1 bg-gray-100 text-gray-600 text-xs rounded-full font-mono">{{ $subject->subject_code }}</span>
                    @endif
                </div>
                
                <!-- Form Level and Category Badges -->
                <div class="flex items-center space-x-2 mb-2">
                    @if($subject->form_level)
                    <span class="px-2 py-1 bg-blue-100 text-blue-700 text-xs rounded-full font-medium">{{ $subject->form_level }}</span>
                    @endif
                    @if($subject->category)
                    <span class="px-2 py-1 
                        @if($subject->category === 'Core') bg-red-100 text-red-700
                        @elseif($subject->category === 'Science') bg-green-100 text-green-700
                        @elseif($subject->category === 'Arts') bg-yellow-100 text-yellow-700
                        @elseif($subject->category === 'Technical') bg-purple-100 text-purple-700
                        @else bg-gray-100 text-gray-700
                        @endif
                        text-xs rounded-full font-medium">{{ $subject->category }}</span>
                    @endif
                </div>
                
                <p class="text-sm text-gray-600 mb-3">{{ Str::limit($subject->description, 80) }}</p>
            </div>
            <div class="flex space-x-1 ml-3">
                <a href="{{ route('admin.subjects.show', $subject) }}" 
                   class="text-blue-600 hover:text-blue-800 p-1" title="View Details">
                    <i class="fas fa-eye"></i>
                </a>
                <a href="{{ route('admin.subjects.edit', $subject) }}" 
                   class="text-indigo-600 hover:text-indigo-800 p-1" title="Edit">
                    <i class="fas fa-edit"></i>
                </a>
                @if(!$subject->users()->exists() && !$subject->notes()->exists())
                <form action="{{ route('admin.subjects.destroy', $subject) }}" method="POST" class="inline"
                      onsubmit="return confirm('Are you sure you want to delete this subject?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-red-600 hover:text-red-800 p-1" title="Delete">
                        <i class="fas fa-trash"></i>
                    </button>
                </form>
                @endif
            </div>
        </div>

        <!-- Statistics -->
        <div class="grid grid-cols-2 gap-3 mb-3">
            <div class="text-center p-2 bg-blue-50 rounded-lg">
                <div class="text-lg font-bold text-blue-600">{{ $subject->users_count }}</div>
                <div class="text-xs text-blue-600">Users</div>
            </div>
            <div class="text-center p-2 bg-green-50 rounded-lg">
                <div class="text-lg font-bold text-green-600">{{ $subject->notes_count }}</div>
                <div class="text-xs text-green-600">Notes</div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="flex space-x-2 mb-3">
            <a href="{{ route('admin.subjects.show', $subject) }}" 
               class="flex-1 bg-blue-100 text-blue-700 text-center py-2 rounded-md hover:bg-blue-200 transition-colors text-sm">
                View Details
            </a>
            <a href="{{ route('admin.subjects.edit', $subject) }}" 
               class="flex-1 bg-gray-100 text-gray-700 text-center py-2 rounded-md hover:bg-gray-200 transition-colors text-sm">
                Edit
            </a>
        </div>

        <!-- Created Date -->
        <div class="pt-3 border-t border-gray-200">
            <p class="text-xs text-gray-500">
                Created {{ $subject->created_at->format('M d, Y') }}
            </p>
        </div>
    </div>
</div>
