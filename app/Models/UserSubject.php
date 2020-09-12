<?php

namespace  App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserSubject extends Model {
    use SoftDeletes;

    protected $table = 'user_subjects';

    protected $fillable = [
        'user_id',
        'subject_id',
        'rating',
    ];

    protected $casts = [
        'user_id'      => 'integer',
        'subject_id'   => 'integer',
        'rating'       => 'integer',
    ];

    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id')->withTrashed();
    }

    public function subject()
    {
        return $this->hasOne('App\Models\Subject','id', 'subject_id')->withTrashed();
    }
}
