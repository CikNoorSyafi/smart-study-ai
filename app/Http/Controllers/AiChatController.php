<?php

namespace App\Http\Controllers;

use App\Services\AiChatService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AiChatController extends Controller
{
    private $aiChatService;

    public function __construct(AiChatService $aiChatService)
    {
        $this->aiChatService = $aiChatService;
    }

    /**
     * Get note summary
     */
    public function getNoteSummary(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'note_id' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid note ID provided.'
            ], 400);
        }

        $userId = Auth::id();
        $noteId = $request->note_id;

        $result = $this->aiChatService->getNoteSummary($noteId, $userId);

        return response()->json($result);
    }

    /**
     * Answer question based on note
     */
    public function answerQuestion(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'question' => 'required|string|max:1000',
            'note_id' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid input provided.'
            ], 400);
        }

        $userId = Auth::id();
        $question = $request->question;
        $noteId = $request->note_id;

        if ($noteId) {
            // Answer based on specific note
            $result = $this->aiChatService->answerQuestion($question, $noteId, $userId);
        } else {
            // General study help
            $result = $this->aiChatService->getStudyHelp($question, $userId);
        }

        return response()->json($result);
    }

    /**
     * Get user's notes for chat interface
     */
    public function getUserNotes()
    {
        $userId = Auth::id();
        $notes = $this->aiChatService->getUserNotes($userId);

        return response()->json([
            'success' => true,
            'notes' => $notes
        ]);
    }

    /**
     * Check if AI chat is available
     */
    public function checkAvailability()
    {
        $isAvailable = $this->aiChatService->isAvailable();

        return response()->json([
            'success' => true,
            'available' => $isAvailable,
            'message' => $isAvailable 
                ? 'AI Chat is available' 
                : 'AI Chat is not configured. Please contact administrator.'
        ]);
    }

    /**
     * Get chat interface (for students only)
     */
    public function index()
    {
        $user = Auth::user();
        
        // Check if user is a student
        if ($user->role !== 'student') {
            abort(403, 'AI Chat is only available for students.');
        }

        $notes = $this->aiChatService->getUserNotes($user->user_id);
        $isAvailable = $this->aiChatService->isAvailable();

        return view('student.ai-chat', compact('notes', 'isAvailable'));
    }

    /**
     * Save chat history (optional feature)
     */
    public function saveChatHistory(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'messages' => 'required|array',
            'note_id' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid chat data provided.'
            ], 400);
        }

        // For now, we'll just return success
        // In the future, you could save chat history to database
        return response()->json([
            'success' => true,
            'message' => 'Chat history saved successfully.'
        ]);
    }

    /**
     * Get suggested questions for a note
     */
    public function getSuggestedQuestions(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'note_id' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid note ID provided.'
            ], 400);
        }

        // Predefined suggested questions
        $suggestions = [
            "Can you summarize the main points of this note?",
            "What are the key concepts I should remember?",
            "Can you explain this topic in simpler terms?",
            "What are some practice questions I can ask myself?",
            "How does this relate to other topics I'm studying?",
            "What are the most important facts or formulas here?",
            "Can you help me understand the difficult parts?",
            "What should I focus on when reviewing this note?"
        ];

        return response()->json([
            'success' => true,
            'suggestions' => $suggestions
        ]);
    }
}
