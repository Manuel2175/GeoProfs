<?php

namespace App\Http\Controllers;
/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="Geoprofs",
 *     description="API documentatie voor geoprofs verlofsregistratiesysteem"
 * )
 *
 * @OA\SecurityScheme(
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     securityScheme="BearerAuth"
 * )
 *
 * @OA\Server(
 *     url=L5_SWAGGER_CONST_HOST,
 *     description="Local api server"
 * )
 */
abstract class Controller
{
    //
}
