@extends('layouts.app')

@section('navbar_home')
@if (Route::has('login'))
  @auth
    <li class="nav-item">
      <a class="nav-link text-dark" href="{{ url('/home') }}">Dashboard</a>
    </li>
  @else
    <li class="nav-item">
      <a class="nav-link text-dark" href="{{ route('login') }}">Login</a>
    </li>
  @endauth
@endif
@endsection

@section('content')
<!-- Modern Split Hero Section -->
<section class="d-flex flex-column flex-lg-row align-items-center justify-content-between bg-light" style="min-height: 90vh;">
    <!-- Left Side: Text Content -->
    <div class="col-lg-6 p-5 text-center text-lg-start">
        <h1 class="display-3 fw-bold mb-3" style="font-family: var(--heading-font); color: var(--bs-dark);">
            🎓 PCU EventEase
        </h1>
        <p class="lead mb-4" style="max-width: 90%; color: var(--accent-color);">
            Real-time event management for PCU-M. Secure, fast, and efficient—built for organizers and students.
        </p>
        <div class="d-flex justify-content-center justify-content-lg-start gap-3">
            <a href="#calendar" class="btn btn-primary btn-pill btn-medium">📅 View Calendar</a>
            <a href="#about" class="btn btn-outline-dark btn-pill btn-medium">Learn More</a>
        </div>
    </div>

    <!-- Right Side: Image -->
    <div class="col-lg-6 p-0">
        <div style="
            background: linear-gradient(rgba(0,0,0,0.3), rgba(0,0,0,0.3)), url('{{ asset('pcu.png') }}') center center / cover no-repeat;
            height: 90vh;
        "></div>
    </div>
</section>

<!-- Feature Highlights -->
<div class="container-fluid padding-medium bg-gray-1">
    <section class="row text-center justify-content-center mb-5">
        <div class="col-md-3 mb-4">
            <i class="fas fa-bolt fa-2x text-primary mb-2"></i>
            <h5 class="fw-bold">Real-time Updates</h5>
            <p class="text-muted">All event changes sync instantly via Firebase.</p>
        </div>
        <div class="col-md-3 mb-4">
            <i class="fas fa-envelope-open-text fa-2x text-primary mb-2"></i>
            <h5 class="fw-bold">Email Verification</h5>
            <p class="text-muted">Only verified users can access the platform.</p>
        </div>
        <div class="col-md-3 mb-4">
            <i class="fas fa-calendar-plus fa-2x text-primary mb-2"></i>
            <h5 class="fw-bold">Join via Google Forms</h5>
            <p class="text-muted">Users register through linked forms.</p>
        </div>
        <div class="col-md-3 mb-4">
            <i class="fas fa-user-shield fa-2x text-primary mb-2"></i>
            <h5 class="fw-bold">Admin-Controlled Events</h5>
            <p class="text-muted">Only admins can add, edit, or delete events.</p>
        </div>
    </section>

    <!-- Event Calendar -->
    <section id="calendar-section" class="bg-light p-5 rounded shadow">
        <h2 class="text-center mb-4" style="font-family: var(--heading-font); color: var(--bs-dark);">
            📆 Event Calendar
        </h2>
        <div id="calendar"></div>
    </section>
</div>

<!-- FullCalendar & Firebase Scripts -->
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
<script type="module">
    import { initializeApp } from "https://www.gstatic.com/firebasejs/9.22.2/firebase-app.js";
    import { getDatabase, ref, onValue } from "https://www.gstatic.com/firebasejs/9.22.2/firebase-database.js";

    const firebaseConfig = {
        apiKey: "AIzaSyA0N-lN3pSjQbQeMbCVphhg__Q9Z0lMy8E",
        authDomain: "appdev2025.firebaseapp.com",
        databaseURL: "https://appdev2025-default-rtdb.firebaseio.com",
        projectId: "appdev2025",
        storageBucket: "appdev2025.appspot.com",
        messagingSenderId: "625769873003",
        appId: "1:625769873003:web:ad7041371631c9576a7b2e"
    };

    const app = initializeApp(firebaseConfig);
    const db = getDatabase(app);
    const eventsRef = ref(db, 'events');

    const calendarEl = document.getElementById('calendar');
    let calendarEvents = [];

    onValue(eventsRef, (snapshot) => {
        const data = snapshot.val();
        calendarEvents = [];

        if (data) {
            Object.entries(data).forEach(([id, event]) => {
                if (event.start_date && event.start_time) {
                    const startDateTime = `${event.start_date}T${event.start_time}:00`;
                    calendarEvents.push({
                        title: event.title || 'Untitled',
                        start: startDateTime,
                        description: event.description || 'No Description',
                        address: event.address || 'No Address',
                        url: event.googleFormLink || null
                    });
                }
            });

            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                height: 600,
                events: calendarEvents,
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay',
                },
                eventColor: '#9f1d1d',
                eventClick: function(info) {
                    if (info.event.url) {
                        window.open(info.event.url, '_blank');
                        info.jsEvent.preventDefault();
                    }
                }
            });
            calendar.render();
        } else {
            calendarEl.innerHTML = '<p class="text-center text-muted">No events found.</p>';
        }
    });
</script>
@endsection
