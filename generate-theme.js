// generate-theme.js
const fs = require('fs/promises');
const postcss = require('postcss');
const tailwindcss = require('tailwindcss');
const fetch = require('node-fetch'); // Or your preferred HTTP client
const autoprefixer = require('autoprefixer');
const tailwindcssPlugin = require('@tailwindcss/postcss');

// The path to your standard tailwind.config.js
const TAILWIND_CONFIG_PATH = './tailwind.config.js';

// The path where the final CSS will be saved.
// This should be in the `public` directory so Vite serves it.
const CSS_OUTPUT_PATH = './public/dynamic-theme.css';

// --- Main function to run the process ---
async function generateTheme() {
  console.log('Fetching latest theme from CMS...');

  // 1. FETCH STYLES FROM CMS
  // In a real app, this would be an API call. We'll mock it here.
  const cmsStyles = await getStylesFromCms();
  /*
    Mocked Data Structure:
    {
      "btn-primary": "bg-blue-600 text-white font-bold hover:bg-blue-700 rounded-lg shadow-md",
      "btn-secondary": "bg-gray-200 text-gray-800 hover:bg-gray-300 rounded-lg",
      "card": "bg-white p-6 rounded-xl shadow-lg",
      "page-title": "text-4xl font-extrabold text-gray-900"
    }
  */

  // 2. GENERATE SOURCE CSS WITH @apply
  const sourceCss = Object.entries(cmsStyles)
    .map(([className, applyRules]) => `
      .${className} {
        @apply ${applyRules};
      }
    `)
    .join('\n');

  console.log('Generated source CSS with @apply rules...');
  // console.log(sourceCss); // For debugging

  try {
    // 3. COMPILE WITH POSTCSS AND TAILWIND
    console.log(`Processing with Tailwind config: ${TAILWIND_CONFIG_PATH}`);
    
    // Important: We pass our source CSS directly to PostCSS.
    // The `content` path in tailwind.config.js is less critical for this script,
    // as we aren't scanning files for classes, but it's good practice to have it configured.
    const result = await postcss([
        tailwindcssPlugin({ config: TAILWIND_CONFIG_PATH }),
        autoprefixer
      ])
      .process(sourceCss, { from: undefined }); // 'from: undefined' is important here

    // 4. SAVE THE FINAL CSS FILE
    await fs.writeFile(CSS_OUTPUT_PATH, result.css);
    console.log(`✅ Successfully generated theme at: ${CSS_OUTPUT_PATH}`);

  } catch (error) {
    console.error('❌ Error generating theme:', error);
  }
}

// --- Helper to simulate fetching from a CMS ---
async function getStylesFromCms() {
  // In a real app, you would use `fetch` to call your CMS API.
  // const response = await fetch('https://my-cms.com/api/styles');
  // const data = await response.json();
  // return data;

  // For this example, we return a hardcoded object.
  // Pretend this data just changed!
  return {
    "btn-primary": "bg-purple-700 text-white font-bold hover:bg-purple-800 rounded-full shadow-xl transition-all",
    "btn-secondary": "bg-yellow-300 text-black hover:bg-yellow-400 rounded-full",
    "card": "bg-gray-50 p-8 rounded-lg border border-gray-200",
    "page-title": "text-5xl font-thin text-purple-900 tracking-wider"
  };
}

// --- Run the script ---
generateTheme();
