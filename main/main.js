const loginBtn = document.getElementById("login-btn");
const loginMenu = document.getElementById("login-menu");

loginBtn.addEventListener("click", (e) => {
  // This prevents the menu from closing immediately when clicking the button
  e.stopPropagation();

  // Toggle the animation classes
  loginMenu.classList.toggle("invisible");
  loginMenu.classList.toggle("opacity-0");
  loginMenu.classList.toggle("translate-y-[-10px]");
  loginMenu.classList.toggle("opacity-100");
  loginMenu.classList.toggle("translate-y-0");
});

// Close menu when clicking anywhere else on the page
window.addEventListener("click", () => {
  loginMenu.classList.add("invisible", "opacity-0", "translate-y-[-10px]");
  loginMenu.classList.remove("opacity-100", "translate-y-0");
});

// Existing Sidebar Toggle Logic
const mobileMenuBtn = document.getElementById("mobile-menu-btn");
const mobileSidebar = document.getElementById("mobile-sidebar");
const sidebarOverlay = document.getElementById("sidebar-overlay");
const closeSidebarBtn = document.getElementById("close-sidebar");

const toggleSidebar = () => {
  const isOpen = !mobileSidebar.classList.contains("-translate-x-full");
  if (isOpen) {
    mobileSidebar.classList.add("-translate-x-full");
    sidebarOverlay.classList.replace("opacity-100", "opacity-0");
    setTimeout(() => sidebarOverlay.classList.add("hidden"), 300); // Wait for fade out
  } else {
    sidebarOverlay.classList.remove("hidden");
    mobileSidebar.classList.remove("-translate-x-full");
    setTimeout(
      () => sidebarOverlay.classList.replace("opacity-0", "opacity-100"),
      10,
    );
  }
};

mobileMenuBtn.addEventListener("click", toggleSidebar);
closeSidebarBtn.addEventListener("click", toggleSidebar);
sidebarOverlay.addEventListener("click", toggleSidebar);

// NEW: Mobile Sub-menu (Accordion) Logic
const dropdownBtns = document.querySelectorAll(".mobile-dropdown-btn");

dropdownBtns.forEach((btn) => {
  btn.addEventListener("click", (e) => {
    e.preventDefault(); // Prevent jump if it's a button
    const subMenu = btn.nextElementSibling;
    const arrowIcon = btn.querySelector("svg");

    // Toggle visibility
    subMenu.classList.toggle("hidden");

    // Rotate arrow icon
    arrowIcon.classList.toggle("rotate-180");
  });
});

// IMPROVED: Logic to close sidebar when ANY link is clicked
// This ensures that when you click "About" or "MCQ", the sidebar disappears
const allSidebarLinks = mobileSidebar.querySelectorAll("a");

allSidebarLinks.forEach((link) => {
  link.addEventListener("click", () => {
    // Only close if it's not a dropdown toggle
    if (!link.classList.contains("mobile-dropdown-btn")) {
      toggleSidebar();
    }
  });
});
///////////////
