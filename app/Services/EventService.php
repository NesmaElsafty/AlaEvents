<?php

namespace App\Services;

use App\Models\Event;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class EventService
{

    public function paginate(int $perPage = 15, $data)
    {
        // if isset data['search']
        if (isset($data['search'])) {
            return Event::query()
                ->where('name', 'like', '%' . $data['search'] . '%')
                ->orWhere('description', 'like', '%' . $data['search'] . '%')
                ->orWhere('link', 'like', '%' . $data['search'] . '%')
                ->orderBy('created_at', 'desc')
                ->paginate($perPage);
        }
        
        return Event::query()
            ->latest()
            ->paginate($perPage);
    }

    
    public function store($data)
    {
        $event = Event::create([
            'name' => $data['name'],
            'description' => $data['description'],
            'link' => $data['link'],
            'is_active' => $data['is_active'],
        ]);
        
        return $event;
    }

    
    public function update(Event $event, $data)
    {
      $event->name = $data['name'];
      $event->description = $data['description'];
      $event->link = $data['link'];
      $event->is_active = $data['is_active'];
      $event->save();

        return $event;
    }

    
    public function delete(Event $event)
    {
        $event->delete();
    }
}

