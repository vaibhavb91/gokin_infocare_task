// js/script.js

document.addEventListener("DOMContentLoaded", function () {
  fetchUsers();

  const userForm = document.getElementById("userForm");
  userForm.addEventListener("submit", function (e) {
    e.preventDefault();
    const userId = document.getElementById("userId").value;
    if (userId === "") {
      addUser();
    } else {
      updateUser();
    }
  });

  document.getElementById("resetBtn").addEventListener("click", function () {
    document.getElementById("userId").value = "";
    document.getElementById("submitBtn").innerText = "Add User";
  });
});

function fetchUsers() {
  const xhr = new XMLHttpRequest();
  xhr.open("GET", "read.php", true);
  xhr.onload = function () {
    if (this.status === 200) {
      document.getElementById("usersData").innerHTML = this.responseText;
    }
  };
  xhr.send();
}

function addUser() {
  const formData = new FormData(document.getElementById("userForm"));
  const xhr = new XMLHttpRequest();
  xhr.open("POST", "create.php", true);
  xhr.onload = function () {
    if (this.status === 200) {
      console.log("create.php response:", this.responseText.trim());
      if (this.responseText.trim() === "success") {
        alert("User added successfully!");
        document.getElementById("userForm").reset();
        fetchUsers();
      } else {
        alert("Error: " + this.responseText);
      }
    } else {
      alert("Error: Server error. Please try again.");
    }
  };
  xhr.send(formData);
}

function editUser(id) {
  const xhr = new XMLHttpRequest();
  xhr.open("GET", `read.php?id=${id}`, true);
  xhr.onload = function () {
    if (this.status === 200) {
      try {
        const user = JSON.parse(this.responseText);
        if (user.error) {
          alert("Error: " + user.error);
          return;
        }
        document.getElementById("userId").value = user.id;
        document.getElementById("firstName").value = user.first_name;
        document.getElementById("lastName").value = user.last_name;
        document.getElementById("phone").value = user.phone;
        document.getElementById("email").value = user.email;
        document.getElementById("address").value = user.address;
        document.getElementById("submitBtn").innerText = "Update User";
      } catch (e) {
        console.error("Error parsing JSON:", e);
        alert("Error: Failed to fetch user data.");
      }
    }
  };
  xhr.send();
}

function updateUser() {
  const formData = new FormData(document.getElementById("userForm"));
  const xhr = new XMLHttpRequest();
  xhr.open("POST", "update.php", true);
  xhr.onload = function () {
    if (this.status === 200) {
      console.log("update.php response:", this.responseText.trim());
      if (this.responseText.trim() === "success") {
        alert("User updated successfully!");
        document.getElementById("userForm").reset();
        document.getElementById("submitBtn").innerText = "Add User";
        fetchUsers();
      } else {
        alert("Error: " + this.responseText);
      }
    } else {
      alert("Error: Server error. Please try again.");
    }
  };
  xhr.send(formData);
}

function deleteUser(id) {
  const willDelete = confirm(
    "Are you sure you want to delete this user? This action cannot be undone."
  );
  if (willDelete) {
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "delete.php", true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.onload = function () {
      if (this.status === 200) {
        console.log("delete.php response:", this.responseText.trim());
        if (this.responseText.trim() === "success") {
          alert("User deleted successfully!");
          fetchUsers();
        } else {
          alert("Error: " + this.responseText);
        }
      } else {
        alert("Error: Server error. Please try again.");
      }
    };
    xhr.send("id=" + id);
  }
}
