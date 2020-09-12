<?php

namespace  App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subject extends Model {
    use SoftDeletes;

    protected $table = 'subjects';

    protected $fillable = [
        'user_id',
        'name',
    ];

    protected $casts = [
        'user_id'      => 'integer',
        'name'         => 'string',
    ];


    public function userSubjects()
    {
        return $this->hasMany('App\Models\UserSubject', 'subject_id');
    }
}
