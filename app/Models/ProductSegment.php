<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductSegment extends Model{

    use HasFactory;

    protected $fillable = ['product_id', 'segment_id', 'segment_price'];

    public function segment (){
        return $this->belongsTo(Segment::class, 'segment_id');
    } 
}