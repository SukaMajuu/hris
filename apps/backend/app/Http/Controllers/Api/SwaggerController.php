<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

class SwaggerController extends Controller
{
    /**
     * Display Swagger UI.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('swagger');
    }

    /**
     * Get the Swagger YAML file.
     *
     * @return \Illuminate\Http\Response
     */
    public function yaml()
    {
        $filePath = app_path('Http/Controllers/Api/Documentation/swagger.yaml');

        if (!File::exists($filePath)) {
            abort(404, 'Swagger YAML file not found');
        }

        $content = File::get($filePath);

        return Response::make($content, 200, [
            'Content-Type' => 'application/yaml',
            'Content-Disposition' => 'inline; filename="swagger.yaml"'
        ]);
    }
}