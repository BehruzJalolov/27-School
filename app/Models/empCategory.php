<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmpCategory extends Model
{
    protected $table = 'emp_categories';
    protected $guarded = [];
    
    public function employee() {
    return $this->hasMany(Employee::class, 'emp_category_id');
}

}
