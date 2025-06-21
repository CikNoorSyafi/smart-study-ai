<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ParentController extends Controller
{
    /**
     * Show children overview and progress
     */
    public function children()
    {
        // Sample children data (in real app, this would come from database)
        $children = [
            [
                'id' => 1,
                'user_id' => 'student-001',
                'name' => 'John Smith Jr.',
                'grade' => 'Grade 8',
                'subjects' => ['Math', 'Science', 'English'],
                'avatar' => 'JS',
                'avatar_color' => 'blue',
                'overall_grade' => 'B+',
                'attendance' => 94,
                'recent_scores' => [
                    ['subject' => 'Math Quiz', 'score' => 85, 'date' => '2024-01-15'],
                    ['subject' => 'Science Test', 'score' => 78, 'date' => '2024-01-12'],
                    ['subject' => 'English Essay', 'score' => 92, 'date' => '2024-01-10'],
                ],
                'upcoming_tests' => [
                    ['subject' => 'History', 'date' => '2024-01-20', 'type' => 'Unit Test'],
                    ['subject' => 'Math', 'date' => '2024-01-22', 'type' => 'Chapter Quiz'],
                ],
                'teacher_notes' => [
                    'Excellent progress in mathematics. Shows strong analytical thinking.',
                    'Needs to improve participation in science discussions.',
                ]
            ],
            [
                'id' => 2,
                'user_id' => 'student-002',
                'name' => 'Emma Smith',
                'grade' => 'Grade 6',
                'subjects' => ['English', 'History', 'Art'],
                'avatar' => 'ES',
                'avatar_color' => 'pink',
                'overall_grade' => 'A-',
                'attendance' => 98,
                'recent_scores' => [
                    ['subject' => 'English Essay', 'score' => 92, 'date' => '2024-01-14'],
                    ['subject' => 'History Project', 'score' => 88, 'date' => '2024-01-11'],
                    ['subject' => 'Art Portfolio', 'score' => 95, 'date' => '2024-01-09'],
                ],
                'upcoming_tests' => [
                    ['subject' => 'English', 'date' => '2024-01-19', 'type' => 'Reading Comprehension'],
                    ['subject' => 'History', 'date' => '2024-01-25', 'type' => 'Timeline Quiz'],
                ],
                'teacher_notes' => [
                    'Outstanding creative writing skills and artistic talent.',
                    'Shows excellent understanding of historical concepts.',
                ]
            ]
        ];

        return view('parent.children', compact('children'));
    }

    /**
     * Show individual child progress
     */
    public function childProgress($childId)
    {
        // Sample individual child data
        $child = [
            'id' => $childId,
            'name' => $childId == 1 ? 'John Smith Jr.' : 'Emma Smith',
            'grade' => $childId == 1 ? 'Grade 8' : 'Grade 6',
            'subjects' => $childId == 1 ? ['Math', 'Science', 'English'] : ['English', 'History', 'Art'],
            'avatar' => $childId == 1 ? 'JS' : 'ES',
            'avatar_color' => $childId == 1 ? 'blue' : 'pink',
            'overall_grade' => $childId == 1 ? 'B+' : 'A-',
            'attendance' => $childId == 1 ? 94 : 98,
            'detailed_progress' => [
                'Math' => ['current_grade' => 'B+', 'trend' => 'improving', 'last_test' => 85],
                'Science' => ['current_grade' => 'B', 'trend' => 'stable', 'last_test' => 78],
                'English' => ['current_grade' => 'A-', 'trend' => 'excellent', 'last_test' => 92],
                'History' => ['current_grade' => 'A', 'trend' => 'excellent', 'last_test' => 88],
                'Art' => ['current_grade' => 'A+', 'trend' => 'outstanding', 'last_test' => 95],
            ],
            'study_time' => [
                'daily_average' => $childId == 1 ? '2.5 hours' : '2.8 hours',
                'weekly_total' => $childId == 1 ? '17.5 hours' : '19.6 hours',
                'most_studied' => $childId == 1 ? 'Math' : 'English',
            ],
            'achievements' => [
                'Math Competition - 2nd Place',
                'Perfect Attendance - December',
                'Student of the Month - November',
            ]
        ];

        return view('parent.child-progress', compact('child'));
    }

    /**
     * Show performance reports
     */
    public function reports()
    {
        // Sample reports data
        $reports = [
            [
                'id' => 1,
                'title' => 'Monthly Progress Report - January 2024',
                'type' => 'Monthly Report',
                'date' => '2024-01-15',
                'children' => ['John Smith Jr.', 'Emma Smith'],
                'status' => 'completed',
                'summary' => 'Both children showing excellent progress. John improving in Math, Emma excelling in English.',
                'download_url' => '#'
            ],
            [
                'id' => 2,
                'title' => 'Mid-Term Assessment Report',
                'type' => 'Assessment Report',
                'date' => '2024-01-10',
                'children' => ['John Smith Jr.', 'Emma Smith'],
                'status' => 'completed',
                'summary' => 'Mid-term results show consistent performance across all subjects.',
                'download_url' => '#'
            ],
            [
                'id' => 3,
                'title' => 'Attendance & Behavior Report',
                'type' => 'Behavioral Report',
                'date' => '2024-01-05',
                'children' => ['John Smith Jr.', 'Emma Smith'],
                'status' => 'completed',
                'summary' => 'Excellent attendance and positive behavior from both students.',
                'download_url' => '#'
            ],
            [
                'id' => 4,
                'title' => 'Parent-Teacher Conference Summary',
                'type' => 'Conference Report',
                'date' => '2024-01-01',
                'children' => ['John Smith Jr.', 'Emma Smith'],
                'status' => 'pending',
                'summary' => 'Scheduled for January 20th. Agenda includes academic progress and goal setting.',
                'download_url' => null
            ]
        ];

        return view('parent.reports', compact('reports'));
    }

    /**
     * Show messages and communication
     */
    public function messages()
    {
        // Sample messages data
        $messages = [
            [
                'id' => 1,
                'from' => 'Mrs. Johnson (Math Teacher)',
                'subject' => 'John\'s Math Progress',
                'preview' => 'John has shown remarkable improvement in algebra. His recent test score of 85% reflects his hard work...',
                'date' => '2024-01-15 10:30 AM',
                'read' => false,
                'type' => 'teacher',
                'priority' => 'normal'
            ],
            [
                'id' => 2,
                'from' => 'Principal Williams',
                'subject' => 'Parent-Teacher Conference Reminder',
                'preview' => 'This is a friendly reminder about the upcoming parent-teacher conference scheduled for January 20th...',
                'date' => '2024-01-14 2:15 PM',
                'read' => true,
                'type' => 'admin',
                'priority' => 'high'
            ],
            [
                'id' => 3,
                'from' => 'Ms. Davis (English Teacher)',
                'subject' => 'Emma\'s Creative Writing Achievement',
                'preview' => 'I wanted to share some exciting news about Emma\'s recent creative writing assignment...',
                'date' => '2024-01-12 4:45 PM',
                'read' => true,
                'type' => 'teacher',
                'priority' => 'normal'
            ],
            [
                'id' => 4,
                'from' => 'School Nurse',
                'subject' => 'Health and Wellness Update',
                'preview' => 'Monthly health screening results and wellness tips for your children...',
                'date' => '2024-01-10 9:00 AM',
                'read' => true,
                'type' => 'health',
                'priority' => 'low'
            ]
        ];

        return view('parent.messages', compact('messages'));
    }

    /**
     * Show children management page
     */
    public function manageChildren()
    {
        // Sample children management data
        $children = [
            [
                'id' => 1,
                'name' => 'John Smith Jr.',
                'grade' => 'Grade 8',
                'student_id' => 'STU-2024-001',
                'enrollment_date' => '2023-09-01',
                'status' => 'active',
                'emergency_contact' => 'Jane Smith (Mother)',
                'medical_notes' => 'No known allergies',
                'transportation' => 'School Bus #15',
                'lunch_plan' => 'Standard Meal Plan',
                'extracurricular' => ['Math Club', 'Soccer Team']
            ],
            [
                'id' => 2,
                'name' => 'Emma Smith',
                'grade' => 'Grade 6',
                'student_id' => 'STU-2024-002',
                'enrollment_date' => '2023-09-01',
                'status' => 'active',
                'emergency_contact' => 'Jane Smith (Mother)',
                'medical_notes' => 'Mild asthma - inhaler available',
                'transportation' => 'Parent Pickup',
                'lunch_plan' => 'Vegetarian Meal Plan',
                'extracurricular' => ['Art Club', 'Drama Club', 'Chess Club']
            ]
        ];

        return view('parent.manage-children', compact('children'));
    }

    /**
     * Show detailed reports page
     */
    public function detailedReports()
    {
        // Sample detailed analytics data
        $analytics = [
            'academic_performance' => [
                'john' => [
                    'overall_gpa' => 3.2,
                    'trend' => 'improving',
                    'subjects' => [
                        'Math' => ['grade' => 'B+', 'improvement' => '+15%'],
                        'Science' => ['grade' => 'B', 'improvement' => '+5%'],
                        'English' => ['grade' => 'A-', 'improvement' => '+10%'],
                    ]
                ],
                'emma' => [
                    'overall_gpa' => 3.7,
                    'trend' => 'excellent',
                    'subjects' => [
                        'English' => ['grade' => 'A', 'improvement' => '+8%'],
                        'History' => ['grade' => 'A-', 'improvement' => '+12%'],
                        'Art' => ['grade' => 'A+', 'improvement' => '+5%'],
                    ]
                ]
            ],
            'attendance_data' => [
                'john' => ['present' => 94, 'absent' => 6, 'late' => 2],
                'emma' => ['present' => 98, 'absent' => 2, 'late' => 1],
            ],
            'behavioral_reports' => [
                'positive_notes' => 15,
                'areas_for_improvement' => 3,
                'teacher_commendations' => 8,
            ]
        ];

        return view('parent.detailed-reports', compact('analytics'));
    }
}
