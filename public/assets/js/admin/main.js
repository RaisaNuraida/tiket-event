function toggleSidebar() {
  document.querySelector(".sidebar").classList.toggle("active");
  document.querySelector(".main-content").classList.toggle("sidebar-active");
}

document
  .getElementById("filterForm")
  .addEventListener("submit", function (event) {
    const inputs = this.querySelectorAll("input, select");
    inputs.forEach(function (input) {
      if (!input.value) {
        input.name = "";  
      }
    });
  });

document.addEventListener("DOMContentLoaded", function () {
  let searchInput = document.getElementById("searchEvent");
  let eventTable = document
    .getElementById("eventTable")
    .getElementsByTagName("tbody")[0];

  searchInput.addEventListener("keyup", function () {
    let filter = searchInput.value.toLowerCase();
    let rows = eventTable.getElementsByTagName("tr");

    for (let i = 0; i < rows.length; i++) {
      let eventName = rows[i].getElementsByTagName("td")[1];
      if (eventName) {
        let textValue = eventName.textContent || eventName.innerText;
        rows[i].style.display =
          textValue.toLowerCase().indexOf(filter) > -1 ? "" : "none";
      }
    }
  });
});
