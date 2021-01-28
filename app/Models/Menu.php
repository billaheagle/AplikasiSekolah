<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    public $timestamps = false;
  	protected $table = 'menus';
    protected $fillable = ['parent_id', 'model_name', 'icon', 'title', 'url', 'maker', 'modifier'];

    public function parent() {
    	return $this->belongsTo('App\Models\Menu', 'parent_id');
	}

	public function children() {
    	return $this->hasMany('App\Models\Menu', 'parent_id');
	}

    public function role() {
        return $this->belongsToMany('Spatie\Permission\Models\Role', 'menu_role', 'menu_id', 'role_id')->using('App\Models\MenuRole');
    }

    public function maker() {
        return $this->belongsTo('App\Models\User', 'maker');
    }

    public function modifier() {
        return $this->belongsTo('App\Models\User', 'modifier');
    }
}
