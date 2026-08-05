// -------------------------- USER BUTTON -------------------------- //
const userButtons = document.querySelectorAll(".user-button");

// Helper to close all open profiles
function closeAllProfiles() {
    document.querySelectorAll(".user-profile.active").forEach(p => p.classList.remove("active"));
}

userButtons.forEach((wrapper) => {
    const button = wrapper.querySelector("button") || wrapper;
    const profile = wrapper.querySelector(".user-profile");
    if (!profile) return;

    // toggle profile for this wrapper
    button.addEventListener("click", (e) => {
        e.stopPropagation(); // prevents the click from bubbling up to document
        document.querySelectorAll(".user-profile.active").forEach(p => {
            if (p !== profile) p.classList.remove("active");
        });
        profile.classList.toggle("active");
    });

    // attach the internal buttons for this profile specifically
    const logOutButton = profile.querySelector(".log-out button") || profile.querySelector(".log-out");
    // const viewDashboardButton = profile.querySelector(".view-dashboard button") || profile.querySelector(".view-dashboard");
    // const manageAccountButton = profile.querySelector(".manage-account");

    // e.stopPropagation() will help keep the panel open
    if (logOutButton) {
        logOutButton.addEventListener("click", (e) => {
            e.stopPropagation();

            // Logout now lives in UsersController.php, which only accepts
            // POST requests — build and submit a hidden form instead of
            // doing a plain GET redirect.
            const logoutForm = document.createElement("form");
            logoutForm.method = "POST";
            logoutForm.action = "../../controllers/UsersController.php?action=logout";
            document.body.appendChild(logoutForm);
            logoutForm.submit();
        });
    }

    // if (manageAccountButton) {
    //     manageAccountButton.addEventListener("click", (e) => {
    //         e.stopPropagation();
    //         window.location.href = 'student_manage-account.html';
    //     });
    // }

    // if (viewDashboardButton) {
    //     viewDashboardButton.addEventListener("click", (e) => {
    //         e.stopPropagation();
    //         window.location.href = 'student_dashboard.html';
    //     });
    // }
});

// close when clicking anywhere outside
document.addEventListener("click", () => {
    closeAllProfiles();
});


// ------------------------------ SIDEBAR ------------------------------ //
const sidebarOpenCloseButton = document.querySelector(".sidebar-open-close button");
const sidebar = document.querySelector(".sidebar");

if (sidebarOpenCloseButton) {
    sidebarOpenCloseButton.addEventListener("click", () => {
        sidebar.classList.toggle("open");
    });
}


// ---------------------------- DARK MODE ------------------------------ //
// Self-contained: handles its own buttons, labels, logos, and images.
// Works anywhere a ".day-night button" exists — navbar, profile panel, admin page, etc.

const enableDarkmode = () => {
    document.body.classList.add('darkmode');
    localStorage.setItem('darkmode', 'active');

    const logos = document.querySelectorAll(".logo");
    if (logos.length) logos.forEach(logo => logo.src = "CCDEVAP-S16-6-ArcherFind-DLSU-Lost-and-Found-System/assets/LOGOS/AF-DARKMODE.png");

    const aboutImg = document.querySelector('#banner img');
    if (aboutImg) aboutImg.src = "../../assets/BACKGROUNDS/AboutDark.png";

    updateDarkModeLabels();
};

const disableDarkmode = () => {
    document.body.classList.remove('darkmode');
    localStorage.setItem('darkmode', null);

    const logos = document.querySelectorAll(".logo");
    if (logos.length) logos.forEach(logo => logo.src = "CCDEVAP-S16-6-ArcherFind-DLSU-Lost-and-Found-System/assets/LOGOS/AF-ORIGINAL.png");

    const aboutImg = document.querySelector('#banner img');
    if (aboutImg) aboutImg.src = "../../assets/BACKGROUNDS/About.png";

    updateDarkModeLabels();
};

// Updates any "switch to X mode" label spans next to day-night buttons
function updateDarkModeLabels() {
    const isDark = document.body.classList.contains('darkmode');
    document.querySelectorAll(".day-night span").forEach(label => {
        label.textContent = isDark ? "Switch to Light" : "Switch to Dark";
    });
}

function toggleDarkmode(e) {
    if (e) e.stopPropagation(); // keeps any parent panel (like .user-profile) open
    const darkmode = localStorage.getItem('darkmode');
    darkmode !== "active" ? enableDarkmode() : disableDarkmode();
}

// Attach the toggle to every day-night button on the page, wherever it lives
document.querySelectorAll(".day-night button").forEach(btn => {
    btn.addEventListener("click", toggleDarkmode);
});

// Apply saved preference and set correct label text on page load
if (localStorage.getItem('darkmode') === "active") {
    enableDarkmode();
} else {
    updateDarkModeLabels();
}