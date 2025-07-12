  function toggleTheme() {
    const body = document.body;
    const toggleBtn = document.getElementById('themeToggle');
    body.classList.toggle('dark');
    toggleBtn.textContent = body.classList.contains('dark') ? '☀️' : '🌙';
    }


    function showSection(id) {
      document.querySelectorAll('.section').forEach(sec => sec.style.display = 'none');
      document.getElementById(id).style.display = 'block';
    }

    function toggleTheme() {
      document.body.classList.toggle('dark');
    }

    // Hide all sections initially
    window.onload = () => {
      document.querySelectorAll('.section').forEach(sec => sec.style.display = 'none');
    };