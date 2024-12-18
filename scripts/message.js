let currentReceiverId = null;

function loadContacts() {
    const xhr = new XMLHttpRequest();
    xhr.open('GET', 'fetch_contacts.php', true); 
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            document.getElementById('contacts-list').innerHTML = xhr.responseText;
        }
    };
    xhr.send();
}

function loadMessages() {
    if (!currentReceiverId) return; 

    const xhr = new XMLHttpRequest();
    xhr.open('GET', 'fetch_message.php?receiver_id=' + currentReceiverId, true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            document.getElementById('chat-box').innerHTML = xhr.responseText;
        }
    };
    xhr.send();
}

function sendMessage() {
    const message = document.getElementById('message').value;
    const receiverId = currentReceiverId; 

    if (message.trim() === '' || receiverId === null) {
        alert('Введите сообщение и выберите получателя.');
        return;
    }

    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'send_message.php', true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            alert(xhr.responseText);
            document.getElementById('message').value = ''; 
            loadMessages(); 
        }
    };
    xhr.send('message=' + encodeURIComponent(message) + '&receiver_admin_id=' + encodeURIComponent(receiverId));
}

function setContact(receiverId, contactName) {
    currentReceiverId = receiverId;
    document.getElementById('chat-contact-name').textContent = contactName;
    loadMessages();
}

window.onload = loadContacts;
setInterval(loadMessages, 5000); 
