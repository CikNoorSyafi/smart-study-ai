# Groq AI Question Generator Integration

## Overview

This integration adds Groq AI as an alternative question generator alongside the existing local generator. Users can now choose between two generators when uploading PDFs:

1. **Local Generator** - Built-in pattern-based question generation
2. **Groq AI Generator** - Advanced AI-powered question generation using Groq's language models

## Features

### Local Generator
- ✅ Fast generation
- ✅ No API costs
- ✅ Offline capability
- ✅ Pattern-based analysis
- ✅ Reliable performance

### Groq AI Generator
- ✅ Advanced AI analysis
- ✅ Natural language understanding
- ✅ Context-aware questions
- ✅ High-quality output
- ✅ Multiple model options

## Setup Instructions

### 1. Get Groq API Key
1. Visit [Groq Console](https://console.groq.com/)
2. Sign up for a free account
3. Generate an API key

### 2. Configure Environment
Add these variables to your `.env` file:

```env
# Groq AI Configuration
GROQ_API_KEY=your_groq_api_key_here
GROQ_MODEL=llama3-8b-8192
```

### 3. Available Models
You can choose from these Groq models:

- `llama3-8b-8192` (Default, fast and efficient)
- `llama3-70b-8192` (More powerful, slower)
- `mixtral-8x7b-32768` (Good balance of speed and quality)
- `gemma-7b-it` (Google's Gemma model)

## Usage

### PDF Upload Interface
1. Navigate to `/pdf-upload`
2. Upload your PDF file
3. Configure question settings
4. **Select Generator Type:**
   - Choose "Local Generator" for fast, offline generation
   - Choose "Groq AI Generator" for advanced AI-powered generation
5. Click "Generate Questions"

### API Endpoints
The integration provides several API endpoints:

```php
// Test generator connection
POST /api/question-generator/test
{
    "generator_type": "groq" // or "local" or "both"
}

// Generate questions
POST /api/question-generator/generate
{
    "generator_type": "groq",
    "text": "Your text content...",
    "question_count": 10,
    "difficulty": "medium",
    "question_types": ["multiple_choice", "true_false"]
}

// Compare generators
POST /api/question-generator/compare
{
    "text": "Your text content...",
    "question_count": 5,
    "difficulty": "medium",
    "question_types": ["multiple_choice"]
}
```

## Implementation Details

### Files Added/Modified

#### New Files:
- `app/Services/GroqQuestionGenerator.php` - Groq AI service
- `app/Http/Controllers/QuestionGeneratorController.php` - Generator management

#### Modified Files:
- `app/Http/Controllers/PdfUploadController.php` - Added generator selection
- `resources/views/pdf-upload/index.blade.php` - Added UI for generator choice
- `routes/web.php` - Added API routes
- `.env.example` - Added Groq configuration

### Question Format
Both generators produce the same output format:

```php
[
    'question' => 'What is machine learning?',
    'type' => 'multiple_choice',
    'answers' => [
        ['text' => 'Correct answer', 'is_correct' => true],
        ['text' => 'Wrong answer 1', 'is_correct' => false],
        ['text' => 'Wrong answer 2', 'is_correct' => false],
        ['text' => 'Wrong answer 3', 'is_correct' => false]
    ]
]
```

### Error Handling
- Groq API failures automatically fall back to local generation
- Connection status is checked before allowing Groq selection
- Comprehensive error logging for debugging

## Benefits

### For Users
- **Choice**: Select the best generator for their needs
- **Quality**: Access to advanced AI capabilities
- **Reliability**: Fallback ensures questions are always generated
- **Transparency**: Clear indication of which generator was used

### For Administrators
- **Flexibility**: Can disable Groq if API costs are a concern
- **Monitoring**: Track usage of each generator type
- **Performance**: Compare quality between generators
- **Scalability**: Easy to add more generators in the future

## Troubleshooting

### Common Issues

#### Groq API Not Available
- Check if `GROQ_API_KEY` is set in `.env`
- Verify API key is valid
- Check internet connection
- Verify Groq service status

#### Questions Not Generated
- Check Laravel logs for errors
- Verify text content is not empty
- Ensure question types are valid
- Check API rate limits

#### Poor Question Quality
- Try different Groq models
- Adjust difficulty settings
- Ensure source text is well-formatted
- Consider using local generator for comparison

### Debug Commands
```bash
# Test Groq connection
php artisan tinker
>>> app(App\Services\GroqQuestionGenerator::class)->testConnection()

# Check environment variables
php artisan config:show | grep GROQ
```

## Future Enhancements

### Planned Features
- Model selection in UI
- Generator performance metrics
- Batch question generation
- Custom prompt templates
- Question quality scoring

### Possible Integrations
- OpenAI GPT models
- Anthropic Claude
- Google Gemini
- Local LLM models

## Cost Considerations

### Groq Pricing
- Free tier available
- Pay-per-token pricing
- Generally more affordable than OpenAI
- Check current pricing at [Groq Pricing](https://groq.com/pricing/)

### Optimization Tips
- Use appropriate model for task complexity
- Implement caching for repeated content
- Monitor usage to avoid unexpected costs
- Set up usage alerts

## Security

### Best Practices
- Store API keys securely in `.env`
- Never commit API keys to version control
- Implement rate limiting
- Monitor API usage
- Validate all inputs before sending to API

### Data Privacy
- Text content is sent to Groq's servers
- Review Groq's privacy policy
- Consider data sensitivity before using
- Implement data retention policies

## Support

For issues with this integration:
1. Check Laravel logs
2. Verify Groq API status
3. Test with local generator first
4. Review configuration settings
5. Check network connectivity

For Groq-specific issues:
- Visit [Groq Documentation](https://console.groq.com/docs)
- Check [Groq Status Page](https://status.groq.com/)
- Contact Groq support if needed
