{{-- resources/views/booking/create.blade.php --}}
@extends('layouts.app')

@section('title', 'Buat Booking Baru')

@section('content')
<div class="py-12">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-gray-800 border-b border-gray-700">
                <h3 class="text-2xl font-bold text-white mb-6 text-center">Buat Booking Baru</h3>

                @if (session('success'))
                    <div class="bg-green-800 border border-green-700 text-green-200 px-4 py-3 rounded relative mb-4" role="alert">
                        <strong class="font-bold">Sukses!</strong>
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif
                @if (session('error'))
                    <div class="bg-red-800 border border-red-700 text-red-200 px-4 py-3 rounded relative mb-4" role="alert">
                        <strong class="font-bold">Error!</strong>
                        <span class="block sm:inline">{{ session('error') }}</span>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="bg-red-800 border border-red-700 text-red-200 px-4 py-3 rounded relative mb-4" role="alert">
                        <strong class="font-bold">Validasi Gagal!</strong>
                        <ul class="mt-1 list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('booking.store') }}" x-data="{
                    bookingType: '{{ Auth::check() ? 'online' : 'walk-in' }}',
                    services: {{ Js::from($services) }},
                    selectedServiceIds: {{ old('selectedServices') ? Js::from(old('selectedServices')) : '[]' }},
                    customerName: '{{ old('customer_name', Auth::user()->name ?? '') }}',
                    customerPhone: '{{ old('customer_phone', Auth::user()->phone ?? '') }}',
                    bookingDate: '{{ old('booking_date', date('Y-m-d')) }}',
                    bookingTime: '{{ old('booking_time', '') }}',
                    notes: '{{ old('notes', '') }}',
                    totalPrice: 0,
                    shopHours: {},
                    isShopClosed: true,
                    openTime: '',
                    closeTime: '',
                    bookingTimeError: '',
                    
                    calculateTotalPrice() {
                        this.totalPrice = this.services.filter(service => this.selectedServiceIds.includes(service.id))
                                                    .reduce((sum, service) => sum + service.price, 0);
                    },

                    async fetchShopHours() {
                        if (!this.bookingDate) {
                            this.isShopClosed = true;
                            this.openTime = '';
                            this.closeTime = '';
                            this.bookingTime = '';
                            this.bookingTimeError = '';
                            return;
                        }
                        try {
                            const response = await fetch('/api/shop-hours?date=' + this.bookingDate);
                            const data = await response.json();
                            this.shopHours = data;
                            this.isShopClosed = data.is_closed;
                            this.openTime = data.open_time;
                            this.closeTime = data.close_time;
                            this.updateBookingTimeDefault();
                            this.validateBookingTime();
                        } catch (e) {
                            console.error('Gagal mengambil jam toko:', e);
                            this.isShopClosed = true;
                            this.openTime = '';
                            this.closeTime = '';
                            this.bookingTime = '';
                            this.bookingTimeError = 'Gagal mengambil jam operasional.';
                        }
                    },
                    
                    updateBookingTimeDefault() {
                        if (this.isShopClosed) {
                            this.bookingTime = '';
                            return;
                        }

                        const currentDateTime = new Date();
                        const selectedDateObj = new Date(this.bookingDate);
                        const openTimeObj = new Date(`${this.bookingDate}T${this.openTime}`);
                        const closeTimeObj = new Date(`${this.bookingDate}T${this.closeTime}`);
                        
                        let suggestedTime = '';

                        if (selectedDateObj.toDateString() === currentDateTime.toDateString()) {
                            if (currentDateTime.getTime() >= closeTimeObj.getTime()) {
                                suggestedTime = ''; 
                            } else if (currentDateTime.getTime() <= openTimeObj.getTime()) {
                                suggestedTime = this.openTime;
                            } else {
                                const currentMinutes = currentDateTime.getHours() * 60 + currentDateTime.getMinutes();
                                let newMinutes = currentMinutes + 15;
                                let newHours = Math.floor(newMinutes / 60);
                                newMinutes = newMinutes % 60;
                                
                                const suggestedTimeDate = new Date(selectedDateObj);
                                suggestedTimeDate.setHours(newHours, newMinutes, 0, 0);

                                if (suggestedTimeDate.getTime() > closeTimeObj.getTime()) {
                                    suggestedTime = ''; 
                                } else {
                                    suggestedTime = `${String(suggestedTimeDate.getHours()).padStart(2, '0')}:${String(suggestedTimeDate.getMinutes()).padStart(2, '0')}`;
                                }
                            }
                        } else if (selectedDateObj.getTime() > currentDateTime.getTime()) {
                            suggestedTime = this.openTime;
                        } else {
                            suggestedTime = '';
                        }
                        
                        if (this.bookingTime !== suggestedTime) { 
                            this.bookingTime = suggestedTime;
                        }
                    },

                    validateBookingTime() {
                        this.bookingTimeError = '';
                        if (this.isShopClosed) {
                            this.bookingTimeError = 'Barbershop tutup pada tanggal ini. Silakan pilih tanggal lain.';
                            return false;
                        }
                        if (!this.bookingTime) {
                            this.bookingTimeError = 'Jam booking wajib diisi.';
                            return false;
                        }

                        const selectedDateObj = new Date(this.bookingDate);
                        const inputTimeParts = this.bookingTime.split(':');
                        const inputHour = parseInt(inputTimeParts[0]);
                        const inputMinute = parseInt(inputTimeParts[1]);

                        const dateTimeInput = new Date(selectedDateObj);
                        dateTimeInput.setHours(inputHour, inputMinute, 0, 0);

                        const openTimeObj = new Date(`${this.bookingDate}T${this.openTime}`);
                        const closeTimeObj = new Date(`${this.bookingDate}T${this.closeTime}`);
                        
                        if (dateTimeInput.getTime() < openTimeObj.getTime() || dateTimeInput.getTime() > closeTimeObj.getTime()) {
                            this.bookingTimeError = `Jam booking harus antara ${this.openTime} dan ${this.closeTime}.`;
                            return false;
                        }

                        const currentDateTime = new Date();
                        if (selectedDateObj.toDateString() === currentDateTime.toDateString() && dateTimeInput.getTime() < currentDateTime.getTime()) {
                            this.bookingTimeError = 'Jam booking tidak bisa di masa lalu.';
                            return false;
                        }
                        return true;
                    },

                    getMinBookingDate() {
                        const today = new Date();
                        return today.toISOString().split('T')[0];
                    }
                }"
                x-init="
                    calculateTotalPrice();
                    fetchShopHours();
                    $watch('selectedServiceIds', () => calculateTotalPrice()); // Sudah ada watcher
                    $watch('bookingDate', () => fetchShopHours());
                    $watch('bookingTime', () => validateBookingTime());
                    $watch('bookingType', (newVal) => {
                        if (newVal === 'online' && {{ Auth::check() ? 'true' : 'false' }}) {
                            customerName = '{{ Auth::user()->name ?? '' }}';
                            customerPhone = '{{ Auth::user()->phone ?? '' }}';
                        } else if (newVal === 'walk-in') {
                            customerName = '{{ old('customer_name', '') }}';
                            customerPhone = '{{ old('customer_phone', '') }}';
                        }
                    });
                ">
                    @csrf

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-300">Jenis Pelanggan</label>
                        <div class="mt-1 flex space-x-4 text-gray-200">
                            <label class="inline-flex items-center">
                                <input type="radio" x-model="bookingType" name="booking_type" value="online" class="form-radio text-yellow-500 rounded-full bg-gray-700 border-gray-600 focus:ring-yellow-500"
                                    {{ Auth::check() ? '' : 'disabled' }}>
                                <span class="ml-2">Booking Online</span>
                            </label>
                            <label class="items-center hidden">
                                <input type="radio" x-model="bookingType" name="booking_type" value="walk-in" class="form-radio text-yellow-500 rounded-full bg-gray-700 border-gray-600 focus:ring-yellow-500">
                                <span class="ml-2">Walk-in (Datang Langsung)</span>
                            </label>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="customer_name" class="block text-sm font-medium text-gray-300">Nama Pelanggan <span class="text-red-500">*</span></label>
                        <input type="text" x-model="customerName" name="customer_name" id="customer_name"
                            :readonly="bookingType === 'online' && {{ Auth::check() ? 'true' : 'false' }}"
                            class="mt-1 block w-full rounded-md border-gray-600 shadow-sm bg-gray-700 text-white focus:border-yellow-500 focus:ring focus:ring-yellow-500 focus:ring-opacity-50"
                            :class="{'bg-gray-700 text-gray-400': bookingType === 'online' && {{ Auth::check() ? 'true' : 'false' }}}">
                        @error('customer_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4 hidden">
                        <label for="customer_phone" class="block text-sm font-medium text-gray-300">Nomor Telepon</label>
                        <input type="tel" x-model="customerPhone" name="customer_phone" id="customer_phone"
                            :readonly="bookingType === 'online' && {{ Auth::check() ? 'true' : 'false' }}"
                            class="mt-1 block w-full rounded-md border-gray-600 shadow-sm bg-gray-700 text-white focus:border-yellow-500 focus:ring focus:ring-yellow-500 focus:ring-opacity-50"
                            :class="{'bg-gray-700 text-gray-400': bookingType === 'online' && {{ Auth::check() ? 'true' : 'false' }}}">
                        @error('customer_phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Layanan yang Dipilih --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-300">Layanan yang Dipilih <span class="text-red-500">*</span></label>
                        <div class="mt-2 grid grid-cols-1 md:grid-cols-2 gap-3">
                            @foreach ($services as $service)
                                <label class="inline-flex items-center bg-gray-700 p-3 rounded-md border border-gray-600">
                                    <input type="checkbox" x-model="selectedServiceIds" name="selectedServices[]" value="{{ $service->id }}" 
                                        @change="calculateTotalPrice()" {{-- NEW: Panggil calculateTotalPrice() saat checkbox berubah --}}
                                        class="form-checkbox text-yellow-500 rounded bg-gray-600 border-gray-500 focus:ring-yellow-500">
                                    <span class="ml-3 text-sm text-gray-200">{{ $service->name }} ({{ Number::currency($service->price, 'IDR') }}) - {{ $service->duration_minutes }} menit</span>
                                </label>
                            @endforeach
                        </div>
                        @error('selectedServices') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Total Harga --}}
                    <div class="mb-6 border-t border-b border-gray-700 py-4 hidden">
                        <p class="flex justify-between items-center text-lg font-bold text-gray-100">
                            <span>Total Harga:</span>
                            <span x-text="new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(totalPrice)"></span>
                        </p>
                    </div>

                    {{-- Tanggal Booking --}}
                    <div class="mb-4">
                        <label for="booking_date" class="block text-sm font-medium text-gray-300">Tanggal Booking <span class="text-red-500">*</span></label>
                        <input type="date" x-model="bookingDate" name="booking_date" id="booking_date" 
                            :min="getMinBookingDate()" 
                            class="mt-1 block w-full rounded-md border-gray-600 shadow-sm bg-gray-700 text-white focus:border-yellow-500 focus:ring focus:ring-yellow-500 focus:ring-opacity-50">
                        @error('booking_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        <p x-show="isShopClosed && bookingDate" class="text-red-500 text-xs mt-1">
                            Barbershop tutup pada tanggal ini. Silakan pilih tanggal lain.
                        </p>
                    </div>

                    {{-- Jam Booking --}}
                    <div class="mb-4">
                        <label for="booking_time" class="block text-sm font-medium text-gray-300">Jam Booking</label>
                        <input type="time" x-model="bookingTime" name="booking_time" id="booking_time" 
                            :min="openTime" :max="closeTime" 
                            :disabled="isShopClosed || !openTime || !closeTime" 
                            class="mt-1 block w-full rounded-md border-gray-600 shadow-sm bg-gray-700 text-white focus:border-yellow-500 focus:ring focus:ring-yellow-500 focus:ring-opacity-50"
                            :class="{'bg-gray-700 text-gray-400': isShopClosed || !openTime || !closeTime}">
                        <p x-show="bookingTimeError" class="text-red-500 text-xs mt-1" x-text="bookingTimeError"></p>
                        @error('booking_time') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        <p class="text-gray-400 text-xs mt-1" x-text="getShopHoursMessage()"></p>
                    </div>

                    {{-- Catatan / Preferensi --}}
                    <div class="mb-6">
                        <label for="notes" class="block text-sm font-medium text-gray-300">Catatan / Preferensi (opsional)</label>
                        <textarea x-model="notes" name="notes" id="notes" rows="3" class="mt-1 block w-full rounded-md border-gray-600 shadow-sm bg-gray-700 text-white focus:border-yellow-500 focus:ring focus:ring-yellow-500 focus:ring-opacity-50"></textarea>
                        @error('notes') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex items-center justify-end">
                        <button type="submit" class="inline-flex items-center px-6 py-3 bg-yellow-500 border border-transparent rounded-md font-semibold text-gray-900 uppercase tracking-widest hover:bg-yellow-600 focus:outline-none focus:border-yellow-700 focus:ring ring-yellow-300 disabled:opacity-25 transition ease-in-out duration-150">
                            Konfirmasi Booking
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection