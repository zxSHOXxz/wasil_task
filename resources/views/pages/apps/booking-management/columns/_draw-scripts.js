// Initialize KTMenu
KTMenu.init();

// Add click event listener to delete buttons
document.querySelectorAll('[data-kt-action="delete_row"]').forEach(function (element) {
    element.addEventListener('click', function () {
        Swal.fire({
            text: 'Are you sure you want to remove?',
            icon: 'warning',
            buttonsStyling: false,
            showCancelButton: true,
            confirmButtonText: 'Yes',
            cancelButtonText: 'No',
            customClass: {
                confirmButton: 'btn btn-danger',
                cancelButton: 'btn btn-secondary',
            }
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('delete_booking', [this.getAttribute('data-kt-booking-id')]);
            }
        });
    });
});

document.querySelectorAll('[data-kt-action="status_update"]').forEach(function (element) {
    element.addEventListener('change', function () {
        Livewire.dispatch('update_status', [this.getAttribute('data-kt-booking-id'), this.value]);
    });
});


// Listen for 'success' event emitted by Livewire
Livewire.on('success', (message) => {
    // Reload the bookings-table datatable
    LaravelDataTables['bookings-table'].ajax.reload();
});
