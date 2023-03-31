<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(title="Cheeta Api Docs", version="0.1")
 *    @OA\Server(
 *      url="http://192.168.100.20/cheeta/api",
 *      description="Demo API Server"
 * )
/**
@OAS\SecurityScheme(
securityScheme="bearerAuth",
type="http",
scheme="bearer"
)
 **/
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
