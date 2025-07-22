document.addEventListener('DOMContentLoaded', () => {
    const myForm = document.getElementById('myForm');
    const nameInput = document.getElementById('nameInput');
    const ageInput = document.getElementById('ageInput');
    const messageArea = document.getElementById('messageArea');

    if (!myForm) {
        console.error('Form with ID "myForm" not found!');
        return;
    }

    // Single event listener on the form
    myForm.addEventListener('click', async (event) => {
        // Check if the clicked element is a button
        if (event.target.tagName === 'BUTTON') {
            const button = event.target;
            const action = button.dataset.action; // Using data-action attribute to identify button purpose

            messageArea.style.color = 'green'; // Reset message color
            messageArea.textContent = ''; // Clear previous message

            switch (action) {
                case 'save':
                    messageArea.textContent = 'Saving...';
                    const name = nameInput.value;
                    const age = ageInput.value;

                    try {
                        const response = await fetch('your-php-save-endpoint.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({ name, age })
                        });
                        const data = await response.json();
                        if (response.ok) {
                            messageArea.textContent = Save successful! Response: ${data.message};
                        } else {
                            messageArea.textContent = Save failed: ${data.error || 'Unknown error'};
                            messageArea.style.color = 'red';
                        }
                    } catch (error) {
                        console.error('Fetch error:', error);
                        messageArea.textContent = An error occurred: ${error.message};
                        messageArea.style.color = 'red';
                    }
                    break;

                case 'delete':
                    messageArea.textContent = 'Deleting...';
                    // Example: Send some ID to delete
                    const recordIdToDelete = 123; // Replace with actual ID
                    try {
                        const response = await fetch('your-php-delete-endpoint.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({ id: recordIdToDelete })
                        });
                        const data = await response.json();
                        if (response.ok) {
                            messageArea.textContent = `Deletion successful! Response: ${data.message}`;
                            // Optionally clear inputs or refresh list
                        } else {
                            messageArea.textContent = `Deletion failed: ${data.error || 'Unknown error'}`;
                            messageArea.style.color = 'red';
                        }
                    } catch (error) {
                        console.error('Fetch error:', error);
                        messageArea.textContent = `An error occurred: ${error.message}`;
                        messageArea.style.color = 'red';
                    }
                    break;

                case 'reset':
                    nameInput.value = '';
                    ageInput.value = '';
                    messageArea.textContent = 'Form reset.';
                    break;

                default:
                    console.warn('Unknown button action:', action);
            }
        }
    });

    // Handle form submission itself (if you had a submit button)
    // If you use type="submit" for any button, this listener will catch it.
    myForm.addEventListener('submit', (event) => {
        event.preventDefault(); // Prevent default form submission (page reload)
        console.log('Form submitted (but default prevented)');
        // If you had a primary "Submit" button, you could trigger a fetch here.
    });
});


// et si j'avais plusieur formulaires diférents, je pourrais réuitiliser le même code, exactement, sans le copier/coller ? en utilisant un fonction genre function handleForm(form1) ?

// Oui, tu peux tout à fait réutiliser la même logique pour plusieurs formulaires sans copier-coller.
// Il suffit de créer une fonction générique, par exemple handleForm(formElement), et de l’appeler pour chaque formulaire.

// Voici comment faire :


function handleForm(formElement) {
    const nameInput = formElement.querySelector('.nameInput');
    const ageInput = formElement.querySelector('.ageInput');
    const messageArea = formElement.querySelector('.messageArea');

    formElement.addEventListener('click', async (event) => {
        if (event.target.tagName === 'BUTTON') {
            const button = event.target;
            const action = button.dataset.action;

            // ... (le même switch/case que dans ton code)
        }
    });

    formElement.addEventListener('submit', (event) => {
        event.preventDefault();
        // ... (logique submit si besoin)
    });
}

// Pour chaque formulaire, tu appelles la fonction :
document.querySelectorAll('form').forEach(form => handleForm(form));