<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserExamStart extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $guarded = [];

    public $casts = [
        "start_at"=>"datetime:Y-m-d H:i:s",
        "end_at"=>"datetime:Y-m-d H:i:s",
    ];

    public function userExams()
    {
        return $this->hasMany(UserExam::class, 'user_id', 'user_id')
            ->where('exam_id', $this->exam_id);
    }

    public function exam(){
        return $this->belongsTo(Exam::class);
    }


    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    
}
