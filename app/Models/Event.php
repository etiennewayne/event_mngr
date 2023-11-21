<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $primaryKey = 'event_id';
    protected $table = 'events';

    protected $fillable = [
        'academic_year_id',
        'event',
        'content',
        'event_datetime',
        'img_path',
        'rating',
        'event_type',
        'approval_status'
    ];



    public function academic_year(){
        return $this->hasOne(AcademicYear::class, 'academic_year_id', 'academic_year_id');
    }

}
