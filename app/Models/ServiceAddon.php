<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceAddon extends Model implements  HasMedia
{
    use InteractsWithMedia,HasFactory,SoftDeletes;
    protected $table = 'service_addons';
    protected $fillable = [
        'name', 'service_id','price','status'
    ];
    protected $casts = [
        'service_id'    => 'integer',
        'price'         => 'double',
        'status'        => 'integer',
    ];
    public function service(){
        return $this->belongsTo(Service::class,'service_id', 'id');
    }
    public function scopeList($query)
    {
        return $query->orderBy('deleted_at', 'asc');
    }
   
}
