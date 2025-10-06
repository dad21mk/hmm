<?php
include 'conn.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Travel GoGo</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        :root {
            --primary-color: #ffa500;
            --secondary-color: #ff8c00;
            --dark: #333;
            --light: #f4f4f4;
            --danger: #dc3545;
            --success: #28a745;
            --warning: #ffc107;
            --info: #0dcaf0;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Nunito', sans-serif;
        }

        body {
            background: #f0f2f5;
            min-height: 100vh;
        }

        .header {
            background: white;
            color: var(--dark);
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .header h2 {
            color: var(--dark);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .header h2 i {
            color: var(--primary-color);
        }

        .header h2 span {
            color: var(--primary-color);
        }

        .container {
            max-width: 1200px;
            margin: 5rem auto 2rem;
            padding: 2rem 1rem;
        }

        .dashboard-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            border-radius: 10px;
            padding: 1.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            transition: transform 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        .stat-icon.destinations {
            background: rgba(255, 165, 0, 0.1);
            color: var(--primary-color);
        }

        .stat-icon.bookings {
            background: rgba(13, 202, 240, 0.1);
            color: var(--info);
        }

        .stat-icon.earnings {
            background: rgba(40, 167, 69, 0.1);
            color: var(--success);
        }

        .stat-info h3 {
            font-size: 1.8rem;
            margin-bottom: 0.3rem;
        }

        .stat-info p {
            color: #666;
            font-size: 0.9rem;
        }

        .card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            padding: 1.5rem;
            margin-bottom: 2rem;
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #eee;
        }

        .card-header h2 {
            font-size: 1.3rem;
            color: var(--dark);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .card-header h2 i {
            color: var(--primary-color);
        }

        .btn {
            padding: 0.6rem 1.2rem;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .btn-primary {
            background: var(--primary-color);
            color: white;
        }

        .btn-warning {
            background: var(--warning);
            color: var(--dark);
        }

        .btn-danger {
            background: var(--danger);
            color: white;
        }

        .btn:hover {
            opacity: 0.9;
            transform: translateY(-1px);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }

        th, td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        th {
            font-weight: 600;
            color: #666;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        tr:hover {
            background: #f8f9fa;
        }

        .action-buttons {
            display: flex;
            gap: 0.5rem;
        }

        .img-preview {
            width: 100px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .price {
            font-weight: 600;
            color: var(--primary-color);
        }

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 1000;
            backdrop-filter: blur(5px);
        }

        .modal.active {
            display: flex;
            justify-content: center;
            align-items: center;
            animation: modalFadeIn 0.3s ease;
        }

        @keyframes modalFadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .modal-content {
            background: white;
            padding: 2rem;
            border-radius: 15px;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }

        .modal-content h2 {
            color: var(--dark);
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #eee;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .modal-content h2 i {
            color: var(--primary-color);
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: #555;
        }

        .form-control {
            width: 100%;
            padding: 0.8rem;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            outline: none;
            box-shadow: 0 0 0 3px rgba(255, 165, 0, 0.1);
        }

        .close-btn {
            position: absolute;
            top: 1rem;
            right: 1rem;
            font-size: 1.5rem;
            cursor: pointer;
            color: #666;
            transition: color 0.3s ease;
        }

        .close-btn:hover {
            color: var(--danger);
        }

        .text-success {
            color: var(--success);
            font-weight: 600;
        }
        
        .text-warning {
            color: var(--warning);
            font-weight: 600;
        }
        
        .text-danger {
            color: var(--danger);
            font-weight: 600;
        }

        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }

        .user-info {
            line-height: 1.4;
        }

        .user-info small {
            color: #666;
            font-size: 0.85rem;
        }
    </style>
</head>
<body>
    <header class="header">
        <h2><i class="fas fa-globe"></i> <span>T</span>ravel GoGo - Admin Panel</h2>
        <a href="../userpanel/index.php" class="btn btn-primary">
            <i class="fas fa-home"></i> Back to Website
        </a>
    </header>

    <div class="container">
        <div class="dashboard-stats">
            <div class="stat-card">
                <div class="stat-icon destinations">
                    <i class="fas fa-map-marked-alt"></i>
                </div>
                <div class="stat-info">
                    <?php
                    $result = $conn->query("SELECT COUNT(*) as total FROM destinations");
                    $total_destinations = $result->fetch_assoc()['total'];
                    ?>
                    <h3><?php echo $total_destinations; ?></h3>
                    <p>Total Destinations</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon bookings">
                    <i class="fas fa-ticket-alt"></i>
                </div>
                <div class="stat-info">
                    <?php
                    $result = $conn->query("SELECT COUNT(*) as total FROM bookings WHERE status = 'confirmed'");
                    $total_bookings = $result->fetch_assoc()['total'];
                    ?>
                    <h3><?php echo $total_bookings; ?></h3>
                    <p>Total Bookings</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon earnings">
                    <i class="fas fa-wallet"></i>
                </div>
                <div class="stat-info">
                    <?php
                    $result = $conn->query("SELECT SUM(amount) as total FROM bookings WHERE status = 'confirmed'");
                    $total_earnings = $result->fetch_assoc()['total'] ?? 0;
                    ?>
                    <h3>Rp <?php echo number_format($total_earnings, 0, ',', '.'); ?></h3>
                    <p>Total Earnings</p>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h2><i class="fas fa-map-location-dot"></i> Manage Destinations</h2>
                <button class="btn btn-primary" onclick="openAddModal()">
                    <i class="fas fa-plus"></i> Add New Destination
                </button>
            </div>

            <table>
                <thead>
                    <tr>
                        <th><i class="fas fa-image"></i> Image</th>
                        <th><i class="fas fa-signature"></i> Name</th>
                        <th><i class="fas fa-location-dot"></i> Location</th>
                        <th><i class="fas fa-tag"></i> Price</th>
                        <th><i class="fas fa-ticket"></i> Available Tickets</th>
                        <th><i class="fas fa-cog"></i> Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "SELECT * FROM destinations ORDER BY id DESC";
                    $result = $conn->query($sql);

                    if ($result && $result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo "<tr>
                                    <td><img src='../uploads/{$row['image']}' alt='{$row['name']}' class='img-preview'></td>
                                    <td>{$row['name']}</td>
                                    <td>{$row['location']}</td>
                                    <td class='price'>Rp " . number_format($row['price'], 0, ',', '.') . "</td>
                                    <td>{$row['ticket_count']}</td>
                                    <td class='action-buttons'>
                                        <button class='btn btn-warning' onclick='openEditModal({$row['id']}, \"{$row['name']}\", \"{$row['location']}\", {$row['price']})'>
                                            <i class='fas fa-edit'></i>
                                        </button>
                                        <button class='btn btn-danger' onclick='deleteDestination({$row['id']})'>
                                            <i class='fas fa-trash'></i>
                                        </button>
                                    </td>
                                  </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5' style='text-align: center;'>No destinations found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <!-- Recent Bookings Card -->
        <div class="card">
            <div class="card-header">
                <h2><i class="fas fa-ticket-alt"></i> Manage Bookings</h2>
            </div>

            <table>
                <thead>
                    <tr>
                        <th><i class="fas fa-hashtag"></i> ID</th>
                        <th><i class="fas fa-user"></i> Customer</th>
                        <th><i class="fas fa-map-marker-alt"></i> Destination</th>
                        <th><i class="fas fa-ticket-alt"></i> Quantity</th>
                        <th><i class="fas fa-calendar"></i> Booking Date</th>
                        <th><i class="fas fa-money-bill-wave"></i> Amount</th>
                        <th><i class="fas fa-info-circle"></i> Status</th>
                        <th><i class="fas fa-sticky-note"></i> Notes</th>
                        <th><i class="fas fa-cog"></i> Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "SELECT b.*, u.username, u.email, d.name as destination_name 
                           FROM bookings b 
                           JOIN users u ON b.user_id = u.id 
                           JOIN destinations d ON b.destination_id = d.id 
                           ORDER BY b.booking_date DESC";
                    $result = $conn->query($sql);

                    if ($result && $result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            $status_class = '';
                            switch($row['status']) {
                                case 'confirmed':
                                    $status_class = 'text-success';
                                    break;
                                case 'pending':
                                    $status_class = 'text-warning';
                                    break;
                                case 'cancelled':
                                    $status_class = 'text-danger';
                                    break;
                            }
                            
                            echo "<tr id='booking-{$row['id']}'>
                                    <td>#{$row['id']}</td>
                                    <td>
                                        <div class='user-info'>
                                            <strong>{$row['username']}</strong><br>
                                            <small>{$row['email']}</small>
                                        </div>
                                    </td>
                                    <td>{$row['destination_name']}</td>
                                    <td>{$row['quantity']} ticket(s)</td>
                                    <td>" . date('d M Y H:i', strtotime($row['booking_date'])) . "</td>
                                    <td class='price'>Rp " . number_format($row['amount'], 0, ',', '.') . "</td>
                                    <td><span class='{$status_class}'>" . ucfirst($row['status']) . "</span></td>
                                    <td>" . ($row['notes'] ? htmlspecialchars($row['notes']) : '-') . "</td>
                                    <td class='action-buttons'>";
                            if($row['status'] == 'pending') {
                                echo "<button class='btn btn-sm btn-success' onclick='updateBookingStatus({$row['id']}, \"confirmed\")'>
                                        <i class='fas fa-check'></i> Confirm
                                    </button>
                                    <button class='btn btn-sm btn-danger' onclick='updateBookingStatus({$row['id']}, \"cancelled\")'>
                                        <i class='fas fa-times'></i> Cancel
                                    </button>";
                            }
                            echo "</td></tr>";
                        }
                    } else {
                        echo "<tr><td colspan='9' style='text-align: center;'>No bookings found</td></tr>";
                    }
                    $conn->close();
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add/Edit Modal -->
    <div id="destinationModal" class="modal">
        <div class="modal-content">
            <span class="close-btn" onclick="closeModal()">&times;</span>
            <h2 id="modalTitle"><i class="fas fa-plus-circle"></i> Add New Destination</h2>
            <form id="destinationForm" method="POST" action="save_destination.php" enctype="multipart/form-data">
                <input type="hidden" name="id" id="destinationId">
                
                <div class="form-group">
                    <label for="name"><i class="fas fa-signature"></i> Destination Name</label>
                    <input type="text" id="name" name="name" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="location"><i class="fas fa-location-dot"></i> Location</label>
                    <input type="text" id="location" name="location" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="price"><i class="fas fa-tag"></i> Price (Rp)</label>
                    <input type="number" id="price" name="price" class="form-control" required min="0">
                </div>

                <div class="form-group">
                    <label for="ticket_count">Available Tickets</label>
                    <input type="number" id="ticket_count" name="ticket_count" class="form-control" required min="0">
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" class="form-control" rows="4" required></textarea>
                </div>

                <div class="form-group">
                    <label for="image"><i class="fas fa-image"></i> Image</label>
                    <input type="file" id="image" name="image" class="form-control" accept="image/*">
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Save Destination
                </button>
            </form>
        </div>
    </div>

    <script>
        function openAddModal() {
            document.getElementById('modalTitle').innerHTML = '<i class="fas fa-plus-circle"></i> Add New Destination';
            document.getElementById('destinationForm').reset();
            document.getElementById('destinationId').value = '';
            document.getElementById('destinationModal').classList.add('active');
        }

        function openEditModal(id, name, location, price) {
            document.getElementById('modalTitle').innerHTML = '<i class="fas fa-edit"></i> Edit Destination';
            document.getElementById('destinationId').value = id;
            document.getElementById('name').value = name;
            document.getElementById('location').value = location;
            document.getElementById('price').value = price;
            document.getElementById('destinationModal').classList.add('active');
        }

        function closeModal() {
            document.getElementById('destinationModal').classList.remove('active');
        }

        function deleteDestination(id) {
            if (confirm('Are you sure you want to delete this destination?')) {
                window.location.href = 'delete_destination.php?id=' + id;
            }
        }

        function updateBookingStatus(bookingId, status) {
            if (confirm('Are you sure you want to ' + status + ' this booking?')) {
                fetch('update_booking.php?id=' + bookingId + '&status=' + status)
                    .then(response => response.text())
                    .then(() => {
                        // Remove the row after successful status update
                        const row = document.getElementById('booking-' + bookingId);
                        if (row) {
                            row.remove();
                        }
                        // Show success message
                        alert('Booking has been ' + status);
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Failed to update booking status');
                    });
            }
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            if (event.target == document.getElementById('destinationModal')) {
                closeModal();
            }
        }
    </script>
</body>
</html> 