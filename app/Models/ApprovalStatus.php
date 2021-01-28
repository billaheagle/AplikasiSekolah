<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApprovalStatus extends Model
{
    use HasFactory;
    protected $table = 'ref_approval_status';
    protected $fillable = ['code', 'name', 'desc', 'maker', 'modifier'];

    public function maker() {
    	return $this->belongsTo('App\Models\User', 'maker');
    }
    
    public function modifier() {
    	return $this->belongsTo('App\Models\User', 'modifier');
	}
}
