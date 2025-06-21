<?php

namespace App\Services;

use App\Models\Question;
use App\Models\Answer;
use App\Models\Note;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class IntelligentQuestionGenerator
{
    private $subjectPatterns;
    private $questionTemplates;
    private $malaysianContext;

    public function __construct()
    {
        $this->initializeSubjectPatterns();
        $this->initializeQuestionTemplates();
        $this->initializeMalaysianContext();
    }

    /**
     * Generate intelligent questions from note content
     */
    public function generateFromNote(Note $note, $count = 5, $difficulty = 'medium')
    {
        $content = $note->content;
        $title = $note->title;
        
        // Analyze content to understand subject and context
        $analysis = $this->analyzeContent($content, $title);
        
        // Generate questions based on analysis
        $questions = $this->generateContextualQuestions($analysis, $count, $difficulty);
        
        // Save questions to database
        $savedQuestions = [];
        foreach ($questions as $questionData) {
            $question = $this->saveQuestion($note, $questionData, $difficulty);
            if ($question) {
                $savedQuestions[] = $question;
            }
        }
        
        return $savedQuestions;
    }

    /**
     * Analyze content to understand subject and extract key information
     */
    private function analyzeContent($content, $title)
    {
        $analysis = [
            'subject' => $this->detectSubject($content, $title),
            'key_concepts' => $this->extractKeyConcepts($content),
            'facts' => $this->extractFacts($content),
            'definitions' => $this->extractDefinitions($content),
            'numbers' => $this->extractNumbers($content),
            'dates' => $this->extractDates($content),
            'names' => $this->extractNames($content),
            'processes' => $this->extractProcesses($content),
            'content_type' => $this->determineContentType($content, $title)
        ];

        return $analysis;
    }

    /**
     * Detect the subject based on content and title
     */
    private function detectSubject($content, $title)
    {
        $text = strtolower($content . ' ' . $title);
        
        foreach ($this->subjectPatterns as $subject => $patterns) {
            foreach ($patterns as $pattern) {
                if (strpos($text, $pattern) !== false) {
                    return $subject;
                }
            }
        }
        
        return 'general';
    }

    /**
     * Extract key concepts from content
     */
    private function extractKeyConcepts($content)
    {
        $concepts = [];

        // Remove common words that aren't concepts
        $commonWords = ['This', 'That', 'These', 'Those', 'The', 'A', 'An', 'And', 'Or', 'But', 'For', 'With', 'From', 'To', 'In', 'On', 'At', 'By'];

        // Look for important nouns and noun phrases (2-4 words)
        preg_match_all('/\b[A-Z][a-z]+(?:\s+[a-z]+){0,3}\b/', $content, $matches);
        foreach ($matches[0] as $match) {
            $words = explode(' ', $match);
            if (!in_array($words[0], $commonWords) && strlen($match) > 3) {
                $concepts[] = $match;
            }
        }

        // Look for terms in quotes or emphasis
        preg_match_all('/"([^"]+)"/', $content, $quoted);
        $concepts = array_merge($concepts, $quoted[1]);

        // Look for definition patterns
        preg_match_all('/(\w+)\s+(?:is|are|means?|refers? to)\s+([^.!?]+)/', $content, $definitions);
        foreach ($definitions[1] as $term) {
            if (!in_array($term, $commonWords) && strlen($term) > 2) {
                $concepts[] = $term;
            }
        }

        // Look for technical terms (words with specific patterns)
        preg_match_all('/\b[a-z]+(?:tion|sion|ment|ness|ity|ism|ology|graphy)\b/i', $content, $technical);
        $concepts = array_merge($concepts, $technical[0]);

        return array_unique(array_slice($concepts, 0, 15));
    }

    /**
     * Extract factual statements
     */
    private function extractFacts($content)
    {
        $facts = [];
        $sentences = preg_split('/[.!?]+/', $content);
        
        foreach ($sentences as $sentence) {
            $sentence = trim($sentence);
            if (strlen($sentence) > 20 && strlen($sentence) < 150) {
                // Look for factual patterns
                if (preg_match('/\b(?:is|are|was|were|has|have|contains?|includes?)\b/i', $sentence)) {
                    $facts[] = $sentence;
                }
            }
        }
        
        return array_slice($facts, 0, 10);
    }

    /**
     * Extract definitions
     */
    private function extractDefinitions($content)
    {
        $definitions = [];
        
        // Pattern: "Term is/means definition"
        preg_match_all('/(\w+)\s+(?:is|means?|refers? to)\s+([^.!?]+)/i', $content, $matches, PREG_SET_ORDER);
        
        foreach ($matches as $match) {
            if (strlen($match[2]) > 10) {
                $definitions[] = [
                    'term' => trim($match[1]),
                    'definition' => trim($match[2])
                ];
            }
        }
        
        return array_slice($definitions, 0, 5);
    }

    /**
     * Extract numbers and quantities
     */
    private function extractNumbers($content)
    {
        $numbers = [];
        
        // Look for numbers with context
        preg_match_all('/(\d+(?:\.\d+)?)\s*([a-zA-Z%]+)/', $content, $matches, PREG_SET_ORDER);
        
        foreach ($matches as $match) {
            $numbers[] = [
                'value' => $match[1],
                'unit' => $match[2],
                'context' => $match[0]
            ];
        }
        
        return array_slice($numbers, 0, 5);
    }

    /**
     * Extract dates
     */
    private function extractDates($content)
    {
        $dates = [];
        
        // Various date patterns
        $patterns = [
            '/\b(\d{1,2})\s+(January|February|March|April|May|June|July|August|September|October|November|December)\s+(\d{4})\b/i',
            '/\b(\d{4})\b/',
            '/\b(\d{1,2})\/(\d{1,2})\/(\d{4})\b/'
        ];
        
        foreach ($patterns as $pattern) {
            preg_match_all($pattern, $content, $matches);
            $dates = array_merge($dates, $matches[0]);
        }
        
        return array_unique(array_slice($dates, 0, 5));
    }

    /**
     * Extract names (people, places, etc.)
     */
    private function extractNames($content)
    {
        $names = [];
        
        // Look for proper nouns (capitalized words)
        preg_match_all('/\b[A-Z][a-z]+(?:\s+[A-Z][a-z]+)*\b/', $content, $matches);
        
        // Filter out common words
        $commonWords = ['The', 'This', 'That', 'These', 'Those', 'When', 'Where', 'What', 'Who', 'Why', 'How'];
        $names = array_diff($matches[0], $commonWords);
        
        return array_slice($names, 0, 10);
    }

    /**
     * Extract processes or procedures
     */
    private function extractProcesses($content)
    {
        $processes = [];
        
        // Look for step-by-step patterns
        preg_match_all('/(?:first|second|third|then|next|finally|step \d+)[^.!?]*[.!?]/i', $content, $matches);
        $processes = array_merge($processes, $matches[0]);
        
        // Look for process verbs
        preg_match_all('/\b(?:calculate|solve|determine|find|identify|analyze|compare|explain|describe)[^.!?]*[.!?]/i', $content, $matches);
        $processes = array_merge($processes, $matches[0]);
        
        return array_slice($processes, 0, 5);
    }

    /**
     * Determine the type of content
     */
    private function determineContentType($content, $title)
    {
        $text = strtolower($content . ' ' . $title);
        
        if (preg_match('/\b(?:formula|equation|calculate|solve|x\s*=|answer)/i', $text)) {
            return 'mathematical';
        } elseif (preg_match('/\b(?:definition|meaning|concept|theory)/i', $text)) {
            return 'conceptual';
        } elseif (preg_match('/\b(?:history|date|year|century|period)/i', $text)) {
            return 'historical';
        } elseif (preg_match('/\b(?:process|step|procedure|method)/i', $text)) {
            return 'procedural';
        } elseif (preg_match('/\b(?:fact|data|statistic|number)/i', $text)) {
            return 'factual';
        }
        
        return 'general';
    }

    /**
     * Generate contextual questions based on analysis
     */
    private function generateContextualQuestions($analysis, $count, $difficulty)
    {
        $questions = [];
        $subject = $analysis['subject'];
        $contentType = $analysis['content_type'];

        // Create a pool of potential questions
        $questionPool = [];

        // 1. Definition questions
        foreach ($analysis['definitions'] as $def) {
            $questionPool[] = $this->createDefinitionQuestion($def, $difficulty, $subject);
        }

        // 2. Fact-based questions
        foreach ($analysis['facts'] as $fact) {
            $questionPool[] = $this->createImprovedFactQuestion($fact, $difficulty, $subject);
        }

        // 3. Concept questions
        foreach ($analysis['key_concepts'] as $concept) {
            if (strlen($concept) > 3 && !in_array(strtolower($concept), ['this', 'that', 'these', 'those'])) {
                $questionPool[] = $this->createImprovedConceptQuestion($concept, $analysis, $difficulty, $subject);
            }
        }

        // 4. Number/calculation questions
        foreach ($analysis['numbers'] as $number) {
            $questionPool[] = $this->createNumberQuestion($number, $difficulty, $subject);
        }

        // 5. Date/historical questions
        foreach ($analysis['dates'] as $date) {
            $questionPool[] = $this->createDateQuestion($date, $analysis, $difficulty, $subject);
        }

        // 6. Content-based comprehension questions
        $questionPool[] = $this->createComprehensionQuestion($analysis, $difficulty, $subject);
        $questionPool[] = $this->createApplicationQuestion($analysis, $difficulty, $subject);

        // Filter out poor quality questions
        $questionPool = array_filter($questionPool, function($q) {
            return $q &&
                   strlen($q['question_text']) > 10 &&
                   !preg_match('/\b(this|that|these|those)\s*\?/i', $q['question_text']) &&
                   !preg_match('/what.*mentioned.*about.*\?/i', $q['question_text']);
        });

        // Shuffle and select the best questions
        shuffle($questionPool);
        $questions = array_slice($questionPool, 0, $count);

        // If we don't have enough good questions, create some general ones
        while (count($questions) < $count) {
            $questions[] = $this->createBetterGeneralQuestion($analysis, $difficulty, $subject);
        }

        return array_slice($questions, 0, $count);
    }

    /**
     * Create a definition question
     */
    private function createDefinitionQuestion($definition, $difficulty, $subject)
    {
        $term = $definition['term'];
        $def = $definition['definition'];
        
        $templates = $this->questionTemplates['definition'][$difficulty];
        $template = $templates[array_rand($templates)];
        
        $questionText = str_replace('{term}', $term, $template);
        
        // Create multiple choice answers
        $correctAnswer = $def;
        $wrongAnswers = $this->generateWrongDefinitions($term, $subject);
        
        return [
            'question_text' => $questionText,
            'question_type' => 'multiple_choice',
            'explanation' => "The correct definition of {$term} is: {$def}",
            'answers' => $this->formatAnswers($correctAnswer, $wrongAnswers)
        ];
    }

    /**
     * Create an improved fact-based question
     */
    private function createImprovedFactQuestion($fact, $difficulty, $subject)
    {
        // Clean up the fact and extract meaningful information
        $fact = trim($fact);
        if (strlen($fact) < 20) return null;

        // Try to identify what the fact is about
        if (preg_match('/(.+?)\s+(?:is|are|was|were|has|have|contains?|includes?)\s+(.+)/i', $fact, $matches)) {
            $subject_part = trim($matches[1]);
            $predicate = trim($matches[2]);

            if (strlen($subject_part) > 3 && strlen($predicate) > 5) {
                $questionText = "What can you tell me about {$subject_part}?";

                return [
                    'question_text' => $questionText,
                    'question_type' => 'short_answer',
                    'explanation' => "Based on the content: {$fact}",
                    'answers' => [
                        ['text' => $predicate, 'is_correct' => true]
                    ]
                ];
            }
        }

        return null;
    }

    /**
     * Create an improved concept question
     */
    private function createImprovedConceptQuestion($concept, $analysis, $difficulty, $subject)
    {
        if (strlen($concept) < 4) return null;

        $questionTemplates = [
            "What is the importance of {$concept} in this context?",
            "How does {$concept} relate to the main topic?",
            "Explain the role of {$concept} in the subject matter.",
            "What are the key characteristics of {$concept}?",
            "Why is {$concept} significant in this study?"
        ];

        $template = $questionTemplates[array_rand($questionTemplates)];
        $questionText = str_replace('{concept}', $concept, $template);

        return [
            'question_text' => $questionText,
            'question_type' => 'essay',
            'explanation' => "This question tests understanding of the key concept: {$concept}",
            'answers' => [
                ['text' => "Comprehensive explanation about {$concept} based on the content", 'is_correct' => true]
            ]
        ];
    }

    /**
     * Create a comprehension question
     */
    private function createComprehensionQuestion($analysis, $difficulty, $subject)
    {
        $questionTemplates = [
            "What is the main purpose of this content?",
            "Summarize the key points discussed in this material.",
            "What are the most important concepts covered?",
            "How would you explain this topic to someone else?",
            "What conclusions can be drawn from this information?"
        ];

        $questionText = $questionTemplates[array_rand($questionTemplates)];

        return [
            'question_text' => $questionText,
            'question_type' => 'essay',
            'explanation' => "This question tests overall comprehension of the content.",
            'answers' => [
                ['text' => "A comprehensive summary covering the main points and concepts", 'is_correct' => true]
            ]
        ];
    }

    /**
     * Create an application question
     */
    private function createApplicationQuestion($analysis, $difficulty, $subject)
    {
        $questionTemplates = [
            "How can the information in this content be applied in real life?",
            "What practical examples can you give related to this topic?",
            "How might this knowledge be useful in solving problems?",
            "What connections can you make between this content and other subjects?",
            "How would you use this information in a practical situation?"
        ];

        $questionText = $questionTemplates[array_rand($questionTemplates)];

        return [
            'question_text' => $questionText,
            'question_type' => 'essay',
            'explanation' => "This question tests the ability to apply knowledge from the content.",
            'answers' => [
                ['text' => "Practical applications and examples based on the content", 'is_correct' => true]
            ]
        ];
    }

    /**
     * Create a better general question
     */
    private function createBetterGeneralQuestion($analysis, $difficulty, $subject)
    {
        $concepts = array_filter($analysis['key_concepts'], function($c) {
            return strlen($c) > 3 && !in_array(strtolower($c), ['this', 'that', 'these', 'those']);
        });

        if (!empty($concepts)) {
            $concept = $concepts[array_rand($concepts)];
            return $this->createImprovedConceptQuestion($concept, $analysis, $difficulty, $subject);
        }

        return $this->createComprehensionQuestion($analysis, $difficulty, $subject);
    }

    /**
     * Save question to database
     */
    private function saveQuestion($note, $questionData, $difficulty)
    {
        try {
            $question = Question::create([
                'question_id' => (string) Str::uuid(),
                'note_id' => $note->note_id,
                'user_id' => $note->user_id,
                'question_text' => $questionData['question_text'],
                'question_type' => $questionData['question_type'],
                'difficulty' => $difficulty,
                'generated_by' => 'AI',
                'explanation' => $questionData['explanation'] ?? null,
            ]);

            // Save answers
            foreach ($questionData['answers'] as $answerData) {
                Answer::create([
                    'answer_id' => (string) Str::uuid(),
                    'question_id' => $question->question_id,
                    'answer_text' => $answerData['text'],
                    'is_correct' => $answerData['is_correct'],
                    'explanation' => $answerData['explanation'] ?? null,
                ]);
            }

            return $question;
        } catch (\Exception $e) {
            Log::error('Failed to save generated question: ' . $e->getMessage());
            return null;
        }
    }

    // Helper methods for formatting answers, generating wrong answers, etc.
    private function formatAnswers($correct, $wrong)
    {
        $answers = [['text' => $correct, 'is_correct' => true]];
        
        foreach ($wrong as $w) {
            $answers[] = ['text' => $w, 'is_correct' => false];
        }
        
        shuffle($answers);
        return $answers;
    }

    private function generateWrongDefinitions($term, $subject)
    {
        // Generate plausible but incorrect definitions
        $wrong = [];
        
        if ($subject === 'mathematics') {
            $wrong = [
                "A type of mathematical operation",
                "A geometric shape or figure",
                "A numerical calculation method"
            ];
        } elseif ($subject === 'science') {
            $wrong = [
                "A chemical compound or element",
                "A biological process or function",
                "A physical property or phenomenon"
            ];
        } elseif ($subject === 'history') {
            $wrong = [
                "A historical period or era",
                "A political movement or system",
                "A cultural or social development"
            ];
        } else {
            $wrong = [
                "A general concept or idea",
                "A specific method or approach",
                "A particular type or category"
            ];
        }
        
        return array_slice($wrong, 0, 3);
    }

    // Initialize methods (continued in next part due to length limit)
    private function initializeSubjectPatterns()
    {
        $this->subjectPatterns = [
            'mathematics' => ['equation', 'formula', 'calculate', 'solve', 'algebra', 'geometry', 'trigonometry', 'calculus', 'statistics'],
            'science' => ['experiment', 'hypothesis', 'theory', 'molecule', 'atom', 'cell', 'DNA', 'evolution', 'physics', 'chemistry', 'biology'],
            'history' => ['century', 'year', 'period', 'war', 'independence', 'revolution', 'empire', 'dynasty', 'colonial'],
            'english' => ['grammar', 'literature', 'essay', 'poem', 'novel', 'author', 'character', 'plot', 'theme'],
            'geography' => ['climate', 'continent', 'country', 'river', 'mountain', 'population', 'capital', 'region']
        ];
    }

    private function initializeQuestionTemplates()
    {
        $this->questionTemplates = [
            'definition' => [
                'easy' => ['What is {term}?', 'Define {term}.'],
                'medium' => ['How would you define {term}?', 'What does {term} mean in this context?'],
                'hard' => ['Analyze the concept of {term}.', 'Explain the significance of {term}.']
            ],
            'fact' => [
                'easy' => ['What does the text say about {fact}?'],
                'medium' => ['According to the content, what is mentioned about {fact}?'],
                'hard' => ['Based on the information provided, analyze {fact}.']
            ]
        ];
    }

    private function initializeMalaysianContext()
    {
        $this->malaysianContext = [
            'subjects' => ['Bahasa Melayu', 'English', 'Mathematics', 'Science', 'History', 'Geography'],
            'levels' => ['Form 4', 'Form 5'],
            'exam' => 'SPM'
        ];
    }

    // Additional helper methods
    private function extractQuestionableElement($fact)
    {
        // Extract the most important part of a fact for questioning
        $words = explode(' ', $fact);
        return implode(' ', array_slice($words, 0, min(8, count($words))));
    }

    private function createConceptQuestion($concept, $analysis, $difficulty, $subject)
    {
        return [
            'question_text' => "What is the significance of {$concept} in this context?",
            'question_type' => 'short_answer',
            'explanation' => "This question tests understanding of the key concept: {$concept}",
            'answers' => [
                ['text' => "Explanation related to {$concept}", 'is_correct' => true]
            ]
        ];
    }

    private function createNumberQuestion($number, $difficulty, $subject)
    {
        return [
            'question_text' => "What is the significance of {$number['value']} {$number['unit']}?",
            'question_type' => 'short_answer',
            'explanation' => "This relates to the numerical data: {$number['context']}",
            'answers' => [
                ['text' => $number['context'], 'is_correct' => true]
            ]
        ];
    }

    private function createDateQuestion($date, $analysis, $difficulty, $subject)
    {
        return [
            'question_text' => "What happened in {$date}?",
            'question_type' => 'short_answer',
            'explanation' => "This question relates to the historical date mentioned in the content.",
            'answers' => [
                ['text' => "Event related to {$date}", 'is_correct' => true]
            ]
        ];
    }

    private function createGeneralQuestion($analysis, $difficulty, $subject)
    {
        $concepts = $analysis['key_concepts'];
        $concept = !empty($concepts) ? $concepts[array_rand($concepts)] : 'the main topic';
        
        return [
            'question_text' => "Explain the main points discussed about {$concept}.",
            'question_type' => 'essay',
            'explanation' => "This question tests overall understanding of the content.",
            'answers' => [
                ['text' => "Comprehensive explanation covering key points about {$concept}", 'is_correct' => true]
            ]
        ];
    }
}
