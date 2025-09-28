<?php
session_start();
include "db.php";

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

// Handle form submission for ADD
$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if it's an edit operation
    if (isset($_POST['edit_id'])) {
        // EDIT operation
        $id = $_POST['edit_id'];
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

        $sql = "UPDATE batteries SET 
                sValue=?, cdRatio=?, voltage=?, current=?, chargeHours=?, ampereHours=?, wattHours=?, 
                specificGravity=?, acidTemp=?, realVoltage=?, realCurrent=?, signature=?, remarks=?
                WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssddddddddsssi", $sValue, $cdRatio, $voltage, $current, $chargeHours, $ampereHours, $wattHours, $specificGravity, $acidTemp, $realVoltage, $realCurrent, $signature, $remarks, $id);

        if ($stmt->execute()) {
            $message = "success:Battery updated successfully!";
        } else {
            $message = "error:Error updating battery: " . $conn->error;
        }
    } else {
        // ADD operation
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
            $message = "success:Battery added successfully!";
        } else {
            $message = "error:Error adding battery: " . $conn->error;
        }
    }
}

// Fetch battery data for editing if ID is provided
$editBattery = null;
if (isset($_GET['edit_id'])) {
    $editId = $_GET['edit_id'];
    $stmt = $conn->prepare("SELECT * FROM batteries WHERE id = ?");
    $stmt->bind_param("i", $editId);
    $stmt->execute();
    $result = $stmt->get_result();
    $editBattery = $result->fetch_assoc();
}

// Fetch all batteries data
$batteries = [];
$result = $conn->query("SELECT * FROM batteries ORDER BY created_at DESC");
if ($result) {
    $batteries = $result->fetch_all(MYSQLI_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard</title>
    <link rel="stylesheet" href="index.css" />
    <link rel="stylesheet" href="dashboard.css" />
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  </head>
  <body>
    <div class="flex">
      <!-- Sidebar -->
      <iframe
        id="sidebar"
        src="sidebar.PHP"
        class="min-[768px]:block hidden"
        style="height: 100vh; border: none"
      ></iframe>
      <iframe
        id="sidebars"
        src="sideBarmd.html"
        class="max-[768px]:!block hidden absolute top-0 !w-screen"
        style="height: 100vh; border: none;"
      ></iframe>

      <!-- Main Section -->
      <div
        id="main"
        class="bg-[var(--background)] min-[768px]:px-8 px-3 max-[768px]:!w-screen w-full"
      >
        <span>
          <header
            class="flex h-[48px] shrink-0 items-center justify-between gap-2 transition-all ease-linear"
          >
            <div class="flex items-center gap-2">
              <button
                id="toggleBtn"
                class="inline-flex items-center bg-[var(--accent)] justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium transition-all shrink-0 outline-none focus-visible:border-ring focus-visible:ring-ring/50 focus-visible:ring-[3px] hover:bg-accent hover:text-accent-foreground dark:hover:bg-accent/50 size-7 hover:!shadow-sm"
              >
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-align-justify-icon lucide-align-justify"><path d="M3 12h18"/><path d="M3 18h18"/><path d="M3 6h18"/></svg>
                <span class="sr-only">Toggle Sidebar</span>
              </button>

              <div class="bg-border shrink-0 mr-2 h-4 w-px"></div>

              <nav>
                <ol
                  class="text-muted-foreground flex flex-wrap items-center gap-1.5 text-sm break-words sm:gap-2.5"
                >
                  <li class="inline-flex items-center gap-1.5">
                    <span class="text-foreground font-normal">Dashboard</span>
                  </li>
                </ol>
              </nav>
            </div>

            <!-- Logout button -->
            <div>
              <span class="mr-4 font-semibold text-gray-700">
                <?php echo $_SESSION['email']; ?>
              </span>
              <a href="logout.php" class="px-3 py-2 bg-red-500 text-white rounded text-sm">Logout</a>
            </div>
          </header>
        </span>

        <!-- Message Display -->
        <?php if ($message): ?>
          <?php 
            list($type, $text) = explode(':', $message, 2);
            $bgColor = $type === 'success' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700';
          ?>
          <div class="p-3 mb-4 rounded <?php echo $bgColor; ?> text-center">
            <?php echo $text; ?>
          </div>
        <?php endif; ?>

        <!-- Search + Add Data -->
        <div class="flex gap-3 items-center mt-8">
          <input
            type="text"
            id="searchInput"
            class="outline-none px-4 w-full py-2 bg-[var(--background)] border rounded"
            placeholder="Search....."
            onkeyup="searchTable()"
          />
          <button
            onclick="handleForm()"
            class="px-3 shrink-0 py-2 rounded bg-[var(--text)] text-[var(--accent)] flex items-center gap-1 max-h-[48px] ml-2 w-max"
          >
            <h1 class="min-[768px]:block hidden">Add Data</h1>
            <svg
              xmlns="http://www.w3.org/2000/svg"
              width="18"
              height="18"
              viewBox="0 0 24 24"
              fill="none"
              stroke="currentColor"
              stroke-width="2"
              stroke-linecap="round"
              stroke-linejoin="round"
              class="lucide lucide-plus-icon lucide-plus max-[768px]:block hidden"
            >
              <path d="M5 12h14" />
              <path d="M12 5v14" />
            </svg>
          </button>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto mt-8" style="scrollbar-width: none">
          <table
            id="batteriesTable"
            class="min-w-full bg-[var(--accent)] text-text text-xs sm:text-sm w-max"
          >
            <thead>
              <tr>
                <th class="px-2 sm:px-4 py-2">Date/Time</th>
                <th class="px-2 sm:px-4 py-2">S</th>
                <th class="px-2 sm:px-4 py-2">C/D</th>
                <th class="px-2 sm:px-4 py-2">Volt</th>
                <th class="px-2 sm:px-4 py-2">Amp</th>
                <th class="px-2 sm:px-4 py-2">Chg Hrs</th>
                <th class="px-2 sm:px-4 py-2">AH</th>
                <th class="px-2 sm:px-4 py-2">WH</th>
                <th class="px-2 sm:px-4 py-2">SG</th>
                <th class="px-2 sm:px-4 py-2">Acid Temp</th>
                <th class="px-2 sm:px-4 py-2">Real V</th>
                <th class="px-2 sm:px-4 py-2">Real A</th>
                <th class="px-2 sm:px-4 py-2">Sign</th>
                <th class="px-2 sm:px-4 py-2">Remarks</th>
                <th class="px-2 sm:px-4 py-2">Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php if (empty($batteries)): ?>
                <tr>
                  <td colspan="15" class="text-center py-4 text-gray-500">No battery data found. Click "Add Data" to get started.</td>
                </tr>
              <?php else: ?>
                <?php foreach ($batteries as $battery): ?>
                  <tr class="text-center w-max" style="border-bottom: 0.1px solid #e5e5e5">
                    <td class="px-2 sm:px-4 py-2 text-xs">
                      <?php echo date('d/m/Y, H:i', strtotime($battery['created_at'])); ?>
                    </td>
                    <td class="px-2 sm:px-4 py-2 text-xs"><?php echo htmlspecialchars($battery['sValue']); ?></td>
                    <td class="px-2 sm:px-4 py-2"><?php echo htmlspecialchars($battery['cdRatio']); ?></td>
                    <td class="px-2 sm:px-4 py-2"><?php echo htmlspecialchars($battery['voltage']); ?></td>
                    <td class="px-2 sm:px-4 py-2"><?php echo htmlspecialchars($battery['current']); ?></td>
                    <td class="px-2 sm:px-4 py-2"><?php echo htmlspecialchars($battery['chargeHours']); ?></td>
                    <td class="px-2 sm:px-4 py-2"><?php echo htmlspecialchars($battery['ampereHours']); ?></td>
                    <td class="px-2 sm:px-4 py-2"><?php echo htmlspecialchars($battery['wattHours']); ?></td>
                    <td class="px-2 sm:px-4 py-2"><?php echo htmlspecialchars($battery['specificGravity']); ?></td>
                    <td class="px-2 sm:px-4 py-2"><?php echo htmlspecialchars($battery['acidTemp']); ?></td>
                    <td class="px-2 sm:px-4 py-2"><?php echo htmlspecialchars($battery['realVoltage']); ?></td>
                    <td class="px-2 sm:px-4 py-2"><?php echo htmlspecialchars($battery['realCurrent']); ?></td>
                    <td class="px-2 sm:px-4 py-2"><?php echo htmlspecialchars($battery['signature']); ?></td>
                    <td class="px-2 sm:px-4 py-2"><?php echo htmlspecialchars($battery['remarks']); ?></td>
                    <td class="px-2 sm:px-4 py-2 text-xs flex justify-center gap-1">
                      <button onclick="editBattery(<?php echo $battery['id']; ?>)" class="px-3 py-2 rounded bg-blue-500 text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" class="lucide lucide-edit h-4 w-4">
                          <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                          <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                        </svg>
                      </button>
                      <button onclick="deleteBattery(<?php echo $battery['id']; ?>)" class="px-3 py-2 rounded bg-red-500 text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" class="lucide lucide-trash2 h-4 w-4">
                          <path d="M3 6h18"></path>
                          <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
                          <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
                          <line x1="10" x2="10" y1="11" y2="17"></line>
                          <line x1="14" x2="14" y1="11" y2="17"></line>
                        </svg>
                      </button>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Add/Edit Data Modal -->
    <div
      id="addData"
      class="hidden fixed inset-0 z-50 min-[768px]:items-center justify-center bg-[rgba(0,0,0,0.3)] backdrop-blur-[2px] bg-opacity-60 px-2 sm:px-0 h-screen max-[768px]:top-0"
    >
      <form
        method="POST"
        class="bg-[var(--accent)] p-6 rounded-lg shadow-lg w-full max-w-md max-h-[95vh] overflow-y-auto"
        style="scrollbar-width: none"
      >
        <h2 class="text-lg font-semibold text-text mb-4 text-center">
          <?php echo $editBattery ? 'Edit Battery Data' : 'Add Battery Data'; ?>
        </h2>
        
        <?php if ($editBattery): ?>
          <input type="hidden" name="edit_id" value="<?php echo $editBattery['id']; ?>">
        <?php endif; ?>
        
        <div class="grid grid-cols-2 gap-4">
          <div class="col-span-2">
            <label class="block text-sm font-medium">S Value</label>
            <input type="text" name="sValue" value="<?php echo $editBattery ? htmlspecialchars($editBattery['sValue']) : ''; ?>" required class="w-full mt-1 p-2 rounded bg-label text-text border" />
          </div>
          <div class="col-span-2">
            <label class="block text-sm font-medium">C/D Ratio</label>
            <select name="cdRatio" required class="w-full mt-1 p-2 rounded bg-label text-text border">
              <option value="">Select</option>
              <option value="C" <?php echo ($editBattery && $editBattery['cdRatio'] == 'C') ? 'selected' : ''; ?>>C</option>
              <option value="D" <?php echo ($editBattery && $editBattery['cdRatio'] == 'D') ? 'selected' : ''; ?>>D</option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium">Voltage (V)</label>
            <input type="number" step="0.01" name="voltage" value="<?php echo $editBattery ? htmlspecialchars($editBattery['voltage']) : ''; ?>" required class="w-full mt-1 p-2 rounded bg-label text-text border" />
          </div>
          <div>
            <label class="block text-sm font-medium">Current (A)</label>
            <input type="number" step="0.01" name="current" value="<?php echo $editBattery ? htmlspecialchars($editBattery['current']) : ''; ?>" required class="w-full mt-1 p-2 rounded bg-label text-text border" />
          </div>
          <div>
            <label class="block text-sm font-medium">Charge Hours</label>
            <input type="number" step="0.1" name="chargeHours" value="<?php echo $editBattery ? htmlspecialchars($editBattery['chargeHours']) : ''; ?>" required class="w-full mt-1 p-2 rounded bg-label text-text border" />
          </div>
          <div>
            <label class="block text-sm font-medium">Ampere Hours (Ah)</label>
            <input type="number" step="0.01" name="ampereHours" value="<?php echo $editBattery ? htmlspecialchars($editBattery['ampereHours']) : ''; ?>" required class="w-full mt-1 p-2 rounded bg-label text-text border" />
          </div>
          <div>
            <label class="block text-sm font-medium">Watt Hours (Wh)</label>
            <input type="number" step="0.01" name="wattHours" value="<?php echo $editBattery ? htmlspecialchars($editBattery['wattHours']) : ''; ?>" required class="w-full mt-1 p-2 rounded bg-label text-text border" />
          </div>
          <div>
            <label class="block text-sm font-medium">Specific Gravity</label>
            <input type="number" step="0.001" name="specificGravity" value="<?php echo $editBattery ? htmlspecialchars($editBattery['specificGravity']) : ''; ?>" required class="w-full mt-1 p-2 rounded bg-label text-text border" />
          </div>
          <div>
            <label class="block text-sm font-medium">Acid Temp (°C)</label>
            <input type="number" step="0.1" name="acidTemp" value="<?php echo $editBattery ? htmlspecialchars($editBattery['acidTemp']) : ''; ?>" required class="w-full mt-1 p-2 rounded bg-label text-text border" />
          </div>
          <div>
            <label class="block text-sm font-medium">Real Voltage (V)</label>
            <input type="number" step="0.01" name="realVoltage" value="<?php echo $editBattery ? htmlspecialchars($editBattery['realVoltage']) : ''; ?>" required class="w-full mt-1 p-2 rounded bg-label text-text border" />
          </div>
          <div>
            <label class="block text-sm font-medium">Real Current (A)</label>
            <input type="number" step="0.01" name="realCurrent" value="<?php echo $editBattery ? htmlspecialchars($editBattery['realCurrent']) : ''; ?>" required class="w-full mt-1 p-2 rounded bg-label text-text border" />
          </div>
          <div class="col-span-2">
            <label class="block text-sm font-medium">Signature</label>
            <input type="text" name="signature" value="<?php echo $editBattery ? htmlspecialchars($editBattery['signature']) : ''; ?>" required class="w-full mt-1 p-2 rounded bg-label text-text border" />
          </div>
          <div class="col-span-2">
            <label class="block text-sm font-medium">Remarks</label>
            <textarea name="remarks" class="w-full mt-1 p-2 rounded bg-label text-text border h-20"><?php echo $editBattery ? htmlspecialchars($editBattery['remarks']) : ''; ?></textarea>
          </div>
        </div>
        <div class="flex justify-center gap-2 mt-6">
          <button type="button" onclick="handleForm()" class="px-4 py-2 rounded bg-gray-500 text-white hover:bg-gray-600 w-full">
            Cancel
          </button>
          <button type="submit" class="px-4 py-2 rounded bg-blue-500 text-white hover:bg-blue-600 w-full">
            <?php echo $editBattery ? 'Update Battery' : 'Add Battery'; ?>
          </button>
        </div>
      </form>
    </div>

    <!-- JS -->
    <script>
      let sidebarVisible = true;
      const main = document.getElementById("main");
      const sidebar = document.getElementById("sidebar");
      const sidebars = document.getElementById("sidebars");
      const addData = document.getElementById("addData");

      function handleForm() {
        if (addData.style.display === "flex") {
          addData.style.display = "none";
          // Clear edit mode when closing modal
          window.location.href = 'dashboard.php';
        } else {
          addData.style.display = "flex";
        }
      }

      function editBattery(id) {
        window.location.href = 'dashboard.php?edit_id=' + id;
      }

      function searchTable() {
        const input = document.getElementById("searchInput");
        const filter = input.value.toLowerCase();
        const table = document.getElementById("batteriesTable");
        const tr = table.getElementsByTagName("tr");

        for (let i = 1; i < tr.length; i++) {
          let td = tr[i].getElementsByTagName("td");
          let found = false;
          for (let j = 0; j < td.length; j++) {
            if (td[j]) {
              if (td[j].textContent.toLowerCase().indexOf(filter) > -1) {
                found = true;
                break;
              }
            }
          }
          tr[i].style.display = found ? "" : "none";
        }
      }

      function deleteBattery(id) {
        if (confirm('Are you sure you want to delete this battery record?')) {
          window.location.href = 'delete_battery.php?id=' + id;
        }
      }

      function applySidebarState(state) {
        if (state === "hide") {
          main.style.width = "calc(100vw)";
          sidebar.style.width = "48px";
          sidebars.style.display = "none";
          sidebars.classList.remove("max-[768px]:!block");
          sidebars.classList.add("max-[768px]:!hidden");
          sidebarVisible = false;
        } else {
          sidebars.classList.add("max-[768px]:!block");
          sidebars.classList.remove("max-[768px]:!hidden");
          sidebar.style.width = "256px";
          sidebars.style.display = "block !important";
          main.style.width = "calc(100vw)";
          sidebarVisible = true;
        }
      }

      document.getElementById("toggleBtn").addEventListener("click", () => {
        const newState = localStorage.getItem("sidebarStatus") == "show" ? "hide" : "show";
        applySidebarState(newState);
        localStorage.setItem("sidebarStatus", newState);
      });

      window.addEventListener("storage", (event) => {
        if (event.key === "sidebarStatus") {
          applySidebarState(event.newValue);
          sidebarVisible = event.newValue === "show";
        }
      });

      const initial = localStorage.getItem("sidebarStatus") || "show";
      applySidebarState(initial);

      // Close modal on ESC key
      document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
          addData.style.display = "none";
          window.location.href = 'dashboard.php';
        }
      });

      // Close modal when clicking outside
      addData.addEventListener('click', (e) => {
        if (e.target === addData) {
          handleForm();
        }
      });

      // Auto-open modal if in edit mode
      <?php if ($editBattery): ?>
        document.addEventListener('DOMContentLoaded', function() {
          addData.style.display = "flex";
        });
      <?php endif; ?>
    </script>
  </body>
</html>