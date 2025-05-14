<?php

namespace App\Http\Controllers;

use App\Services\ThemeService;
use Illuminate\Http\JsonResponse;

class ThemeController extends Controller
{
    public function index(ThemeService $themeService): JsonResponse
    {
        return response()->json($themeService->loadTheme());
    }
}
