<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@17.0.8/build/css/intlTelInput.min.css">
    <title>Create Booking</title>
</head>

<body>
    @extends('layouts.app')

    @section('content')
        <div class="container">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

        @section('breadcrumb')
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('bookings.index') }}">Transaction</a></li>
            <li class="breadcrumb-item active" aria-current="page">Create Booking</li>
        @endsection

        @if (session('alert'))
            <div class="alert alert-{{ session('alert')['type'] }}">{{ session('alert')['message'] }}</div>
        @endif

        <div class="card">
            <div class="card-header bg-primary text-white">
                <h2>Booking Page</h2>
            </div>
            <div class="card-body">
                <form action="{{ route('bookings.store') }}" method="POST" id="bookingForm">
                    @csrf

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Customer Name <span class="text-danger">*</span></label>
                            <input type="text" name="customer_name" class="form-control"
                                value="{{ old('customer_name') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Phone Number <span class="text-danger">*</span></label><p>
                            <input type="tel" name="phone_number" id="phone_number" class="form-control"
                                value="{{ old('phone_number') }}" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Booking Date <span class="text-danger">*</span></label>
                            <input type="date" name="booking_date" class="form-control" min="{{ date('Y-m-d') }}"
                                value="{{ old('booking_date') }}" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Console <span class="text-danger">*</span></label>
                            <select name="console_id" class="form-select" id="consoleSelect" required>
                                <option value="">Choose Console</option>
                                @foreach ($consoles as $console)
                                    <option value="{{ $console->id_console }}" data-price="{{ $console->price }}"
                                        {{ old('console_id') == $console->id_console ? 'selected' : '' }}>
                                        {{ $console->typeConsole }} ({{ $console->consoleRoom }})
                                        <!-- Gabungan typeConsole dan consoleRoom -->
                                    </option>
                                @endforeach
                            </select>
                        </div>



                        <div class="col-md-4">
                            <label class="form-label">Start Time <span class="text-danger">*</span></label>
                            <select name="start_time" class="form-select" required>
                                @foreach ($timeSlots as $time)
                                    <option value="{{ $time }}"
                                        {{ old('start_time') == $time ? 'selected' : '' }}>{{ $time }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Duration (hour) <span class="text-danger">*</span></label>
                            <select name="estimated_hours" class="form-select" id="durationSelect" required>
                                @for ($i = 1; $i <= 8; $i++)
                                    <option value="{{ $i }}"
                                        {{ old('estimated_hours') == $i ? 'selected' : '' }}>{{ $i }} hour
                                    </option>
                                @endfor
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Payment Methods <span class="text-danger">*</span></label>
                            <select name="payment_type" class="form-select" required>
                                <option value="Cash" {{ old('payment_type') == 'Cash' ? 'selected' : '' }}>Cash
                                </option>
                                <option value="Transfer" {{ old('payment_type') == 'Transfer' ? 'selected' : '' }}>
                                    Transfer
                                </option>
                                <option value="QRIS" {{ old('payment_type') == 'QRIS' ? 'selected' : '' }}>QRIS
                                </option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Status <span class="text-danger">*</span></label>
                            <select name="status" class="form-select" required>
                                <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending
                                </option>
                                <option value="success" {{ old('status') == 'success' ? 'selected' : '' }}>Success
                                </option>
                                <option value="canceled" {{ old('status') == 'canceled' ? 'selected' : '' }}>Canceled
                                </option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label class="form-label">Total Payment</label>
                            <input type="text" id="totalPayment" class="form-control" value="Rp0" readonly>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Selected Games <span class="text-danger">*</span></label>
                        <select name="selected_games[]" class="form-select" multiple required>
                            @foreach (['FIFA 23', 'Call of Duty', 'NBA 2K23', 'Mortal Kombat', 'Forza Horizon'] as $game)
                                <option value="{{ $game }}"
                                    {{ in_array($game, old('selected_games', [])) ? 'selected' : '' }}>
                                    {{ $game }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Playing Status <span class="text-danger">*</span></label>
                        <select name="playing_status" class="form-select" required>
                            <option value="Play" {{ old('playing_status') == 'Play' ? 'selected' : '' }}>Play
                            </option>
                            <option value="Not Play" {{ old('playing_status') == 'Not Play' ? 'selected' : '' }}>Not
                                Play
                            </option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">Create Booking</button>
                    <a href="{{ route('bookings.index') }}" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@17.0.8/build/js/intlTelInput.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const phoneInput = document.querySelector("#phone_number");

            const iti = window.intlTelInput(phoneInput, {
                initialCountry: "ID", // Set initial country as Indonesia, you can change this
                separateDialCode: true, // Display country dial code
                utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@17.0.8/build/js/utils.js", // For validation
            });

            // On form submission, validate the phone number using intlTelInput's isValidNumber method
            const bookingForm = document.getElementById("bookingForm");
            bookingForm.addEventListener('submit', function(event) {
                if (!iti.isValidNumber()) {
                    event.preventDefault();
                    alert('Please enter a valid international phone number.');
                    phoneInput.focus(); // Focus back on the phone input field
                }
            });
            const consoleSelect = document.getElementById('consoleSelect');
            const durationSelect = document.getElementById('durationSelect');
            const totalPayment = document.getElementById('totalPayment');

            function calculateTotal() {
                const selectedOption = consoleSelect.options[consoleSelect.selectedIndex];
                const price = selectedOption ? parseFloat(selectedOption.dataset.price) || 0 : 0;
                const hours = parseInt(durationSelect.value) || 0;
                const total = price * hours;
                totalPayment.value = 'Rp' + total.toLocaleString('id-ID');
            }

            consoleSelect.addEventListener('change', calculateTotal);
            durationSelect.addEventListener('change', calculateTotal);

            calculateTotal();
        });
    </script>
@endsection

</body>

</html>
