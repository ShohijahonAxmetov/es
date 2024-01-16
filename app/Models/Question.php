<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Question extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
    	'property_id',
    	'question',
    ];

    public function property()
    {
    	return $this->belongsTo(Property::class);
    }

    public function answers()
    {
    	return $this->belongsToMany(Answer::class);
    }
}
