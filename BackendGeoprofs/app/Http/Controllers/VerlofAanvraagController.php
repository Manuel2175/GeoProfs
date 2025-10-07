<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\VerlofAanvraag;
use Illuminate\Http\Request;
use function Pest\Laravel\json;

class VerlofAanvraagController extends Controller
{
    /**
     * @OA\Get(
     *     path="/user/verlofaanvraag",
     *     summary="Get the currently logged-in user",
     *     tags={"Verlofaanvraag"},
     * )
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
        //validation
        $request->validate([
            'reden' => 'required',
            'startdatum' => 'required:date',
            'einddatum' => 'required',
            'status' => 'required',
        ]);
        //creation
        $aanvraag = VerlofAanvraag::create([
           'reden' => $request->get('reden'),
           'startdatum' => $request->get('startdatum'),
           'einddatum' => $request->get('einddatum'),
           'status' => $request->get('status'),
           'userId' => $user->id,
        ]);
        //return with succes
        return response()->json('Aanvraag:' . 'from:' . $user->name . $aanvraag->reden . 'is created!');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user, VerlofAanvraag $verlofAanvraag)
    {
        return response()->json($verlofAanvraag);
    }

    /**
     * Update the specified resource in storage.
     */
    public function approve(Request $request, User $user, VerlofAanvraag $verlofAanvraag)
    {
        if ($user->role == 'admin') {
            $request->validate([
                'status' => 'required',
            ]);
            $verlofAanvraag->update([
                'status' => $request->get('status'),
            ]);
            return response()->json($verlofAanvraag->reden . 'from:' . $user->name . ' is approved!');
        }
        return response()->json()->setStatusCode(403);
    }

    /**
     * Update the specified resource in storage.
     */
    public function reject(Request $request, User $user, VerlofAanvraag $verlofAanvraag)
    {
        if ($user->role == 'admin') {
            $request->validate([
                'status' => 'required',
                'afkeuringsreden' => 'required',
            ]);
            $verlofAanvraag->update([
                'status' => $request->get('status'),
                'afkeuringsreden' => $request->get('afkeuringsreden'),
            ]);
            return response()->json($verlofAanvraag->reden . 'from:' . $user->name . ' is rejected!');
        }
        return response()->json()->setStatusCode(403);
    }
}
