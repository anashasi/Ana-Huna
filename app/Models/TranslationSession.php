<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TranslationSession extends Model
{
    public function user() {
        return $this->belongsTo(User::class);
    }

    public function translations(){
        $this->hasMany(Translation::class);
    }
}
