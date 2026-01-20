</div> 

    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>

    <script src="../../assets/js/main.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.alert-message');
            alerts.forEach(function(alert) {
                setTimeout(function() {
                    alert.style.display = 'none';
                }, 3000);
            });
        });
    </script>
    <script>
    // មុខងារផ្ញើសារទៅដាស់ Server (Keep Alive)
    function keepSessionAlive() {
        fetch('<?php echo "../../config/session.php"; ?>')
            .then(response => console.log("Session refreshed at: " + new Date().toLocaleTimeString()))
            .catch(err => console.warn("Session refresh failed."));
    }

    // ឱ្យវាដើររៀងរាល់ ៥ នាទីម្ដង (៣០០,០០០ មីលីវិនាទី)
    setInterval(keepSessionAlive, 5 * 60 * 1000);
</script>
</body>
</html>