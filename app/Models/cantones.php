<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class cantones extends Model
{
    use HasFactory;
    protected $table= 'canton';
    public $timestamps = false;
    protected $fillable = [
        'cantones',
        'imagen',
        'eliminado',
        'provincia_id'
    ];
}
