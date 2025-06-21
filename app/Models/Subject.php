<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Subject extends Model
{
    use HasFactory;

    /**
     * The primary key associated with the table.
     */
    protected $primaryKey = 'subject_id';

    /**
     * The "type" of the primary key ID.
     */
    protected $keyType = 'string';

    /**
     * Indicates if the IDs are auto-incrementing.
     */
    public $incrementing = false;

    /**
     * Indicates if the model should be timestamped.
     */
    public $timestamps = false;

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName()
    {
        return 'subject_id';
    }

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'subject_id',
        'name',
        'description',
        'form_level',
        'category',
        'subject_code',
    ];

    /**
     * The attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
        ];
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->subject_id)) {
                $model->subject_id = (string) Str::uuid();
            }
        });
    }

    /**
     * Get the users associated with the subject.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_subjects', 'subject_id', 'user_id', 'subject_id', 'user_id')
                    ->withPivot('role_in_subject', 'level');
    }

    /**
     * Get the notes associated with the subject.
     */
    public function notes(): BelongsToMany
    {
        return $this->belongsToMany(Note::class, 'note_subjects', 'subject_id', 'note_id', 'subject_id', 'note_id');
    }

    /**
     * Get teachers for this subject.
     */
    public function teachers()
    {
        return $this->users()->wherePivot('role_in_subject', 'teacher');
    }

    /**
     * Get students for this subject.
     */
    public function students()
    {
        return $this->users()->wherePivot('role_in_subject', 'student');
    }

    /**
     * Get the total number of notes for this subject.
     */
    public function getNotesCountAttribute()
    {
        return $this->notes()->count();
    }

    /**
     * Get the total number of users for this subject.
     */
    public function getUsersCountAttribute()
    {
        return $this->users()->count();
    }
}
