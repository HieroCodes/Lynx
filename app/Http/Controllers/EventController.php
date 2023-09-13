<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use Inertia\Inertia;

class EventController extends Controller
{

    public function index() {
          $events = Event::all();
          return response()->json(['events' => $events], 200);
    }

    public function scope(Request $request) {
        $eventsQuery = Event::query();

        if ($request->has(['start_date', 'end_date'])) {
            $eventsQuery->dateRange($request->start_date, $request->end_date);
        }

        $events = $eventsQuery->get();
        return response()->json(['events' => $events], 200);
    }

    public function create()
    {
        return view('events.create');
    }

     public function store(Request $request)
     {
         $validated = $request->validate([
             'title' => 'required|max:255',
             'description' => 'nullable|string',
             'date' => 'required|date',
         ]);

         $event = Event::create($validated);
        // return response()->json(['event' => $event], 201);
     }

     public function update(Request $request, $id) {
         $validated = $request->validate([
             'title' => 'required|max:255',
             'description' => 'nullable|string',
             //'date' => 'required|date',
         ]);

         // Si la validation échoue, les lignes suivantes ne seront pas exécutées.
         // Vous pouvez vérifier cela en ajoutant un log ou en renvoyant une réponse ici.

         $event = Event::find($id);
         if (!$event) {
             return response()->json(['message' => 'Event not found'], 404);
         }

         $event->update($validated);

         return response()->json(['message' => 'Event updated successfully']);
     }







    public function show($id)
    {
        $event = Event::findOrFail($id);
        return view('events.show', compact('event'));
    }

    public function edit($id)
    {
        $event = Event::findOrFail($id);
        return view('events.edit', compact('event'));
    }

    public function destroy($id)
        {
            // Trouver l'événement par ID
            $event = Event::find($id);

            // Si l'événement n'existe pas, retourner une réponse d'erreur
            if (!$event) {
                return response()->json(['error' => 'Event not found'], 404);
            }

            // Supprimer l'événement
            $event->delete();

            // Retourner une réponse de succès
            return response()->json(['message' => 'Event deleted successfully'], 200);
        }

}
