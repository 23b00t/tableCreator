function generateFields() {
    document.getElementById('submitAttributeNumber').addEventListener('click', function() {
        event.preventDefault(); // Prevent form submission
        const attributeFields = document.getElementById('attributeFields');
        const fieldControll = document.getElementById('fieldControll');

        const numberOfFields = parseInt(document.getElementById('numberOfFields').value, 10);
        if (isNaN(numberOfFields) || numberOfFields <= 0) return;

        fieldControll.innerHTML = '';

        for (let i = 0; i < numberOfFields; i++) {
            // Create a container for each input field
            const formGroup = document.createElement('div');
            formGroup.className = 'form-group';

            // Create a label for the input field
            const label = document.createElement('label');
            label.textContent = `Spalte ${i + 1}`;
            label.setAttribute('for', `attribute${i + 1}`);

            // Create the input field
            const input = document.createElement('input');
            input.type = 'text';
            input.className = 'form-control';
            input.id = `attribute${i + 1}`;
            input.name = `attributes[]`;
            input.placeholder = `Attribute ${i + 1}`;
            input.required = true;

            // Append label and input to the container
            formGroup.appendChild(label);
            formGroup.appendChild(input);

            // Append the container to attributeFields
            attributeFields.appendChild(formGroup);
            console.log(attributeFields);
        }
    });
}

// Call function onload
window.onload = generateFields;
