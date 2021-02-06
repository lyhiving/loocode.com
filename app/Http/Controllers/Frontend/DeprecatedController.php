<?php


namespace App\Http\Controllers\Frontend;


use Illuminate\Http\RedirectResponse;

class DeprecatedController
{

    /**
     * @return RedirectResponse
     */
    public function redirect(): RedirectResponse
    {
        return redirect("/");
    }
}
