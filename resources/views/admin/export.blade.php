@extends('layouts.admin')

@section('title', 'Export Data')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Export Data</h1>
                <p class="mt-1 text-sm text-gray-600">Download your data in various formats for backup or analysis</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.dashboard') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Dashboard
                </a>
            </div>
        </div>
    </div>

    <!-- Export Options -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Quick Export -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">
                    <i class="fas fa-download mr-2 text-blue-600"></i>Quick Export
                </h3>
                <p class="text-sm text-gray-600 mb-6">Export all data in a single file</p>
                
                <div class="space-y-4">
                    <div class="flex space-x-3">
                        <button onclick="exportData('all', 'pdf')"
                                class="flex-1 bg-red-600 text-white px-4 py-3 rounded-lg hover:bg-red-700 transition-colors">
                            <i class="fas fa-file-pdf mr-2"></i>Export as PDF
                        </button>
                        <button onclick="exportData('all', 'csv')"
                                class="flex-1 bg-green-600 text-white px-4 py-3 rounded-lg hover:bg-green-700 transition-colors">
                            <i class="fas fa-file-csv mr-2"></i>Export as CSV
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Selective Export -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">
                    <i class="fas fa-filter mr-2 text-purple-600"></i>Selective Export
                </h3>
                <p class="text-sm text-gray-600 mb-6">Export specific data types</p>
                
                <div class="space-y-3">
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-users text-blue-600 mr-3"></i>
                            <span class="font-medium">Users</span>
                            <span class="ml-2 text-sm text-gray-500">({{ \App\Models\User::count() }} records)</span>
                        </div>
                        <div class="flex space-x-2">
                            <button onclick="exportData('users', 'pdf')"
                                    class="text-red-600 hover:text-red-800 text-sm">PDF</button>
                            <button onclick="exportData('users', 'csv')"
                                    class="text-green-600 hover:text-green-800 text-sm">CSV</button>
                        </div>
                    </div>

                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-sticky-note text-yellow-600 mr-3"></i>
                            <span class="font-medium">Notes</span>
                            <span class="ml-2 text-sm text-gray-500">({{ \App\Models\Note::count() }} records)</span>
                        </div>
                        <div class="flex space-x-2">
                            <button onclick="exportData('notes', 'pdf')"
                                    class="text-red-600 hover:text-red-800 text-sm">PDF</button>
                            <button onclick="exportData('notes', 'csv')"
                                    class="text-green-600 hover:text-green-800 text-sm">CSV</button>
                        </div>
                    </div>

                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-question-circle text-purple-600 mr-3"></i>
                            <span class="font-medium">Questions</span>
                            <span class="ml-2 text-sm text-gray-500">({{ \App\Models\Question::count() }} records)</span>
                        </div>
                        <div class="flex space-x-2">
                            <button onclick="exportData('questions', 'pdf')"
                                    class="text-red-600 hover:text-red-800 text-sm">PDF</button>
                            <button onclick="exportData('questions', 'csv')"
                                    class="text-green-600 hover:text-green-800 text-sm">CSV</button>
                        </div>
                    </div>

                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-book text-indigo-600 mr-3"></i>
                            <span class="font-medium">Subjects</span>
                            <span class="ml-2 text-sm text-gray-500">({{ \App\Models\Subject::count() }} records)</span>
                        </div>
                        <div class="flex space-x-2">
                            <button onclick="exportData('subjects', 'pdf')"
                                    class="text-red-600 hover:text-red-800 text-sm">PDF</button>
                            <button onclick="exportData('subjects', 'csv')"
                                    class="text-green-600 hover:text-green-800 text-sm">CSV</button>
                        </div>
                    </div>

                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-comment text-orange-600 mr-3"></i>
                            <span class="font-medium">Feedback</span>
                            <span class="ml-2 text-sm text-gray-500">({{ \App\Models\Feedback::count() }} records)</span>
                        </div>
                        <div class="flex space-x-2">
                            <button onclick="exportData('feedback', 'pdf')"
                                    class="text-red-600 hover:text-red-800 text-sm">PDF</button>
                            <button onclick="exportData('feedback', 'csv')"
                                    class="text-green-600 hover:text-green-800 text-sm">CSV</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Export Information -->
    <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-6">
        <h3 class="text-lg font-medium text-blue-900 mb-3">
            <i class="fas fa-info-circle mr-2"></i>Export Information
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-blue-800">
            <div>
                <h4 class="font-medium mb-2">PDF Format:</h4>
                <ul class="space-y-1">
                    <li>• Professional formatted reports</li>
                    <li>• Ready for printing and sharing</li>
                    <li>• Best for presentations and documentation</li>
                    <li>• Preserves formatting and layout</li>
                </ul>
            </div>
            <div>
                <h4 class="font-medium mb-2">CSV Format:</h4>
                <ul class="space-y-1">
                    <li>• Tabular data format</li>
                    <li>• Compatible with Excel and spreadsheet apps</li>
                    <li>• Best for data analysis and reporting</li>
                    <li>• Flattened structure for easy viewing</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Recent Exports -->
    <div class="mt-6 bg-white rounded-lg shadow">
        <div class="p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">
                <i class="fas fa-history mr-2 text-gray-600"></i>Export Guidelines
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm text-gray-600">
                <div class="p-4 bg-gray-50 rounded-lg">
                    <h4 class="font-medium text-gray-900 mb-2">
                        <i class="fas fa-shield-alt mr-2 text-green-600"></i>Data Security
                    </h4>
                    <p>Exported files contain sensitive data. Store them securely and delete when no longer needed.</p>
                </div>
                <div class="p-4 bg-gray-50 rounded-lg">
                    <h4 class="font-medium text-gray-900 mb-2">
                        <i class="fas fa-clock mr-2 text-blue-600"></i>Regular Backups
                    </h4>
                    <p>Schedule regular exports to ensure you have up-to-date backups of your important data.</p>
                </div>
                <div class="p-4 bg-gray-50 rounded-lg">
                    <h4 class="font-medium text-gray-900 mb-2">
                        <i class="fas fa-file-pdf mr-2 text-purple-600"></i>PDF Reports
                    </h4>
                    <p>PDF exports are perfect for sharing reports with stakeholders and creating professional documentation.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Loading Modal -->
<div id="loadingModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-1/2 mx-auto p-5 border w-80 shadow-lg rounded-md bg-white transform -translate-y-1/2">
        <div class="text-center">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto mb-4"></div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Preparing Export...</h3>
            <p class="text-sm text-gray-600">Please wait while we prepare your data for download.</p>
        </div>
    </div>
</div>

<script>
function exportData(type, format) {
    // Show loading modal
    document.getElementById('loadingModal').classList.remove('hidden');
    
    // Create download URL
    const url = `{{ route('admin.export-data') }}?type=${type}&format=${format}`;
    
    // Create temporary link and trigger download
    const link = document.createElement('a');
    link.href = url;
    link.style.display = 'none';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    
    // Hide loading modal after a short delay
    setTimeout(() => {
        document.getElementById('loadingModal').classList.add('hidden');
        
        // Show success notification
        showNotification('success', `${type.charAt(0).toUpperCase() + type.slice(1)} data exported successfully as ${format.toUpperCase()}!`);
    }, 2000);
}

function showNotification(type, message) {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg ${
        type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
    }`;
    notification.innerHTML = `
        <div class="flex items-center">
            <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'} mr-2"></i>
            <span>${message}</span>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    // Remove notification after 3 seconds
    setTimeout(() => {
        notification.remove();
    }, 3000);
}
</script>
@endsection
