<div class="bg-white border-gray-200 dark:bg-gray-900"
     x-data="themeToggle()" 
     x-init="init()">

  <nav class="bg-white border-gray-200 dark:bg-gray-900">
    <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
      <a href="<?= ROOT ?>" class="flex items-center space-x-3 rtl:space-x-reverse">
        <img src="<?= ROOT ?>/assets/images/scope.png" class="h-12" alt="Scope Logo" />
        <span class="self-center text-2xl font-semibold whitespace-nowrap dark:text-white">Hotbox</span>
      </a>
      <!-- Dark Mode Toggle Button -->
      <button @click="toggleTheme()" 
              type="button" 
              class="text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 rounded-lg text-sm p-2.5 transition-colors duration-200"
              :title="isDark ? 'Switch to light mode' : 'Switch to dark mode'">
          <!-- Sun Icon (Light Mode) -->
          <svg x-show="isDark" 
              x-transition:enter="transition-opacity ease-in-out duration-200"
              x-transition:enter-start="opacity-0"
              x-transition:enter-end="opacity-100"
              x-transition:leave="transition-opacity ease-in-out duration-200"
              x-transition:leave-start="opacity-100"
              x-transition:leave-end="opacity-0"
              class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd"></path>
          </svg>
          <!-- Moon Icon (Dark Mode) -->
          <svg x-show="!isDark" 
              x-transition:enter="transition-opacity ease-in-out duration-200"
              x-transition:enter-start="opacity-0"
              x-transition:enter-end="opacity-100"
              x-transition:leave="transition-opacity ease-in-out duration-200"
              x-transition:leave-start="opacity-100"
              x-transition:leave-end="opacity-0"
              class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
              <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
          </svg>
      </button>
     <button @click="mobileMenuOpen = !mobileMenuOpen" 
            type="button" 
            class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600" 
            :aria-expanded="mobileMenuOpen">
        <span class="sr-only">Open main menu</span>
        <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 17 14">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h15M1 7h15M1 13h15" />
        </svg>
      </button> 
 <div x-show="mobileMenuOpen || window.innerWidth >= 540" 
     x-transition:enter="transition ease-out duration-200"
     x-transition:enter-start="opacity-0 scale-95"
     x-transition:enter-end="opacity-100 scale-100"
     x-transition:leave="transition ease-in duration-150"
     x-transition:leave-start="opacity-100 scale-100"
     x-transition:leave-end="opacity-0 scale-95"
     class="w-full md:block md:w-auto" 
     id="navbar-default">
        <ul class="font-medium flex flex-col p-2 md:p-0 mt-4 border border-gray-100 rounded-lg bg-gray-50 md:flex-row md:space-x-8 rtl:space-x-reverse md:mt-0 md:border-0 md:bg-white dark:bg-gray-800 md:dark:bg-gray-900 dark:border-gray-700">
          <li>
            <a href="<?= ROOT ?>/home" class="<?php $URL = URL(0);
                                              if ($URL === "home")
                                              {
                                                echo "md:dark:text-purple-500 ";
                                              }
                                              else
                                              {
                                                echo "dark:text-white ";
                                              } ?> block py-2 px-3 rounded hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-blue-700 md:p-0 md:dark:hover:text-purple-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent" aria-current="page">Home</a>
          </li>
          <li>
            <a href="<?= ROOT ?>/about" class="<?php $URL = URL(0);
                                                if ($URL === "about")
                                                {
                                                  echo "md:dark:text-purple-500 ";
                                                }
                                                else
                                                {
                                                  echo "dark:text-white ";
                                                } ?> block py-2 px-3 rounded hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-blue-700 md:p-0 md:dark:hover:text-purple-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent">About</a>
          </li>
          <?php if (isset($_SESSION["USER"])): ?>
            <li>
              <a href="<?= ROOT ?>/book" class="<?php $URL = URL(0);
                                                if ($URL === "book")
                                                {
                                                  echo "md:dark:text-purple-500 ";
                                                }
                                                else
                                                {
                                                  echo "dark:text-white ";
                                                } ?> block py-2 px-3 rounded hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-blue-700 md:p-0 md:dark:hover:text-purple-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent">Booking</a>
            </li>
          <?php endif; ?>
          <?php if (isset($_SESSION["USER"]) && $_SESSION["USER"]->is_admin): ?>
            <li>
              <a href="<?= ROOT ?>/admin" class="<?php $URL = URL(0);
                                                  if ($URL === "admin")
                                                  {
                                                    echo "md:dark:text-purple-500 ";
                                                  }
                                                  else
                                                  {
                                                    echo "dark:text-white ";
                                                  } ?> block py-2 px-3 rounded hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-blue-700 md:p-0 md:dark:hover:text-purple-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent">Admin Panel</a>
            </li>
          <?php endif; ?>
              <li>
              <a href="<?= ROOT ?>/analytics" class="<?php $URL = URL(0);
                                                  if ($URL === "analytics")
                                                  {
                                                    echo "md:dark:text-purple-500 ";
                                                  }
                                                  else
                                                  {
                                                    echo "dark:text-white ";
                                                  } ?> block py-2 px-3 rounded hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-blue-700 md:p-0 md:dark:hover:text-purple-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent">Analytics</a>
            </li>

          <li>
            <a href="<?= ROOT ?>/faq" class="<?php $URL = URL(0);
                                              if ($URL === "faq")
                                              {
                                                echo "md:dark:text-purple-500 ";
                                              }
                                              else
                                              {
                                                echo "dark:text-white ";
                                              } ?> block py-2 px-3 rounded hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-blue-700 md:p-0 md:dark:hover:text-purple-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent">FAQ</a>
          </li>

          <?php if(isset($_SESSION['USER']) && $_SESSION['USER']->domain !== 'LDAP'): ?>
            <li>
            <a href="<?= ROOT ?>/resetpassword" class="<?php $URL = URL(0);
                                              if ($URL === "resetpassword")
                                              {
                                                echo "md:dark:text-purple-500 ";
                                              }
                                              else
                                              {
                                                echo "dark:text-white ";
                                              } ?> block py-2 px-3 rounded hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-blue-700 md:p-0 md:dark:hover:text-purple-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent">Reset Password</a>
            </li>
            <?php endif; ?> 
            <?php if (isset($_SESSION["USER"]) && $_SESSION["USER"]->is_admin): ?>
              <li>
              <a href="<?= ROOT ?>/config" class="<?php $URL = URL(0);
                                                if ($URL === "config")
                                                {
                                                  echo "md:dark:text-purple-500 ";
                                                }
                                                else
                                                {
                                                  echo "dark:text-white ";
                                                } ?> block py-2 px-3 rounded hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-blue-700 md:p-0 md:dark:hover:text-purple-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent">System Settings</a>
              </li>
          <?php endif; ?> 
          <?php if (!empty($_SESSION['USER'])) : ?>
            <li>
              <a href="<?= ROOT ?>/logout" class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-blue-700 md:p-0 dark:text-white md:dark:hover:text-purple-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent">Logout</a>
            </li>
          <?php endif ?>
          <?php if (empty($_SESSION['USER'])) : ?>
            <li>
              <a href="<?= ROOT ?>/login" class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-blue-700 md:p-0 dark:text-white md:dark:hover:text-purple-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent">Login</a>
            </li>
          <?php endif ?>
        </ul>
      </div>
    </div>
  </nav>
