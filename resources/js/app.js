import grapesjs from 'grapesjs';
import basicBlocks from 'grapesjs-blocks-basic';
import grapesjsTailwind from 'grapesjs-tailwind';
// Note: gjsForms is not included in this reverted version as per the state before recent extensive changes.

document.addEventListener('DOMContentLoaded', () => {
    const gjsContainer = document.getElementById('gjs');
    const saveButton = document.getElementById('save-content');

    // Only initialize GrapesJS and add listener if the container exists
    if (gjsContainer) {
        const editor = initializeGrapesJS(gjsContainer, {
            upload: '/api/assets/upload', // Endpoint for uploads
            uploadName: 'files', // Name of the file input field in the POST request
            params: { 
                '_token': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
        });

        addCustomBlocks(editor);
        addCustomStyleManagerProperties(editor);
        setupComponentUpdateListeners(editor);
        loadInitialContent(editor);
        setupSaveButton(editor, saveButton);
    } else {
         console.warn('GrapesJS container (#gjs) not found.');
    }
});

function initializeGrapesJS(container, assetManager) {
    const editor = grapesjs.init({
        container: container, // Use the element directly or the selector
        height: '100vh',
        width: 'auto',
        storageManager: false,
        assetManager: assetManager,
        canvas: {
            styles: [
              'https://grape.test:5176/resources/css/app.css',
            ]
        },
        // Load basic blocks , gjsForms, grapesjsTailwind
        plugins: [basicBlocks],
    });

    editor.on('project:get', ({ project }) => {
        const css = editor.Css;
        css.clear();
    });
    
    /* editor.on('component:update', (component) => {
        if(component.getClasses().includes('gjs-selected')) {
            console.log('component0');
            console.log(component);
            component.removeClass('gjs-selected');
        }
    });*/

    editor.on('component:selected', (component) => {
        console.log('component');
        console.log(component);
        component.removeClass('gjs-selected');
      });
      
      editor.on('component:toggled', () => {
        const selectedComponents = editor.getSelectedAll();
        selectedComponents.forEach(component => {
          console.log('component2');
          console.log(component);
          component.removeClass('gjs-selected');
        });
      });

    return editor;
}

function addCustomBlocks(editor) {
    editor.BlockManager.add('custom-button', {
        label: 'Custom Button',
        category: 'Custom',
        content: '<a id="btn" href="#">Click me</a>',
    });
}

function addCustomStyleManagerProperties(editor) {
    const TEXT_COLOR_PROP = 'tailwind-text-class';
    const BG_COLOR_PROP = 'tailwind-bg-class';
    const ROUNDED_PROP ='tailwind-rounded-class';

    editor.StyleManager.addType('tailwind-class', {
        create({ props }) {
        const wrapper = document.createElement('div');
        const select = document.createElement('select');
    
        select.style.width = '100%';
        select.style.padding = '4px';
        select.style.marginTop = '4px';
    
        // Add options
        (props.options || []).forEach(opt => {
            const option = document.createElement('option');
            option.text = opt.name;
            option.value = opt.id;
            select.appendChild(option);
        });
    
        // Set initial value
        if (props.model?.getValue) {
            select.value = props.model.getValue() || '';
        }
    
        // Change listener
        select.addEventListener('change', () => {
            const value = select.value;
            console.log('change');
            console.log(value);
            if (props.model?.setValue) {
            props.model.setValue(value);
            }
    
            const prefixMap = {
                'text-': 'tailwind-text-class',
                'bg-': 'tailwind-bg-class',
                'rounded-': 'tailwind-rounded-class', // example
                'btn-': 'button-type',
              };
              
              const component = editor.getSelected();
              if (component && typeof value === 'string') {
                const matchedPrefix = Object.keys(prefixMap).find(prefix => value.startsWith(prefix));
                if (matchedPrefix) {
                  component.set(prefixMap[matchedPrefix], value);
                }
              }
        });
    
        wrapper.appendChild(select);
    
        // ✅ Return the DOM node directly (NOT an object)
        return wrapper;
        },
    
        apply() {} // ⛔ prevents CSS generation
    });

    editor.on('load', () => {
        const sm = editor.StyleManager;

        const styleManagerPanelId = 'styles'; // Default ID of the Style Manager panel
        editor.Panels.removePanel(styleManagerPanelId);

        sm.addSector('tailwind_utils', {
        name: 'Button Styles',
        open: true,
        }, { at: 0 });

        sm.addProperty('tailwind_utils', {
        id: TEXT_COLOR_PROP,
        name: 'Text Color',
        type: 'tailwind-class',
        changeProp: 1,
        defaults: '',
        options: [
            { id: '', name: 'Default' },
            { id: 'text-black', name: 'Black' },
            { id: 'text-white', name: 'White' },
            { id: 'text-red-500', name: 'Red 500' },
            { id: 'text-green-500', name: 'Green 500' },
            { id: 'text-blue-500', name: 'Blue 500' },
        ],
        });

        sm.addProperty('tailwind_utils', {
            id: BG_COLOR_PROP,
            name: 'Background Color',
            type: 'tailwind-class',
            changeProp: 1,
            defaults: '',
            options: [
                { id: '', name: 'Default' },
                { id: 'bg-black', name: 'Black' },
                { id: 'bg-white', name: 'White' },
                { id: 'bg-red-500', name: 'Red 500' },
                { id: 'bg-green-500', name: 'Green 500' },
                { id: 'bg-blue-500', name: 'Blue 500' },
            ],
        });

        sm.addProperty('tailwind_utils', {
            id: 'button-type',
            name: 'Button Type',
            type: 'tailwind-class',
            changeProp: 1,
            defaults: '',
            options: [
                { id: '', name: 'Default' },
                { id: 'btn-primary', name: 'Primary' },
                { id: 'btn-secondary', name: 'Secondary' },
                { id: 'btn-outline', name: 'Outline' },
            ],
        });
    });
}

function setupComponentUpdateListeners(editor) {
    const TEXT_COLOR_PROP = 'tailwind-text-class';
    const BG_COLOR_PROP = 'tailwind-bg-class';

    editor.on('component:selected', (component) => {
        if (component && component.el) {
        component.el.classList.remove('gjs-selected');
        }
    });

    editor.on(`component:update:${TEXT_COLOR_PROP}`, (component) => {
        const value = component.get(TEXT_COLOR_PROP);
        const el = component.getEl();
        if (!el) return;

        Array.from(el.classList).forEach(cls => {
            if (cls.startsWith('text-')) el.classList.remove(cls);
        });

        if (value && value.startsWith('text-')) {
            el.classList.add(value);
        }

        const current = component.getAttributes().class || '';
        const updated = el.className;

        if (current !== updated) {
            component.setAttributes({ class: updated });
        }
    });

    editor.on(`component:update:${BG_COLOR_PROP}`, (component) => {
        const value = component.get(BG_COLOR_PROP);
        const el = component.getEl();
        if (!el) return;

        Array.from(el.classList).forEach(cls => {
            if (cls.startsWith('bg-')) el.classList.remove(cls);
        });

        if (value && value.startsWith('bg-')) {
            el.classList.add(value);
        }

        const current = component.getAttributes().class || '';
        const updated = el.className;

        if (current !== updated) {
            component.setAttributes({ class: updated });
        }
    });

    editor.on(`component:update:button-type`, (component) => {
        const value = component.get('button-type');
        const el = component.getEl();
        if (!el) return;

        // Remove existing button type classes
        ['btn-primary', 'btn-secondary', 'btn-outline'].forEach(cls => {
            if (el.classList.contains(cls)) el.classList.remove(cls);
        });

        // Add the new button type class
        if (value && value.startsWith('btn-')) {
            el.classList.add(value);
        }

        const current = component.getAttributes().class || '';
        const updated = el.className;

        if (current !== updated) {
            component.setAttributes({ class: updated });
        }
    });
}

function loadInitialContent(editor) {
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
}

function setupSaveButton(editor, saveButton) {
    const gjsDataContainer = document.getElementById('gjs-container'); 

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
}

// Helper functions like replaceClassByPrefix and getClassName are removed as they were part of the additions.
