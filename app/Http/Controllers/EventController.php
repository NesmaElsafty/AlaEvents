<?php

namespace App\Http\Controllers;

use App\Http\Resources\EventResource;
use App\Models\Event;
use App\Services\EventService;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function __construct(
        protected EventService $events,
    ) {}
  
    public function index(Request $request)
    {
        try {
        $perPage = (int) $request->get('per_page', 15);

        $events = $this->events->paginate($perPage, $request->all());

        // return response with pagination
        return response()->json([
            'data' => EventResource::collection($events),
            'links' => $events->links(),
            'meta' => [
                'current_page' => $events->currentPage(),
                'from' => $events->firstItem(),
                'to' => $events->lastItem(),
            ],
        ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error fetching events',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
            'name' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'link' => ['nullable', 'string', 'max:255', 'url', 'unique:events,link'],
            'is_active' => ['sometimes', 'boolean'],
            'image' => ['nullable', 'image', 'max:2048'],
        ]);

            $event = $this->events->store($request->all());
            // upload image
            if ($request->hasFile('image')) {
                $event->addMediaFromRequest('image')->toMediaCollection('image');
            }

            return response()->json([
                'message' => 'Event stored successfully',
                'data' => new EventResource($event),
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error storing event',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $event = Event::find($id);
            if(!$event) {
                return response()->json([
                    'message' => 'Event not found',
                ], 404);
            }
            return response()->json([
                'message' => 'Event fetched successfully',
                'data' => new EventResource($event),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error fetching event',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $event = Event::find($id);
            if(!$event) {
                return response()->json([
                    'message' => 'Event not found',
                ], 404);
            }
            $request->validate([
            'name' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'link' => ['nullable', 'string', 'max:255', 'url', 'unique:events,link,' . $event->id],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $event = $this->events->update($event, $request->all());
        // upload image
        if ($request->hasFile('image')) {
            $event->clearMediaCollection('image');
            $event->addMediaFromRequest('image')->toMediaCollection('image');
        }
        return response()->json([
            'message' => 'Event updated successfully',
            'data' => new EventResource($event),
        ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error updating event',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $event = Event::find($id);
            if(!$event) {
                return response()->json([
                    'message' => 'Event not found',
                ], 404);
            }
            $this->events->delete($event);
            return response()->json([
                'message' => 'Event deleted successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error deleting event',
                'error' => $e->getMessage(),
            ], 500);
        }

    }
}
