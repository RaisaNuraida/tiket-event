function toggleSidebar() {
    document.querySelector('.sidebar').classList.toggle('active');
    document.querySelector('.main-content').classList.toggle('sidebar-active');
}

function toggleStatus() {
    const button = document.getElementById("statusButton");
    const statusText = document.getElementById("statusText");

    if (button.classList.contains("active")) {
        button.classList.remove("active");
        button.classList.add("inactive");
        button.textContent = "Inactive";
        statusText.textContent = "Inactive";
    } else {
        button.classList.remove("inactive");
        button.classList.add("active");
        button.textContent = "Active";
        statusText.textContent = "Active";
    }
}

function reviewEvent() {
    alert("Tinjau event ini!");
}
