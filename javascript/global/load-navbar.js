document.addEventListener("DOMContentLoaded", function () {
    const path = window.location.pathname;
    const page = path.split("/").pop();

    let navbarFile = "navbar-student.html"; // Default fallback

    if (page.startsWith("admin_")) {
        navbarFile = "../../pages/shared/NAVBAR-admin.html";
    } else if (page.startsWith("staff_")) {
        navbarFile = "../../pages/shared/NAVBAR-staff.html";
    } else if (page.startsWith("student_")) {
        navbarFile = "../../pages/shared/NAVBAR-student.html";
    }

    fetch(navbarFile)
        .then(response => {
            if (!response.ok) throw new Error("Navbar file not found");
            return response.text();
        })
        .then(data => {
            document.getElementById("navbar-placeholder").innerHTML = data;
            
            // to be added: put interactive JS for  navbars (like dropdowns),
            // initialize those functions here after the HTML is loaded.
        })
        .catch(error => console.error("Error loading dynamic navbar:", error));
});