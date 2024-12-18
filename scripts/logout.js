window.addEventListener('beforeunload', function() {
    navigator.sendBeacon('../auth/logout.php');
});
