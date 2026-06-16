/** @type {import('tailwindcss').Config} */
const baseConfig = require('./tailwind.config.js');

module.exports = {
  ...baseConfig,
  // NOTE: previously `important: '.acf-block-preview'` prefixed every generated utility with
  // that wrapper class. But the E365 blocks run ACF `mode: auto` and never render an
  // `.acf-block-preview` wrapper, so all those utilities were inert in the editor canvas — the
  // editor's intended layout overrides never bound, causing editor/front-end divergence
  // (columns collapsing/overlapping in the iframe). style.editor.css is loaded ONLY into the
  // editor iframe (enqueue_block_assets gated by is_admin() in enable365_editor_iframe_styles),
  // so utilities are already scoped to the canvas without a wrapper prefix.
  corePlugins: {
    // Critical: disable preflight to prevent CSS resets from breaking WordPress admin
    preflight: false,
  },
}
