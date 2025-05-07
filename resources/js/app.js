import grapesjs from 'grapesjs';
import basicBlocks from 'grapesjs-blocks-basic';
import grapesjsTailwind from 'grapesjs-tailwind';
import gjsForms from 'grapesjs-plugin-forms';

document.addEventListener('DOMContentLoaded', () => {
    const gjsContainer = document.getElementById('gjs');
    const saveButton = document.getElementById('save-content');

    // Only initialize GrapesJS and add listener if the container exists
    if (gjsContainer) {
        const editor = grapesjs.init({
            container: '#gjs', // Use the element directly or the selector
            height: '100vh',
            width: 'auto',
            storageManager: false,
            assetManager: {
                upload: '/api/assets/upload', // Endpoint for uploads
                uploadName: 'files', // Name of the file input field in the POST request (GrapesJS default is 'files[]', Laravel expects 'files' for array or 'files.0' etc.)
                                     // Our route expects 'files' as an array, so GrapesJS default 'files[]' should map to 'files' in PHP.
                                     // If GrapesJS sends 'files[]', PHP will see it as `$_FILES['files']` which is an array of files.
                                     // If it sends 'file-0', 'file-1', then PHP sees `$_FILES['file-0']`.
                                     // The default 'files[]' from GrapesJS should work with Laravel's $request->file('files') which returns an array.
                params: { // Additional parameters to send with the upload request
                    '_token': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
                // Optional: Define how to handle the response from the server
                // By default, GrapesJS expects a JSON response like: { data: [{ src: 'url1' }, { src: 'url2' }] } or { data: ['url1', 'url2'] }
                // Our Laravel route returns { data: ['url1', 'url2'] } which should be compatible.
            },
            // Load basic blocks
            plugins: [basicBlocks, gjsForms, grapesjsTailwind],
        });
        
        editor.BlockManager.add('text', {
            label: 'Text T',
            category: 'Basic',
            content: '<p class="text-gray-700">Insert your text here...</p>',
        });
        
        editor.BlockManager.add('button', {
            label: 'Button T',
            category: 'Basic',
            content: '<a class="inline-block px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700" href="#">Button</a>',
        });
        
        

        // Load initial content from data attribute
        const gjsDataContainer = document.getElementById('gjs-container'); // Get the container with the data attribute
        if (gjsDataContainer && typeof gjsDataContainer.dataset.content === 'string') { // Ensure content is a string
            try {
                const parsedJson = JSON.parse(gjsDataContainer.dataset.content);
                // GrapesJS loadProjectData expects an object.
                // Check if parsedJson is an object and not an empty array (which we might default to in PHP)
                // and ensure it's not null (JSON.parse('null') is valid but not useful here).
                if (parsedJson && typeof parsedJson === 'object' && (Object.keys(parsedJson).length > 0 || Array.isArray(parsedJson) && parsedJson.length > 0) ) {
                    editor.loadProjectData(parsedJson);
                    console.log('Loaded initial content into GrapesJS:', parsedJson); // Debug log
                } else if (Object.keys(parsedJson).length === 0 && !Array.isArray(parsedJson)) {
                    // It's an empty object {}, which is fine, GrapesJS will just be empty.
                    console.log('Initial content is an empty object, GrapesJS will be empty.');
                } else {
                    console.log('Initial content is empty or not in a loadable format, GrapesJS will be empty.');
                }
            } catch (e) {
                console.error('Failed to parse GrapesJS content from data-content attribute:', e, "Raw data:", gjsDataContainer.dataset.content);
            }
        }

        // Add the save button listener only if the button also exists
        if (saveButton && gjsDataContainer) { // Ensure gjsDataContainer is available for pageId
            const pageId = gjsDataContainer.dataset.pageId;
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            if (!pageId) {
                console.error('Page ID not found. Cannot save content.');
                // Optionally disable the save button or show an error to the user
                if(saveButton) saveButton.disabled = true;
            } else if (!csrfToken) {
                console.error('CSRF token not found. Cannot save content.');
                if(saveButton) saveButton.disabled = true;
            } else {
                saveButton.addEventListener('click', () => {
                    const contentJson = editor.getProjectData();
                    // Show some loading indicator on the button
                    saveButton.textContent = 'Saving...';
                    saveButton.disabled = true;

                    fetch(`/api/pages/${pageId}/save-content`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json', // Expect a JSON response
                        },
                        body: JSON.stringify({
                            content: contentJson, // Ensure this matches the validation key in the route
                        }),
                    })
                    .then(response => {
                        if (!response.ok) {
                            // Try to parse error response if it's JSON, otherwise throw generic error
                            return response.json().catch(() => {
                                throw new Error(`HTTP error! status: ${response.status}`);
                            }).then(errData => {
                                throw errData; // Throw parsed error data
                            });
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log('Content saved successfully via API:', data);
                        // Optionally, display a success message to the user (e.g., using a toast notification)
                        // Or use Livewire to flash a session message if preferred, though this is now outside Livewire's direct save.
                        // For simplicity, just log for now.
                        alert(data.message || 'Content saved!'); // Simple alert
                    })
                    .catch(error => {
                        console.error('Error saving content via API:', error);
                        let errorMessage = 'Error saving content.';
                        if (error && error.message) {
                            errorMessage += ` ${error.message}`;
                        }
                        if (error && error.errors) { // Handle Laravel validation errors
                            errorMessage += `\nDetails: ${JSON.stringify(error.errors)}`;
                        }
                        alert(errorMessage); // Simple alert for errors
                    })
                    .finally(() => {
                        // Reset button state
                        saveButton.textContent = 'Save Content';
                        saveButton.disabled = false;
                    });
                });
            }
            // Optional: Load initial content if available (needs data passed from backend)
            // Example: if (window.pageContent) { editor.loadProjectData(window.pageContent); }
        } else {
            console.warn('Save button (#save-content) not found.');
        }
    } else {
         console.warn('GrapesJS container (#gjs) not found.');
    }
});
