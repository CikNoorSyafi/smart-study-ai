<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title }} - Smart Study Platform</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        
        .header {
            text-align: center;
            border-bottom: 2px solid #3B82F6;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        
        .header h1 {
            color: #1F2937;
            font-size: 24px;
            margin: 0 0 10px 0;
        }
        
        .header .subtitle {
            color: #6B7280;
            font-size: 14px;
            margin: 5px 0;
        }
        
        .export-info {
            background-color: #F3F4F6;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 30px;
        }
        
        .export-info h3 {
            margin: 0 0 10px 0;
            color: #374151;
            font-size: 16px;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }
        
        .info-item {
            display: flex;
            justify-content: space-between;
        }
        
        .info-label {
            font-weight: bold;
            color: #4B5563;
        }
        
        .info-value {
            color: #1F2937;
        }
        
        .section {
            margin-bottom: 30px;
            page-break-inside: avoid;
        }
        
        .section-title {
            background-color: #3B82F6;
            color: white;
            padding: 10px 15px;
            margin: 0 0 15px 0;
            font-size: 16px;
            font-weight: bold;
        }
        
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        .table th {
            background-color: #F9FAFB;
            border: 1px solid #D1D5DB;
            padding: 8px;
            text-align: left;
            font-weight: bold;
            color: #374151;
            font-size: 11px;
        }
        
        .table td {
            border: 1px solid #E5E7EB;
            padding: 8px;
            font-size: 10px;
            vertical-align: top;
        }
        
        .table tr:nth-child(even) {
            background-color: #F9FAFB;
        }
        
        .no-data {
            text-align: center;
            color: #6B7280;
            font-style: italic;
            padding: 20px;
        }
        
        .footer {
            position: fixed;
            bottom: 20px;
            left: 20px;
            right: 20px;
            text-align: center;
            font-size: 10px;
            color: #6B7280;
            border-top: 1px solid #E5E7EB;
            padding-top: 10px;
        }
        
        .page-break {
            page-break-before: always;
        }
        
        .summary-stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            margin-bottom: 20px;
        }
        
        .stat-card {
            background-color: #F3F4F6;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
        }
        
        .stat-number {
            font-size: 24px;
            font-weight: bold;
            color: #3B82F6;
            margin-bottom: 5px;
        }
        
        .stat-label {
            color: #6B7280;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>{{ $title }}</h1>
        <div class="subtitle">Smart Study Platform - Data Export Report</div>
        <div class="subtitle">Generated on {{ $exported_at }} by {{ $exported_by }}</div>
    </div>

    <!-- Export Information -->
    @if(isset($data['export_info']))
    <div class="export-info">
        <h3>Export Summary</h3>
        <div class="info-grid">
            @if(isset($data['export_info']['total_users']))
            <div class="info-item">
                <span class="info-label">Total Users:</span>
                <span class="info-value">{{ $data['export_info']['total_users'] }}</span>
            </div>
            @endif
            @if(isset($data['export_info']['total_subjects']))
            <div class="info-item">
                <span class="info-label">Total Subjects:</span>
                <span class="info-value">{{ $data['export_info']['total_subjects'] }}</span>
            </div>
            @endif
            @if(isset($data['export_info']['total_notes']))
            <div class="info-item">
                <span class="info-label">Total Notes:</span>
                <span class="info-value">{{ $data['export_info']['total_notes'] }}</span>
            </div>
            @endif
            @if(isset($data['export_info']['total_questions']))
            <div class="info-item">
                <span class="info-label">Total Questions:</span>
                <span class="info-value">{{ $data['export_info']['total_questions'] }}</span>
            </div>
            @endif
            @if(isset($data['export_info']['total_feedback']))
            <div class="info-item">
                <span class="info-label">Total Feedback:</span>
                <span class="info-value">{{ $data['export_info']['total_feedback'] }}</span>
            </div>
            @endif
        </div>
    </div>
    @endif

    <!-- Data Sections -->
    @if(isset($data['users']) && count($data['users']) > 0)
    <div class="section">
        <h2 class="section-title">Users ({{ count($data['users']) }} records)</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Created</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data['users'] as $user)
                <tr>
                    <td>{{ $user['name'] ?? 'N/A' }}</td>
                    <td>{{ $user['email'] ?? 'N/A' }}</td>
                    <td>{{ $user['role'] ?? 'N/A' }}</td>
                    <td>{{ $user['status'] ?? ($user['is_active'] ?? false ? 'Active' : 'Inactive') }}</td>
                    <td>{{ $user['created_at'] ?? 'N/A' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    @if(isset($data['subjects']) && count($data['subjects']) > 0)
    <div class="section">
        <h2 class="section-title">Subjects ({{ count($data['subjects']) }} records)</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Notes Count</th>
                    <th>Created</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data['subjects'] as $subject)
                <tr>
                    <td>{{ $subject['name'] ?? 'N/A' }}</td>
                    <td>{{ $subject['description'] ?? 'No description' }}</td>
                    <td>{{ $subject['notes_count'] ?? 0 }}</td>
                    <td>{{ $subject['created_at'] ?? 'N/A' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    @if(isset($data['notes']) && count($data['notes']) > 0)
    <div class="section">
        <h2 class="section-title">Notes ({{ count($data['notes']) }} records)</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Status</th>
                    <th>Author</th>
                    <th>Subjects</th>
                    <th>Created</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data['notes'] as $note)
                <tr>
                    <td>{{ $note['title'] ?? 'N/A' }}</td>
                    <td>{{ $note['status'] ?? 'N/A' }}</td>
                    <td>{{ $note['author'] ?? 'Unknown' }}</td>
                    <td>{{ $note['subjects'] ?? 'No subjects' }}</td>
                    <td>{{ $note['created_at'] ?? 'N/A' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    @if(isset($data['questions']) && count($data['questions']) > 0)
    <div class="section">
        <h2 class="section-title">Questions ({{ count($data['questions']) }} records)</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Question</th>
                    <th>Type</th>
                    <th>Difficulty</th>
                    <th>Answers</th>
                    <th>Created</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data['questions'] as $question)
                <tr>
                    <td>{{ $question['question_text'] ?? 'N/A' }}</td>
                    <td>{{ $question['question_type'] ?? 'Multiple Choice' }}</td>
                    <td>{{ $question['difficulty'] ?? 'Medium' }}</td>
                    <td>{{ $question['answers_count'] ?? 0 }}</td>
                    <td>{{ $question['created_at'] ?? 'N/A' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    @if(isset($data['feedback']) && count($data['feedback']) > 0)
    <div class="section">
        <h2 class="section-title">Feedback ({{ count($data['feedback']) }} records)</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Rating</th>
                    <th>Comment</th>
                    <th>Created</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data['feedback'] as $feedback)
                <tr>
                    <td>{{ $feedback['user_name'] ?? 'Anonymous' }}</td>
                    <td>{{ $feedback['rating'] ?? 'N/A' }}</td>
                    <td>{{ $feedback['comment'] ?? 'No comment' }}</td>
                    <td>{{ $feedback['created_at'] ?? 'N/A' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <!-- Single table export (when not complete export) -->
    @if(!isset($data['export_info']) && !isset($data['users']) && is_array($data) && count($data) > 0)
    <div class="section">
        <table class="table">
            <thead>
                <tr>
                    @foreach(array_keys($data[0]) as $header)
                    <th>{{ ucfirst(str_replace('_', ' ', $header)) }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($data as $row)
                <tr>
                    @foreach($row as $value)
                    <td>{{ $value ?? 'N/A' }}</td>
                    @endforeach
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <!-- Footer -->
    <div class="footer">
        <p>Smart Study Platform - Data Export Report | Generated on {{ $exported_at }} | Page <span class="pagenum"></span></p>
    </div>
</body>
</html>
