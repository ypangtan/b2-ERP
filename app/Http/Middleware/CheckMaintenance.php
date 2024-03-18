<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

use App\Models\{
    Maintenance,
};

use Carbon\Carbon;

class CheckMaintenance
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle( Request $request, Closure $next )
    {
        // Daily, Schedule, Emergency

        $maintenance = Maintenance::where( 'type', 3 )
            ->where( 'status', 10 )
            ->first();

        if ( $maintenance ) {
            return redirect()->route( 'web.maintenance' );
        }

        return $next( $request );
    }
}
