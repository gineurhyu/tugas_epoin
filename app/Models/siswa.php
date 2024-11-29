<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class siswa extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_user',
        'image',
        'nis',
        'tingkatan',
        'jurusan',
        'kelas',
        'hp',
        'status',

    ];
}
