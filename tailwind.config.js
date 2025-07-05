/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    content: ['./app/views/*.view.php', './app/views/partials/*.view.php', './public/index.php', './node_modules/flowbite/**/*.js'],
    theme: {
      extend: {
  
      },
    },
    plugins: [
      require('flowbite/plugin')
    ],
  }
