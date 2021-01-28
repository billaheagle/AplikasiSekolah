<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserUnitAccess extends Model
{
    use HasFactory;
    protected $table = 'user_unit_accesses';

    protected $fillable = ['user_id', 'unit_id', 'maker', 'modifier'];

    public function user() {
    	return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function unit() {
    	return $this->belongsTo('App\Models\Unit', 'unit_id');
    }

    public function maker() {
    	return $this->belongsTo('App\Models\User', 'maker');
    }

    public function modifier() {
    	return $this->belongsTo('App\Models\User', 'modifier');
	}
}
