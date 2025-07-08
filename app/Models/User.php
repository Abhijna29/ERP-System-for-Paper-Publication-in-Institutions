<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * @method \App\Models\Subscription|null activeSubscription()
 */
class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'mobile_number',
        'force_password_reset',
        'institution_id',
        'department_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function researchPapers()
    {
        return $this->hasMany(ResearchPaper::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, "reviewer_id");
    }

    public function bookChapterReviews()
    {
        return $this->hasMany(BookChapterReviews::class, "reviewer_id");
    }
    public function department()
    {
        return $this->belongsTo(User::class, 'department_id');
    }
    public function institution()
    {
        return $this->belongsTo(User::class, 'institution_id')->where('role', 'institution');
    }
    public function researchers()
    {
        return $this->hasMany(User::class, 'institution_id');
    }

    //Subscriptions:
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function activeSubscription()
    {
        return $this->subscriptions()->where('starts_at', '<=', now())
            ->where('ends_at', '>=', now())
            ->latest()->first();
    }

    public function hasActiveSubscription()
    {
        return $this->activeSubscription() !== null;
    }

    public function downloads()
    {
        return $this->hasMany(Download::class);
    }

    //Book Chapters
    public function chapterReviews()
    {
        return $this->hasMany(BookChapterReviews::class, 'reviewer_id');
    }
}
