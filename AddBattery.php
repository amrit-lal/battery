<?php
session_start();
include "db.php";  // same db.php you made earlier

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $sValue         = $_POST['sValue'];
    $cdRatio        = $_POST['cdRatio'];
    $voltage        = $_POST['voltage'];
    $current        = $_POST['current'];
    $chargeHours    = $_POST['chargeHours'];
    $ampereHours    = $_POST['ampereHours'];
    $wattHours      = $_POST['wattHours'];
    $specificGravity= $_POST['specificGravity'];
    $acidTemp       = $_POST['acidTemp'];
    $realVoltage    = $_POST['realVoltage'];
    $realCurrent    = $_POST['realCurrent'];
    $signature      = $_POST['signature'];
    $remarks        = $_POST['remarks'];

    $sql = "INSERT INTO batteries 
            (sValue, cdRatio, voltage, current, chargeHours, ampereHours, wattHours, specificGravity, acidTemp, realVoltage, realCurrent, signature, remarks) 
            VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssddddddddsss", $sValue, $cdRatio, $voltage, $current, $chargeHours, $ampereHours, $wattHours, $specificGravity, $acidTemp, $realVoltage, $realCurrent, $signature, $remarks);

    if ($stmt->execute()) {
        $message = "✅ Battery added successfully!";
    } else {
        $message = "❌ Error: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <title>Add Battery</title>
</head>
<body class="min-h-screen bg-gray-50 p-4">
    <div class="max-w-6xl mx-auto">
        <div class="bg-white rounded-2xl p-8 shadow-2xl">
            <h2 class="text-2xl font-bold text-center mb-4">Add New Battery</h2>

            <?php if ($message): ?>
                <div class="p-3 mb-4 text-center rounded 
                            <?php echo strpos($message, '✅') !== false ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'; ?>">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>

            <form method="POST" class="space-y-6">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Column 1 -->
                    <div class="space-y-4">
                        <h3 class="text-xl font-semibold text-gray-700 mb-4 border-b pb-2">Primary Specifications</h3>
                        <div>
                            <label class="block font-semibold">S Value</label>
                            <input type="text" name="sValue" required class="w-full border rounded-lg p-3"/>
                        </div>
                        <div>
                            <label class="block font-semibold">C/D Ratio</label>
                            <input type="text" name="cdRatio" required class="w-full border rounded-lg p-3"/>
                        </div>
                        <div>
                            <label class="block font-semibold">Voltage (V)</label>
                            <input type="number" step="0.01" name="voltage" required class="w-full border rounded-lg p-3"/>
                        </div>
                        <div>
                            <label class="block font-semibold">Current (A)</label>
                            <input type="number" step="0.01" name="current" required class="w-full border rounded-lg p-3"/>
                        </div>
                        <div>
                            <label class="block font-semibold">Charge Hours</label>
                            <input type="number" step="0.1" name="chargeHours" required class="w-full border rounded-lg p-3"/>
                        </div>
                        <div>
                            <label class="block font-semibold">Ampere Hours (Ah)</label>
                            <input type="number" step="0.01" name="ampereHours" required class="w-full border rounded-lg p-3"/>
                        </div>
                    </div>

                    <!-- Column 2 -->
                    <div class="space-y-4">
                        <h3 class="text-xl font-semibold text-gray-700 mb-4 border-b pb-2">Secondary Specifications</h3>
                        <div>
                            <label class="block font-semibold">Watt Hours (Wh)</label>
                            <input type="number" step="0.01" name="wattHours" required class="w-full border rounded-lg p-3"/>
                        </div>
                        <div>
                            <label class="block font-semibold">Specific Gravity (SG)</label>
                            <input type="number" step="0.001" name="specificGravity" required class="w-full border rounded-lg p-3"/>
                        </div>
                        <div>
                            <label class="block font-semibold">Acid Temperature (°C)</label>
                            <input type="number" step="0.1" name="acidTemp" required class="w-full border rounded-lg p-3"/>
                        </div>
                        <div>
                            <label class="block font-semibold">Real Voltage (V)</label>
                            <input type="number" step="0.01" name="realVoltage" required class="w-full border rounded-lg p-3"/>
                        </div>
                        <div>
                            <label class="block font-semibold">Real Current (A)</label>
                            <input type="number" step="0.01" name="realCurrent" required class="w-full border rounded-lg p-3"/>
                        </div>
                        <div>
                            <label class="block font-semibold">Signature</label>
                            <input type="text" name="signature" required class="w-full border rounded-lg p-3"/>
                        </div>
                    </div>
                </div>

                <!-- Remarks -->
                <div class="mt-6">
                    <label class="block font-semibold">Remarks</label>
                    <textarea name="remarks" class="w-full border rounded-lg p-3 h-24"></textarea>
                </div>

                <!-- Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center pt-6">
                    <button type="submit" class="px-8 py-4 bg-blue-500 text-white rounded-lg hover:bg-blue-600">Add Battery</button>
                    <button type="reset" class="px-8 py-4 border rounded-lg">Reset</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
