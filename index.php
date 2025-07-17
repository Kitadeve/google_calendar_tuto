<?php 
$chemin = "./calendar.php";
if (file_exists($chemin)) {
    include $chemin;
} else {
    echo "Fichier non trouvé : $chemin";
}
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <meta charset="utf8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Calendar project</title>
        <meta name="description" content="My own calendar projext">
        <link rel="stylesheet" href="style.css">
        <link rel="shortcut icon" href="./calendar.png" type="image/x-icon">
    </head>

    <body>
        <header>
            <h1>📆 Course Calendar <br> My calendar Project</h1>
        </header>

        <!-- clock -->
        <div class="clock-container">
            <div id="clock"></div>
        </div>

        <?php if (!empty($successMsg)): ?>
            <div class="success"><?= htmlspecialchars($successMsg) ?></div>
        <?php endif; ?>

        <?php if (!empty($errorMsg)): ?>
            <div class="error"><?= htmlspecialchars($errorMsg) ?></div>
        <?php endif; ?>

         <!-- Calendar Section -->
        <div class="calendar">
            <div class="nav-btn-container">
                <button class="nav-btn" onclick="changeMonth(-1)">👈</button>
                <h2 id="monthYear" style="margin:0;text-transform: capitalize;" ></h2>
                <button class="nav-btn" onclick="changeMonth(1)">👉</button>
            </div>

            <div class="calendar-grid" id="calendar"></div>
        </div>

        <!-- Modal fo Add/Edit/Delete appointment -->
        <div class="modal" id="eventModal">
            <div class="modal-content">

                <div id="eventSelectorWrapper">
                    <label for="eventSelector">
                        <strong>Select Event: </strong>
                    </label>
                    <select name="eventSelector" id="eventSelector">
                        <option value="" disabled selected>Choose Event...</option>
                    </select>
                </div>

                    <!-- Main FOrm -->

                <form action="" id=eventForm method="post">
                    <input type="hidden" name="action" value="add" id="formAction">
                    <input type="hidden" name="event_id" id="eventId">

                    <label for="courseName">Course Title:</label>
                    <input type="text" name="course_name" id="courseName" required>

                    <label for="instructorName">Instructo Name:</label>
                    <input type="text" name="instructor_name" id="instructorName" required>

                    <label for="startDate">Start Date:</label>
                    <input type="date" name="start_date" id="startDate" required>

                    <label for="endDate">End Date:</label>
                    <input type="date" name="end_date" id="endDate" required>

                    <label for="startTime">Start time:</label>
                    <input type="time" name="start_time" id="startTime" required>

                    <label for="endTime"> End Time:</label>
                    <input type="time" name="end_time" id="endTime" required>

                    <button type="submit">💾 Save</button>

                </form>

                <!-- Delete Form -->

                <form onsubmit="return confirm('Are yousure you want to delete this appointement ?')" method="post">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="event_id" id="deleteEventId">
                    <button type="submit" class="submit-btn">🗑 Delete</button>
                </form>

                <!-- ❌ Cancel -->
                <button type="button" class="submit-btn" onclick="closeModal()">❌ Cancel</button>
            </div>
        </div>     
        <script>
            const events = <?= json_encode($eventsFromDb, JSON_UNESCAPED_UNICODE); ?>
        </script>
        <script src="calendar.js"></script>
    </body>
</html>