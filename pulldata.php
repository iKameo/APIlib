<?php
// Allow CORS
header("Access-Control-Allow-Origin: *"); // You can specify a specific domain instead of '*'
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

// Start output buffering to prevent headers from being sent too early
ob_start();

// Function to output HP data as HTML
function outputHPData($data) {
    echo '<h2>Current HP Data</h2>';
    echo '<ul>';
    foreach ($data as $item) {
        echo '<li>';
        echo '<strong>Name:</strong> ' . htmlspecialchars($item['name']) . '<br>';
        echo '<strong>HP:</strong> ' . htmlspecialchars($item['currentHP']) . '/' . htmlspecialchars($item['maxHP']) . '<br>';
        
        // Calculate HP percentage for the bar (rounded to 1 decimal place)
        $hpPercentage = round(($item['currentHP'] / $item['maxHP']) * 100, 1);

        // Determine the color of the HP bar based on the percentage
        $hpColor = '#4CAF50'; // Default color (green)
        if ($hpPercentage <= 25) {
            $hpColor = '#FF0000'; // Red color for <= 25%
        } elseif ($hpPercentage <= 50) {
            $hpColor = '#f0dc2f'; // Yellow color for <= 50%
        }

        echo '<strong>HP Bar:</strong> ';
        echo '<div style="border: 1px solid #000; width: 200px; height: 10px; display: inline-block;">';
        echo '<div style="background-color: ' . $hpColor . '; width: ' . $hpPercentage . '%; height: 100%;"></div>';
        echo '</div>';
        
        echo '</li>';
    }
    echo '</ul>';
}


// Get JSON data from input
$input = file_get_contents('php://input');

// Handle empty input
if (empty($input)) {
    // Read the last saved data from the log file
    $lastData = trim(file_get_contents('dnd_data.txt'));
    
    // Check if there's no data in the log file
    if (empty($lastData)) {
        echo '<p>Error: Empty input and no saved data</p>';
        ob_end_flush(); // Flush output buffer before exiting
        exit;
    }
    
    // Output the last saved data as HTML
    $lastDataArray = json_decode($lastData, true);
    outputHPData($lastDataArray);
    ob_end_flush(); // Flush output buffer before exiting
    exit;
}

// Overwrite the log file with the new input
file_put_contents('dnd_data.txt', $input);

// Attempt to decode JSON
$data = json_decode($input, true);

// Check JSON decoding errors
if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
    echo '<p>Error: Invalid JSON data</p>';
    ob_end_flush(); // Flush output buffer before exiting
    exit;
}

// Output the HTML representation
outputHPData($data);

// Refresh the page every 5 seconds
header('refresh: 5');

// End and flush the output buffer
ob_end_flush();
?>
