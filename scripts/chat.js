document.getElementById('sendMessage').addEventListener('click', function() {
    var message = document.getElementById('messageInput').value;
    var userId = document.getElementById('chatBox').getAttribute('data-user-id'); // Получаем user_id из атрибута

    if (message.trim() !== '') {
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'send_message_chat.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function() {
            if (xhr.status === 200) {
                document.getElementById('messageInput').value = '';
                loadMessages();  
            }
        };
        // Отправляем message и user_id
        xhr.send('message=' + encodeURIComponent(message) + '&user_id=' + userId); 
    }
});

function loadMessages() {
    var userId = document.getElementById('chatBox').getAttribute('data-user-id'); // Получаем user_id из атрибута
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'load_messages.php?id=' + userId, true);
    xhr.onload = function() {
        if (xhr.status === 200) {
            document.getElementById('chatBox').innerHTML = xhr.responseText;
        }
    };
    xhr.send();
}

document.getElementById('closeTicket').addEventListener('click', function() {
    var userId = document.getElementById('chatBox').getAttribute('data-user-id'); // Получаем user_id
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'close_ticket.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function() {
        if (xhr.status === 200) {
            alert('Тикет закрыт!');
            window.location.href = 'admin_panel.php';  
        }
    };
    // Отправляем ticket_id
    xhr.send('ticket_id=' + userId); 
});

window.onload = loadMessages;
