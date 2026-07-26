$(document).ready(async function () {

    let data = [];

    try {
        const response = await fetch("../../controllers/ManageUsersController.php?action=list");
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
                    <button class="function-button delete-btn" data-id="${row.user_id}">Delete</button>
                `;
            }
        }
     ], 
        dom: 'lrtip'
    });

    // ADD USER BUTTON CLICK
    $('#addUserBtn').on('click', async function () {

        const firstName = prompt("First Name:");
        if (firstName === null) return;

        const lastName = prompt("Last Name:");
        if (lastName === null) return;

        const email = prompt("DLSU Email:");
        if (email === null) return;

        const password = prompt("Password:");
        if (password === null) return;

        const role = prompt("Role (Student, Staff, Admin):", "Student");
        if (role === null) return;

        try {

            const response = await fetch("../../controllers/ManageUsersController.php?action=add", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    first_name: firstName,
                    last_name: lastName,
                    email: email,
                    password: password,
                    role: role
                })
            });

            const result = await response.json();

            if (result.success) {

                table.row.add({
                    user_id: result.user_id,
                    first_name: firstName,
                    last_name: lastName,
                    email: email.toLowerCase(),
                    role: role
                }).draw(false);

                alert("Account added successfully.");

            } else {
                alert("Failed to add account: " + result.message);
            }

        } catch (error) {
            console.error("Add User Error:", error);
            alert("An error occurred while adding the account.");
        }

    });

    // EDIT BUTTON CLICK
    $('#manageUsersTable tbody').on('click', '.edit-btn', async function () {

        const button = $(this);
        const row = table.row(button.closest('tr'));
        const user = row.data();

        const firstName = prompt("First Name:", user.first_name);
        if (firstName === null) return;

        const lastName = prompt("Last Name:", user.last_name);
        if (lastName === null) return;

        const email = prompt("Email:", user.email);
        if (email === null) return;

        const role = prompt(
            "Role (Student, Staff, Admin):",
            user.role
        );

        if (role === null) return;

        try {

            const response = await fetch("../../controllers/ManageUsersController.php?action=update", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    user_id: user.user_id,
                    first_name: firstName,
                    last_name: lastName,
                    email: email,
                    role: role
                })
            });

            const result = await response.json();

            if (result.success) {

                user.first_name = firstName;
                user.last_name = lastName;
                user.email = email;
                user.role = role;

                row.data(user).draw(false);

                alert("Account updated successfully.");

            } else {
                alert("Failed to update account: " + result.message);
            }

        } catch (error) {
            console.error("Update User Error:", error);
            alert("An error occurred while updating the account.");
        }

    });

   // DELETE BUTTON CLICK
    $('#manageUsersTable tbody').on('click', '.delete-btn', async function () {

        const button = $(this);
        const id = button.data('id');

        const confirmed = confirm(
            "Are you sure you want to delete this account?"
        );

        if (!confirmed) {
            return;
        }

        try {

            const response = await fetch("../../controllers/ManageUsersController.php?action=delete", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    user_id: id
                })
            });

            const result = await response.json();

            if (result.success) {
                table.row(button.closest('tr')).remove().draw();

                alert("Account deleted successfully.");
            } else {
                alert("Failed to delete account: " + result.message);
            }

        } catch (error) {
            console.error("Delete User Error:", error);
            alert("An error occurred while deleting the account.");
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
});