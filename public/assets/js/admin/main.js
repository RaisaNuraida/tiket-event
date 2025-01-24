function toggleSidebar() {
  document.querySelector(".sidebar").classList.toggle("active");
  document.querySelector(".main-content").classList.toggle("sidebar-active");
}

// Hapus input yang kosong sebelum submit
document.getElementById('filterForm').addEventListener('submit', function (event) {
  const inputs = this.querySelectorAll('input, select');
  inputs.forEach(function (input) {
      if (!input.value) {
          input.name = ''; // Hapus atribut name jika kosong
      }
  });
});