<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller {

    public function download($filename) {
        $file = Storage::disk('public')->get($filename);
        return (new Response($file, 200))
            ->header('Content-Type', 'application/octet-stream')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }
}
