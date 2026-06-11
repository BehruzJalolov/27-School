<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryChild extends Model
{
    protected $table = 'categorychildrens';
    protected $guarded = [];
    // CategoryChildren.php
public function category()
{
    return $this->belongsTo(Category::class, 'category_id');
}

}
