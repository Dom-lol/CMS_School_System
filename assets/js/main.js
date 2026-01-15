// មុខងារ Toggle Sidebar សម្រាប់ Mobile
function toggleSidebar() {
  const sidebar = document.getElementById("sidebar");
  sidebar.classList.toggle("-translate-x-full");
}

// មុខងារបិទ Alert ស្វ័យប្រវត្តិ
setTimeout(() => {
  const alerts = document.querySelectorAll(".alert-box");
  alerts.forEach((alert) => (alert.style.display = "none"));
}, 3000);
