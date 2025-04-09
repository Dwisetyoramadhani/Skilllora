<?php

namespace App\Http\Controllers;

use App\Http\Resources\CourseResource;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CourseController extends Controller
{
    public function createCourse(Request $request)
    {
        try {

            $request->validate([
                'course_title' => 'required|string',
                'category_id'  => 'required|exists:categories,category_id',
                'description' => 'required|string',
                'thumbnail' => 'required|image|mimes:jpeg,png,jpg,svg|max:2048',
                'vidio_link' => 'required|string',
                'price' => 'required|string',
            ]);

            $user = $request->user();
            if (!$user) {
                return response()->json(['message' => 'User tidak terautentikasi'], 401);
            }

            if ($request->hasFile('thumbnail')) {
                $file = $request->file('thumbnail');
                $extension = $file->getClientOriginalExtension();
                $filename = Str::uuid() . '.' . $extension;

                $filePath = $file->storeAs('thumbnails', $filename, 'public');
            }

            $course = Course::create([
                'course_title' => $request->course_title,
                'category_id' => $request->category_id,
                'description' => $request->description,
                'thumbnail' => Storage::url($filePath),
                'author' => $user->id,
                'vidio_link' => $request->vidio_link,
                'price' => $request->price,
            ]);

            $course->load('user', 'category');

            return response()->json([
                'message' => 'Course berhasil dibuat',
                'course' => $course,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Ada kesalahan',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function index()
    {
        $courses = Course::with(['category', 'user', 'videos'])->get();
        return CourseResource::collection($courses);
    }

    public function show($id)
    {
        $course = Course::with(['category', 'user', 'videos'])->findOrFail($id);
        return new CourseResource($course);
    }

    public function destroy($id)
    {
        try {
            $course = Course::find($id);

            if (!$course) {
                return response()->json([
                    'message' => 'Course tidak ditemukan'
                ], 404);
            }

            if ($course->thumbnail) {
                $thumbnailPath = public_path('storage/' . $course->thumbnail);
                if (file_exists($thumbnailPath)) {
                    unlink($thumbnailPath);
                }
            }

            $course->delete();

            return response()->json([
                'message' => 'Course berhasil di hapus'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
