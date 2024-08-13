<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = ['action', 'employee_id', 'type'];

    public function employee(){
        return $this->belongsTo(Employee::class, 'employee_id');
    }
}