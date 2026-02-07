<?php
session_start();

/* Initialize student array */
if (!isset($_SESSION['students'])) {
    $_SESSION['students'] = [];
}

$message = "";

/* Add Student */
if (isset($_POST['add'])) {
    $roll = $_POST['roll'];

    if (isset($_SESSION['students'][$roll])) {
        $message = "Roll number already exists!";
    } else {
        $_SESSION['students'][$roll] = [
            "name" => $_POST['name'],
            "semester" => $_POST['semester'],
            "attendance" => 0,
            "marks" => null
        ];
        $message = "Student added successfully!";
    }
}

/* Mark Attendance */
if (isset($_POST['attendance'])) {
    $roll = $_POST['aroll'];

    if (isset($_SESSION['students'][$roll])) {
        $total = $_POST['total'];
        $attended = $_POST['attended'];
        $_SESSION['students'][$roll]['attendance'] = ($attended / $total) * 100;
        $message = "Attendance saved successfully!";
    } else {
        $message = "Student not found!";
    }
}

/* Enter Marks */
if (isset($_POST['marks'])) {
    $roll = $_POST['mroll'];

    if (isset($_SESSION['students'][$roll])) {
        $_SESSION['students'][$roll]['marks'] = $_POST['marksvalue'];
        $message = "Marks saved successfully!";
    } else {
        $message = "Student not found!";
    }
}

/* AI-Based Performance Remark */
function getRemark($marks) {
    if ($marks >= 75) {
        return "Good";
    } elseif ($marks >= 50) {
        return "Average";
    } else {
        return "Needs Improvement";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Smart Attendance & Performance Tracker</title>
    <style>
        body {
            font-family: Arial;
            background: #f4f6f8;
        }
        h2 {
            text-align: center;
        }
        .box {
            background: #fff;
            padding: 15px;
            width: 400px;
            margin: 15px auto;
            border-radius: 8px;
        }
        input, button {
            width: 100%;
            padding: 8px;
            margin: 6px 0;
        }
        button {
            background: #007bff;
            color: white;
            border: none;
        }
        .report {
            background: #eef;
            padding: 12px;
            margin-top: 10px;
            border-radius: 6px;
        }
        .warning {
            color: red;
            font-weight: bold;
        }
        .msg {
            text-align: center;
            font-weight: bold;
        }
    </style>
</head>

<body>

<h2>SMART ATTENDANCE & PERFORMANCE TRACKER</h2>
<p class="msg"><?php echo $message; ?></p>

<!-- Add Student -->
<div class="box">
    <h3>Add Student</h3>
    <form method="post">
        <input type="text" name="roll" placeholder="Roll No" required>
        <input type="text" name="name" placeholder="Name" required>
        <input type="text" name="semester" placeholder="Semester" required>
        <button name="add">Add Student</button>
    </form>
</div>

<!-- Mark Attendance -->
<div class="box">
    <h3>Mark Attendance</h3>
    <form method="post">
        <input type="text" name="aroll" placeholder="Roll No" required>
        <input type="number" name="total" placeholder="Total Lectures" required>
        <input type="number" name="attended" placeholder="Lectures Attended" required>
        <button name="attendance">Save Attendance</button>
    </form>
</div>

<!-- Enter Marks -->
<div class="box">
    <h3>Enter Marks</h3>
    <form method="post">
        <input type="text" name="mroll" placeholder="Roll No" required>
        <input type="number" name="marksvalue" placeholder="Marks out of 100" required>
        <button name="marks">Save Marks</button>
    </form>
</div>

<!-- Student Report -->
<div class="box">
    <h3>STUDENT REPORT</h3>

    <?php
    if (!empty($_SESSION['students'])) {
        foreach ($_SESSION['students'] as $roll => $s) {
            echo "<div class='report'>";
            echo "<b>Roll No:</b> $roll<br>";
            echo "<b>Name:</b> {$s['name']}<br>";
            echo "<b>Semester:</b> {$s['semester']}<br><br>";

            echo "<b>Attendance:</b> " . number_format($s['attendance'], 2) . "%<br>";

            if ($s['attendance'] < 75) {
                echo "<span class='warning'>⚠ Attendance Shortage</span><br>";
            }

            echo "<br>";

            if ($s['marks'] !== null) {
                echo "<b>Marks:</b> {$s['marks']}<br>";
                echo "<b>Performance:</b> " . getRemark($s['marks']);
            } else {
                echo "<b>Marks:</b> Not Entered<br>";
                echo "<b>Performance:</b> -";
            }

            echo "</div>";
        }
    } else {
        echo "No student records found.";
    }
    ?>
</div>

</body>
</html>
