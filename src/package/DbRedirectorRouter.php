<?php

namespace Movor\LaravelDbRedirector;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Router;
use Movor\LaravelDbRedirector\Models\RedirectRule;

class DbRedirectorRouter
{
    /**
     * @var Router
     */
    protected $router;

    /**
     * DbRedirector Router constructor.
     *
     * @param Router $router
     */
    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    /**
     * @param Request $request
     *
     * @return Response|null
     */
    public function getRedirectFor(Request $request)
    {
        // Make routes for each record in database
        RedirectRule::all()->each(function ($redirect) {
            $this->router->get($redirect->origin, function () use ($redirect) {
                $destination = $this->resolveDestination($redirect->destination);

                return redirect($destination, $redirect->status_code);
            });
        });

        // If one of the routes could be dispatched it means
        // we have a match in database and we can continue with redirection
        // (from callback above)
        try {
            return $this->router->dispatch($request);
        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * Resolve destination by replacing parameters from
     * current route into destination rule
     *
     * @param string $destination
     *
     * @return mixed
     */
    protected function resolveDestination($destination)
    {
        foreach ($this->router->getCurrentRoute()->parameters() as $key => $value) {
            $destination = str_replace("{{$key}}", $value, $destination);
        }

        return $destination;
    }
}