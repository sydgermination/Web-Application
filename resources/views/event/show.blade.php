@extends('layouts.app')
@section('navbar_home')
@if (Route::has('login'))
@auth
<li class="nav-item">
  <a class="nav-link text-dark" href="{{ url('/home') }}">Home</a>
</li>
@else
<li class="nav-item">
  <a class="nav-link text-dark" href="{{ route('login') }}">Login</a>
</li>

@if (Route::has('register'))
<li class="nav-item">
  <a class="nav-link text-dark" href="{{ route('register') }}">Register</a>
</li>
@endif
@endauth
@else
@endif
@endsection
@section('content')
<div class="container mt-5">
    <div class="d-flex justify-content-center mb-4 flex-wrap gap-3">
        <a href="{{ url('event/show') }}" class="nav-link-animated">
            <h3 class="font-weight-bold mx-3">All Events</h3>
        </a>
        @auth
            @if(Session::get('is_admin'))
                <a href="{{ url('event/create') }}" class="nav-link-animated">
                    <h3 class="font-weight-bold mx-3">Create New Event</h3>
                </a>
            @endif
        @endauth
    </div>

<style>
    /* Base settings */
    body {
        background-color:rgba(58, 19, 255, 0);
        font-family: 'Nunito', sans-serif;
        animation: fadeIn 1.5s ease-out;
        color: #333;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    /* Intro Section */
    .intro-section {
        background-color: rgba(255, 255, 255, 0.8);
        padding: 3rem;
        border-radius: 15px;
        box-shadow: 0 8px 16px rgba(83, 22, 196, 0.1);
        margin-bottom: 30px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .intro-section:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
    }

    .intro-section h2 {
        font-size: 2.2rem;
        color: #1e3a8a;
        text-align: center;
        margin-bottom: 20px;
        animation: slideIn 1s ease-out;
    }

    @keyframes slideIn {
        from { transform: translateX(-50px); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }

    .intro-section p, .intro-section ul li {
        animation: fadeIn 1.2s ease-out;
    }

    .intro-section p {
        font-size: 1.1rem;
        line-height: 1.8;
        text-align: center;
        margin-bottom: 20px;
    }

    .intro-section ul {
        list-style-type: none;
        padding-left: 0;
        text-align: center;
        margin-top: 20px;
    }

    .intro-section ul li {
        font-size: 1.1rem;
        color: #1e3a8a;
        margin: 10px 0;
    }

    .intro-section ul li i {
        color: #4c6ef5;
        margin-right: 10px;
    }

    /* Event List */
    #eventList .card {
        border-radius: 12px;
        background-color:rgb(255, 255, 255);
        border: 1px solid #4c6ef5;
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.34);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    #eventList .card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
    }

    #eventList .card-img-top {
        height: 200px;
        object-fit: cover;
        border-top-left-radius: 12px;
        border-top-right-radius: 12px;
    }

    #eventList .card-title {
        font-size: 1.3rem;
        font-weight: bold;
        color: #1e3a8a;
    }

    #eventList .card-text {
        color: #333;
        font-size: 0.95rem;
        max-height: 100px;
        overflow: auto;
        word-wrap: break-word;
        white-space: normal;
    }

    #eventList .btn {
        background-color:rgb(31, 45, 100);
        color: white;
        font-weight: 600;
        border: none;
        border-radius: 6px;
        transition: background-color 0.3s ease;
    }

    #eventList .btn:hover {
        background-color: #1e3a8a;
    }

    .booked-floating-btn {
        position: fixed;
        bottom: 30px;
        right: 30px;
        z-index: 1055;
        border-radius: 50px;
        padding: 14px 28px;
        font-size: 17px;
        box-shadow: 0 6px 16px rgba(0, 123, 255, 0.35);
        background: linear-gradient(45deg, #007BFF, #0056b3);
        color: white;
        border: none;
        transition: box-shadow 0.3s ease, background 0.3s ease;
    }

    .booked-floating-btn:hover {
        background: linear-gradient(45deg, #0056b3, #003d80);
    }

    .modal-content {
        border-radius: 1rem !important;
    }

    .modal-header.bg-primary {
        background: linear-gradient(45deg, #007BFF, #0056b3);
        color: white;
    }

    /* Footer */
    footer {
        background-color: #1e3a8a;
        color: white;
        padding: 1rem;
        text-align: center;
        margin-top: 30px;
        border-radius: 10px;
        box-shadow: 0 -5px 15px rgba(0, 0, 0, 0.1);
    }

    footer a {
        color: #ffd700;
        text-decoration: none;
    }

    footer a:hover {
        text-decoration: underline;
    }

    @media (max-width: 640px) {
        #eventList .card {
            margin: 10px;
            padding: 15px;
            max-width: 90%;
        }
    }
</style>


    <div id="eventList" class="row"></div>
</div>

<!-- View Modal (existing) -->
<div class="modal fade animated-modal" id="viewModal" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" style="max-width: 800px;">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header bg-primary text-white rounded-top-4">
                <h5 class="modal-title" id="viewModalLabel">🎉 Event Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <!-- Modal content as before -->
                <div class="row">
                    <div class="col-md-5">
                        <img id="modalImage" class="img-fluid rounded-3 shadow-sm" style="max-height: 300px; object-fit: cover;">
                    </div>
                    <div class="col-md-7">
                        <h4 id="modalTitle" class="fw-bold mb-3 text-primary"></h4>
                        <p id="modalDescription" class="text-muted mb-3"></p>
                        <div class="row mt-4">
                            <div class="col-md-6">
                                <p><strong>📅 Start:</strong> <span id="modalStartDate"></span> at <span id="modalStartTime"></span></p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>⏰ End:</strong> <span id="modalEndDate"></span></p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>📍 Address:</strong> <span id="modalAddress"></span></p>
                            </div>
                            
                        </div>
                        <div class="mt-4">
                            <p><strong>Created by:</strong> <span id="modalCreator"></span></p>
                            <p><strong>Contact Name:</strong> <span id="modalContactName"></span></p>
                            <p><strong>Contact Email:</strong> <span id="modalContactEmail"></span></p>
                            <p><strong>Contact Phone:</strong> <span id="modalContactPhone"></span></p>
                        </div>
                        <div class="mt-3">
    <a id="modalJoinUrl" href="#" target="_blank" class="btn btn-success w-100" style="display: none;">🔗 Join This Event</a>
</div>
                        <button id="bookEventBtn" class="btn btn-primary mt-3" data-id="">Join This Event</button>
                        <button id="unbookEventBtn" class="btn btn-danger mt-3" data-id="" style="display:none;">Unjoin This Event</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" style="max-width: 800px;">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <!-- Changed bg-warning to a blue gradient and text to white -->
            <div class="modal-header rounded-top-4" style="background: linear-gradient(45deg, #007BFF, #0056b3); color: white;">
                <h5 class="modal-title" id="editModalLabel">✏️ Edit Event</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="editEventForm">
                    <input type="hidden" id="editEventId">
                    <div class="row">
                        <div class="form-group col-md-6 mb-3">
                            <label class="form-label">Title</label>
                            <input type="text" id="editTitle" class="form-control border-primary" style="box-shadow: none;">
                        </div>
                        <div class="form-group col-md-6 mb-3">
                            <label class="form-label">Slug</label>
                            <input type="text" id="editSlug" class="form-control border-primary" placeholder="e.g. my-event" style="box-shadow: none;">
                        </div>
                        <div class="form-group col-md-6 mb-3">
                            <label class="form-label">Start Date</label>
                            <input type="date" id="editStartDate" class="form-control border-primary" style="box-shadow: none;">
                            <small class="form-text text-muted">Start date of the event</small>
                        </div>
                        <div class="form-group col-md-6 mb-3">
                            <label class="form-label">End Date</label>
                            <input type="date" id="editEndDate" class="form-control border-primary" style="box-shadow: none;">
                            <small class="form-text text-muted">End date of the event</small>
                        </div>
                        <div class="form-group col-md-6 mb-3">
                            <label class="form-label">Start Time</label>
                            <input type="time" id="editStartTime" class="form-control border-primary" style="box-shadow: none;">
                            <small class="form-text text-muted">Start time of the event</small>
                        </div>
                        <div class="form-group col-md-6 mb-3">
                            <label class="form-label">Venue/Address</label>
                            <input type="text" id="editAddress" class="form-control border-primary" style="box-shadow: none;">
                        </div>
                        <div class="form-group col-md-12 mb-3">
                            <label class="form-label">Image URL</label>
                            <input type="url" id="editImageUrl" class="form-control border-primary" style="box-shadow: none;">
                        </div>
                        <div class="form-group col-md-12 mb-3">
                            <label class="form-label">Description</label>
                            <textarea id="editDescription" class="form-control border-primary" rows="4" style="box-shadow: none;"></textarea>
                        </div>
                        <div class="form-group col-md-6 mb-3">
                            <label class="form-label">Contact Name</label>
                            <input type="text" id="editContactName" class="form-control border-primary" style="box-shadow: none;">
                        </div>
                        <div class="form-group col-md-6 mb-3">
                            <label class="form-label">Contact Email</label>
                            <input type="email" id="editContactEmail" class="form-control border-primary" style="box-shadow: none;">
                        </div>
                        <div class="form-group col-md-6 mb-3">
                            <label class="form-label">Contact Phone</label>
                            <input type="text" id="editContactPhone" class="form-control border-primary" style="box-shadow: none;">
                        </div>
                    </div>
                    <div class="text-end mt-3">
                        <!-- Changed btn-warning to btn-primary -->
                        <button type="submit" class="btn btn-primary" style="background: linear-gradient(45deg, #007BFF, #0056b3); border:none;">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<!-- Booked Events Modal -->
<div class="modal fade" id="bookedModal" tabindex="-1" aria-labelledby="bookedModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-scrollable">
    <div class="modal-content border-0 shadow rounded-4">
      
      <!-- Header -->
      <div class="modal-header rounded-top-4 text-white" style="background: linear-gradient(90deg, #0d6efd, #0056b3);">
        <h5 class="modal-title d-flex align-items-center gap-2 mb-0" id="bookedModalLabel">
          🎫 Your Joined Events
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <!-- Body -->
      <div class="modal-body bg-light px-4 py-3">
        <div id="bookedEventsList" class="row g-4">
          <!-- Example Card Template -->
          <!-- This will be dynamically inserted -->
        </div>
      </div>

    </div>
  </div>
</div>



<!-- Floating Booked Button -->
<button id="bookedBtn" class="btn btn-success booked-floating-btn">Joined (0)</button>

<script type="module">
import { initializeApp } from "https://www.gstatic.com/firebasejs/9.22.2/firebase-app.js";
import { getDatabase, ref, onValue, remove, push, get, set } from "https://www.gstatic.com/firebasejs/9.22.2/firebase-database.js";


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
const bookingsRef = ref(db, 'bookings');
const currentUserId = "{{ auth()->id() }}";

const eventList = document.getElementById('eventList');
const bookEventBtn = document.getElementById('bookEventBtn');
const unbookEventBtn = document.getElementById('unbookEventBtn');
const bookedBtn = document.getElementById('bookedBtn');
const bookedEventsList = document.getElementById('bookedEventsList');

let allEvents = {};
let userBookings = [];

onValue(eventsRef, (snapshot) => {
    eventList.innerHTML = '';
    allEvents = snapshot.val() || {};
    renderEvents();
});

function renderEvents() {
    for (let key in allEvents) {
        const event = allEvents[key];
        const isOwner = event.user_id == currentUserId;
        const card = document.createElement('div');
        card.className = 'col-md-4 mb-4';
        card.innerHTML = `
            <div class="card h-100 shadow-sm">
                <img src="${event.image}" class="card-img-top" style="height: 200px; object-fit: cover;">
                <div class="card-body">
                    <h5 class="card-title">${event.title}</h5>
                    <p class="card-text">${event.description}</p>
                    <small class="text-muted">${event.start_date} at ${event.start_time}</small>
                    <div class="mt-3">
                        <button class="btn btn-sm btn-info view-btn" data-id="${key}">View</button>
                        ${isOwner ? `
                            <button class="btn btn-sm btn-warning edit-btn" data-id="${key}">Edit</button>
                            <button class="btn btn-sm btn-danger delete-btn" data-id="${key}">Delete</button>
                        ` : ''}
                    </div>
                </div>
            </div>
        `;
        eventList.appendChild(card);
    }

    attachEventListeners();
}

function attachEventListeners() {
    document.querySelectorAll('.view-btn').forEach(btn => {
        btn.addEventListener('click', (e) => {
            const key = e.target.dataset.id;
            const event = allEvents[key];

            document.getElementById('modalImage').src = event.image;
            document.getElementById('modalTitle').textContent = event.title;
            document.getElementById('modalDescription').textContent = event.description;
            document.getElementById('modalStartDate').textContent = event.start_date;
            document.getElementById('modalStartTime').textContent = event.start_time;
            document.getElementById('modalEndDate').textContent = event.end_date;
            document.getElementById('modalAddress').textContent = event.address;
            // document.getElementById('modalTickets').textContent = event.tickets;
            document.getElementById('modalCreator').textContent = event.creatorName;
            document.getElementById('modalContactName').textContent = event.contact_name;
            document.getElementById('modalContactEmail').textContent = event.contact_email;
            document.getElementById('modalContactPhone').textContent = event.contact_phone;

            bookEventBtn.dataset.id = key;
            unbookEventBtn.dataset.id = key;

            get(bookingsRef).then(snapshot => {
                const bookings = snapshot.val() || {};
                const isBooked = Object.values(bookings).some(b => b.user_id === currentUserId && b.event_id === key);
                bookEventBtn.style.display = isBooked ? 'none' : 'inline-block';
                unbookEventBtn.style.display = isBooked ? 'inline-block' : 'none';
            });

            new bootstrap.Modal(document.getElementById('viewModal')).show();
        });
    });
    document.querySelectorAll('.edit-btn').forEach(btn => {
    btn.addEventListener('click', (e) => {
        const key = e.target.dataset.id;
        const event = allEvents[key];

        // Fill form fields with current event data
        document.getElementById('editEventId').value = key;
        document.getElementById('editTitle').value = event.title;
        document.getElementById('editDescription').value = event.description;
        
        // If you have more fields like image, date, etc., populate them too

        new bootstrap.Modal(document.getElementById('editModal')).show();
    });
});
    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', async (e) => {
            const key = e.target.dataset.id;
            if (confirm('Are you sure you want to delete this event? Action cannot be undone.')) {
                await remove(ref(db, 'events/' + key));
                alert('Event deleted.');
            }
        });
    });
}
document.getElementById('editEventForm').addEventListener('submit', async function (e) {
    e.preventDefault();

    const key = document.getElementById('editEventId').value;

    // Gather all fields
    const updatedEvent = {
        ...allEvents[key], // Keep existing fields
        title: document.getElementById('editTitle').value.trim(),
        slug: document.getElementById('editSlug').value.trim(),
        start_date: document.getElementById('editStartDate').value,
        end_date: document.getElementById('editEndDate').value,
        start_time: document.getElementById('editStartTime').value,
        address: document.getElementById('editAddress').value.trim(),
        image_url: document.getElementById('editImageUrl').value.trim(),
        description: document.getElementById('editDescription').value.trim(),
        contact_name: document.getElementById('editContactName').value.trim(),
        contact_email: document.getElementById('editContactEmail').value.trim(),
        contact_phone: document.getElementById('editContactPhone').value.trim()
    };

    // Simple validation: check for empty required values
    const hasEmpty = Object.values(updatedEvent).some(value => value === '');
    if (hasEmpty) {
        alert('⚠️ Please fill out all fields before saving.');
        return;
    }

    // Update in Firebase
    await set(ref(db, 'events/' + key), updatedEvent);

    alert('✅ Event updated successfully!');
    bootstrap.Modal.getInstance(document.getElementById('editModal')).hide();
});


bookEventBtn.addEventListener('click', async function () {
    const bookingData = {
        event_id: this.dataset.id,
        user_id: currentUserId,
        booked_at: new Date().toISOString()
    };

    
    await push(bookingsRef, bookingData);
    alert('✅ Event successfully joined!');
    bootstrap.Modal.getInstance(document.getElementById('viewModal')).hide();
});

unbookEventBtn.addEventListener('click', async function () {
    const bookingSnap = await get(bookingsRef);
    const eventId = this.dataset.id;
    const bookings = bookingSnap.val();
    for (let key in bookings) {
        if (bookings[key].event_id === eventId && bookings[key].user_id === currentUserId) {
            await remove(ref(db, 'bookings/' + key));
            break;
        }
    }
    alert('✅ Event successfully unjoined!');
    bootstrap.Modal.getInstance(document.getElementById('viewModal')).hide();
});

// Floating booked button logic
bookedBtn.addEventListener('click', async () => {
    const snapshot = await get(bookingsRef);
    const allBookings = snapshot.val() || {};
    userBookings = Object.values(allBookings).filter(b => b.user_id === currentUserId);

    bookedEventsList.innerHTML = '';
    

function formatDate(dateString) {
    const options = { year: 'numeric', month: 'long', day: 'numeric' };
    const dateObj = new Date(dateString);
    if (isNaN(dateObj)) return dateString;  // fallback if invalid date
    return dateObj.toLocaleDateString(undefined, options);
}
userBookings.forEach((b, index) => {
    const ev = Object.entries(allEvents).find(([id]) => id === b.event_id);
    if (ev) {
        const [id, e] = ev;
        const bookingKey = Object.keys(allBookings).find(
            key => allBookings[key].event_id === b.event_id && allBookings[key].user_id === currentUserId
        );

        const registerBtn = e.join_url && e.join_url.trim() !== ''
            ? `<a href="${e.join_url}" target="_blank" class="btn btn-outline-primary" style="width: 150px; height: 40px;">
                    Register to Event
               </a>`
            : '';

        const div = document.createElement('div');
        div.className = 'col-md-6 mb-4';
        div.innerHTML = `
            <div class="card h-100 shadow-sm">
                <img src="${e.image}" class="card-img-top" style="height: 250px; object-fit: cover;">
                <div class="card-body d-flex flex-column justify-content-between">
                    <div>
                        <h5 class="card-title">${e.title}</h5>
                        <p class="card-text">${e.description}</p>
                        <small class="text-muted">${formatDate(e.start_date)} at ${e.start_time}</small>
                    </div>
                    <div class="mt-3 d-flex justify-content-center" style="gap: 20px;">
                        <button class="btn btn-outline-danger unbook-btn" style="width: 150px; height: 40px;" data-booking-key="${bookingKey}">
                            Unbook
                        </button>
                        ${registerBtn}
                    </div>
                </div>
            </div>`;
        bookedEventsList.appendChild(div);
    }
});





// Attach unbook event listeners
setTimeout(() => {
    document.querySelectorAll('.unbook-btn').forEach(btn => {
        btn.addEventListener('click', async function () {
            const bookingKey = this.dataset.bookingKey;
            if (confirm('Are you sure you want to unbook this event?')) {
                await remove(ref(db, 'bookings/' + bookingKey));
                alert('❌ Event unbooked.');
                bootstrap.Modal.getInstance(document.getElementById('bookedModal')).hide();
            }
        });
    });
}, 100);

    new bootstrap.Modal(document.getElementById('bookedModal')).show();
});

// Update float button count
onValue(bookingsRef, (snapshot) => {
    const bookings = snapshot.val() || {};
    const count = Object.values(bookings).filter(b => b.user_id === currentUserId).length;
    bookedBtn.textContent = `Booked (${count})`;
});
</script>
@endsection
