<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Event;
use App\Models\User;
use App\Models\Objetivo;

class EventController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $avs = $user->avs;

        $search = request('search');

        if ($search) {
            $avs = $avs::where([
                ['title', 'like', '%'.$search. '%']
            ])->get();
        }

        return view('welcome', ['avs' => $avs, 'search' => $search]);
    }

    public function avs()
    {
        $user = auth()->user();
        $events = $user->events;
        return view('events.avs', ['events' => $events]);
    }

    public function create()
    {
        $objetivos = Objetivo::all();
        return view('events.create', ['objetivos' => $objetivos]);
    }

    public function store(Request $request)
    {
        $event = new Event();

        $event->title = $request->title;
        $event-> date = $request->date;
        $event->city = $request->city;
        $event->private = $request->private;
        $event->description = $request->description;
        $event->items = $request->items;

        // Imagem upload
        if($request->hasFile('image') && $request->file('image')->isValid())
        {
            $requestImage = $request->image;

            $extension = $requestImage->extension();

            $imageName = md5($requestImage->getClientOriginalName() . strtotime("now")) . "." . $extension;
            
            $requestImage->move(public_path('img/events'), $imageName);

            $event->image = $imageName;

        }
        $user = auth()->user();
        $event->user_id = $user->id;

        $event->save();

        return redirect('/')->with('success', 'AV criada com sucesso!');
    }

    public function show($id)
    {
        $event = Event::findOrFail($id);

        $eventOwner = User::where('id', $event->user_id)->first()->toArray();

        return view('events.show', ['event' => $event, 'eventOwner' =>$eventOwner]);
    }

    public function dashboard()
    {
        $search = request('search');

        if ($search) {
            $events = Event::where([
                ['title', 'like', '%'.$search. '%']
            ])->get();
        } else {
            $events = Event::all();
        }

        $user = auth()->user();
        $events = $user->events;

        return view('events.dashboard', ['events' => $events], ['search' => $search]);
    }

    public function destroy($id)
    {
        $event = Event::findOrFail($id)->delete();

        return redirect('/dashboard')->with('msg', 'Event excluído com sucesso!');
    }

    public function edit($id)
    {
        $user = auth()->user();

        $event = Event::findOrFail($id);

        if($user->id != $event->user->id) {
            return redirect('/dashboard')->with('msg', 'Você não tem permissão para editar este evento!');
        }

        return view('events.edit', ['event' => $event]);
    }

    public function update(Request $request)
    {
        $data = $request->all();

        //Image upload
        if($request->hasFile('image') && $request->file('image')->isValid())
        {
            $requestImage = $request->image;

            $extension = $requestImage->extension();

            $imageName = md5($requestImage->getClientOriginalName() . strtotime("now")) . "." . $extension;
            
            $requestImage->move(public_path('img/events'), $imageName);

            $data['image'] = $imageName;

        }

        Event::findOrFail($request->id)->update($data);

        return redirect('/dashboard')->with('msg', 'Event editado com sucesso!');
    }

    public function joinEvent($id)
    {
        $user = auth()->user();

        //$user->eventsAsParticipant()->attach($id);

        $event = Event::findOrFail($id);

        return redirect('/dashboard')->with('msg', 'Sua presença está confirmada no evento ' . $event->title);
    }
}
