<?php
// Controller voor verlofaanvraag model met aanvullend functies om een verlofaanvraag goed te keuren en af te keuren
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\VerlofAanvraag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;

class VerlofAanvraagController extends Controller
{
    /**
     * @OA\Get(
     *     path="/user/{user}/verlofaanvraag",
     *     summary="Get all verlofaanvragen of a specific user",
     *     security={{"BearerAuth": {}}},
     *     tags={"Verlofaanvraag"},
     *     @OA\Parameter(
     *         name="user",
     *         in="path",
     *         required=true,
     *         description="ID of the user",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of verlofaanvragen retrieved successfully"
     *     )
     * )
     */
    //ophalen alle verlofaanvragen van user
    public function index(User $user)
    {
        $aanvragen = $user->verlofaanvraags()->with('user')->get();
        return response()->json($aanvragen);
    }

    /**
     * @OA\Post(
     *     path="/user/{user}/verlofaanvraag",
     *     summary="Create a new verlofaanvraag for a user",
     *     security={{"BearerAuth": {}}},
     *     tags={"Verlofaanvraag"},
     *     @OA\Parameter(
     *         name="user",
     *         in="path",
     *         required=true,
     *         description="ID of the user",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Verlofaanvraag details",
     *         @OA\JsonContent(
     *             required={"reden", "startdatum", "einddatum", "status"},
     *             @OA\Property(property="reden", type="string", example="Vakantie"),
     *             @OA\Property(property="startdatum", type="string", format="date", example="2025-07-01"),
     *             @OA\Property(property="einddatum", type="string", format="date", example="2025-07-10"),
     *             @OA\Property(property="status", type="string", example="aangevraagd")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Verlofaanvraag successfully created"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
     */
    // opslaan nieuwe verlofaanvraag die meegestuurd wordt vanuit request resource
    public function store(Request $request, User $user)
    {
        //validation
        $request->validate([
            'reden' => 'required',
            'startdatum' => 'required|date',
            'einddatum' => 'required|date',
            'status' => 'required',
        ]);
        if ($request->get('startdatum') <= Date::today() || $request->get('einddatum') <= Date::today()) {
            return response()->json([
                'message' => 'Startdatum en/of einddatum moet in de toekomst zijn!'
            ], 422);
        }
        if ($user->verlofsaldo <= 1)
        {
            return response()->json([
                'message' => 'Geen voldoende verlofsaldo'
            ], 422);
        }
        $user->update([
           'verlofsaldo' =>  $user->verlofsaldo - 1
        ]);
        //creation
        $aanvraag = VerlofAanvraag::create([
            'reden' => $request->get('reden'),
            'startdatum' => $request->get('startdatum'),
            'einddatum' => $request->get('einddatum'),
            'status' => $request->get('status'),
            'user_id' => $user->id,
        ]);
        //return with succes
        return response()->json([
            'message' => 'Aanvraag created',
            'aanvraag' => $aanvraag
        ], 201);
    }

    /**
     * @OA\Get(
     *     path="/user/{user}/verlofaanvraag/{verlofaanvraag}",
     *     summary="Get a specific verlofaanvraag of a user",
     *     security={{"BearerAuth": {}}},
     *     tags={"Verlofaanvraag"},
     *     @OA\Parameter(
     *         name="user",
     *         in="path",
     *         required=true,
     *         description="ID of the user",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Parameter(
     *         name="verlofaanvraag",
     *         in="path",
     *         required=true,
     *         description="ID of the verlofaanvraag",
     *         @OA\Schema(type="integer", example=10)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Verlofaanvraag retrieved successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Verlofaanvraag not found"
     *     )
     * )
     */
    // tonen specifieke verlofaanvraag
    public function show(User $user, VerlofAanvraag $verlofAanvraag)
    {
        return response()->json($verlofAanvraag);
    }

    /**
     * @OA\Put(
     *     path="/user/{user}/verlofaanvraag/{verlofaanvraag}/approve",
     *     summary="Approve a verlofaanvraag (admin only)",
     *     security={{"BearerAuth": {}}},
     *     tags={"Verlofaanvraag"},
     *     @OA\Parameter(
     *         name="user",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Parameter(
     *         name="verlofaanvraag",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer", example=10)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Approval status update",
     *         @OA\JsonContent(
     *             required={"status"},
     *             @OA\Property(property="status", type="string", example="Goedgekeurd")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Verlofaanvraag approved successfully"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden (not an admin)"
     *     )
     * )
     */
    // goedkeuren verlofaanvraag door status aan te passen naar goedgekeurd
    public function approve(Request $request, User $userId, VerlofAanvraag $verlofAanvraag)
    {
        if ($userId->role == 'admin') {
            $request->validate([
                'status' => 'required',
            ]);
            $verlofAanvraag->update([
                'status' => $request->get('status'),
            ]);
            return response()->json($verlofAanvraag->reden . 'from:' . $userId->name . ' is approved!');
        }
        return response()->json()->setStatusCode(403);
    }

    /**
     * @OA\Put(
     *     path="/user/{user}/verlofaanvraag/{verlofaanvraag}/reject",
     *     summary="Reject a verlofaanvraag (admin only)",
     *     security={{"BearerAuth": {}}},
     *     tags={"Verlofaanvraag"},
     *     @OA\Parameter(
     *         name="user",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Parameter(
     *         name="verlofaanvraag",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer", example=10)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Rejection reason and status update",
     *         @OA\JsonContent(
     *             required={"status", "afkeuringsreden"},
     *             @OA\Property(property="status", type="string", example="Afgekeurd"),
     *             @OA\Property(property="afkeuringsreden", type="string", example="Te weinig vakantiedagen")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Verlofaanvraag rejected successfully"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden (not an admin)"
     *     )
     * )
     */
    // verlofaanvraag afwijzen met het toevoegen van afkeuringsreden
    public function reject(Request $request, User $userId, VerlofAanvraag $verlofAanvraag)
    {
        if ($userId->role == 'admin') {
            $request->validate([
                'status' => 'required',
                'afkeuringsreden' => 'required',
            ]);
            $verlofAanvraag->update([
                'status' => $request->get('status'),
                'afkeuringsreden' => $request->get('afkeuringsreden'),
            ]);
            return response()->json($verlofAanvraag->reden . 'from:' . $userId->name . ' is rejected!');
        }
        return response()->json()->setStatusCode(403);
    }
}
