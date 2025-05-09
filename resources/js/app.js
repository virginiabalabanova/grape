import grapesjs from 'grapesjs';
import basicBlocks from 'grapesjs-blocks-basic';
import grapesjsTailwind from 'grapesjs-tailwind';
// Note: gjsForms is not included in this reverted version as per the state before recent extensive changes.

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
                uploadName: 'files', // Name of the file input field in the POST request
                params: { 
                    '_token': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
            },
            plugins: [basicBlocks, grapesjsTailwind], // Reverted to this plugin list
        });
        
        // All custom BlockManager, StyleManager, and event listener code that was added is now removed.

        // Load initial content from data attribute
        const gjsDataContainer = document.getElementById('gjs-container'); 
        if (gjsDataContainer && typeof gjsDataContainer.dataset.content === 'string') { 
            try {
                const parsedJson = JSON.parse(gjsDataContainer.dataset.content);
                if (parsedJson && typeof parsedJson === 'object' && (Object.keys(parsedJson).length > 0 || Array.isArray(parsedJson) && parsedJson.length > 0) ) {
                    editor.loadProjectData(parsedJson);
                    console.log('Loaded initial content into GrapesJS:', parsedJson); 
                } else if (Object.keys(parsedJson).length === 0 && !Array.isArray(parsedJson)) {
                    console.log('Initial content is an empty object, GrapesJS will be empty.');
                } else {
                    console.log('Initial content is empty or not in a loadable format, GrapesJS will be empty.');
                }
            } catch (e) {
                console.error('Failed to parse GrapesJS content from data-content attribute:', e, "Raw data:", gjsDataContainer.dataset.content);
            }
        }

        // Add the save button listener only if the button also exists
        if (saveButton && gjsDataContainer) { 
            const pageId = gjsDataContainer.dataset.pageId;
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            if (!pageId) {
                console.error('Page ID not found. Cannot save content.');
                if(saveButton) saveButton.disabled = true;
            } else if (!csrfToken) {
                console.error('CSRF token not found. Cannot save content.');
                if(saveButton) saveButton.disabled = true;
            } else {
                saveButton.addEventListener('click', () => {
                    const contentJson = editor.getProjectData();
                    saveButton.textContent = 'Saving...';
                    saveButton.disabled = true;

                    fetch(`/api/pages/${pageId}/save-content`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json', 
                        },
                        body: JSON.stringify({
                            content: contentJson, 
                        }),
                    })
                    .then(response => {
                        if (!response.ok) {
                            return response.json().catch(() => {
                                throw new Error(`HTTP error! status: ${response.status}`);
                            }).then(errData => {
                                throw errData; 
                            });
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log('Content saved successfully via API:', data);
                        alert(data.message || 'Content saved!'); 
                    })
                    .catch(error => {
                        console.error('Error saving content via API:', error);
                        let errorMessage = 'Error saving content.';
                        if (error && error.message) {
                            errorMessage += ` ${error.message}`;
                        }
                        if (error && error.errors) { 
                            errorMessage += `\nDetails: ${JSON.stringify(error.errors)}`;
                        }
                        alert(errorMessage); 
                    })
                    .finally(() => {
                        saveButton.textContent = 'Save Content';
                        saveButton.disabled = false;
                    });
                });
            }
        } else {
            console.warn('Save button (#save-content) not found.');
        }
    } else {
         console.warn('GrapesJS container (#gjs) not found.');
    }
});

// Helper functions like replaceClassByPrefix and getClassName are removed as they were part of the additions.
