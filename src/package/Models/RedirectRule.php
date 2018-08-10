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

    /**
     * Delete chained redirect with recursive delete
     *
     * @param $destination
     *
     * @throws \Exception When there is multiple rules with same destination
     *                    exception will be raised
     *
     * @return void
     */
    public static function deleteChainedRecursively($destination)
    {
        $redirectRules = RedirectRule::where('destination', $destination)->get();

        if ($redirectRules->count() > 1) {
            $message = 'There is multiple redirections with the same destination! ';
            $message .= 'Recursive delete will not continue';

            throw new \Exception($message);
        }

        $redirectRule = $redirectRules->first();

        if ($redirectRule === null) {
            return;
        }

        $nextDestination = $redirectRule->origin;

        $redirectRule->delete();

        self::deleteChainedRecursively($nextDestination);
    }
}