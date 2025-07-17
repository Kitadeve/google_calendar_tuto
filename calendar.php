<?php

include "./connexion.php";

$successMsg = "";
$errorMsg = "";
$eventsFromDb = [];

// Handle add appointment
if ($_SERVER["REQUEST_METHOD"] === "POST" && ($_POST['action'] ?? '') === "add") {
    $course = trim($_POST["course_name"] ?? "");
    $instructor = trim($_POST['instructor_name'] ?? '');
    $start = $_POST["start_date"] ?? '';
    $end = $_POST['end_date'] ?? '';
    $startTime = $_POST['start_time'] ?? '';
    $endTime = $_POST['end_time'] ?? '';

    if ($course && $instructor && $start && $end) {
        $stmnt =$conn->prepare(
            "INSERT INTO appointments (course_name, instructor_name, start_date, end_date, start_time, end_time) VALUES (?, ?, ?, ?, ?, ?)"
        );

        $stmnt->bind_param("ssssss", $course, $instructor, $start, $end, $startTime, $endTime);
        $stmnt->execute();
        $stmnt->close();

        header("Location: " . $_SERVER["PHP_SELF"] ."?success=1");
        exit;
    } else {
        header("Location: " . $_SERVER["PHP_SELF"] . "?error=1");
    }
} 


// Handle edit appointment

    if($_SERVER["REQUEST_METHOD"] === "POST" && ($_POST["action"] ?? '') === 'edit') {
        $id = $_POST["event_id"] ?? null;
        $course = trim($_POST["course_name"] ?? '');
        $instructor = trim($_POST["instructor_name"] ?? '');
        $start = $_POST["start_date"] ?? '';
        $end = $_POST['end_date'] ?? '';
        $startTime = $_POST['start_time'] ?? '';
        $endTime = $_POST['end_time'] ?? '';


        if($id && $course && $instructor && $start && $end) {
            $stmnt = $conn->prepare(
                "UPDATE appointments SET course_name = ?, instructor_name = ?, start_date = ?, end_date = ?, start_time = ?, end_time = ? WHERE id= ?"
            );
            $stmnt->bind_param("ssssssi", $course, $instructor, $start, $end, $startTime, $endTime, $id);
            $stmnt->execute();
            $stmnt->close();

            header("Location: " . $_SERVER["PHP_SELF"] . "?success=2");
            exit;
        } else {
            header("Location: " . $_SERVER["PHP_SELF"] . "?error=2");
            exit;
        }
    }

    // HAndle delete
    if($_SERVER["REQUEST_METHOD"] === "POST" && ($_POST["action"] ?? '') === 'delete') {
        $id = $_POST['event_id'] ?? null;

        if ($id) {
            $stmnt = $conn->prepare(
                "DELETE FROM appointments WHERE id = ?"
            );
            $stmnt->bind_param("i", $id);
            $stmnt->execute();
            $stmnt->close();

            header("Location: " . $_SERVER["PHP_SELF"] . "?success=3");
            exit;
        } else {
            header("Location: " . $_SERVER["PHP_SELF"] . "?error=3");
            exit;
        } 
    }

    // Success
    if (isset($_GET["success"])) {
        $successMsg = match ($_GET["success"]) {
            "1" => "✅ Appointment added successfully",
            "2" => "✅ Appointment updated successfully",
            "3" => "🗑 Appointment deleted successfully",
            default => ""
        };
    }

    if (isset($_GET["error"])) {
        $errorMsg = match ($_GET["error"]) {
            "1" => "❌ Appointment did not register",
            "2" => "❌ Appointment did not update",
            "3" => "❌ Appointment deleted successfully",
            default => ""
        };
    }

    // Fetch all appointments and spread over date range
    $result = $conn->query("SELECT * FROM appointments");

    if ($result && $result->num_rows >0) {
        while ($row = $result->fetch_assoc()) {
            $start = new DateTime($row["start_date"]);
            $end = new DateTime($row["end_date"]);

            while ($start <= $end) {
                $eventsFromDb[] = [
                    "id" => $row["id"],
                    "title" => "{$row['course_name']} - {$row['instructor_name']}",
                    "date" => $start->format("Y-m-d"),
                    "start" => $row["start_date"],
                    "end" => $row["end_date"],
                    "start_time" => $row["start_time"],
                    "end_time" => $row["end_time"]
                ];

                $start->modify("+1 day");
            }
        }
    }
    $conn->close();
?>