#!/bin/bash

# Make the script executable if it's not already
chmod +x setup-tailwind.sh

# Install dependencies if node_modules doesn't exist
if [ ! -d "node_modules" ]; then
  echo "Installing dependencies..."
  npm install
fi

# Build the CSS file
echo "Building Tailwind CSS..."
npx tailwindcss -i ./src/input.css -o ./style.tailwind.css

echo "Done! Tailwind CSS has been built."
echo "To use Tailwind CSS in development mode with watch, run: npx tailwindcss -i ./src/input.css -o ./style.tailwind.css --watch"
