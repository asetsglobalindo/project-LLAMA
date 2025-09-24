<?php

/**
 * Tailwind CSS Direct Builder for Laravel
 *
 * This script builds Tailwind CSS without requiring npm or terminal access.
 * It downloads the standalone Tailwind CLI directly and uses it to build your CSS.
 */

// Configuration
$config = [
    // Path to your Tailwind input file (adjusted for your structure)
    'input_css' => '../asets-apps/resources/css/app.css',

    // Path to your Tailwind output file (adjusted for your structure)
    'output_css' => 'css/app.css',

    // Path to your tailwind.config.js file (adjusted for your structure)
    'config_file' => '../asets-apps/tailwind.config.js',

    // Tailwind CLI download URL (standalone version)
    'tailwind_cli_url' => 'https://github.com/tailwindlabs/tailwindcss/releases/download/v3.3.5/tailwindcss-linux-x64',

    // Where to save the downloaded Tailwind CLI
    'tailwind_cli_path' => './tailwindcss',

    // Password to protect this file from unauthorized access
    'password' => 'your-secure-password', // CHANGE THIS TO A SECURE PASSWORD

    // Enable/disable minification for production builds
    'minify' => true,

    // Auto delete this file after successful build (security measure)
    'auto_delete' => false,
];

// Security check - simple password protection
session_start();
$authenticated = false;

if (isset($_POST['password']) && $_POST['password'] === $config['password']) {
    $_SESSION['authenticated'] = true;
}

if (isset($_SESSION['authenticated']) && $_SESSION['authenticated'] === true) {
    $authenticated = true;
}

if (isset($_GET['logout'])) {
    $_SESSION['authenticated'] = false;
    $authenticated = false;
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

// Handle build requests
$output = '';
$error = '';
$success = false;

if ($authenticated && isset($_POST['build'])) {
    try {
        // Check if Tailwind CLI exists, if not download it
        if (!file_exists($config['tailwind_cli_path'])) {
            $output .= "Downloading Tailwind CLI...\n";

            $tailwindBinary = file_get_contents($config['tailwind_cli_url']);
            if ($tailwindBinary === false) {
                throw new Exception("Failed to download Tailwind CLI. Please check the URL and your internet connection.");
            }

            file_put_contents($config['tailwind_cli_path'], $tailwindBinary);
            chmod($config['tailwind_cli_path'], 0755); // Make executable

            $output .= "Tailwind CLI downloaded successfully.\n";
        }

        // Verify the input CSS file exists
        if (!file_exists($config['input_css'])) {
            throw new Exception("Input CSS file not found: {$config['input_css']}");
        }

        // Verify the config file exists
        if (!file_exists($config['config_file'])) {
            throw new Exception("Tailwind config file not found: {$config['config_file']}");
        }

        // Create directory for output CSS file if it doesn't exist
        $outputDir = dirname($config['output_css']);
        if (!is_dir($outputDir)) {
            mkdir($outputDir, 0755, true);
        }

        // Build the command
        $buildMode = $_POST['mode'] === 'production' ? '--minify' : '';
        $command = "./{$config['tailwind_cli_path']} -i {$config['input_css']} -o {$config['output_css']} -c {$config['config_file']} $buildMode";

        $output .= "Executing: $command\n";

        // Execute the build command
        $execOutput = [];
        $returnValue = 0;
        exec($command . " 2>&1", $execOutput, $returnValue);

        $output .= implode("\n", $execOutput) . "\n";

        if ($returnValue !== 0) {
            throw new Exception("Command execution failed with status $returnValue");
        }

        $output .= "Tailwind CSS built successfully!\n";
        $success = true;

        // Auto delete if enabled and build was successful
        if ($config['auto_delete'] && $success) {
            // Set a flag to delete after response is sent
            $deleteFile = true;
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

// Delete the file after usage if requested
if (isset($deleteFile) && $deleteFile) {
    // Register shutdown function to delete this file
    register_shutdown_function(function () {
        @unlink(__FILE__);
    });
}

// Detect tailwind.config.js contents for diagnostic purposes
$configContents = '';
if (file_exists($config['config_file'])) {
    $configContents = file_get_contents($config['config_file']);
}

// Detect input CSS contents for diagnostic purposes
$inputCssContents = '';
if (file_exists($config['input_css'])) {
    $inputCssContents = file_get_contents($config['input_css']);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tailwind CSS Direct Builder</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        h1,
        h2,
        h3 {
            color: #2d3748;
        }

        h1 {
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 10px;
        }

        .card {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
        }

        input[type="password"],
        input[type="text"],
        select {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }

        button {
            background-color: #4299e1;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background-color: #3182ce;
        }

        .success {
            color: #2f855a;
            background-color: #f0fff4;
            padding: 10px;
            border-radius: 4px;
            border-left: 4px solid #48bb78;
            margin-bottom: 15px;
        }

        .error {
            color: #c53030;
            background-color: #fff5f5;
            padding: 10px;
            border-radius: 4px;
            border-left: 4px solid #f56565;
            margin-bottom: 15px;
        }

        .output {
            background-color: #2d3748;
            color: #e2e8f0;
            padding: 15px;
            border-radius: 4px;
            font-family: monospace;
            white-space: pre-wrap;
            overflow-x: auto;
            margin-bottom: 15px;
        }

        .warning {
            background-color: #fffaf0;
            color: #c05621;
            padding: 10px;
            border-radius: 4px;
            border-left: 4px solid #ed8936;
            margin-bottom: 20px;
        }

        .tabs {
            display: flex;
            border-bottom: 1px solid #e2e8f0;
            margin-bottom: 15px;
        }

        .tab {
            padding: 8px 16px;
            cursor: pointer;
            border-bottom: 2px solid transparent;
        }

        .tab.active {
            border-bottom: 2px solid #4299e1;
            font-weight: bold;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }
    </style>
    <script>
        function showTab(tabId) {
            // Hide all tab contents
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.remove('active');
            });

            // Deactivate all tabs
            document.querySelectorAll('.tab').forEach(tab => {
                tab.classList.remove('active');
            });

            // Show the selected tab content
            document.getElementById(tabId).classList.add('active');

            // Activate the corresponding tab
            document.querySelector(`[onclick="showTab('${tabId}')"]`).classList.add('active');
        }
    </script>
</head>

<body>
    <h1>Tailwind CSS Direct Builder</h1>

    <?php if (!$authenticated): ?>
        <div class="card">
            <form method="post">
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <button type="submit">Login</button>
            </form>
        </div>
    <?php else: ?>
        <div class="warning">
            <strong>Security Warning:</strong> This file provides access to run commands on your server.
            Make sure to:
            <ul>
                <li>Change the default password</li>
                <li>Delete this file after use or enable auto-delete</li>
                <li>Restrict access to this file via .htaccess if possible</li>
            </ul>
        </div>

        <div class="card">
            <div class="tabs">
                <div class="tab active" onclick="showTab('build-tab')">Build</div>
                <div class="tab" onclick="showTab('settings-tab')">Settings</div>
                <div class="tab" onclick="showTab('diagnostics-tab')">Diagnostics</div>
            </div>

            <div id="build-tab" class="tab-content active">
                <form method="post">
                    <div class="form-group">
                        <label for="mode">Build Mode:</label>
                        <select id="mode" name="mode">
                            <option value="development">Development</option>
                            <option value="production">Production (Minified)</option>
                        </select>
                    </div>
                    <button type="submit" name="build" value="1">Build Tailwind CSS</button>
                </form>
            </div>

            <div id="settings-tab" class="tab-content">
                <h3>Current Configuration</h3>
                <p>These settings are read-only. To change them, edit the PHP file directly.</p>

                <div class="form-group">
                    <label>Input CSS File:</label>
                    <input type="text" value="<?php echo htmlspecialchars($config['input_css']); ?>" readonly>
                </div>

                <div class="form-group">
                    <label>Output CSS File:</label>
                    <input type="text" value="<?php echo htmlspecialchars($config['output_css']); ?>" readonly>
                </div>

                <div class="form-group">
                    <label>Config File:</label>
                    <input type="text" value="<?php echo htmlspecialchars($config['config_file']); ?>" readonly>
                </div>

                <div class="form-group">
                    <label>Auto Delete:</label>
                    <input type="text" value="<?php echo $config['auto_delete'] ? 'Enabled' : 'Disabled'; ?>" readonly>
                </div>
            </div>

            <div id="diagnostics-tab" class="tab-content">
                <h3>System Diagnostics</h3>

                <div class="form-group">
                    <label>PHP Version:</label>
                    <input type="text" value="<?php echo phpversion(); ?>" readonly>
                </div>

                <div class="form-group">
                    <label>Server OS:</label>
                    <input type="text" value="<?php echo PHP_OS; ?>" readonly>
                </div>

                <div class="form-group">
                    <label>Tailwind CLI Status:</label>
                    <input type="text" value="<?php echo file_exists($config['tailwind_cli_path']) ? 'Downloaded' : 'Not Downloaded'; ?>" readonly>
                </div>

                <div class="form-group">
                    <label>Input CSS File Status:</label>
                    <input type="text" value="<?php echo file_exists($config['input_css']) ? 'Found' : 'Not Found'; ?>" readonly>
                </div>

                <div class="form-group">
                    <label>Config File Status:</label>
                    <input type="text" value="<?php echo file_exists($config['config_file']) ? 'Found' : 'Not Found'; ?>" readonly>
                </div>

                <h4>tailwind.config.js Contents:</h4>
                <div class="output"><?php echo htmlspecialchars($configContents ?: 'File not found'); ?></div>

                <h4>Input CSS Contents:</h4>
                <div class="output"><?php echo htmlspecialchars($inputCssContents ?: 'File not found'); ?></div>
            </div>
        </div>

        <?php if (isset($_POST['build'])): ?>
            <?php if ($success): ?>
                <div class="success">
                    <strong>Success!</strong> Tailwind CSS has been built successfully.
                </div>
            <?php endif; ?>

            <?php if ($error): ?>
                <div class="error">
                    <strong>Error:</strong> <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <?php if ($output): ?>
                <h3>Build Output:</h3>
                <div class="output"><?php echo htmlspecialchars($output); ?></div>
            <?php endif; ?>
        <?php endif; ?>

        <p><a href="?logout=1">Logout</a></p>
    <?php endif; ?>
</body>

</html>