<?php

namespace Luminouslabs\Installer\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpinPoint extends Model
{
    use HasFactory;
    protected $table ='spiner_point';
    protected $guarded =['id'];
}
