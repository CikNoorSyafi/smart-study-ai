<?php

namespace App\Services;

use App\Models\Note;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiChatService
{
    private $apiKey;
    private $baseUrl;
    private $model;

    public function __construct()
    {
        $this->apiKey = env('GROQ_API_KEY');
        $this->baseUrl = 'https://api.groq.com/openai/v1/chat/completions';
        $this->model = env('GROQ_MODEL', 'llama3-8b-8192');
    }

    /**
     * Get note summary using AI
     */
    public function getNoteSummary($noteId, $userId)
    {
        try {
            $note = Note::where('note_id', $noteId)
                       ->where('user_id', $userId)
                       ->first();

            if (!$note) {
                return [
                    'success' => false,
                    'message' => 'Note not found or access denied.'
                ];
            }

            $prompt = $this->buildSummaryPrompt($note);
            $response = $this->callGroqApi($prompt);

            return [
                'success' => true,
                'summary' => $response,
                'note_title' => $note->title
            ];

        } catch (\Exception $e) {
            Log::error('AI Chat Service Error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Failed to generate summary. Please try again.'
            ];
        }
    }

    /**
     * Answer question based on note content
     */
    public function answerQuestion($question, $noteId, $userId)
    {
        try {
            $note = Note::where('note_id', $noteId)
                       ->where('user_id', $userId)
                       ->first();

            if (!$note) {
                return [
                    'success' => false,
                    'message' => 'Note not found or access denied.'
                ];
            }

            $prompt = $this->buildQuestionPrompt($question, $note);
            $response = $this->callGroqApi($prompt);

            return [
                'success' => true,
                'answer' => $response,
                'note_title' => $note->title,
                'question' => $question
            ];

        } catch (\Exception $e) {
            Log::error('AI Chat Service Error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Failed to answer question. Please try again.'
            ];
        }
    }

    /**
     * Get general study help without specific note
     */
    public function getStudyHelp($question, $userId)
    {
        try {
            $user = User::find($userId);
            $userNotes = Note::where('user_id', $userId)->get();

            $prompt = $this->buildStudyHelpPrompt($question, $user, $userNotes);
            $response = $this->callGroqApi($prompt);

            return [
                'success' => true,
                'answer' => $response,
                'question' => $question
            ];

        } catch (\Exception $e) {
            Log::error('AI Chat Service Error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Failed to provide study help. Please try again.'
            ];
        }
    }

    /**
     * Build prompt for note summary
     */
    private function buildSummaryPrompt($note)
    {
        return "Please provide a comprehensive summary of the following note. Focus on key concepts, main points, and important details that a student should remember for studying.

Note Title: {$note->title}
Note Content: {$note->content}

Please provide a well-structured summary that includes:
1. Main topics covered
2. Key concepts and definitions
3. Important points to remember
4. Any formulas, dates, or specific facts mentioned

Keep the summary clear, concise, and educational.";
    }

    /**
     * Build prompt for question answering
     */
    private function buildQuestionPrompt($question, $note)
    {
        return "Based on the following note content, please answer the student's question. If the answer is not directly in the note, provide the best possible guidance based on the related content.

Note Title: {$note->title}
Note Content: {$note->content}

Student's Question: {$question}

Please provide a helpful, accurate answer based on the note content. If the question cannot be fully answered from the note, explain what information is available and suggest what additional resources might be needed.";
    }

    /**
     * Build prompt for general study help
     */
    private function buildStudyHelpPrompt($question, $user, $userNotes)
    {
        $notesList = $userNotes->pluck('title')->take(10)->implode(', ');
        
        return "You are an AI study assistant helping a student named {$user->name}. The student has the following notes: {$notesList}

Student's Question: {$question}

Please provide helpful study guidance, tips, or general educational assistance. If the question relates to topics that might be covered in their notes, suggest they check specific notes. Provide encouraging and educational responses that help with learning and studying.";
    }

    /**
     * Call Groq API
     */
    private function callGroqApi($prompt)
    {
        if (empty($this->apiKey)) {
            throw new \Exception('Groq API key not configured');
        }

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
        ])->timeout(30)->post($this->baseUrl, [
            'model' => $this->model,
            'messages' => [
                [
                    'role' => 'system',
                    'content' => 'You are a helpful AI study assistant. Provide clear, educational, and encouraging responses to help students learn better.'
                ],
                [
                    'role' => 'user',
                    'content' => $prompt
                ]
            ],
            'max_tokens' => 1000,
            'temperature' => 0.7,
        ]);

        if (!$response->successful()) {
            throw new \Exception('API request failed: ' . $response->body());
        }

        $data = $response->json();
        
        if (!isset($data['choices'][0]['message']['content'])) {
            throw new \Exception('Invalid API response format');
        }

        return trim($data['choices'][0]['message']['content']);
    }

    /**
     * Check if Groq API is available
     */
    public function isAvailable()
    {
        return !empty($this->apiKey);
    }

    /**
     * Get user's notes for chat interface
     */
    public function getUserNotes($userId)
    {
        return Note::where('user_id', $userId)
                  ->select('note_id', 'title', 'created_at')
                  ->orderBy('created_at', 'desc')
                  ->get();
    }
}
