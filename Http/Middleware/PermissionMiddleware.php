<?php namespace Modules\Core\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Redirect;
use Laracasts\Flash\Flash;
use Modules\Core\Contracts\Authentication;

class PermissionMiddleware
{
    /**
     * @var Authentication
     */
    private $auth;

    public function __construct(Authentication $auth)
    {
        $this->auth = $auth;
    }

    public function handle(Route $route, Request $request, \Closure $next)
    {
        $action = $route->getActionName();
        $actionMethod = substr($action, strpos($action, "@") + 1);

        $segmentPosition = $this->getSegmentPosition($request);
        $moduleName = $request->segment($segmentPosition - 1);
        $entityName = $request->segment($segmentPosition);

        if (!$this->auth->hasAccess("$moduleName.$entityName.$actionMethod")) {
            Flash::error('Permission denied.');

            return Redirect::to('/'.config('asgard.core.core.admin-prefix'));
        }

        return $next($request);
    }

    /**
     * Get the correct segment position based on the locale or not
     *
     * @param $request
     * @return mixed
     */
    private function getSegmentPosition(Request $request)
    {
        $segmentPosition = 4;

        if ($request->segment($segmentPosition) == config('asgard.core.core.admin-prefix')) {
            return ++ $segmentPosition;
        }

        return $segmentPosition;
    }
}
