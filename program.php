// routes/web.php

use App\Http\Controllers\EventController;

Route::get('/events', [EventController::class, 'index'])->name('events.index');
Route::post('/events', [EventController::class, 'store'])->name('events.store');
Route::delete('/events/{id}', [EventController::class, 'destroy'])->name('events.destroy');

// app/Http/Controllers/EventController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use Illuminate\Support\Facades\Mail;
use App\Mail\EventNotification;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::all();
        return view('events.index', compact('events'));
    }

    public function store(Request $request)
    {
        $event = Event::create($request->all());

        // Send email notification
        Mail::to('members@houseofgrace.org')->send(new EventNotification($event));

        return redirect()->route('events.index')->with('success', 'Event Created & Notified!');
    }

    public function destroy($id)
    {
        Event::findOrFail($id)->delete();
        return redirect()->route('events.index')->with('success', 'Event Deleted!');
    }
}

// app/Models/Event.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'description', 'date', 'time'];
}

// resources/views/events/index.blade.php

@extends('layouts.app')
@section('content')
<div class="container">
    <h2>Event Scheduling</h2>
    <form action="{{ route('events.store') }}" method="POST">
        @csrf
        <input type="text" name="title" placeholder="Event Title" required>
        <input type="text" name="description" placeholder="Event Description" required>
        <input type="date" name="date" required>
        <input type="time" name="time" required>
        <button type="submit">Schedule Event</button>
    </form>
    <h3>Upcoming Events</h3>
    <ul>
        @foreach($events as $event)
        <li>{{ $event->title }} - {{ $event->date }} at {{ $event->time }}
            <form action="{{ route('events.destroy', $event->id) }}" method="POST" style="display:inline;">
                @csrf @method('DELETE')
                <button type="submit">Delete</button>
            </form>
        </li>
        @endforeach
    </ul>
</div>
@endsection

// app/Mail/EventNotification.php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Event;

class EventNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $event;

    public function __construct(Event $event)
    {
        $this->event = $event;
    }

    public function build()
    {
        return $this->subject('New Church Event Scheduled')->view('emails.event_notification');
    }
}

// resources/views/emails/event_notification.blade.php

<!DOCTYPE html>
<html>
<head>
    <title>New Event Notification</title>
</head>
<body>
    <h2>{{ $event->title }}</h2>
    <p>{{ $event->description }}</p>
    <p>Date: {{ $event->date }} | Time: {{ $event->time }}</p>
</body>
</html>

// Run migrations
// php artisan make:migration create_events_table --create=events

// database/migrations/xxxx_xx_xx_create_events_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->date('date');
            $table->time('time');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('events');
    }
};
