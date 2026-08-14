<?php
$pageTitle = "Patient Dashboard";
require_once __DIR__ . '/../includes/header.php';
requireAuth('patient');

$patientId = $_SESSION['user_id'];

// Get patient stats
$stmtUpcoming = $pdo->prepare("SELECT COUNT(*) FROM appointment WHERE patient_id = ? AND status IN ('Pending', 'Approved') AND appointment_date >= CURDATE()");
$stmtUpcoming->execute([$patientId]);
$upcomingCount = $stmtUpcoming->fetchColumn();

$stmtCompleted = $pdo->prepare("SELECT COUNT(*) FROM appointment WHERE patient_id = ? AND status = 'Completed'");
$stmtCompleted->execute([$patientId]);
$completedCount = $stmtCompleted->fetchColumn();

$stmtTotal = $pdo->prepare("SELECT COUNT(*) FROM appointment WHERE patient_id = ?");
$stmtTotal->execute([$patientId]);
$totalCount = $stmtTotal->fetchColumn();

// Get next upcoming appointment details
$stmtNext = $pdo->prepare("
    SELECT a.*, d.doctor_name, d.specialization 
    FROM appointment a 
    JOIN doctor d ON a.doctor_id = d.doctor_id 
    WHERE a.patient_id = ? AND a.status IN ('Pending', 'Approved') AND a.appointment_date >= CURDATE() 
    ORDER BY a.appointment_date ASC, a.appointment_time ASC 
    LIMIT 1
");
$stmtNext->execute([$patientId]);
$nextAppointment = $stmtNext->fetch();
?>

<!-- Welcome Banner -->
<div style="background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-light) 100%); color:#ffffff; padding:25px 30px; border-radius:var(--radius-md); margin-bottom:30px; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:15px;">
    <div>
        <h2 style="font-size:24px; font-weight:800;">Welcome back, <?php echo htmlspecialchars($currentUser['full_name']); ?>!</h2>
        <p style="opacity:0.9; font-size:14px; margin-top:4px;">Manage your medical consultations, schedule new appointments, and review your health history.</p>
    </div>
    <a href="<?php echo base_url('patient/book-appointment.php'); ?>" class="btn btn-primary" style="background:#ffffff; color:var(--primary-color); font-weight:700;">
        <i class="fas fa-calendar-plus"></i> Book New Appointment
    </a>
</div>

<!-- Dashboard Stats Overview -->
<div class="dashboard-grid">
    <div class="stat-card">
        <div class="stat-info">
            <span class="stat-label">Upcoming Consultations</span>
            <div class="stat-value"><?php echo $upcomingCount; ?></div>
        </div>
        <div class="stat-icon blue"><i class="fas fa-calendar-check"></i></div>
    </div>
    
    <div class="stat-card">
        <div class="stat-info">
            <span class="stat-label">Completed Visits</span>
            <div class="stat-value"><?php echo $completedCount; ?></div>
        </div>
        <div class="stat-icon green"><i class="fas fa-check-double"></i></div>
    </div>

    <div class="stat-card">
        <div class="stat-info">
            <span class="stat-label">Total Bookings</span>
            <div class="stat-value"><?php echo $totalCount; ?></div>
        </div>
        <div class="stat-icon purple"><i class="fas fa-notes-medical"></i></div>
    </div>
</div>

<!-- Next Scheduled Appointment Banner / Quick Actions -->
<?php if ($nextAppointment): ?>
    <div class="card" style="border-left: 5px solid var(--accent-color);">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-clock" style="color:var(--accent-color);"></i> Next Scheduled Appointment</h3>
            <span class="badge badge-<?php echo strtolower($nextAppointment['status']); ?>">
                <?php echo htmlspecialchars($nextAppointment['status']); ?>
            </span>
        </div>
        <div class="card-body" style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:20px;">
            <div>
                <h4 style="font-size:18px; color:var(--primary-color);"><?php echo htmlspecialchars($nextAppointment['doctor_name']); ?></h4>
                <p style="color:var(--text-muted); font-size:14px;"><?php echo htmlspecialchars($nextAppointment['specialization']); ?></p>
                <div style="margin-top:10px; font-weight:600; font-size:15px; color:var(--text-primary);">
                    <i class="far fa-calendar-alt"></i> <?php echo date('F j, Y', strtotime($nextAppointment['appointment_date'])); ?> 
                    &nbsp;|&nbsp; 
                    <i class="far fa-clock"></i> <?php echo date('g:i A', strtotime($nextAppointment['appointment_time'])); ?>
                </div>
            </div>
            <div>
                <a href="<?php echo base_url('patient/appointments.php'); ?>" class="btn btn-secondary btn-sm">View All Appointments</a>
            </div>
        </div>
    </div>
<?php endif; ?>

<!-- Quick Doctor Directory Shortcut -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-user-md"></i> Quick Actions</h3>
    </div>
    <div class="card-body" style="display:grid; grid-template-columns:repeat(auto-fit, minmax(200px, 1fr)); gap:15px;">
        <a href="<?php echo base_url('patient/doctors.php'); ?>" class="btn btn-secondary" style="padding:20px; justify-content:flex-start; font-size:15px;">
            <i class="fas fa-search-location" style="font-size:22px; color:var(--primary-light);"></i> Browse Specialist Doctors
        </a>
        <a href="<?php echo base_url('patient/book-appointment.php'); ?>" class="btn btn-secondary" style="padding:20px; justify-content:flex-start; font-size:15px;">
            <i class="fas fa-calendar-plus" style="font-size:22px; color:#38a169;"></i> Schedule Consultation
        </a>
        <a href="<?php echo base_url('patient/profile.php'); ?>" class="btn btn-secondary" style="padding:20px; justify-content:flex-start; font-size:15px;">
            <i class="fas fa-user-edit" style="font-size:22px; color:#805ad5;"></i> Update Medical Profile
        </a>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
