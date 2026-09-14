<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'parent_id',
        'code',
        'name',
    ];

    public function parent()
    {
        return $this->belongsTo(Region::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Region::class, 'parent_id');
    }

    public function scopeProvinces($query)
    {
        return $query->where('type', 'provinsi');
    }

    public function scopeRegencies($query, $parentId = null)
    {
        $q = $query->where('type', 'kabupaten');
        return $parentId ? $q->where('parent_id', $parentId) : $q;
    }

    public function scopeDistricts($query, $parentId = null)
    {
        $q = $query->where('type', 'kecamatan');
        return $parentId ? $q->where('parent_id', $parentId) : $q;
    }

    public function scopeVillages($query, $parentId = null)
    {
        $q = $query->where('type', 'kelurahan');
        return $parentId ? $q->where('parent_id', $parentId) : $q;
    }
}
