<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EditingPerson extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [];
    protected $guarded = [];
    public $table = 'editing_persons';
    public function category()
    {
        return $this->hasMany(EditingPersonCategory::class, 'id', 'category_id');
    }
}
