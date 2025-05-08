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
            // Load basic blocks gjsForms
            plugins: [basicBlocks, grapesjsTailwind],
        });
        
        editor.BlockManager.add('section', {
            label: 'Section',
            category: 'Custom',
            attributes: { class: 'gjs-block-section' },
            content: '<section class="container mx-auto px-4 py-8"><h2 class="text-2xl font-bold mb-4">Your Heading</h2><p class="text-gray-700">Your content goes here...</p></section>',
        });
        
        editor.BlockManager.add('2-columns', {
            label: '2 Columns',
            category: 'Custom',
            attributes: { class: 'gjs-block-2-columns' },
            content: `
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-gray-100 p-4" data-gjs-type="default" data-gjs-droppable="true">
                    </div>
                    <div class="bg-gray-100 p-4" data-gjs-type="default" data-gjs-droppable="true">
                    </div>
                </div>
            `,
        });

        editor.BlockManager.add('text1', {
            id: 'text-1',
            label: 'Text',
            category: 'Additional',
            content: '<p class="text-gray-700">Insert your text here...</p>',
        });
        
        editor.BlockManager.add('button', {
            id: 'button-1',
            label: 'Button',
            category: 'Additional',
            content: '<a class="inline-block px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700" href="#">Button</a>',
        });
        

        // Add a custom sector
        editor.StyleManager.addSector('tailwind', {
            name: 'Tailwind Styles',
            open: true,
            buildProps: ['text-color', 'bg-color', 'padding', 'button-variant'],
        });

        // Add custom style properties
        editor.StyleManager.addProperty('tailwind', [
            {
                property: 'text-color',
                name: 'Text Color',
                type: 'select',
                defaults: 'text-gray-800',
                options: [
                    { value: 'text-gray-800', name: 'Gray' },
                    { value: 'text-red-500', name: 'Red' },
                    { value: 'text-green-500', name: 'Green' },
                    { value: 'text-blue-500', name: 'Blue' },
                ]
            },
            {
                property: 'bg-color',
                name: 'Background Color',
                type: 'select',
                defaults: 'bg-white',
                options: [
                    { value: 'bg-white', name: 'White' },
                    { value: 'bg-gray-100', name: 'Gray' },
                    { value: 'bg-yellow-200', name: 'Yellow' },
                    { value: 'bg-blue-200', name: 'Blue' },
                ]
            },
            {
                property: 'padding',
                name: 'Padding',
                type: 'select',
                defaults: 'p-4',
                options: [
                    { value: 'p-2', name: 'Small (p-2)' },
                    { value: 'p-4', name: 'Normal (p-4)' },
                    { value: 'p-8', name: 'Large (p-8)' },
                ]
            },
            {
                property: 'button-variant',
                name: 'Button Variant',
                type: 'select',
                defaults: '',
                options: [
                    { value: '', name: 'None' },
                    { value: 'btn-primary', name: 'Primary' },
                    { value: 'btn-secondary', name: 'Secondary' },
                ]
            },
        ]);

        editor.on('component:styleUpdate', (model, prop) => {
            const style = model.getStyle();
        
            const twClasses = [
                style['text-color'],
                style['bg-color'],
                style['padding'],
                style['button-variant']
            ].filter(Boolean); // Remove empty ones
        
            const el = model.getEl();
        
            if (el) {
                // Remove previous TW classes
                el.className = el.className
                    .split(' ')
                    .filter(c => !c.startsWith('text-') && !c.startsWith('bg-') && !c.startsWith('p-') && !c.startsWith('btn-'))
                    .join(' ');
        
                // Add new TW classes
                el.classList.add(...twClasses);
            }
        });

       

        editor.BlockManager.add('my-block', {
            label: 'My Block',
            content: '<div data-custom-type="my-special-block" class="p-4 bg-gray-100">Content</div>',
        });

        editor.BlockManager.add('my-special-button', {
            label: 'Special Button',
            category: 'Buttons',
            content: {
                type: 'my-special-button',
                content: 'Click me!',
                classes: ['btn-primary', 'px-6', 'py-2', 'bg-blue-600', 'text-white', 'rounded'],
                attributes: {
                    href: '#',
                },
            },
        });

        editor.DomComponents.addType('my-special-button', {
            isComponent(el) {
              if (el.tagName === 'BUTTON' && el.classList.contains('btn-primary')) {
                return { type: 'my-special-button' };
              }
            },
            model: {
              defaults: {
                tagName: 'button',
                draggable: true,
                droppable: false,
              },
            }
          });

        /*editor.DomComponents.addType('my-special-block', {
            model: {
              defaults: {
                tagName: 'div',
                classes: ['p-4', 'bg-gray-100'],
                customName: 'Special Block',
              },
            },
        });*/

        editor.on('component:selected', (component) => {
            const type = component.get('type') || component.getAttributes()['data-custom-type'];
            console.log(type);
            if (type === 'link') {
              // Clear previous properties
              editor.StyleManager.getSectors().reset();
          
              // Add custom sector + properties for this block
              editor.StyleManager.addSector('special', {
                name: 'Special Block Styles',
                open: true,
                //buildProps: ['text-color', 'bg-color'],
              });
          
              editor.StyleManager.addProperty('special', [
                {
                  property: 'text-color',
                  name: 'Text Color',
                  type: 'select',
                  defaults: 'text-black',
                  options: [
                    { value: 'text-black', name: 'Black' },
                    { value: 'text-red-500', name: 'Red' },
                    { value: 'text-blue-500', name: 'Blue' },
                  ],
                },
                {
                  property: 'bg-color',
                  name: 'Background',
                  type: 'select',
                  defaults: 'bg-white',
                  options: [
                    { value: 'bg-white', name: 'White' },
                    { value: 'bg-gray-200', name: 'Gray' },
                    { value: 'bg-yellow-300', name: 'Yellow' },
                  ],
                },
                {
                    property: 'button-variant',
                    name: 'Button Variant',
                    type: 'select',
                    defaults: '',
                    options: [
                        { value: '', name: 'None' },
                        { value: 'btn-primary', name: 'Primary' },
                        { value: 'btn-secondary', name: 'Secondary' },
                    ]
                },
              ]);
            } else {
              // Reset style manager for other components
              //editor.StyleManager.getSectors().reset();
            }
          });  

       /* editor.on('component:styleUpdate:text-color', (component, value) => {
            const el = component.getEl();
            if (!el) return;
            const className = value ?? ''; // Simplified: value is expected to be the class string directly
            replaceClassByPrefix(el, 'text-', className);
        });*/

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

function replaceClassByPrefix(el, prefix, newClass) {
    Array.from(el.classList)
        .filter(cls => cls.startsWith(prefix))
        .forEach(cls => el.classList.remove(cls));

    if (newClass) {
        el.classList.add(newClass);
    }
}
