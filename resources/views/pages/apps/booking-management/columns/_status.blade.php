@if (auth()->user()->hasRole('admin'))
    <select class="form-select form-select-sm form-select-solid" data-kt-booking-id="{{ $booking->id }}"
        data-kt-action="status_update" data-control="select2" data-hide-search="true">
        <option value="pending" @if ($booking->status === 'pending') selected @endif>Pending</option>
        <option value="approved" @if ($booking->status === 'approved') selected @endif>Approved</option>
        <option value="rejected" @if ($booking->status === 'rejected') selected @endif>Rejected</option>
    </select>
@else
    <div
        class="badge badge-{{ $booking->status === 'pending' ? 'warning' : ($booking->status === 'approved' ? 'success' : 'danger') }} fw-bold">
        {{ ucfirst($booking->status) }}
    </div>
@endif
