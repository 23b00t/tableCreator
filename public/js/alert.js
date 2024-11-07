export function showMessages() {
    const messageElement = document.querySelector('.message');

    const messageText = messageElement.innerText.trim();

    if (messageText !== '') {
        // If a message exists, show it 
        messageElement.style.display = "block";

        // If message includes 'Achtung' it is a warning
        if (messageText.includes('Achtung')) {
            messageElement.classList.add('alert-warning');
            messageElement.classList.remove('alert-success');
        } else {
            messageElement.classList.add('alert-success');
            messageElement.classList.remove('alert-warning');
        }

        // Auto-remove the alert after 10 seconds)
        setTimeout(() => {
            messageElement.style.display = "none";
        }, 10000);
    }

}
