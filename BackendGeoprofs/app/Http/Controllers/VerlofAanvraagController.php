<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\VerlofAanvraag;
use Illuminate\Http\Request;
use function Pest\Laravel\json;

class VerlofAanvraagController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(User $user)
    {

        return response()->json($user->aanvragen);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, User $user)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user, VerlofAanvraag $verlofAanvraag)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user, VerlofAanvraag $verlofAanvraag)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user,VerlofAanvraag $verlofAanvraag)
    {
        //
    }
}
