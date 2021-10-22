<?php


namespace modules;

use App\Http\Controllers\Controller;
use App\Http\Traits\ApiDesignTrait;
/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="E-commerce Documentation",
 * )
 */

class BaseController extends Controller
{
    use ApiDesignTrait;

}
