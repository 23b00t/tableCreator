// main.js

// Import Bootrap JS
import 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js';
// Import custom functions
import { showMessages } from './alert.js';
import { generateFields } from './generateFields.js';

// Use the function only in the view it is needed
window.onload = function() {
    if (document.getElementById('submitAttributeNumber')) {
        generateFields();
    }
};

showMessages();
