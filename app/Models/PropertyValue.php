<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PropertyValue extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
    	'property_id',
    	'name',
    ];

    public function property()
    {
    	return $this->belongsTo(Property::class);
    }

    public function characters()
    {
    	return $this->belongsToMany(Character::class);
    }
}
