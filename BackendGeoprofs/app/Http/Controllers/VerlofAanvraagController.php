<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\VerlofAanvraag;
use Illuminate\Http\Request;

class VerlofAanvraagController extends Controller
{
    /**
     * @OA\Get(
     *     path="/user/{userId}/verlofaanvraag",
     *     summary="Get all verlofaanvragen of a specific user",
     *     tags={"Verlofaanvraag"},
     *     @OA\Parameter(
     *         name="userId",
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
    public function index(User $user)
    {
        return response()->json($user->aanvragen);
    }

    /**
     * @OA\Post(
     *     path="/user/{userId}/verlofaanvraag",
     *     summary="Create a new verlofaanvraag for a user",
     *     tags={"Verlofaanvraag"},
     *     @OA\Parameter(
     *         name="userId",
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
     *             @OA\Property(property="status", type="string", example="In behandeling")
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
     *     path="/user/{userId}/verlofaanvraag/{id}",
     *     summary="Get a specific verlofaanvraag of a user",
     *     tags={"Verlofaanvraag"},
     *     @OA\Parameter(
     *         name="userId",
     *         in="path",
     *         required=true,
     *         description="ID of the user",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Parameter(
     *         name="id",
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
    public function show(User $user, VerlofAanvraag $verlofAanvraag)
    {
        return response()->json($verlofAanvraag);
    }

    /**
     * @OA\Put(
     *     path="/user/{userId}/verlofaanvraag/{id}/approve",
     *     summary="Approve a verlofaanvraag (admin only)",
     *     tags={"Verlofaanvraag"},
     *     @OA\Parameter(
     *         name="userId",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Parameter(
     *         name="id",
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
     * @OA\Put(
     *     path="/user/{userId}/verlofaanvraag/{id}/reject",
     *     summary="Reject a verlofaanvraag (admin only)",
     *     tags={"Verlofaanvraag"},
     *     @OA\Parameter(
     *         name="userId",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Parameter(
     *         name="id",
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
