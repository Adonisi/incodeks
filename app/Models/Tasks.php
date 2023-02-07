<?php

namespace App\Models;

use App\Models\User;
use App\Models\Status;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tasks extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'description','due_date','status_id'];

    /**
     * The users that belong to the task.
     */
    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function status(){
        return $this->belongsTo(Status::class);
    }
}
