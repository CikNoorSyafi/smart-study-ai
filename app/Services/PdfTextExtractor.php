<?php

namespace App\Services;

use Exception;
use Smalot\PdfParser\Parser;

class PdfTextExtractor
{
    /**
     * Extract text from PDF file
     */
    public function extractText($filePath)
    {
        try {
            // Method 1: Try using pdftotext (if available)
            if ($this->isPdfToTextAvailable()) {
                return $this->extractWithPdfToText($filePath);
            }

            // Method 2: Try using PHP PDF parser library
            return $this->extractWithPhpParser($filePath);

        } catch (Exception $e) {
            throw new Exception("Failed to extract text from PDF: " . $e->getMessage());
        }
    }

    /**
     * Check if pdftotext command is available
     */
    private function isPdfToTextAvailable()
    {
        $output = [];
        $returnCode = 0;
        exec('which pdftotext 2>/dev/null', $output, $returnCode);
        return $returnCode === 0;
    }

    /**
     * Extract text using pdftotext command
     */
    private function extractWithPdfToText($filePath)
    {
        $tempFile = tempnam(sys_get_temp_dir(), 'pdf_text_');
        $command = "pdftotext " . escapeshellarg($filePath) . " " . escapeshellarg($tempFile);
        
        exec($command, $output, $returnCode);
        
        if ($returnCode !== 0) {
            throw new Exception("pdftotext command failed");
        }

        $text = file_get_contents($tempFile);
        unlink($tempFile);

        return $this->cleanText($text);
    }

    /**
     * Extract text using PHP PDF parser (fallback method)
     */
    private function extractWithPhpParser($filePath)
    {
        try {
            // Use smalot/pdfparser library
            $parser = new Parser();
            $pdf = $parser->parseFile($filePath);
            $text = $pdf->getText();

            if (!empty($text)) {
                return $this->cleanText($text);
            }
        } catch (Exception $e) {
            // Fall back to basic extraction if library fails
        }

        // Basic fallback extraction for simple PDFs
        $content = file_get_contents($filePath);

        // Basic text extraction for simple PDFs
        $text = '';
        if (preg_match_all('/\(([^)]+)\)/', $content, $matches)) {
            $text = implode(' ', $matches[1]);
        }

        // If that doesn't work, try another pattern
        if (empty($text)) {
            if (preg_match_all('/BT\s*(.*?)\s*ET/s', $content, $matches)) {
                foreach ($matches[1] as $match) {
                    if (preg_match_all('/\[(.*?)\]/s', $match, $textMatches)) {
                        $text .= implode(' ', $textMatches[1]) . ' ';
                    }
                }
            }
        }

        // If still no text, try to extract readable strings
        if (empty($text)) {
            $text = $this->extractReadableStrings($content);
        }

        return $this->cleanText($text);
    }

    /**
     * Extract readable strings from PDF content
     */
    private function extractReadableStrings($content)
    {
        // Remove binary data and extract readable text
        $text = '';
        $lines = explode("\n", $content);
        
        foreach ($lines as $line) {
            // Look for lines that contain readable text
            if (preg_match('/[a-zA-Z]{3,}/', $line)) {
                // Extract words from the line
                if (preg_match_all('/[a-zA-Z][a-zA-Z0-9\s\.,;:!?\-]{2,}/', $line, $matches)) {
                    $text .= implode(' ', $matches[0]) . ' ';
                }
            }
        }

        return $text;
    }

    /**
     * Clean and normalize extracted text
     */
    private function cleanText($text)
    {
        if (empty($text)) {
            return '';
        }

        // Remove extra whitespace
        $text = preg_replace('/\s+/', ' ', $text);
        
        // Remove special characters that might interfere
        $text = preg_replace('/[^\w\s\.,;:!?\-()"]/', ' ', $text);
        
        // Remove multiple spaces
        $text = preg_replace('/\s+/', ' ', $text);
        
        // Trim
        $text = trim($text);

        // Ensure minimum length
        if (strlen($text) < 50) {
            throw new Exception("Extracted text is too short. The PDF might be image-based or corrupted.");
        }

        return $text;
    }

    /**
     * Get text statistics
     */
    public function getTextStats($text)
    {
        return [
            'character_count' => strlen($text),
            'word_count' => str_word_count($text),
            'sentence_count' => substr_count($text, '.') + substr_count($text, '!') + substr_count($text, '?'),
            'paragraph_count' => substr_count($text, "\n\n") + 1,
        ];
    }

    /**
     * Split text into chunks for processing
     */
    public function splitIntoChunks($text, $maxChunkSize = 2000)
    {
        $sentences = preg_split('/(?<=[.!?])\s+/', $text);
        $chunks = [];
        $currentChunk = '';

        foreach ($sentences as $sentence) {
            if (strlen($currentChunk . $sentence) > $maxChunkSize && !empty($currentChunk)) {
                $chunks[] = trim($currentChunk);
                $currentChunk = $sentence;
            } else {
                $currentChunk .= ' ' . $sentence;
            }
        }

        if (!empty($currentChunk)) {
            $chunks[] = trim($currentChunk);
        }

        return $chunks;
    }
}
