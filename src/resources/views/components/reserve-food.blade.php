<div class="card">
    <div class="card-content">
        <div class="card-header">
            <h1 class="card-title">
                افزودن دستی غذا
            </h1>
        </div>
        <div class="card-body">
            @include('template.messages')
            <div class="form-group">
                <label>نام غذا:</label>
                <select name="food" class="form-select" wire:model.defer='bookId_foodId' onchange="$('#bookingId').val(this.options[this.selectedIndex].id)">
                    <option value="">انتخاب کنید</option>
                    @foreach($bookings as $booking)
                        @foreach($booking->foods as $food)
                            <option value="{{$booking->id}},{{ $food->id }}" id="{{ $booking->id }}">
                                {{ $booking->meal->name }} - {{ $food->name }} {{ $food->price }}
                                تومان
                            </option>
                        @endforeach
                    @endforeach
                </select>
            </div>

            <input type='hidden' value='' wire:model.defer='bookingId' id="bookingId">

            <div class="form-group">
                <label>نام ساختمان:</label>
                <select name="salon" wire:model.defer='salonId' class="form-select">
                    <option value="">انتخاب کنید</option>
                    @foreach($salons as $salon)
                        <option value="{{ $salon->id }}">{{ $salon->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>تعداد</label>
                <input type="number" class="form-control" wire:model="quantity">
            </div>
            <div class="form-group">
                <label>جستجو همکار</label>
                <div class="input-group mb-3">
                    <input type="search" class="form-control" placeholder="جستجوی نام کاربر"
                           wire:model.debounce.500ms="query"
                           wire:keydown.enter="searchUser"
                    />
                </div>
            </div>
            @foreach($users as $user)
                <div class="form-check">
                    <input class="form-check-input" type="radio" wire:model="userId" value="{{ $user->id }}"
                           id="user-{{ $user->id }}">
                    <label class="form-check-label" for="user-{{ $user->id }}">
                        {{ $user->name }}
                    </label>
                </div>
            @endforeach
        </div>
    </div>
    <div class="card-footer d-flex justify-content-between">
        <button class="btn btn-primary ml-auto" type="submit" wire:click="submitReserve">ثبت</button>
    </div>
</div>

@push('js')
    <script>
        document.addEventListener('livewire:load', function () {

            function persistBookingId(bookingId) {
                console.log(bookingId);
                $('#bookingId').val(bookingId);
            }
        })
    </script>
@endpush

