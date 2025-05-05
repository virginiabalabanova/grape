import grapesjs from 'grapesjs';
import basicBlocks from 'grapesjs-blocks-basic';
import grapesjsTailwind from 'grapesjs-tailwind';

document.addEventListener('DOMContentLoaded', () => {
    const gjsContainer = document.getElementById('gjs');
    const saveButton = document.getElementById('save-content');

    // Only initialize GrapesJS and add listener if the container exists
    if (gjsContainer) {
        const editor = grapesjs.init({
            container: '#gjs', // Use the element directly or the selector
            height: '600px',
    width: 'auto',
    storageManager: false,

    // Load basic blocks
            plugins: [basicBlocks, grapesjsTailwind],
        });

        // Add the save button listener only if the button also exists
        if (saveButton) {
            saveButton.addEventListener('click', () => {
                const contentJson = editor.getProjectData();
                // Find the Livewire component instance associated with the editor's parent
        // and dispatch an event to it.
        const livewireComponent = Livewire.find(gjsContainer.closest('[wire\\:id]').getAttribute('wire:id'));
        if (livewireComponent) {
            console.log('Sending content to Livewire:', contentJson); // Debug log
            livewireComponent.dispatch('saveGrapesContent', { content: contentJson });
        } else {
            console.error('Livewire component not found for GrapesJS editor.');
        }
    });

            // Optional: Load initial content if available (needs data passed from backend)
            // Example: if (window.pageContent) { editor.loadProjectData(window.pageContent); }
        } else {
            console.warn('Save button (#save-content) not found.');
        }
    } else {
         console.warn('GrapesJS container (#gjs) not found.');
    }
});
