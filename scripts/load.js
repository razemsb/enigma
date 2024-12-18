window.addEventListener("load", function() {
    document.querySelector(".container").classList.add("loaded");
    document.querySelectorAll(".container").forEach(function(container) {
        container.classList.add("loaded");
    });
});
if (localStorage.getItem('theme') === 'dark') {
    document.body.classList.add('dark-theme');
    document.getElementById('theme-toggle').textContent = '🌞'; 
}

document.getElementById('theme-toggle').addEventListener('click', function() {
    document.body.classList.toggle('dark-theme');
    
    if (document.body.classList.contains('dark-theme')) {
        localStorage.setItem('theme', 'dark');
        document.getElementById('theme-toggle').textContent = '🌞';
    } else {
        localStorage.setItem('theme', 'light');
        document.getElementById('theme-toggle').textContent = '🌙';
    }
});
