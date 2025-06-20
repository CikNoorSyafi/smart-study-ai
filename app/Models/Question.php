<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Question extends Model
{
    use HasFactory;

    /**
     * The primary key associated with the table.
     */
    protected $primaryKey = 'question_id';

    /**
     * The "type" of the primary key ID.
     */
    protected $keyType = 'string';

    /**
     * Indicates if the IDs are auto-incrementing.
     */
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'question_id',
        'note_id',
        'user_id',
        'created_by',
        'question_text',
        'question_type',
        'difficulty_level',
        'difficulty',
        'generated_by',
        'explanation',
        'status',
        'approved_at',
        'approved_by',
    ];

    /**
     * The attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->question_id)) {
                $model->question_id = (string) Str::uuid();
            }
        });
    }

    /**
     * Get the note that owns the question.
     */
    public function note(): BelongsTo
    {
        return $this->belongsTo(Note::class, 'note_id', 'note_id');
    }

    /**
     * Get the user that owns the question.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    /**
     * Get the answers for the question.
     */
    public function answers(): HasMany
    {
        return $this->hasMany(Answer::class, 'question_id', 'question_id');
    }

    /**
     * Get the feedback for the question.
     */
    public function feedback(): HasMany
    {
        return $this->hasMany(Feedback::class, 'question_id', 'question_id');
    }

    /**
     * Get the correct answer for the question.
     */
    public function correctAnswer()
    {
        return $this->answers()->where('is_correct', true)->first();
    }

    /**
     * Scope a query to only include AI-generated questions.
     */
    public function scopeAiGenerated($query)
    {
        return $query->where('generated_by', 'AI');
    }

    /**
     * Scope a query to only include manually created questions.
     */
    public function scopeManual($query)
    {
        return $query->where('generated_by', 'Manual');
    }

    /**
     * Scope a query to filter by difficulty.
     */
    public function scopeByDifficulty($query, $difficulty)
    {
        return $query->where('difficulty', $difficulty);
    }

    /**
     * Check if the question was generated by AI.
     */
    public function isAiGenerated(): bool
    {
        return $this->generated_by === 'AI';
    }

    /**
     * Get the question's character count.
     */
    public function getCharacterCountAttribute()
    {
        return strlen($this->question_text);
    }

    /**
     * Get the difficulty level (alias for difficulty).
     */
    public function getDifficultyLevelAttribute()
    {
        return $this->difficulty;
    }

    /**
     * Set the difficulty level (alias for difficulty).
     */
    public function setDifficultyLevelAttribute($value)
    {
        $this->attributes['difficulty'] = $value;
    }

    /**
     * Get the created by (alias for user_id).
     */
    public function getCreatedByAttribute()
    {
        return $this->user_id;
    }

    /**
     * Set the created by (alias for user_id).
     */
    public function setCreatedByAttribute($value)
    {
        $this->attributes['user_id'] = $value;
    }
}
