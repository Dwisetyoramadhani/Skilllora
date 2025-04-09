<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseVideo;
use Illuminate\Http\Request;

class CourseVideoController extends Controller
{
    public function create(Request $request, $courseId)
    {
        try {
            $request->validate([
                'video' => 'required|file|mimes:mp4,mov,avi,wmv|max:102400',
                'title' => 'nullable|string',
            ]);

            $course = Course::findOrFail($courseId);
            $partNumber = $course->videos()->count() + 1;

            $path = $request->file('video')->store("videos/courses/{$courseId}", 'public');

            CourseVideo::create([
                'course_id' => $courseId,
                'part_number' => $partNumber,
                'video_path' => $path,
                'title' => $request->title,
            ]);

            return response()->json(['message' => 'Video uploaded successfully', 'path' => $path]);
        } catch (\Exception $e) {
            return response()->json([
                'message'=>'Terjadi Kesalahan',
                'error'=> $e->getMessage()
            ], 500);
        }
    }
}