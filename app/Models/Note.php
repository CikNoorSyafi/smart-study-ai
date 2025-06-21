<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Note extends Model
{
    use HasFactory;

    /**
     * The primary key associated with the table.
     */
    protected $primaryKey = 'note_id';

    /**
     * The "type" of the primary key ID.
     */
    protected $keyType = 'string';

    /**
     * Indicates if the IDs are auto-incrementing.
     */
    public $incrementing = false;

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName()
    {
        return 'note_id';
    }

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'note_id',
        'user_id',
        'title',
        'content',
        'status',
        'file_path',
        'file_name',
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
            if (empty($model->note_id)) {
                $model->note_id = (string) Str::uuid();
            }
        });
    }

    /**
     * Get the user that owns the note.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    /**
     * Get the questions for the note.
     */
    public function questions(): HasMany
    {
        return $this->hasMany(Question::class, 'note_id', 'note_id');
    }

    /**
     * Get the subjects associated with the note.
     */
    public function subjects(): BelongsToMany
    {
        return $this->belongsToMany(Subject::class, 'note_subjects', 'note_id', 'subject_id', 'note_id', 'subject_id');
    }

    /**
     * Scope a query to only include published notes.
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    /**
     * Scope a query to only include draft notes.
     */
    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    /**
     * Get the note's excerpt.
     */
    public function getExcerptAttribute()
    {
        return Str::limit(strip_tags($this->content), 150);
    }

    /**
     * Get the note's word count.
     */
    public function getWordCountAttribute()
    {
        return str_word_count(strip_tags($this->content));
    }
}
