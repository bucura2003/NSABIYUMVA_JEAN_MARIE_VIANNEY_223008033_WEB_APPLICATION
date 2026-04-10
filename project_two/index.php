<?php

$host = 'localhost';
$dbname = 'student_registration_db';
$username = 'root';
$password = '';


$conn = new mysqli($host, $username, $password);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$conn->query("CREATE DATABASE IF NOT EXISTS $dbname");
$conn->select_db($dbname);


$students_table = "CREATE TABLE IF NOT EXISTS students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    dob DATE NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    mobile VARCHAR(15) NOT NULL,
    gender ENUM('Male', 'Female') NOT NULL,
    address TEXT NOT NULL,
    city VARCHAR(50) NOT NULL,
    pin_code VARCHAR(10) NOT NULL,
    state VARCHAR(50) NOT NULL,
    country VARCHAR(50) NOT NULL,
    hobbies VARCHAR(255),
    course_applied VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
$conn->query($students_table);


$qualifications_table = "CREATE TABLE IF NOT EXISTS qualifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    examination VARCHAR(50) NOT NULL,
    board VARCHAR(100),
    percentage DECIMAL(5,2),
    year_of_passing INT,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE
)";
$conn->query($qualifications_table);


$message = '';
$message_type = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    // Get student basic info
    $first_name = trim($_POST['first_name'] ?? '');
    $last_name = trim($_POST['last_name'] ?? '');
    $dob = trim($_POST['dob_year'] ?? '') . '-' . 
           str_pad(trim($_POST['dob_month'] ?? ''), 2, '0', STR_PAD_LEFT) . '-' . 
           str_pad(trim($_POST['dob_day'] ?? ''), 2, '0', STR_PAD_LEFT);
    $email = trim($_POST['email'] ?? '');
    $mobile = trim($_POST['mobile'] ?? '');
    $gender = trim($_POST['gender'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $city = trim($_POST['city'] ?? '');
    $pin_code = trim($_POST['pin_code'] ?? '');
    $state = trim($_POST['state'] ?? '');
    $country = trim($_POST['country'] ?? '');
    $course_applied = trim($_POST['course_applied'] ?? '');
    
    
    $hobbies = isset($_POST['hobbies']) ? implode(', ', $_POST['hobbies']) : '';
    if (!empty(trim($_POST['hobby_other'] ?? ''))) {
        $hobbies = empty($hobbies) ? trim($_POST['hobby_other']) : $hobbies . ', ' . trim($_POST['hobby_other']);
    }
    
    
    $exams = $_POST['examination'] ?? [];
    $boards = $_POST['board'] ?? [];
    $percentages = $_POST['percentage'] ?? [];
    $years = $_POST['year_of_passing'] ?? [];
    
    
    $errors = [];
    if (empty($first_name)) $errors[] = "First name is required";
    if (empty($last_name)) $errors[] = "Last name is required";
    if (empty($dob) || $dob == '--') $errors[] = "Date of birth is required";
    if (empty($email)) $errors[] = "Email is required";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Invalid email format";
    if (empty($mobile)) $errors[] = "Mobile number is required";
    if (empty($gender)) $errors[] = "Gender is required";
    if (empty($address)) $errors[] = "Address is required";
    if (empty($city)) $errors[] = "City is required";
    if (empty($pin_code)) $errors[] = "Pin code is required";
    if (empty($state)) $errors[] = "State is required";
    if (empty($country)) $errors[] = "Country is required";
    if (empty($course_applied)) $errors[] = "Course is required";
    
    if (empty($errors)) {
        
        $check_stmt = $conn->prepare("SELECT id FROM students WHERE email = ?");
        $check_stmt->bind_param("s", $email);
        $check_stmt->execute();
        $check_stmt->store_result();
        
        if ($check_stmt->num_rows > 0) {
            $message = "❌ Email already registered!";
            $message_type = "error";
        } else {
            
            $stmt = $conn->prepare("INSERT INTO students (first_name, last_name, dob, email, mobile, gender, address, city, pin_code, state, country, hobbies, course_applied) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssssssssss", $first_name, $last_name, $dob, $email, $mobile, $gender, $address, $city, $pin_code, $state, $country, $hobbies, $course_applied);
            
            if ($stmt->execute()) {
                $student_id = $stmt->insert_id;
                
                
                $qual_stmt = $conn->prepare("INSERT INTO qualifications (student_id, examination, board, percentage, year_of_passing) VALUES (?, ?, ?, ?, ?)");
                for ($i = 0; $i < count($exams); $i++) {
                    if (!empty($exams[$i])) {
                        $board = !empty($boards[$i]) ? $boards[$i] : null;
                        $percentage = !empty($percentages[$i]) ? floatval($percentages[$i]) : null;
                        $year = !empty($years[$i]) ? intval($years[$i]) : null;
                        $qual_stmt->bind_param("issdi", $student_id, $exams[$i], $board, $percentage, $year);
                        $qual_stmt->execute();
                    }
                }
                $qual_stmt->close();
                
                $message = "✅ Student registered successfully!";
                $message_type = "success";
                
               
                $_POST = array();
            } else {
                $message = "❌ Database error: " . $stmt->error;
                $message_type = "error";
            }
            $stmt->close();
        }
        $check_stmt->close();
    } else {
        $message = "❌ " . implode(", ", $errors);
        $message_type = "error";
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Registration Form | Project Two</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            min-height: 100vh;
            padding: 40px 20px;
        }

        .container {
            max-width: 1000px;
            margin: 0 auto;
        }

        .form-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }

        .form-header {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            color: white;
            padding: 25px;
            text-align: center;
        }

        .form-header h1 {
            font-size: 28px;
            margin-bottom: 5px;
        }

        .form-header p {
            opacity: 0.9;
            font-size: 14px;
        }

        .form-body {
            padding: 30px;
        }

        .form-section {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 25px;
        }

        .form-section h3 {
            color: #1e3c72;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #1e3c72;
        }

        .form-row {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            margin-bottom: 15px;
        }

        .form-group {
            flex: 1;
            min-width: 200px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
            font-size: 14px;
        }

        .form-group label .required {
            color: red;
        }

        .form-group input, 
        .form-group select, 
        .form-group textarea {
            width: 100%;
            padding: 10px 12px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s;
        }

        .form-group input:focus, 
        .form-group select:focus, 
        .form-group textarea:focus {
            outline: none;
            border-color: #1e3c72;
            box-shadow: 0 0 0 3px rgba(30,60,114,0.1);
        }

        .dob-group {
            display: flex;
            gap: 10px;
        }

        .dob-group select {
            flex: 1;
        }

        .radio-group {
            display: flex;
            gap: 20px;
            align-items: center;
            padding: 10px 0;
        }

        .radio-group label {
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: normal;
            cursor: pointer;
        }

        .checkbox-group {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            padding: 10px 0;
        }

        .checkbox-group label {
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: normal;
            cursor: pointer;
        }

        .qualification-table {
            overflow-x: auto;
        }

        .qualification-table table {
            width: 100%;
            border-collapse: collapse;
        }

        .qualification-table th,
        .qualification-table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        .qualification-table th {
            background: #1e3c72;
            color: white;
            font-size: 13px;
        }

        .qualification-table input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .btn-submit {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s;
            margin-top: 20px;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
        }

        .btn-reset {
            background: #6c757d;
            margin-top: 10px;
        }

        .btn-reset:hover {
            background: #5a6268;
            transform: translateY(-2px);
        }

        .message {
            margin-bottom: 20px;
            padding: 12px;
            border-radius: 8px;
            display: none;
        }

        .message.success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            display: block;
        }

        .message.error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
            display: block;
        }

        .note {
            font-size: 12px;
            color: #666;
            margin-top: 5px;
        }

        @media (max-width: 768px) {
            .form-row {
                flex-direction: column;
            }
            .form-group {
                min-width: 100%;
            }
            .dob-group {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <div class="form-card">
        <div class="form-header">
            <h1>STUDENT REGISTRATION FORM</h1>
            <p>Please fill in all the required details</p>
        </div>
        <div class="form-body">
            <?php if ($message): ?>
                <div class="message <?php echo $message_type; ?>">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="" id="registrationForm">
                <!-- Personal Information -->
                <div class="form-section">
                    <h3>📋 Personal Information</h3>
                    <div class="form-row">
                        <div class="form-group">
                            <label>FIRST NAME <span class="required">*</span></label>
                            <input type="text" name="first_name" value="<?php echo htmlspecialchars($_POST['first_name'] ?? ''); ?>" required>
                        </div>
                        <div class="form-group">
                            <label>LAST NAME <span class="required">*</span></label>
                            <input type="text" name="last_name" value="<?php echo htmlspecialchars($_POST['last_name'] ?? ''); ?>" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>DATE OF BIRTH <span class="required">*</span></label>
                        <div class="dob-group">
                            <select name="dob_day" id="dob_day" required>
                                <option value="">Day</option>
                            </select>
                            <select name="dob_month" id="dob_month" required>
                                <option value="">Month</option>
                                <option value="1">January</option><option value="2">February</option>
                                <option value="3">March</option><option value="4">April</option>
                                <option value="5">May</option><option value="6">June</option>
                                <option value="7">July</option><option value="8">August</option>
                                <option value="9">September</option><option value="10">October</option>
                                <option value="11">November</option><option value="12">December</option>
                            </select>
                            <select name="dob_year" id="dob_year" required>
                                <option value="">Year</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>EMAIL ID <span class="required">*</span></label>
                            <input type="email" name="email" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" required>
                        </div>
                        <div class="form-group">
                            <label>MOBILE NUMBER <span class="required">*</span></label>
                            <input type="tel" name="mobile" value="<?php echo htmlspecialchars($_POST['mobile'] ?? ''); ?>" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>GENDER <span class="required">*</span></label>
                        <div class="radio-group">
                            <label><input type="radio" name="gender" value="Male" <?php echo (isset($_POST['gender']) && $_POST['gender'] == 'Male') ? 'checked' : ''; ?>> Male</label>
                            <label><input type="radio" name="gender" value="Female" <?php echo (isset($_POST['gender']) && $_POST['gender'] == 'Female') ? 'checked' : ''; ?>> Female</label>
                        </div>
                    </div>
                </div>

                <!-- Address Information -->
                <div class="form-section">
                    <h3>📍 Address Information</h3>
                    <div class="form-group">
                        <label>ADDRESS <span class="required">*</span></label>
                        <textarea name="address" rows="3" required><?php echo htmlspecialchars($_POST['address'] ?? ''); ?></textarea>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>CITY <span class="required">*</span></label>
                            <input type="text" name="city" value="<?php echo htmlspecialchars($_POST['city'] ?? ''); ?>" required>
                        </div>
                        <div class="form-group">
                            <label>PIN CODE <span class="required">*</span></label>
                            <input type="text" name="pin_code" value="<?php echo htmlspecialchars($_POST['pin_code'] ?? ''); ?>" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>STATE <span class="required">*</span></label>
                            <input type="text" name="state" value="<?php echo htmlspecialchars($_POST['state'] ?? ''); ?>" required>
                        </div>
                        <div class="form-group">
                            <label>COUNTRY <span class="required">*</span></label>
                            <select name="country" id="countrySelect" required>
                                <option value="">-- Select Country --</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Hobbies -->
                <div class="form-section">
                    <h3>🎨 Hobbies</h3>
                    <div class="checkbox-group">
                        <label><input type="checkbox" name="hobbies[]" value="Drawing"> Drawing</label>
                        <label><input type="checkbox" name="hobbies[]" value="Singing"> Singing</label>
                        <label><input type="checkbox" name="hobbies[]" value="Dancing"> Dancing</label>
                        <label><input type="checkbox" name="hobbies[]" value="Sketching"> Sketching</label>
                        <label><input type="checkbox" name="hobbies[]" value="Others"> Others</label>
                    </div>
                    <div class="form-group">
                        <input type="text" name="hobby_other" placeholder="Specify other hobbies" style="margin-top: 10px;">
                    </div>
                </div>

                <!-- Qualification -->
                <div class="form-section">
                    <h3>📚 Qualification</h3>
                    <div class="qualification-table">
                        <table>
                            <thead>
                                <tr><th>SL.No.</th><th>Examination</th><th>Board</th><th>Percentage</th><th>Year of Passing</th></tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td><input type="text" name="examination[]" value="Class X" readonly style="background:#f0f0f0;"></td>
                                    <td><input type="text" name="board[]" placeholder="Board name"></td>
                                    <td><input type="number" name="percentage[]" step="0.01" min="0" max="100" placeholder="0.00"></td>
                                    <td><input type="number" name="year_of_passing[]" min="1950" max="2030" placeholder="Year"></td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td><input type="text" name="examination[]" value="Class XII" readonly style="background:#f0f0f0;"></td>
                                    <td><input type="text" name="board[]" placeholder="Board name"></td>
                                    <td><input type="number" name="percentage[]" step="0.01" min="0" max="100" placeholder="0.00"></td>
                                    <td><input type="number" name="year_of_passing[]" min="1950" max="2030" placeholder="Year"></td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td><input type="text" name="examination[]" value="Graduation" readonly style="background:#f0f0f0;"></td>
                                    <td><input type="text" name="board[]" placeholder="University name"></td>
                                    <td><input type="number" name="percentage[]" step="0.01" min="0" max="100" placeholder="0.00"></td>
                                    <td><input type="number" name="year_of_passing[]" min="1950" max="2030" placeholder="Year"></td>
                                </tr>
                                <tr>
                                    <td>4</td>
                                    <td><input type="text" name="examination[]" value="Masters" readonly style="background:#f0f0f0;"></td>
                                    <td><input type="text" name="board[]" placeholder="University name"></td>
                                    <td><input type="number" name="percentage[]" step="0.01" min="0" max="100" placeholder="0.00"></td>
                                    <td><input type="number" name="year_of_passing[]" min="1950" max="2030" placeholder="Year"></td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="note">Note: Percentage up to 2 decimal places</div>
                    </div>
                </div>

                <!-- Course Selection -->
                <div class="form-section">
                    <h3>🎓 Courses Applied For</h3>
                    <div class="radio-group">
                        <label><input type="radio" name="course_applied" value="BCA" <?php echo (isset($_POST['course_applied']) && $_POST['course_applied'] == 'BCA') ? 'checked' : ''; ?>> BCA</label>
                        <label><input type="radio" name="course_applied" value="B.Com" <?php echo (isset($_POST['course_applied']) && $_POST['course_applied'] == 'B.Com') ? 'checked' : ''; ?>> B.Com</label>
                        <label><input type="radio" name="course_applied" value="B.Sc" <?php echo (isset($_POST['course_applied']) && $_POST['course_applied'] == 'B.Sc') ? 'checked' : ''; ?>> B.Sc</label>
                        <label><input type="radio" name="course_applied" value="BA" <?php echo (isset($_POST['course_applied']) && $_POST['course_applied'] == 'BA') ? 'checked' : ''; ?>> BA</label>
                    </div>
                </div>

                <button type="submit" name="submit" class="btn-submit">✓ Submit Registration</button>
                <button type="reset" class="btn-submit btn-reset">⟳ Reset Form</button>
            </form>
        </div>
    </div>
</div>

<script>

function populateDays() {
    const daySelect = document.getElementById('dob_day');
    for (let i = 1; i <= 31; i++) {
        const option = document.createElement('option');
        option.value = i;
        option.textContent = i;
        daySelect.appendChild(option);
    }
}


function populateYears() {
    const yearSelect = document.getElementById('dob_year');
    const currentYear = new Date().getFullYear();
    for (let i = currentYear; i >= 1950; i--) {
        const option = document.createElement('option');
        option.value = i;
        option.textContent = i;
        yearSelect.appendChild(option);
    }
}


const countries = [
    "Afghanistan", "Albania", "Algeria", "Andorra", "Angola", "Argentina", "Armenia", 
    "Australia", "Austria", "Bangladesh", "Belgium", "Bhutan", "Brazil", "Canada", 
    "Chile", "China", "Colombia", "Denmark", "Egypt", "Finland", "France", "Germany", 
    "Greece", "Hungary", "Iceland", "India", "Indonesia", "Iran", "Iraq", "Ireland", 
    "Israel", "Italy", "Japan", "Jordan", "Kazakhstan", "Kenya", "Kuwait", "Malaysia", 
    "Maldives", "Mexico", "Morocco", "Myanmar", "Nepal", "Netherlands", "New Zealand", 
    "Nigeria", "Norway", "Oman", "Pakistan", "Peru", "Philippines", "Poland", "Portugal", 
    "Qatar", "Romania", "Russia", "Rwanda", "Saudi Arabia", "Singapore", "South Africa", 
    "South Korea", "Spain", "Sri Lanka", "Sudan", "Sweden", "Switzerland", "Syria", 
    "Taiwan", "Thailand", "Tunisia", "Turkey", "Uganda", "Ukraine", "United Arab Emirates", 
    "United Kingdom", "United States", "Uruguay", "Vietnam", "Yemen", "Zambia", "Zimbabwe"
];


const countrySelect = document.getElementById('countrySelect');
if (countrySelect) {
    countries.forEach(country => {
        const option = document.createElement('option');
        option.value = country;
        option.textContent = country;
        if (country === 'India') option.selected = true;
        countrySelect.appendChild(option);
    });
}


document.addEventListener('DOMContentLoaded', function() {
    populateDays();
    populateYears();
    
    
    <?php if (isset($_POST['dob_day']) && $_POST['dob_day']): ?>
        document.getElementById('dob_day').value = '<?php echo $_POST['dob_day']; ?>';
        document.getElementById('dob_month').value = '<?php echo $_POST['dob_month']; ?>';
        document.getElementById('dob_year').value = '<?php echo $_POST['dob_year']; ?>';
    <?php endif; ?>
    
    <?php if (isset($_POST['hobbies']) && is_array($_POST['hobbies'])): ?>
        <?php foreach ($_POST['hobbies'] as $hobby): ?>
            const cb = document.querySelector('input[type="checkbox"][value="<?php echo $hobby; ?>"]');
            if (cb) cb.checked = true;
        <?php endforeach; ?>
    <?php endif; ?>
});


setTimeout(function() {
    const msg = document.querySelector('.message');
    if (msg) {
        msg.style.opacity = '0';
        setTimeout(function() {
            msg.style.display = 'none';
        }, 500);
    }
}, 5000);
</script>
</body>
</html>