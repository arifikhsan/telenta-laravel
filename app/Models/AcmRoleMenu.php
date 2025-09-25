<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AcmRoleMenu extends Model
{
    public function role() {
        return $this->belongsTo(Role::class);
    }

    public function acmMenu() {
        return $this->belongsTo(AcmMenu::class);
    }
}
