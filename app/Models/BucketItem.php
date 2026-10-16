<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class BucketItem extends Model
{
    protected $guarded = [];

    public function bucket(): BelongsTo
    {
        return $this->belongsTo(PendingBucket::class, 'pending_bucket_id');
    }

    public function payable(): MorphTo
    {
        return $this->morphTo();
    }
}