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
     *     summary="Get the currently logged-in user verlofaanvraag",
     *     tags={"Verlofaanvraag"},
     *          @OA\Response(
     *          response=200,
     *          description="Lijst aanvragen succesvol verzonden"
     *      )
     * )
     */
    public function index(User $user)
    {
        return response()->json($user->aanvragen);
    }

    /**
     * @OA\Post(
     *     path="/user/{userId}/verlof/aanvraag ",
     *     summary="stores a new verlofaanvraag",
     *     tags={"Verlofaanvraag"},
     *          @OA\Response(
     *          response=200,
     *          description="Lijst aanvragen succesvol opgeslagen"
     *      )
     * )
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
     * @OA\Get(
     *     path="/user/{userid}/verlofaanvraag/{id} ",
     *     summary="Get a specific users verofaanvragen",
     *     tags={"Verlofaanvraag"},
     *          @OA\Response(
     *          response=200,
     *          description="users verlofaanvragen"
     *      )
     * )
     */
    public function show(User $user, VerlofAanvraag $verlofAanvraag)
    {
        return response()->json($verlofAanvraag);
    }

    /**
     * @OA\Put (
     *     path="/user/{userid}/verlofaanvraag/{id}/approve",
     *     summary="goedkeuring verlofaanvraag",
     *     tags={"Verlofaanvraag"},
     *          @OA\Response(
     *          response=200,
     *          description="users verlofaanvraag goedkeure"
     *      )
     * )
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
     * @OA\Put (
     *     path="/user/{userId}/verlofaanvraag/{id}}/reject",
     *     summary="afwijzing verlofaanvraag",
     *     tags={"Verlofaanvraag"},
     *          @OA\Response(
     *          response=200,
     *          description="users verlofaanvraag afwijzen"
     *      )
     * )
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
