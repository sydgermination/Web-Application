@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2 class="text-center mb-4">📚 My Booked Events</h2>
    <div class="table-responsive">
        <table class="table table-bordered table-striped text-center">
            <thead class="table-primary">
                <tr>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Date & Time</th>
                    <th>Location</th>
                    <th>Tickets</th>
                </tr>
            </thead>
            <tbody id="bookedEventsTable">
                <tr><td colspan="5">Loading booked events...</td></tr>
            </tbody>
        </table>
    </div>
</div>

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
const currentUserId = "{{ auth()->id() }}";

const bookingsRef = ref(db, 'bookings');
const eventsRef = ref(db, 'events');
const tableBody = document.getElementById('bookedEventsTable');

onValue(bookingsRef, (bookingSnapshot) => {
    const bookings = bookingSnapshot.val();
    const bookedEventIds = [];

    for (let key in bookings) {
        if (bookings[key].user_id == currentUserId) {
            bookedEventIds.push(bookings[key].event_id);
        }
    }

    onValue(eventsRef, (eventSnapshot) => {
        const events = eventSnapshot.val();
        tableBody.innerHTML = '';

        bookedEventIds.forEach(eventId => {
            const event = events[eventId];
            if (event) {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${event.title}</td>
                    <td>${event.description}</td>
                    <td>${event.start_date} ${event.start_time}</td>
                    <td>${event.address}</td>
                    <td>${event.tickets}</td>
                `;
                tableBody.appendChild(row);
            }
        });

        if (bookedEventIds.length === 0) {
            tableBody.innerHTML = `<tr><td colspan="5">You have no booked events.</td></tr>`;
        }
    }, { onlyOnce: true });

}, { onlyOnce: true });
</script>
@endsection
