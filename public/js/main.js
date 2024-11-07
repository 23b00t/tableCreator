// main.js
import 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js';
import { showMessages } from './alert.js';
import { generateFields } from './generateFields.js';

window.onload = function() {
    if (document.getElementById('submitAttributeNumber')) {
        generateFields();
    }
};

showMessages();
