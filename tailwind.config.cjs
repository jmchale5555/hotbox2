/** @type {import('tailwindcss').Config} */
module.exports = {
    darkMode: 'media',
    content: ['/var/www/sbox2/app/views/*.view.php', '/var/www/sbox2/app/views/partials/*.view.php', '/var/www/sbox2/public/index.php', '/var/www/sbox2/node_modules/flowbite/**/*.js'],
    theme: {
      extend: {
  
      },
    },
    plugins: [
      require('flowbite/plugin')
    ],
  }