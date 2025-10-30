/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './**/*.php',
    './*.php',
    './template-parts/**/*.php',
    './blocks/**/*.php',
  ],
  safelist: [
    // Safelist common WordPress image classes to prevent overrides
    'alignnone',
    'alignleft',
    'alignright',
    'aligncenter',
    'size-thumbnail',
    'size-medium',
    'size-large',
    'size-full',
    'wp-image',
    'wp-caption'
  ],
  theme: {
    extend: {
      colors: {
        // Brand colors
        primary: '#AA1010',
        // Add more custom colors here
      },
    },
  },
  corePlugins: {
    // Disable Tailwind's preflight to avoid conflicts with WordPress styles
    preflight: false,
  },
  plugins: [],
}
