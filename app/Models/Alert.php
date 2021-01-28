<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alert extends Model
{
    use HasFactory;
    public $type = '';
    public $message = '';

    public function __construct($type, $message)
	{
	    $this->type = $type;
	    $this->message = $message;
	}
}
