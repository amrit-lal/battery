<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sidebar</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="index.css">
    <link rel="stylesheet" href="sidebar.css">
</head>
<body>
    <div class="h-[100vh] overflow-hidden bg-[var(--accent)]" id="sidebar">
        <div id="topbar" class="flex justify-between items-center p-[8px] iconOnly max-h-[69px]">
            <span class="flex items-center justify-start p-[8px] gap-2">
                <div class="bg-sidebar-primary text-sidebar-primary-foreground shrink-0 flex aspect-square size-8 items-center justify-center rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-gallery-vertical-end size-4">
                        <path d="M7 2h10"></path>
                        <path d="M5 6h14"></path>
                        <rect width="18" height="12" x="3" y="10" rx="2"></rect>
                    </svg>
                </div>
                <span class="" id="menu2">
                    <h1 class="text-[14px]">Battery Panel</h1>
                    <h2 class="text-xs">Welcome, <?php echo htmlspecialchars($_SESSION['email']); ?></h2>
                </span>
            </span>
            <button class="flex items-center gap-2 px-3 py-1 rounded text-text shadow-none hover:!shadow-sm h-[48px]">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-moon h-5 w-5">
                    <path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z"></path>
                </svg>
            </button>
        </div>
        <ul>
            <span class="text-[12px] text-[#131313] p-[8px]" id="menu">Main Menu</span>
            <li class="iconOnly">
                <a href="Dashboard.php" class="flex items-center gap-2 p-2 hover:bg-sidebar-primary hover:text-sidebar-primary-foreground rounded">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-layout-dashboard">
                        <rect width="7" height="9" x="3" y="3" rx="1"></rect>
                        <rect width="7" height="5" x="14" y="3" rx="1"></rect>
                        <rect width="7" height="9" x="14" y="12" rx="1"></rect>
                        <rect width="7" height="5" x="3" y="16" rx="1"></rect>
                    </svg>
                    <h3>Dashboard</h3>
                </a>
            </li>
            <li class="iconOnly">
                <a href="logs.php" class="flex items-center gap-2 p-2 hover:bg-sidebar-primary hover:text-sidebar-primary-foreground rounded">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-bar-chart3">
                        <path d="M3 3v18h18"></path>
                        <path d="M18 17V9"></path>
                        <path d="M13 17V5"></path>
                        <path d="M8 17v-3"></path>
                    </svg>
                    <h3>Check Logs</h3>
                </a>
            </li>
            <li class="iconOnly">
                <a href="users.php" class="flex items-center gap-2 p-2 hover:bg-sidebar-primary hover:text-sidebar-primary-foreground rounded">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-file-text">
                        <path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                        <line x1="16" x2="8" y1="13" y2="13"></line>
                        <line x1="16" x2="8" y1="17" y2="17"></line>
                        <line x1="10" x2="8" y1="9" y2="9"></line>
                    </svg>
                    <h3>Add Issues</h3>
                </a>
            </li>
            <li class="iconOnly">
                <a href="logout.php" class="flex items-center gap-2 p-2 hover:bg-red-500 hover:text-white rounded mt-4">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-log-out">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                        <polyline points="16 17 21 12 16 7"></path>
                        <line x1="21" x2="9" y1="12" y2="12"></line>
                    </svg>
                    <h3>Logout</h3>
                </a>
            </li>
        </ul>
    </div>
    <script>
        const sidebar = document.getElementById("sidebar");
        const iconOnly = document.getElementsByClassName("iconOnly");
        const topbar = document.getElementById("topbar");
        const menu = document.getElementById("menu");

        function setIconOnlyDisplay(show) {
            if (!show) {
                topbar.style.padding = "0px";
                menu.classList = "opacity-[0] mt-[-2rem]";
            } else {
                menu.classList = "text-[12px] text-[#131313] p-[8px]";
                topbar.style.padding = "8px";
            }
            for (let i = 0; i < iconOnly.length; i++) {
                iconOnly[i].style.width = show ? "100%" : "2rem";
                iconOnly[i].style.height = show ? "100%" : "2rem";
            }
        }

        window.addEventListener("storage", (event) => {
            if (event.key === "sidebarStatus") {
                applySidebarState(event.newValue);
            }
        });

        function applySidebarState(status) {
            if (status === "hide") {
                sidebar.style.width = "48px";
                setIconOnlyDisplay(false);
            } else {
                sidebar.style.width = "256px";
                setIconOnlyDisplay(true);
            }
        }

        const initialStatus = localStorage.getItem("sidebarStatus") || "show";
        applySidebarState(initialStatus);
    </script>
</body>
</html>