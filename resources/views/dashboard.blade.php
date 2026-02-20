<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Omnific Machine Test</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
</head>
<body>
    @include('modals.lightbox')
    @include('modals.view-modal')
    @if(auth()->user()->role === 'admin')
        @include('modals.entry-modal')
    @endif

    <nav>
        <div style="font-weight: 600; font-size: 1.125rem;">Omnific Machine Test</div>
        <div style="display: flex; align-items: center; gap: 1rem;">
            <span class="badge badge-role">{{ auth()->user()->role }}</span>
            <span style="font-weight: 500;">{{ auth()->user()->name }}</span>
            <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                @csrf
                <button type="submit" class="secondary">Logout</button>
            </form>
        </div>
    </nav>

    <div class="container">
        <!-- Entries Display Section -->
        <div class="card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                <h2 style="margin: 0;">Entries</h2>
                @if(auth()->user()->role === 'admin')
                    <button onclick="openEntryModal()" style="display: flex; align-items: center; gap: 0.5rem;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                        </svg>
                        Create New Entry
                    </button>
                @endif
            </div>
            <div style="overflow-x: auto;">
                <table id="entries-table">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Amount</th>
                            <th id="th-staff">Assigned Staff</th>
                            <th id="th-action">Action</th>
                        </tr>
                    </thead>
                    <tbody id="entries-table-body">
                      <!-- AJAX -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        const API_URL = '/api/entries';
        const USER_ROLE = '{{ auth()->user()->role }}';
    </script>
    <script src="{{ asset('js/dashboard.js') }}"></script>
</body>
</html>
