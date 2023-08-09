<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class Page extends Model
{
    use HasFactory;

    protected $casts = [
        "timer_timestamp" => 'datetime'
    ];

    public function template() : belongsTo
    {
        return $this->belongsTo(Template::class);
    }

    public function funnel() : belongsTo
    {
        return $this->belongsTo(Funnel::class);
    }

    public function domain() : hasOneThrough
    {
        return $this->hasOneThrough(
            Domain::class,
            Funnel::class,
            'id',
            'id',
            'funnel_id',
            'domain_id'
        );
    }

    public function checkout() : belongsTo
    {
        return $this->belongsTo(Page::class);
    }

    public function getBuyButtonOneAttribute($value)
    {
        if($this->checkout_id) {
            $checkout = Page::with('domain', 'funnel')->where('id', $this->checkout_id)->first();
            return 'https://' . $checkout->domain->domain_name . '/' . $checkout->funnel->url . '/' . $checkout->url;
        }

        return $value;
    }
}
