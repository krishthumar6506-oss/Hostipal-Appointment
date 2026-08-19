/**
 * MedCare Hospital Management System - Main Interactive JavaScript
 * Powered by jQuery & Vanilla JavaScript
 */

$(document).ready(function () {

    // Sidebar Toggle for Mobile View
    $('.topbar-toggle, #sidebar-overlay').on('click', function () {
        $('.sidebar').toggleClass('active');
        $('#sidebar-overlay').toggleClass('active');
    });

    // Auto-dismiss Alerts after 5 seconds
    setTimeout(function () {
        $('.alert-dismissible').fadeOut('slow', function () {
            $(this).remove();
        });
    }, 5000);

    // Modal Trigger Handlers
    $('[data-modal-target]').on('click', function () {
        var targetModal = $(this).attr('data-modal-target');
        $(targetModal).addClass('show');
    });

    $('.modal-close, [data-modal-dismiss]').on('click', function () {
        $(this).closest('.modal-backdrop').removeClass('show');
    });

    $('.modal-backdrop').on('click', function (e) {
        if ($(e.target).hasClass('modal-backdrop')) {
            $(this).removeClass('show');
        }
    });

    // Live Doctor Search Filter in Patient Doctor List
    $('#doctorSearchInput').on('keyup search', function () {
        var value = $(this).val().toLowerCase();
        $('.doctor-card-item').filter(function () {
            var text = $(this).text().toLowerCase();
            $(this).toggle(text.indexOf(value) > -1);
        });
    });

    // General Table Search Filter
    $('#tableSearchInput').on('keyup', function () {
        var value = $(this).val().toLowerCase();
        $('.data-table tbody tr').filter(function () {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
    });

    // Client-side Form Validation Handlers
    $('form.validate-form').on('submit', function (e) {
        var isValid = true;
        var form = $(this);

        form.find('[required]').each(function () {
            if ($.trim($(this).val()) === '') {
                isValid = false;
                $(this).addClass('is-invalid');
            } else {
                $(this).removeClass('is-invalid');
            }
        });

        // Email validation
        var emailInput = form.find('input[type="email"]');
        if (emailInput.length > 0 && emailInput.val() !== '') {
            var emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
            if (!emailPattern.test(emailInput.val())) {
                isValid = false;
                emailInput.addClass('is-invalid');
                alert('Please enter a valid email address.');
            }
        }

        // Phone number validation (Minimum 10 digits)
        var phoneInput = form.find('input[name="phone"]');
        if (phoneInput.length > 0 && phoneInput.val() !== '') {
            var phonePattern = /^[0-9]{10,15}$/;
            if (!phonePattern.test(phoneInput.val())) {
                isValid = false;
                phoneInput.addClass('is-invalid');
                alert('Please enter a valid phone number (10 to 15 digits).');
            }
        }

        // Confirm Password Match Check
        var password = form.find('input[name="password"]');
        var confirmPassword = form.find('input[name="confirm_password"]');
        if (password.length > 0 && confirmPassword.length > 0) {
            if (password.val() !== confirmPassword.val()) {
                isValid = false;
                confirmPassword.addClass('is-invalid');
                alert('Passwords do not match!');
            }
        }

        if (!isValid) {
            e.preventDefault();
        }
    });

    // Dynamic Doctor Schedule Slot Loader for Book Appointment Form
    $('#appointmentDoctorSelect, #appointmentDateInput').on('change', function () {
        var doctorId = $('#appointmentDoctorSelect').val();
        var dateVal = $('#appointmentDateInput').val();

        if (doctorId && dateVal) {
            $('#timeSlotContainer').html('<p class="text-muted"><i class="fas fa-spinner fa-spin"></i> Checking available time slots...</p>');

            $.ajax({
                url: '../patient/book-appointment.php',
                type: 'GET',
                data: {
                    action: 'get_slots',
                    doctor_id: doctorId,
                    date: dateVal
                },
                dataType: 'json',
                success: function (response) {
                    if (response.success && response.slots.length > 0) {
                        var html = '<select name="appointment_time" class="form-select" required>';
                        html += '<option value="">-- Select Available Time Slot --</option>';
                        $.each(response.slots, function (i, slot) {
                            html += '<option value="' + slot.time + '" data-schedule-id="' + slot.schedule_id + '">' + slot.display_time + '</option>';
                        });
                        html += '</select>';
                        $('#timeSlotContainer').html(html);
                    } else {
                        $('#timeSlotContainer').html('<div class="alert alert-warning mb-0"><i class="fas fa-exclamation-triangle"></i> No available schedule slots for this doctor on selected date.</div>');
                    }
                },
                error: function () {
                    // Fallback to manual time selector if server endpoint is direct page post
                    $('#timeSlotContainer').html('<input type="time" name="appointment_time" class="form-control" required>');
                }
            });
        }
    });

});

// Print Report Function
function printReportSection(sectionId) {
    var printContent = document.getElementById(sectionId).innerHTML;
    var originalContent = document.body.innerHTML;
    document.body.innerHTML = '<html><head><title>Hospital Report Print</title><link rel="stylesheet" href="../css/style.css"></head><body style="background:#fff; padding:20px;">' + printContent + '</body></html>';
    window.print();
    document.body.innerHTML = originalContent;
    window.location.reload();
}
