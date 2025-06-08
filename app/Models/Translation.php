<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Translation extends Model
{
    public function session() {
        return $this->belongsTo(TranslationSession::class);
    }
}
