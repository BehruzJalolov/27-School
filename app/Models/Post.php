<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        'title_uz',
        'title_ru',
        'body_uz',
        'body_ru',
        'image',
        'emp_category_id',
    ];

public function category()
{
    return $this->belongsTo(EmpCategory::class, 'emp_category_id');
}

}

