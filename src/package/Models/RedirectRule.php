<?php

namespace Movor\LaravelDbRedirector\Models;

use Illuminate\Database\Eloquent\Model;

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
}