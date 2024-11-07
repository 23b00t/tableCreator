// Function to dynamically generate input fields based on the number specified
export function generateFields() {
    // Adding event listener to the "Generate Fields" button
    document.getElementById('submitAttributeNumber').addEventListener('click', function(event) {
        event.preventDefault(); // Prevent the default form submission action

        // Get references to the fields container and field control area
        const attributeFields = document.getElementById('attributeFields');
        const fieldControll = document.getElementById('fieldControll');

        // Parse the number of fields specified in the input
        const numberOfFields = parseInt(document.getElementById('numberOfFields').value, 10);

        // If the number is invalid or less than or equal to 0, stop execution
        if (isNaN(numberOfFields) || numberOfFields <= 0) return;

        // Clear any existing fields in the control area
        fieldControll.innerHTML = '';

        // Loop to create the specified number of fields
        for (let i = 0; i < numberOfFields; i++) {
            // Create a container for each input field
            const formGroup = document.createElement('div');
            formGroup.className = 'form-group';

            // Create a label for each input field
            const label = document.createElement('label');
            label.textContent = `Spalte ${i + 1}`;
            label.setAttribute('for', `attribute${i + 1}`);

            // Create an input field
            const input = document.createElement('input');
            input.type = 'text';
            input.className = 'form-control';
            input.id = `attribute${i + 1}`;
            input.name = `attributes[]`;
            input.placeholder = `Attribute ${i + 1}`;
            input.required = true;

            // Append label and input field to the form group container
            formGroup.appendChild(label);
            formGroup.appendChild(input);

            // Append the form group to the attribute fields container
            attributeFields.appendChild(formGroup);
        }
    });
}
