<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    // 🔹 Store new event
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'publish_at' => 'required|date',
            'photos.*' => 'image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $event = Event::create($validated);

        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('event_photos', 'public');
                EventImage::create([
                    'event_id' => $event->id,
                    'photo_path' => $path
                ]);
            }
        }

        return response()->json(['message' => 'Event created successfully', 'data' => $event]);
    }

    // 🔹 List events (filtered + time-zone adjusted)
    public function index(Request $request)
    {
        $timezone = $request->header('Time-Zone', config('app.timezone'));

        $events = Event::with(['event_images', 'category'])
            ->where('publish_at', '<=', now())
            ->get()
            ->map(function ($event) use ($timezone) {
                $event->publish_at = $event->publish_at->timezone($timezone)->format('Y-m-d H:i:s');
                return $event;
            });

        return response()->json($events);
    }

    // 🔹 List all (admin panel)
    public function all()
    {
        return Event::with(['images', 'category'])
            ->orderBy('publish_at', 'desc')
            ->get();
    }

    // 🔴 Soft delete event
    public function destroy($id)
    {
        $event = Event::findOrFail($id);
        $event->delete();

        return response()->json(['message' => 'Event deleted (soft delete).']);
    }

    // 🛑 Force delete event (permanent)
    public function forceDelete($id)
    {
        $event = Event::withTrashed()->findOrFail($id);

        // Delete images
        foreach ($event->images as $img) {
            Storage::disk('public')->delete($img->photo_path);
            $img->delete();
        }

        $event->forceDelete();

        return response()->json(['message' => 'Event permanently deleted.']);
    }

    public function viewPage()
    {
        return view('admin.events');
    }
}
