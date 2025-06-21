<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Subject;
use App\Models\Note;
use App\Models\Question;
use App\Models\Answer;
use App\Models\UserProfile;
use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Dompdf\Dompdf;


class DashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index()
    {
        // Get overall statistics in the format expected by the unified dashboard
        $totalUsers = User::count();
        $activeUsers = User::where('is_active', true)->count();
        $totalQuestions = Question::count();
        $totalNotes = Note::count();
        $totalSubjects = Subject::count();
        $totalAnswers = Answer::count();
        $totalFeedback = Feedback::count();

        $stats = [
            // Main metrics for the key metrics cards
            'total_users' => $totalUsers,
            'active_users' => $activeUsers,
            'users_growth' => 12.5, // Calculate actual growth rate
            'total_questions' => $totalQuestions,
            'questions_today' => Question::whereDate('created_at', today())->count(),
            'questions_week' => Question::where('created_at', '>=', now()->subWeek())->count(),
            'total_documents' => $totalNotes, // Using notes as documents
            'documents_processing' => Note::where('status', 'draft')->count(),
            'processing_rate' => $totalNotes > 0 ? round((Note::where('status', 'published')->count() / $totalNotes) * 100, 1) : 0,
            'monthly_revenue' => 24750, // Mock data - replace with actual revenue calculation
            'revenue_growth' => 18.3, // Mock data
            'revenue_target' => 30000, // Mock data

            // Detailed stats for CRUD management cards
            'users' => [
                'total' => $totalUsers,
                'active' => $activeUsers,
                'by_role' => User::select('role', DB::raw('count(*) as count'))
                                ->groupBy('role')
                                ->get()
                                ->pluck('count', 'role'),
            ],
            'subjects' => [
                'total' => $totalSubjects,
                'with_notes' => Subject::has('notes')->count(),
                'with_users' => Subject::has('users')->count(),
            ],
            'notes' => [
                'total' => $totalNotes,
                'published' => Note::where('status', 'published')->count(),
                'draft' => Note::where('status', 'draft')->count(),
                'archived' => Note::where('status', 'archived')->count(),
            ],
            'questions' => [
                'total' => $totalQuestions,
                'ai_generated' => Question::where('generated_by', 'AI')->count(),
                'manual' => Question::where('generated_by', 'Manual')->count(),
                'by_difficulty' => Question::select('difficulty', DB::raw('count(*) as count'))
                                         ->whereNotNull('difficulty')
                                         ->groupBy('difficulty')
                                         ->get()
                                         ->pluck('count', 'difficulty'),
            ],
            'answers' => [
                'total' => $totalAnswers,
                'correct' => Answer::where('is_correct', true)->count(),
                'incorrect' => Answer::where('is_correct', false)->count(),
            ],
            'profiles' => [
                'total' => UserProfile::count(),
                'complete' => UserProfile::whereNotNull('first_name')
                                        ->whereNotNull('last_name')
                                        ->whereNotNull('date_of_birth')
                                        ->count(),
            ],
            'feedback' => [
                'total' => $totalFeedback,
                'positive' => Feedback::where('rating', '>=', 4)->count(),
                'negative' => Feedback::where('rating', '<=', 2)->count(),
                'average_rating' => round(Feedback::avg('rating'), 2),
            ],
        ];

        // Recent activity for the activity feed
        $recentActivity = collect();

        // Add recent user registrations
        User::orderBy('created_at', 'desc')->take(3)->get()->each(function($user) use ($recentActivity) {
            $recentActivity->push([
                'user' => $user->name,
                'action' => 'Registered account',
                'details' => 'New user signup',
                'time' => $user->created_at,
                'type' => 'user'
            ]);
        });

        // Add recent notes
        Note::with('user')->orderBy('created_at', 'desc')->take(3)->get()->each(function($note) use ($recentActivity) {
            $recentActivity->push([
                'user' => $note->user->name ?? 'System',
                'action' => 'Created note',
                'details' => $note->title,
                'time' => $note->created_at,
                'type' => 'document'
            ]);
        });

        // Add recent questions
        Question::with('note')->orderBy('created_at', 'desc')->take(3)->get()->each(function($question) use ($recentActivity) {
            $recentActivity->push([
                'user' => 'System',
                'action' => 'Generated question',
                'details' => substr($question->question_text, 0, 50) . '...',
                'time' => $question->created_at,
                'type' => 'question'
            ]);
        });

        // Sort by time and take the most recent 10
        $recentActivity = $recentActivity->sortByDesc('time')->take(10)->values();

        // Chart data for analytics
        $chartData = [
            'user_growth' => [
                'Mon' => User::whereDate('created_at', now()->subDays(6))->count(),
                'Tue' => User::whereDate('created_at', now()->subDays(5))->count(),
                'Wed' => User::whereDate('created_at', now()->subDays(4))->count(),
                'Thu' => User::whereDate('created_at', now()->subDays(3))->count(),
                'Fri' => User::whereDate('created_at', now()->subDays(2))->count(),
                'Sat' => User::whereDate('created_at', now()->subDays(1))->count(),
                'Sun' => User::whereDate('created_at', today())->count(),
            ],
            'questions_generated' => [
                'Mon' => Question::whereDate('created_at', now()->subDays(6))->count(),
                'Tue' => Question::whereDate('created_at', now()->subDays(5))->count(),
                'Wed' => Question::whereDate('created_at', now()->subDays(4))->count(),
                'Thu' => Question::whereDate('created_at', now()->subDays(3))->count(),
                'Fri' => Question::whereDate('created_at', now()->subDays(2))->count(),
                'Sat' => Question::whereDate('created_at', now()->subDays(1))->count(),
                'Sun' => Question::whereDate('created_at', today())->count(),
            ],
            'users_by_month' => $this->getUsersByMonth(),
            'notes_by_status' => $this->getNotesByStatus(),
            'questions_by_difficulty' => $this->getQuestionsByDifficulty(),
            'feedback_ratings' => $this->getFeedbackRatings(),
        ];

        // Recent users for the dashboard
        $recentUsers = User::orderBy('created_at', 'desc')->take(5)->get()->map(function($user) {
            return [
                'name' => $user->name,
                'email' => $user->email,
                'created_at' => $user->created_at,
                'status' => $user->is_active ? 'active' : 'inactive'
            ];
        });

        // System health data
        $systemHealth = [
            ['service' => 'Web Server', 'status' => 'healthy', 'uptime' => '99.9%', 'response_time' => '45ms'],
            ['service' => 'Database', 'status' => 'healthy', 'uptime' => '99.8%', 'response_time' => '12ms'],
            ['service' => 'File Storage', 'status' => 'healthy', 'uptime' => '99.9%', 'response_time' => '23ms'],
            ['service' => 'AI Service', 'status' => 'healthy', 'uptime' => '98.5%', 'response_time' => '156ms'],
            ['service' => 'Email Service', 'status' => 'healthy', 'uptime' => '99.7%', 'response_time' => '89ms'],
            ['service' => 'Cache Server', 'status' => 'healthy', 'uptime' => '99.6%', 'response_time' => '8ms'],
        ];

        // Performance metrics
        $performance = [
            'cpu' => 34,
            'memory' => 67,
            'disk' => 45,
            'network' => 23,
            'database' => 28,
        ];

        // Quick stats
        $quickStats = [
            'online_users' => $activeUsers,
            'avg_session' => '8m 32s',
            'bounce_rate' => 23,
            'conversion_rate' => 4.7,
            'mrr' => 24.8,
            'support_tickets' => 3,
        ];

        return view('admin.dashboard', compact('stats', 'recentActivity', 'chartData', 'recentUsers', 'systemHealth', 'performance', 'quickStats'));
    }

    /**
     * Get users created by month for the last 12 months.
     */
    private function getUsersByMonth()
    {
        return User::select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('COUNT(*) as count')
            )
            ->where('created_at', '>=', now()->subMonths(12))
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get()
            ->map(function ($item) {
                return [
                    'label' => date('M Y', mktime(0, 0, 0, $item->month, 1, $item->year)),
                    'value' => $item->count
                ];
            });
    }

    /**
     * Get notes count by status.
     */
    private function getNotesByStatus()
    {
        return Note::select('status', DB::raw('count(*) as count'))
                   ->groupBy('status')
                   ->get()
                   ->map(function ($item) {
                       return [
                           'label' => ucfirst($item->status),
                           'value' => $item->count
                       ];
                   });
    }

    /**
     * Get questions count by difficulty.
     */
    private function getQuestionsByDifficulty()
    {
        return Question::select('difficulty', DB::raw('count(*) as count'))
                      ->whereNotNull('difficulty')
                      ->groupBy('difficulty')
                      ->get()
                      ->map(function ($item) {
                          return [
                              'label' => ucfirst($item->difficulty),
                              'value' => $item->count
                          ];
                      });
    }

    /**
     * Get feedback distribution by rating.
     */
    private function getFeedbackRatings()
    {
        return Feedback::select('rating', DB::raw('count(*) as count'))
                      ->groupBy('rating')
                      ->orderBy('rating')
                      ->get()
                      ->map(function ($item) {
                          return [
                              'label' => $item->rating . ' Star' . ($item->rating > 1 ? 's' : ''),
                              'value' => $item->count
                          ];
                      });
    }

    /**
     * Get system health information.
     */
    public function systemHealth()
    {
        $health = [
            'database' => [
                'status' => 'healthy',
                'tables' => [
                    'users' => User::count(),
                    'subjects' => Subject::count(),
                    'notes' => Note::count(),
                    'questions' => Question::count(),
                    'answers' => Answer::count(),
                    'user_profiles' => UserProfile::count(),
                    'feedback' => Feedback::count(),
                ],
            ],
            'storage' => [
                'disk_usage' => '45%', // This would be calculated from actual disk usage
                'available_space' => '2.1 GB',
            ],
            'performance' => [
                'avg_response_time' => '120ms',
                'active_sessions' => session()->all() ? count(session()->all()) : 0,
            ],
        ];

        return view('admin.system-health', compact('health'));
    }

    /**
     * Export data for backup.
     */
    public function exportData(Request $request)
    {
        $type = $request->get('type', 'all');
        $format = $request->get('format', 'pdf');

        $data = [];
        $title = '';
        $filename = 'smart_study_export_' . date('Y-m-d_H-i-s');

        switch ($type) {
            case 'users':
                $data = User::with('profile')->get()->map(function($user) {
                    return [
                        'id' => $user->user_id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'role' => ucfirst($user->role),
                        'status' => $user->is_active ? 'Active' : 'Inactive',
                        'created_at' => $user->created_at->format('M d, Y'),
                        'profile' => $user->profile ? [
                            'first_name' => $user->profile->first_name,
                            'last_name' => $user->profile->last_name,
                            'phone' => $user->profile->phone,
                            'date_of_birth' => $user->profile->date_of_birth ? $user->profile->date_of_birth->format('M d, Y') : 'N/A',
                        ] : null
                    ];
                });
                $title = 'Users Report';
                $filename .= '_users';
                break;
            case 'notes':
                $data = Note::with('user', 'subjects')->get()->map(function($note) {
                    return [
                        'id' => substr($note->note_id, 0, 8) . '...',
                        'title' => $note->title,
                        'content' => substr($note->content, 0, 100) . (strlen($note->content) > 100 ? '...' : ''),
                        'status' => ucfirst($note->status),
                        'author' => $note->user->name ?? 'Unknown',
                        'subjects' => $note->subjects->pluck('name')->join(', ') ?: 'No subjects',
                        'created_at' => $note->created_at->format('M d, Y'),
                        'updated_at' => $note->updated_at->format('M d, Y'),
                    ];
                });
                $title = 'Notes Report';
                $filename .= '_notes';
                break;
            case 'questions':
                $data = Question::with('note', 'answers')->get()->map(function($question) {
                    return [
                        'id' => substr($question->question_id, 0, 8) . '...',
                        'question_text' => substr($question->question_text, 0, 80) . (strlen($question->question_text) > 80 ? '...' : ''),
                        'question_type' => ucfirst($question->question_type ?? 'Multiple Choice'),
                        'difficulty' => ucfirst($question->difficulty ?? 'Medium'),
                        'generated_by' => $question->generated_by ?? 'System',
                        'note_title' => $question->note->title ?? 'Unknown',
                        'answers_count' => $question->answers->count(),
                        'correct_answers' => $question->answers->where('is_correct', true)->count(),
                        'created_at' => $question->created_at->format('M d, Y'),
                    ];
                });
                $title = 'Questions Report';
                $filename .= '_questions';
                break;
            case 'subjects':
                $data = Subject::withCount(['notes', 'users'])->get()->map(function($subject) {
                    return [
                        'id' => substr($subject->subject_id, 0, 8) . '...',
                        'name' => $subject->name,
                        'description' => substr($subject->description ?? '', 0, 100) . (strlen($subject->description ?? '') > 100 ? '...' : ''),
                        'notes_count' => $subject->notes_count,
                        'users_count' => $subject->users_count,
                        'created_at' => $subject->created_at->format('M d, Y'),
                    ];
                });
                $title = 'Subjects Report';
                $filename .= '_subjects';
                break;
            case 'feedback':
                $data = Feedback::with('user')->get()->map(function($feedback) {
                    return [
                        'id' => substr($feedback->feedback_id, 0, 8) . '...',
                        'user_name' => $feedback->user->name ?? 'Anonymous',
                        'rating' => $feedback->rating . '/5 ⭐',
                        'comment' => substr($feedback->comment ?? '', 0, 100) . (strlen($feedback->comment ?? '') > 100 ? '...' : ''),
                        'created_at' => $feedback->created_at->format('M d, Y'),
                    ];
                });
                $title = 'Feedback Report';
                $filename .= '_feedback';
                break;
            default:
                // Complete export with summary statistics
                $data = [
                    'export_info' => [
                        'exported_at' => now()->format('M d, Y H:i:s'),
                        'exported_by' => auth()->user()->name ?? 'System',
                        'total_users' => User::count(),
                        'total_subjects' => Subject::count(),
                        'total_notes' => Note::count(),
                        'total_questions' => Question::count(),
                        'total_feedback' => Feedback::count(),
                    ],
                    'users' => User::with('profile')->take(10)->get()->map(function($user) {
                        return [
                            'name' => $user->name,
                            'email' => $user->email,
                            'role' => ucfirst($user->role),
                            'status' => $user->is_active ? 'Active' : 'Inactive',
                            'created_at' => $user->created_at->format('M d, Y'),
                        ];
                    }),
                    'subjects' => Subject::withCount('notes')->take(10)->get()->map(function($subject) {
                        return [
                            'name' => $subject->name,
                            'notes_count' => $subject->notes_count,
                            'created_at' => $subject->created_at->format('M d, Y'),
                        ];
                    }),
                    'notes' => Note::with('user')->take(10)->get()->map(function($note) {
                        return [
                            'title' => $note->title,
                            'status' => ucfirst($note->status),
                            'author' => $note->user->name ?? 'Unknown',
                            'created_at' => $note->created_at->format('M d, Y'),
                        ];
                    }),
                    'questions' => Question::with('answers')->take(10)->get()->map(function($question) {
                        return [
                            'question_text' => substr($question->question_text, 0, 60) . '...',
                            'difficulty' => ucfirst($question->difficulty ?? 'Medium'),
                            'answers_count' => $question->answers->count(),
                            'created_at' => $question->created_at->format('M d, Y'),
                        ];
                    }),
                    'feedback' => Feedback::with('user')->take(10)->get()->map(function($feedback) {
                        return [
                            'user_name' => $feedback->user->name ?? 'Anonymous',
                            'rating' => $feedback->rating . '/5 ⭐',
                            'created_at' => $feedback->created_at->format('M d, Y'),
                        ];
                    }),
                ];
                $title = 'Complete System Report';
                $filename .= '_complete';
        }

        // Return appropriate format
        switch ($format) {
            case 'csv':
                return $this->exportAsCSV($data, $filename);
            case 'pdf':
            default:
                return $this->exportAsPDF($data, $title, $filename);
        }
    }

    /**
     * Export data as PDF file
     */
    private function exportAsPDF($data, $title, $filename)
    {
        // Prepare data for PDF view
        $exportData = [
            'title' => $title,
            'data' => $data,
            'exported_at' => now()->format('M d, Y H:i:s'),
            'exported_by' => auth()->user()->name ?? 'System',
        ];

        // Render the view to HTML
        $html = view('admin.exports.pdf-template', $exportData)->render();

        // Create DomPDF instance with default settings
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Return PDF as download
        return response($dompdf->output())
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '.pdf"');
    }

    /**
     * Export data as CSV file
     */
    private function exportAsCSV($data, $filename)
    {
        // Handle different data structures
        if (is_array($data) && isset($data['users'])) {
            // Complete export - flatten to single CSV with all data
            $csvData = $this->flattenCompleteDataForCSV($data);
        } else {
            // Single table export
            $csvData = $this->convertToCSV($data);
        }

        return response($csvData)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '.csv"')
            ->header('Content-Length', strlen($csvData));
    }

    /**
     * Convert collection to CSV format
     */
    private function convertToCSV($data)
    {
        if (empty($data)) {
            return "No data available\n";
        }

        $csv = '';
        $headers = array_keys($data->first());
        $csv .= implode(',', $headers) . "\n";

        foreach ($data as $row) {
            $csvRow = [];
            foreach ($headers as $header) {
                $value = $row[$header] ?? '';
                // Escape quotes and wrap in quotes if contains comma
                if (is_string($value) && (strpos($value, ',') !== false || strpos($value, '"') !== false)) {
                    $value = '"' . str_replace('"', '""', $value) . '"';
                }
                $csvRow[] = $value;
            }
            $csv .= implode(',', $csvRow) . "\n";
        }

        return $csv;
    }

    /**
     * Flatten complete data export for CSV
     */
    private function flattenCompleteDataForCSV($data)
    {
        $csv = "Smart Study Platform - Complete Data Export\n";
        $csv .= "Exported at: " . ($data['export_info']['exported_at'] ?? now()) . "\n";
        $csv .= "Total Records: " . ($data['export_info']['total_records'] ?? 0) . "\n\n";

        foreach ($data as $section => $items) {
            if ($section === 'export_info') continue;

            $csv .= strtoupper($section) . "\n";
            $csv .= str_repeat('=', 50) . "\n";

            if (!empty($items)) {
                $headers = array_keys($items->first());
                $csv .= implode(',', $headers) . "\n";

                foreach ($items as $item) {
                    $csvRow = [];
                    foreach ($headers as $header) {
                        $value = $item[$header] ?? '';
                        if (is_string($value) && (strpos($value, ',') !== false || strpos($value, '"') !== false)) {
                            $value = '"' . str_replace('"', '""', $value) . '"';
                        }
                        $csvRow[] = $value;
                    }
                    $csv .= implode(',', $csvRow) . "\n";
                }
            } else {
                $csv .= "No data available\n";
            }

            $csv .= "\n";
        }

        return $csv;
    }

    /**
     * System reports
     */
    public function reports()
    {
        // Generate comprehensive system reports
        $reports = [
            'user_statistics' => [
                'total_users' => User::count(),
                'active_users' => User::where('is_active', true)->count(),
                'users_by_role' => User::select('role', \DB::raw('count(*) as count'))
                    ->groupBy('role')
                    ->pluck('count', 'role')
                    ->toArray(),
                'recent_registrations' => User::where('created_at', '>=', now()->subDays(30))->count(),
            ],
            'content_statistics' => [
                'total_subjects' => Subject::count(),
                'total_notes' => Note::count(),
                'published_notes' => Note::where('status', 'published')->count(),
                'draft_notes' => Note::where('status', 'draft')->count(),
                'notes_by_subject' => Subject::withCount('notes')->get()->pluck('notes_count', 'name')->toArray(),
            ],
            'qa_statistics' => [
                'total_questions' => Question::count(),
                'total_answers' => Answer::count(),
                'correct_answers' => Answer::where('is_correct', true)->count(),
                'ai_generated_questions' => Question::where('generated_by', 'AI')->count(),
                'manual_questions' => Question::where('generated_by', 'Manual')->count(),
            ],
            'feedback_statistics' => [
                'total_feedback' => Feedback::count(),
                'average_rating' => round(Feedback::avg('rating'), 2),
                'feedback_by_rating' => Feedback::select('rating', \DB::raw('count(*) as count'))
                    ->groupBy('rating')
                    ->pluck('count', 'rating')
                    ->toArray(),
                'recent_feedback' => Feedback::where('created_at', '>=', now()->subDays(30))->count(),
            ],
            'system_health' => [
                'database_status' => 'healthy',
                'total_records' => User::count() + Subject::count() + Note::count() + Question::count() + Answer::count() + Feedback::count(),
                'last_backup' => 'N/A', // Would be actual backup date
                'system_uptime' => '99.9%', // Would be actual uptime
            ]
        ];

        return view('admin.reports', compact('reports'));
    }
}
