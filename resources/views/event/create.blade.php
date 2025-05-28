@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="d-flex justify-content-center mb-4">
        <a href="{{ url('event/show') }}" class="nav-link-animated">
            <h3 class="font-weight-bold mx-3">All Events</h3>
        </a>
        <a href="{{ url('event/create') }}" class="nav-link-animated">
            <h3 class="font-weight-bold mx-3">Create New Event</h3>
        </a>
    </div>

    <style>
        .nav-link-animated h3 {
            position: relative;
            display: inline-block;
            color: #333;
            transition: color 0.3s ease;
        }

        .nav-link-animated h3::after {
            content: "";
            position: absolute;
            left: 0;
            bottom: -5px;
            width: 0%;
            height: 3px;
            background-color: #007BFF;
            transition: width 0.3s ease;
        }

        .nav-link-animated:hover h3 {
            color: #007BFF;
        }

        .nav-link-animated:hover h3::after {
            width: 100%;
        }

        .nav-link-animated:active h3 {
            color: #0056b3;
        }

        .input-styled {
            border: 2px solid #dee2e6;
            border-radius: 0.5rem;
            padding: 0.75rem;
            background-color: #f8f9fa;
            transition: all 0.3s ease-in-out;
        }

        .input-styled:focus {
            border-color: #0d6efd;
            background-color: #fff;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.2);
            outline: none;
        }

        .input-styled::placeholder {
            color: #6c757d;
            opacity: 0.8;
            font-style: italic;
        }
    </style>

    <div class="card shadow p-4">
        <form id="eventForm">
            @csrf
            <div class="form-row">
                <div class="form-group col-md-6">
                    <input type="text" id="title" placeholder="Enter event title" class="form-control input-styled" required>
                </div>
                <div class="form-group col-md-6">
                    <input type="text" id="slug" placeholder="Enter event slug (e.g. my-event)" class="form-control input-styled" required>
                </div>
                <div class="form-group col-md-6">
                    <input type="date" id="start_date" class="form-control input-styled" placeholder="Start Date" required>
                    <small class="form-text text-muted">Start date of the event</small>
                </div>
                <div class="form-group col-md-6">
                    <input type="date" id="end_date" class="form-control input-styled" placeholder="End Date" required>
                    <small class="form-text text-muted">End date of the event</small>
                </div>
                <div class="form-group col-md-6">
                    <input type="time" id="start_time" class="form-control input-styled" placeholder="Start Time" required>
                    <small class="form-text text-muted">Start time of the event</small>
                </div>
                <div class="form-group col-md-12">
    <input type="url" id="join_url" placeholder="Paste 'Join This Event' link here (e.g. Google Form)" class="form-control input-styled">
    <small class="form-text text-muted">This link will be used for users to join/register for your event.</small>
</div>
                <div class="form-group col-md-6">
                    <input type="text" id="address" placeholder="Event venue/address" class="form-control input-styled" required>
                </div>
                <div class="form-group col-md-12">
                    <input type="url" id="image_url" placeholder="Paste image URL here" class="form-control input-styled">
                </div>
                <div class="form-group col-md-12">
                    <textarea id="description" class="form-control input-styled" rows="4" placeholder="Write a short description of the event..."></textarea>
                </div>
                <!-- Contact Information Section -->
                <div class="form-group col-md-6">
                    <input type="text" id="contact_name" placeholder="Contact Name" class="form-control input-styled" required>
                </div>
                <div class="form-group col-md-6">
                    <input type="email" id="contact_email" placeholder="Contact Email" class="form-control input-styled" required>
                </div>
                <div class="form-group col-md-6">
                    <input type="text" id="contact_phone" placeholder="Contact Phone" class="form-control input-styled" required>
                </div>
            </div>
            <button type="submit" class="btn btn-primary w-100 mt-3 shadow-sm">Add Event</button>
        </form>
    </div>

    <script type="module">
        import { initializeApp } from "https://www.gstatic.com/firebasejs/9.22.2/firebase-app.js";
        import { getDatabase, ref, push, set } from "https://www.gstatic.com/firebasejs/9.22.2/firebase-database.js";

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

        // Get the user ID from Blade (if needed)
        const currentUserId = "{{ auth()->id() }}";

        document.getElementById('eventForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const data = {
    title: document.getElementById('title').value,
    slug: document.getElementById('slug').value,
    start_date: document.getElementById('start_date').value,
    end_date: document.getElementById('end_date').value,
    start_time: document.getElementById('start_time').value,
    address: document.getElementById('address').value,
    description: document.getElementById('description').value,
    image: document.getElementById('image_url').value,
    join_url: document.getElementById('join_url').value, // ✅ this line
    user_id: currentUserId,
    contact_name: document.getElementById('contact_name').value,
    contact_email: document.getElementById('contact_email').value,
    contact_phone: document.getElementById('contact_phone').value
};


            const newEventRef = push(eventsRef);
            await set(newEventRef, data);

            document.getElementById('eventForm').reset();
            alert('Event created!');
        });
    </script>
@endsection
