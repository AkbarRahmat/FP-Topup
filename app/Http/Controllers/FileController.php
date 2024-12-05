<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\File;

class FileController extends Controller
{
    protected $faker;

    public function __construct() {
        $this->faker = Faker::create();
    }

    public function uploadImage(Request $request) {
        $request->validate([
            'file' => 'required|image|mimes:jpeg,png,jpg,gif|max:5048'
        ]);

        try {
            $imageFormat = $request->file->extension();
            $imageName = str_replace('-', '', $this->faker->uuid) . '.' . $imageFormat;

            // Move the uploaded file
            $request->file->move(public_path('file/image'), $imageName);

            return response()->json([
                'success' => true,
                'message' => 'success_upload_file',
                'data' => [
                    'filename' => $imageName,
                    'format' => $imageFormat
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'fail_upload_file',
                'debug' => $e->getMessage()
            ], 500);
        }
    }

    public function deleteImage($filename) {
        $filePath = public_path('file/image') . '/' . $filename;

        if (File::exists($filePath)) {
            try {
                File::delete($filePath);

                return response()->json([
                    'success' => true,
                    'message' => 'success_delete_file',
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'fail_delete_file',
                    'debug' => $e->getMessage()
                ], 500);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => 'fail_delete_file_notfound',
            ], 404);
        }
    }
}
