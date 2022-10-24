<?php

namespace App\Models\Assessments;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JcpSkill extends Model
{
    use HasFactory;

    protected $fillable = [
        'j_c_p_id',
        'skill_id',
    ];

    protected $casts = [
        // Without this it will throw "Array to string conversion" error
        'skill_id' => 'array',
    ];

    public function skills() {
        return $this->belongsTo(Skill::class, 'skill_id');
    }

    public function jcp() {
        return $this->belongsTo(JCP::class, 'j_c_p_id');
    }
}
