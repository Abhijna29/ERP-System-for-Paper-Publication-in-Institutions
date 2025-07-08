// import { Offcanvas } from "bootstrap";

document.addEventListener("DOMContentLoaded", function () {
    const menuBtn = document.getElementById("menuToggleBtn");
    const desktopSidebar = document.getElementById("desktopSidebar");
    const offCanvasSidebarEl = document.getElementById("sidebarOffcanvas");

    if (offCanvasSidebarEl && menuBtn) {
        const offCanvasSidebar = new bootstrap.Offcanvas(offCanvasSidebarEl); // Default backdrop enabled

        menuBtn.addEventListener("click", function () {
            // Check window size and toggle the sidebar accordingly
            if (window.innerWidth < 1200) {
                console.log(
                    "Mobile view detected. Toggling offcanvas sidebar."
                );
                offCanvasSidebar.toggle(); // Mobile sidebar toggle
            } else {
                console.log(
                    "Desktop view detected. Toggling sidebar visibility."
                );
                if (desktopSidebar) {
                    desktopSidebar.classList.toggle("sidebar-hidden"); // Desktop sidebar toggle
                }
            }
        });
    } else {
        console.error("Sidebar or Menu Button not found.");
    }
});
