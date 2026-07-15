<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IncomingLetter extends Model
{
    protected $fillable = [
        'letter_date',
        'sender',
        'letter_number',
        'classification',
        'attachment',
        'subject',
        'file_path',
    ];
}
