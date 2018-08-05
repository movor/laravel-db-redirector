<?php

namespace Movor\LaravelDbRedirector\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class RedirectRule extends Model
{
    protected $casts = [
        'last_redirect_at' => 'datetime',
        'status_code' => 'integer',
        'hits' => 'integer'
    ];

    protected $guarded = [];

    /**
     * Setter for the "status" attribute
     *
     * @param $value
     *
     * @throws \Exception
     */
    public function setStatusCodeAttribute($value)
    {
        $this->attributes['status_code'] = $value ?? 301;
    }

    /**
     * Update hits amount and last hit date
     * for the db redirect rule
     *
     * @return void
     */
    public function hit()
    {
        $this->update([
            'last_hit_at' => new Carbon,
            'hits' => $this->hits + 1
        ]);
    }
}