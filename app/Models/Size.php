<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Size extends Model
{
    protected $fillable = [
        'body_color',
        'color_temp',
        'beam_angle',
        'cut_out',
        'reflector_color'
    ];

    // Cast these fields to arrays automatically
    protected $casts = [
        'body_color' => 'array',
        'color_temp' => 'array',
        'beam_angle' => 'array',
        'cut_out' => 'array',
        'reflector_color' => 'array',
    ];
}
