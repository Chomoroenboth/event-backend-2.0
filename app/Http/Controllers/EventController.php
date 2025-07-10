<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EventController extends Controller
{
    public function dashboard()
    {
        // Sample data - replace with actual database queries
        $events = [
            [
                'id' => 1,
                'title' => 'React Conf 2024',
                'date' => 'March 15, 2024',
                'status' => 'upcoming',
                'color' => 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
                'description' => 'The premier React conference featuring the latest in React development.',
                'location' => 'San Francisco, CA',
                'time' => '9:00 AM - 6:00 PM',
                'attendees' => 500
            ],
            [
                'id' => 2,
                'title' => 'Laravel Live',
                'date' => 'March 20, 2024',
                'status' => 'live',
                'color' => 'linear-gradient(135deg, #f093fb 0%, #f5576c 100%)',
                'description' => 'Join Laravel experts for live coding sessions and best practices.',
                'location' => 'Virtual Event',
                'time' => '10:00 AM - 4:00 PM',
                'attendees' => 300
            ],
            [
                'id' => 3,
                'title' => 'JavaScript Summit',
                'date' => 'February 28, 2024',
                'status' => 'past',
                'color' => 'linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)',
                'description' => 'A comprehensive look at modern JavaScript frameworks and tools.',
                'location' => 'New York, NY',
                'time' => '8:00 AM - 5:00 PM',
                'attendees' => 800
            ],
            [
                'id' => 4,
                'title' => 'Vue.js Nation',
                'date' => 'April 10, 2024',
                'status' => 'upcoming',
                'color' => 'linear-gradient(135deg, #43e97b 0%, #38f9d7 100%)',
                'description' => 'Everything about Vue.js, from basics to advanced concepts.',
                'location' => 'Online',
                'time' => '9:00 AM - 5:00 PM',
                'attendees' => 250
            ],
            [
                'id' => 5,
                'title' => 'DevOps Days',
                'date' => 'March 25, 2024',
                'status' => 'upcoming',
                'color' => 'linear-gradient(135deg, #fa709a 0%, #fee140 100%)',
                'description' => 'Learn about the latest DevOps practices and tools.',
                'location' => 'Chicago, IL',
                'time' => '8:30 AM - 6:00 PM',
                'attendees' => 400
            ],
            [
                'id' => 6,
                'title' => 'Mobile Dev Conference',
                'date' => 'February 15, 2024',
                'status' => 'past',
                'color' => 'linear-gradient(135deg, #a8edea 0%, #fed6e3 100%)',
                'description' => 'Mobile development trends and best practices.',
                'location' => 'Austin, TX',
                'time' => '9:00 AM - 5:00 PM',
                'attendees' => 350
            ]
        ];

        // Add newly created event from session if it exists
        if (session('new_event')) {
            $newEvent = session('new_event');
            $events[] = $newEvent;
            session()->forget('new_event'); // Remove from session after adding
        }

        return view('dashboard', compact('events'));
    }

    public function show($id)
    {
        // Sample data - replace with actual database query
        $events = [
            1 => [
                'id' => 1,
                'title' => 'React Conf 2024',
                'date' => 'March 15, 2024',
                'status' => 'upcoming',
                'description' => 'The premier React conference featuring the latest in React development, cutting-edge techniques, and networking opportunities with industry leaders.',
                'location' => 'San Francisco, CA',
                'time' => '9:00 AM - 6:00 PM',
                'attendees' => 500,
                'duration' => 'Full Day',
                'category' => 'Frontend Development',
                'language' => 'English',
                'format' => 'Hybrid'
            ],
            2 => [
                'id' => 2,
                'title' => 'Laravel Live',
                'date' => 'March 20, 2024',
                'status' => 'live',
                'description' => 'Join Laravel experts for live coding sessions, best practices, and real-world application development.',
                'location' => 'Virtual Event',
                'time' => '10:00 AM - 4:00 PM',
                'attendees' => 300,
                'duration' => '6 Hours',
                'category' => 'Backend Development',
                'language' => 'English',
                'format' => 'Virtual'
            ],
            3 => [
                'id' => 3,
                'title' => 'JavaScript Summit',
                'date' => 'February 28, 2024',
                'status' => 'past',
                'description' => 'A comprehensive look at modern JavaScript frameworks, tools, and the future of web development.',
                'location' => 'New York, NY',
                'time' => '8:00 AM - 5:00 PM',
                'attendees' => 800,
                'duration' => '9 Hours',
                'category' => 'Web Development',
                'language' => 'English',
                'format' => 'In-Person'
            ]
        ];

        $event = $events[$id] ?? abort(404);

        return view('events.show', compact('event'));
    }

    public function create()
    {
        return view('events.create');
    }

    public function store(Request $request)
    {
        // Validate the request
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'time' => 'required',
            'date' => 'required|date|after_or_equal:today',
            'entry_type' => 'required|in:free,paid',
            'ticket_price' => 'nullable|numeric|min:0|required_if:entry_type,paid',
            'location' => 'required|string|max:255',
            'area' => 'required|string|max:255',
            'meet_link' => 'nullable|url',
            'category' => 'required|string|in:technology,business,education,health,arts,sports,social,other',
            'event_type' => 'required|string|in:conference,workshop,seminar,meetup,webinar,networking,competition,exhibition,other',
            'event_form' => 'nullable|url',
            'social_media_link' => 'nullable|url',
            'event_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle file uploads
        $imagePaths = [];
        if ($request->hasFile('event_images')) {
            foreach ($request->file('event_images') as $image) {
                $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('uploads/events'), $imageName);
                $imagePaths[] = 'uploads/events/' . $imageName;
            }
        }

        // For now, just add to our sample data array (replace with database save later)
        // In a real app, you would do: Event::create($validatedData);
        
        // Generate a simple ID for demo purposes
        $newId = 7; // In real app, this would be auto-generated by database
        
        // Add the new event to session for demo (in real app, save to database)
        session(['new_event' => [
            'id' => $newId,
            'title' => $validatedData['title'],
            'description' => $validatedData['description'] ?? 'New event created by user',
            'date' => date('M j, Y', strtotime($validatedData['date'])),
            'time' => $validatedData['time'],
            'status' => 'upcoming',
            'entry_type' => $validatedData['entry_type'],
            'ticket_price' => $validatedData['ticket_price'] ?? null,
            'location' => $validatedData['location'],
            'area' => $validatedData['area'],
            'meet_link' => $validatedData['meet_link'],
            'category' => $validatedData['category'],
            'event_type' => $validatedData['event_type'],
            'event_form' => $validatedData['event_form'],
            'social_media_link' => $validatedData['social_media_link'],
            'event_images' => $imagePaths,
            'attendees' => 0,
            'duration' => 'TBD',
            'language' => 'English',
            'format' => $validatedData['meet_link'] ? 'Hybrid' : 'In-Person'
        ]]);

        return redirect()->route('dashboard')->with('success', 'Event created successfully!');
    }
}