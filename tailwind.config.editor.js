/** @type {import('tailwindcss').Config} */
const baseConfig = require('./tailwind.config.js');

module.exports = {
  ...baseConfig,
  // Scope all utilities to .acf-block-preview to avoid conflicts with WordPress admin UI
  important: '.acf-block-preview',
  corePlugins: {
    // Critical: disable preflight to prevent CSS resets from breaking WordPress admin
    preflight: false,
  },
}
