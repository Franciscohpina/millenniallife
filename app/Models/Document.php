<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Document extends Model
{
    protected $guarded = [];

    public function services(): MorphToMany
    {
        return $this->morphedByMany(Service::class, 'documentable');
    }

    public function appointments(): MorphToMany
    {
        return $this->morphedByMany(MedicalAppointment::class, 'documentable');
    }
}