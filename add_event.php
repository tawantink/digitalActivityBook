<?php
// รับวันที่จาก URL
$date = isset($_GET['date']) ? $_GET['date'] : null;

if (!$date) {
    echo "No date selected!";
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add New Event</title>
</head>
<body>
    <h2>Add Event on <?php echo htmlspecialchars($date); ?></h2>
    <form action="save_event.php" method="POST">
        <input type="hidden" name="event_date" value="<?php echo htmlspecialchars($date); ?>">
        <label for="event_name">Event Name:</label><br>
        <input type="text" id="event_name" name="event_name" required><br><br>
        
        <label for="event_descrip">Description:</label><br>
        <textarea id="event_descrip" name="event_descrip" required></textarea><br><br>
        
        <label for="event_point">Points:</label><br>
        <input type="number" id="event_point" name="event_point" required><br><br>
        
        <button type="submit">Save Event</button>
        <a href="events.php?date=<?php echo htmlspecialchars($date); ?>"><button type="button">Cancel</button></a>
    </form>
</body>
</html>
