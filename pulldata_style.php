<?php
// Allow CORS
header("Access-Control-Allow-Origin: *"); // You can specify a specific domain instead of '*'
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

// Start output buffering to prevent headers from being sent too early
ob_start();

// Function to output HP data as HTML
function outputHPData($data) {
    echo '<style>
        @font-face {
            font-family: "PKMN";
            src: url("pkmn.ttf") format("truetype");
            font-weight: normal;
            font-style: normal;
        }
        body {
            font-family: "PKMN", sans-serif;
        }
        .pokemon-container {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            padding: 5px;
            width: 240px;
            background-color: #fff;
        }
        .pokemon-name {
            font-size: 18px;
            font-weight: bold;
        }
        .hp-label {
            background-color: #000;
            color: #ffcccb;
            padding: 2px 5px;
            font-size: 12px;
            font-weight: bold;
        }
        .hp-bar-container {
            width: 200px;
            height: 10px;
            background-color: #fff;
            position: relative;
        }
        .hp-bar {
            height: 100%;
        }
        .hp-text {
            font-size: 14px;
            font-weight: bold;
            text-align: right;
            width: 200px;
        }
        .hp-wrapper {
            display: flex;
            align-items: center;
        }
    </style>';

    echo '<h2>Current HP Data</h2>';
    echo '<div class="pokemon-list">';
    foreach ($data as $item) {
        // Calculate HP percentage for the bar
        $hpPercentage = round(($item['currentHP'] / $item['maxHP']) * 100, 1);

        // Determine the color of the HP bar based on the percentage
        $hpColor = '#4CAF50'; // Default color (green)
        if ($hpPercentage <= 25) {
            $hpColor = '#FF0000'; // Red color for <= 25%
        } elseif ($hpPercentage <= 50) {
            $hpColor = '#f0dc2f'; // Yellow color for <= 50%
        }

        echo '<div class="pokemon-container">';
        echo '<div>';
        echo '<div class="pokemon-name">' . htmlspecialchars($item['name']) . '</div>';
        echo '<div class="hp-wrapper">';
        echo '<div class="hp-label">HP:</div>';
        echo '<div class="hp-bar-container">';
        echo '<div class="hp-bar" style="width: ' . $hpPercentage . '%; background-color: ' . $hpColor . ';"></div>';
        echo '</div>';
        echo '</div>';
        echo '<div class="hp-text">' . htmlspecialchars($item['currentHP']) . '/' . htmlspecialchars($item['maxHP']) . '</div>';
        echo '</div>';
        echo '</div>';
    }
    echo '</div>';
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
