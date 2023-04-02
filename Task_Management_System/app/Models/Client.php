<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $table='clients';
    use HasFactory;
    protected $fillable = [
       'name',
       'address',
       'phone',
       'remarks',
       'isActive'
    ];

    public function project(){
        return $this->hasMany(Project::class,'client_id','id');
    }
}
