// build-tailwind.js
const postcss = require('postcss');
const tailwindcssPostcss = require('@tailwindcss/postcss');
const autoprefixer = require('autoprefixer');
const fs = require('fs');
const path = require('path');

// Read the input CSS file
const inputCss = fs.readFileSync(path.join(__dirname, 'src/input.css'), 'utf8');

// Process it with PostCSS and plugins
postcss([
  tailwindcssPostcss({
    config: path.join(__dirname, 'tailwind.config.js')
  }),
  autoprefixer,
])
  .process(inputCss, {
    from: path.join(__dirname, 'src/input.css'),
    to: path.join(__dirname, 'style.tailwind.css'),
  })
  .then(result => {
    // Write the result to the output file
    fs.writeFileSync(path.join(__dirname, 'style.tailwind.css'), result.css);
    console.log('Tailwind CSS built successfully!');
  })
  .catch(error => {
    console.error('Error building Tailwind CSS:', error);
  });
