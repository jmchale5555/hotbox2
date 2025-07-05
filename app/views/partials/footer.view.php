<script defer src='./dist/app.js'></script>
<script>
    function themeToggle() {
        return {
            isDark: false,
            mobileMenuOpen: false,
            
            init() {
                this.isDark = document.documentElement.classList.contains('dark');
            },
            
            toggleTheme() {
              this.isDark = !this.isDark;
              console.log('New isDark value:', this.isDark); 
                if (this.isDark) {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('theme', 'dark');
                } else {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('theme', 'light');
                }
                
                // Dispatch custom event for other components that might need it
                this.$dispatch('theme-changed', { isDark: this.isDark });
            }
        }
    }
</script>
<!-- <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script> -->
</div>
</body>
