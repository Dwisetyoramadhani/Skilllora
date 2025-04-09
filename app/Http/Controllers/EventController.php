<?php

namespace App\Http\Controllers;

use App\Http\Resources\EventResource;
use App\Models\Course;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EventController extends Controller
{
    public function create(Request $request)
    {
        try {
            $request->validate([
                'title' => 'required|string',
                'description' => 'required|string',
                'location' => 'required|string',
                'date' => 'required|date',
                'thumbnail' => 'required|mimes:jpg,png,jpeg,svg|max:2048'
            ]);

            $user = $request->user();
            if (!$user) {
                return response()->json(['message' => 'User tidak terautentikasi'], 401);
            }

            if ($request->hasFile('thumbnail')) {
                $file = $request->file('thumbnail');
                $extension = $file->getClientOriginalExtension();
                $filename = Str::uuid() . '.' . $extension;

                $filePath = $file->storeAs('event_thumbnails', $filename, 'public');
            }

            $event = Event::create([
                'title' => $request->title,
                'description' => $request->description,
                'location' => $request->location,
                'date' => $request->date,
                'thumbnail' => Storage::url($filePath),
                'author' => $user->id
            ]);

            $event->load('author');

            return response()->json([
                'message' => 'Event Berhasil dibuat',
                'event' => $event
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function index()
    {
        try {
            $event = Event::with(['user'])->get();
            return EventResource::collection($event);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $event = Event::with(['user'])->findOrFail($id);
            return new EventResource($event);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $event = Event::find($id);

            if (!$event) {
                return response()->json([
                    'message' => 'Event tidak ditemukan',
                ], 404);
            }

            if ($event->thumbnail) {
                $thumbnailPath = public_path('/storage' . $event->thumbnail);
                if (file_exists($thumbnailPath)) {
                    unlink($thumbnailPath);
                }
            }

            $event->delete();

            return response()->json([
                'message' => 'Event berhasil dihapus'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
