$(document).ready(async function () {

    let currentMode = "";

    function openOverlay() {
        $("#modalOverlay").css("display", "flex");
    }

    function closeOverlay() {
        $("#modalOverlay").hide();
        $(".modal-box").hide();

        $("#modalUserId").val("");
        $("#modalFirstName").val("");
        $("#modalLastName").val("");
        $("#modalEmail").val("");
        $("#modalPassword").val("");
        $("#modalRole").val("Student");
    }

    function showUserModal(mode) {

        currentMode = mode;

        $("#passwordGroup").show();

        if (mode === "add") {

            $("#userModalTitle").text("Add User");
            $("#saveModal").text("Add User");

        } else {

            $("#userModalTitle").text("Edit User");
            $("#saveModal").text("Save Changes");

            $("#passwordGroup").hide();

        }

        $(".modal-box").hide();

        $("#userModal").show();

        openOverlay();
    }

    let data = [];

    try {
        const response = await fetch("../../controllers/UsersController.php?action=list");
        const result = await response.json();

        if (result.success) {
            data = result.users;
        } else {
            console.error("Failed to load users:", result.message);
        }

    } catch (error) {
        console.error("User Fetch Error:", error);
    }

    var table = $('#manageUsersTable').DataTable({
        data: data,
        columns: [
        { data: 'last_name' },
        { data: 'first_name' },
        { data: 'user_id' },
        { data: 'email' },
        { data: 'role' },
        {
            data: null,
            orderable: false,
            searchable: false,
            render: function (data, type, row) {
                return `
                    <button class="function-button edit-btn" data-id="${row.user_id}">Edit</button>
                    <button class="function-button password-btn" data-id="${row.user_id}">Password</button>
                    <button class="function-button delete-btn" data-id="${row.user_id}">Delete</button>
                `;
            }
        }
     ], 
        dom: 'lrtip'
    });

    // ADD USER BUTTON CLICK
    $('#addUserBtn').click(function () {

        $("#modalUserId").val("");
        $("#modalFirstName").val("");
        $("#modalLastName").val("");
        $("#modalEmail").val("");
        $("#modalPassword").val("");
        $("#modalRole").val("Student");

        showUserModal("add");

    });

    // SAVE BUTTON
    $("#saveModal").click(async function () {

        try {

            let url = "";
            let body = {};

            if (currentMode === "add") {

                url = "../../controllers/UsersController.php?action=add";

                body = {
                    first_name: $("#modalFirstName").val(),
                    last_name: $("#modalLastName").val(),
                    email: $("#modalEmail").val(),
                    password: $("#modalPassword").val(),
                    role: $("#modalRole").val()
                };

            } else {

                url = "../../controllers/UsersController.php?action=update";

                body = {
                    user_id: $("#modalUserId").val(),
                    first_name: $("#modalFirstName").val(),
                    last_name: $("#modalLastName").val(),
                    email: $("#modalEmail").val(),
                    role: $("#modalRole").val()
                };

            }

            const response = await fetch(url, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify(body)
            });

            const result = await response.json();

            if (!result.success) {
                showToast(
                    result.message,
                    "var(--color-errorMsg)"
                );
                return;
            }

            if (currentMode === "add") {

                table.row.add({
                    user_id: result.user_id,
                    first_name: $("#modalFirstName").val(),
                    last_name: $("#modalLastName").val(),
                    email: $("#modalEmail").val(),
                    role: $("#modalRole").val()
                }).draw(false);

            } else {

                const row = table.row(
                    $('.edit-btn[data-id="' + $("#modalUserId").val() + '"]').closest('tr')
                );

                const user = row.data();

                user.first_name = $("#modalFirstName").val();
                user.last_name = $("#modalLastName").val();
                user.email = $("#modalEmail").val();
                user.role = $("#modalRole").val();

                row.data(user).draw(false);

            }

            closeOverlay();

            if (currentMode === "add") {
                showSuccess("User added successfully!");
            } else {
                showSuccess("Account updated successfully!");
            }

        } catch (err) {

            console.error(err);

        }

    });

    // EDIT BUTTON CLICK
    $('#manageUsersTable tbody').on('click', '.edit-btn', function () {

    currentMode = "edit";

    const row = table.row($(this).closest('tr'));
    const user = row.data();

    $("#modalUserId").val(user.user_id);

    $("#modalFirstName").val(user.first_name);

    $("#modalLastName").val(user.last_name);

    $("#modalEmail").val(user.email);

    $("#modalRole").val(user.role);

    $("#passwordGroup").hide();

    $("#userModalTitle").text("Edit User");

    $("#saveModal").text("Save Changes");

    $(".modal-box").hide();

    $("#userModal").show();

    openOverlay();

});
    
    // PASSWORD BUTTON CLICK
    $('#manageUsersTable tbody').on('click', '.password-btn', function () {

        $("#toast").removeClass("show");
    
        currentMode = "password";

        const row = table.row($(this).closest('tr'));
        const user = row.data();

        $("#passwordUserId").val(user.user_id);

        $("#newPassword").val("");
        $("#confirmNewPassword").val("");

        $(".modal-box").hide();

        $("#passwordModal").show();

        openOverlay();

    });

    $("#updatePasswordBtn").click(async function () {

        const password = $("#newPassword").val();
        const confirm = $("#confirmNewPassword").val();

        if (password === "" || confirm === "") {

            showToast(
                "Please complete all fields.",
                "var(--color-errorMsg)"
            );
            return;
        }

        if (password !== confirm) {

            showToast(
                "Passwords do not match.",
                "var(--color-errorMsg)"
            );
            return;
        }

        try {

            const response = await fetch(
                "../../controllers/UsersController.php?action=resetPassword",
                {

                    method: "POST",

                    headers: {
                        "Content-Type": "application/json"
                    },

                    body: JSON.stringify({

                        user_id: $("#passwordUserId").val(),

                        password: password

                    })

                });

            const result = await response.json();

            if (result.success) {

                closeOverlay();

                showSuccess("Password updated successfully!");

            } else {

                closeOverlay();

                showToast(
                    result.message,
                    "var(--color-errorMsg)"
                );

            }

        } catch (error) {

            console.error(error);

        }

    });
  
    // DELETE BUTTON CLICK
    $('#manageUsersTable tbody').on('click', '.delete-btn', function () {

        const row = table.row($(this).closest('tr'));
        const user = row.data();

        $("#deleteUserId").val(user.user_id);

        $("#deleteMessage").html(
            `Are you sure you want to delete <b>${user.first_name} ${user.last_name}</b>?`
        );

        $(".modal-box").hide();

        $("#deleteModal").show();

        openOverlay();

    });

    $("#confirmDelete").click(async function () {

        try {

            const id = $("#deleteUserId").val();

            const response = await fetch(
                "../../controllers/UsersController.php?action=delete",
                {

                    method: "POST",

                    headers: {
                        "Content-Type": "application/json"
                    },

                    body: JSON.stringify({

                        user_id: id

                    })

                });

            const result = await response.json();

            if (!result.success) {

                showToast(
                    result.message,
                    "var(--color-errorMsg)"
                );

                return;

            }

            table.rows().every(function () {

                if (this.data().user_id == id) {

                    this.remove();

                }

            });

            table.draw(false);

            closeOverlay();

            showSuccess("Account deleted successfully!");

        }

        catch (error) {

            console.error(error);

        }

    });

    // map sortField values to actual column indices
    var columnMap = {
            firstname: 1,
            lastname: 0,
            idnum: 2,
            userlevel: 4
        };

    var currentDirection = 'asc'; // track current sort direction

    // SEARCH BAR
    $('.search-bar').on('keyup', function () {
        table.search(this.value).draw();
    });

    // SORT FIELD DROPDOWN
    $('#sortField').on('change', function () {
        applySort();
    });

    // SORT DIRECTION TOGGLE BUTTON
    $('#sortDirection').on('click', function () {
        currentDirection = currentDirection === 'asc' ? 'desc' : 'asc';
        $(this).text(currentDirection === 'asc' ? '↑' : '↓'); // update button label
        applySort();
    });

    function applySort() {
        var field = $('#sortField').val();
        var columnIndex = columnMap[field];
        table.order([columnIndex, currentDirection]).draw();
    }

    // FILTER DROPDOWN
    $('.filter-dropdown').on('change', function () {
        var value = $(this).val();
        if (value === 'Filter: All') {
            table.column(4).search('').draw();
        } else {
            table.column(4).search('^' + value + '$', true, false).draw();
        }
    });

    $("#cancelModal").click(function () {
    closeOverlay();
    });

    $("#cancelPassword").click(function () {
        closeOverlay();
    });

    $("#cancelDelete").click(function () {
        closeOverlay();
    });

    $("#modalOverlay").click(function (e) {

        if (e.target.id === "modalOverlay") {
            closeOverlay();
        }

    });
});

function showSuccess(message){

    // hides any toast immediately
    $("#toast").removeClass("show-toast");

    $("#successMessage").text(message);

    $("#modalOverlay").show();
    $("#successModal").show();
}

$("#closeSuccess").click(function(){

    $("#successModal").hide();
    $("#modalOverlay").hide();

});