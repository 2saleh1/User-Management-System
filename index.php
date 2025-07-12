<?php
// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "info";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if (isset($_POST['action']) && $_POST['action'] == 'add' && !empty($_POST['name']) && !empty($_POST['age'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $age = intval($_POST['age']);
    
    $sql = "INSERT INTO user (name, age) VALUES ('$name', $age)";
    $conn->query($sql);
}

// Handle status toggle
if (isset($_POST['action']) && $_POST['action'] == 'toggle' && !empty($_POST['id'])) {
    $id = intval($_POST['id']);
    $sql = "UPDATE user SET status = 1 - status WHERE id = $id";
    $conn->query($sql);
}

// Fetch all records - Changed to ORDER BY id ASC
$result = $conn->query("SELECT * FROM user ORDER BY id ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management System</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: #0f0f23;
            color: #ffffff;
            min-height: 100vh;
            padding: 20px;
            background-image: 
                radial-gradient(circle at 20% 50%, rgba(120, 119, 198, 0.3) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(255, 119, 198, 0.3) 0%, transparent 50%),
                radial-gradient(circle at 40% 80%, rgba(120, 219, 255, 0.3) 0%, transparent 50%);
        }

        .container {
            max-width: 1100px;
            margin: 0 auto;
        }

        .header {
            text-align: center;
            margin-bottom: 40px;
        }

        .header h1 {
            font-size: 3rem;
            font-weight: 700;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 10px;
        }

        .header p {
            font-size: 1.2rem;
            color: #a0a0a0;
            font-weight: 300;
        }

        .form-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.2);
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr auto;
            gap: 20px;
            align-items: end;
        }

        .input-group {
            position: relative;
        }

        .input-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #e0e0e0;
            font-size: 0.9rem;
        }

        .input-group input {
            width: 100%;
            padding: 15px 20px;
            border: 2px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.05);
            color: #ffffff;
            font-size: 1rem;
            transition: all 0.3s ease;
            outline: none;
        }

        .input-group input:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
            background: rgba(255, 255, 255, 0.08);
        }

        .submit-btn {
            padding: 15px 35px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 35px rgba(102, 126, 234, 0.4);
        }

        .submit-btn:active {
            transform: translateY(0);
        }

        .table-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.2);
        }

        .table-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .table-header h2 {
            font-size: 1.5rem;
            font-weight: 600;
            color: #e0e0e0;
        }

        .stats {
            display: flex;
            gap: 20px;
        }

        .stat-item {
            text-align: center;
            padding: 10px 20px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 10px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .stat-number {
            font-size: 1.5rem;
            font-weight: 700;
            color: #667eea;
        }

        .stat-label {
            font-size: 0.8rem;
            color: #a0a0a0;
            text-transform: uppercase;
        }

        .table-wrapper {
            background: rgba(255, 255, 255, 0.02);
            border-radius: 15px;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 20px;
            text-align: left;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        th {
            background: rgba(255, 255, 255, 0.05);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 0.85rem;
            color: #b0b0b0;
        }

        tr:hover {
            background: rgba(255, 255, 255, 0.02);
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            font-size: 0.9rem;
            font-weight: 600;
        }

        .status-active {
            background: rgba(76, 175, 76, 0.2);
            color: #4CAF50;
            border: 2px solid #4CAF50;
        }

        .status-inactive {
            background: rgba(244, 67, 54, 0.2);
            color: #f44336;
            border: 2px solid #f44336;
        }

        .toggle-btn {
            background: linear-gradient(135deg, #ff9800 0%, #f57c00 100%);
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 0.85rem;
            font-weight: 500;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .toggle-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 5px 15px rgba(255, 152, 0, 0.4);
        }

        .no-records {
            text-align: center;
            padding: 60px 20px;
            color: #808080;
        }

        .no-records-icon {
            font-size: 4rem;
            margin-bottom: 20px;
            opacity: 0.3;
        }

        .no-records p {
            font-size: 1.1rem;
            margin-bottom: 10px;
        }

        .no-records small {
            font-size: 0.9rem;
            opacity: 0.7;
        }

        @media (max-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr;
                gap: 15px;
            }
            
            .header h1 {
                font-size: 2rem;
            }
            
            .stats {
                flex-direction: column;
                gap: 10px;
            }
            
            table {
                font-size: 0.9rem;
            }
            
            th, td {
                padding: 15px 10px;
            }
        }

        /* Scrollbar Styling */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.3);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>User Management</h1>
            
        </div>

        <div class="form-card">
            <form method="POST" action="">
                <input type="hidden" name="action" value="add">
                <div class="form-grid">
                    <div class="input-group">
                        <label for="name">Full Name</label>
                        <input type="text" id="name" name="name" placeholder="Enter full name" required>
                    </div>
                    <div class="input-group">
                        <label for="age">Age</label>
                        <input type="number" id="age" name="age" placeholder="Enter age" min="1" max="120" required>
                    </div>
                    <button type="submit" class="submit-btn">Add User</button>
                </div>
            </form>
        </div>

        <div class="table-card">
            <div class="table-header">
                <h2>All Users</h2>
                <div class="stats">
                    <div class="stat-item">
                        <div class="stat-number"><?php echo $result->num_rows; ?></div>
                        <div class="stat-label">Total</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number"><?php 
                            $active_count = 0;
                            $temp_result = $conn->query("SELECT COUNT(*) as count FROM user WHERE status = 1");
                            if ($temp_result) {
                                $temp_row = $temp_result->fetch_assoc();
                                $active_count = $temp_row['count'];
                            }
                            echo $active_count;
                        ?></div>
                        <div class="stat-label">Active</div>
                    </div>
                </div>
            </div>
            
            <div class="table-wrapper">
                <?php if ($result->num_rows > 0): ?>
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Age</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><strong>#<?php echo $row['id']; ?></strong></td>
                                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                                    <td><?php echo $row['age']; ?> years</td>
                                    <td>
                                        <span class="status-badge <?php echo $row['status'] ? 'status-active' : 'status-inactive'; ?>">
                                            <?php echo $row['status']; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <form method="POST" action="" style="display: inline;">
                                            <input type="hidden" name="action" value="toggle">
                                            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                            <button type="submit" class="toggle-btn">Toggle</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="no-records">
                        <div class="no-records-icon">👥</div>
                        <p>No users found</p>
                        <small>Add your first user using the form above</small>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            const toggleBtns = document.querySelectorAll('.toggle-btn');
            const submitBtn = document.querySelector('.submit-btn');
            
            // Form submission animation
            form.addEventListener('submit', function(e) {
                submitBtn.style.transform = 'scale(0.95)';
                submitBtn.innerHTML = 'Adding...';
                setTimeout(() => {
                    submitBtn.style.transform = 'scale(1)';
                }, 200);
            });
            
            // Toggle button animations
            toggleBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    this.style.transform = 'scale(0.9)';
                    this.innerHTML = 'Updating...';
                    setTimeout(() => {
                        this.style.transform = 'scale(1)';
                    }, 200);
                });
            });

            // Add floating animation to form card
            const formCard = document.querySelector('.form-card');
            const tableCard = document.querySelector('.table-card');
            
            setTimeout(() => {
                formCard.style.transform = 'translateY(0)';
                formCard.style.opacity = '1';
            }, 100);
            
            setTimeout(() => {
                tableCard.style.transform = 'translateY(0)';
                tableCard.style.opacity = '1';
            }, 300);
        });
    </script>
</body>
</html>

<?php
$conn->close();
?>