<?php include 'partials/header.view.php' ?>

<section class="bg-gray-50 dark:bg-gray-900 min-h-screen">
    <div class="container mx-auto px-6 py-8">
        <div class="max-w-4xl mx-auto">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-8">System Settings</h1>
            
            <div class="bg-white rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
                <div class="p-6">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-6">LDAP Authentication Settings</h2>
                    
                    <div x-data="settingsForm" x-init="loadSettings()">
                        <!-- Success/Error Messages -->
                        <template x-if="errors.length > 0">
                            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                                <template x-for="error in errors" :key="error">
                                    <div x-text="error"></div>
                                </template>
                            </div>
                        </template>
                        
                        <template x-if="message">
                            <div class="mb-4" x-init="setTimeout(() => message = '', 5000)"
                                 :class="{'bg-green-100 border border-green-400 text-green-700': !hasError, 'bg-red-100 border border-red-400 text-red-700': hasError}"
                                 class="px-4 py-3 rounded">
                                <span x-text="message"></span>
                            </div>
                        </template>

                        <form @submit.prevent="submitForm" class="space-y-6">
                            <!-- LDAP Enabled Toggle -->
                            <div class="flex items-center">
                                <input type="checkbox" 
                                       x-model="formData.ldap_enabled" 
                                       id="ldap_enabled" 
                                       class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="ldap_enabled" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">
                                    Enable LDAP Authentication
                                </label>
                            </div>

                            <!-- LDAP Settings Container -->
                            <div x-show="formData.ldap_enabled" x-transition class="space-y-4 border-t pt-6">
                                
                                <!-- LDAP Host -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="ldap_host" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                            LDAP Host
                                        </label>
                                        <input type="text" 
                                               x-model="formData.ldap_host" 
                                               id="ldap_host" 
                                               class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                               placeholder="AD-DC-SL02.ad.contoso.co.uk"
                                               required>
                                    </div>

                                    <!-- LDAP Port -->
                                    <div>
                                        <label for="ldap_port" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                            LDAP Port
                                        </label>
                                        <input type="number" 
                                               x-model="formData.ldap_port" 
                                               id="ldap_port" 
                                               class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                               placeholder="389"
                                               min="1"
                                               max="65535"
                                               required>
                                    </div>
                                </div>

                                <!-- Base DN -->
                                <div>
                                    <label for="ldap_base_dn" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                        Base DN
                                    </label>
                                    <input type="text" 
                                           x-model="formData.ldap_base_dn" 
                                           id="ldap_base_dn" 
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                           placeholder="DC=ad,DC=contoso,DC=co,DC=uk"
                                           required>
                                </div>

                                <!-- User DN -->
                                <div>
                                    <label for="ldap_user_dn" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                        User DN
                                    </label>
                                    <input type="text" 
                                           x-model="formData.ldap_user_dn" 
                                           id="ldap_user_dn" 
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                           placeholder="OU=FIN,OU=PS,OU=Accounts,DC=ad,DC=contoso,DC=co,DC=uk"
                                           required>
                                </div>

                                <!-- Protocol and Security Options -->
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div>
                                        <label for="ldap_protocol" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                            Protocol
                                        </label>
                                        <select x-model="formData.ldap_protocol" 
                                                id="ldap_protocol" 
                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                            <option value="ldap://">LDAP</option>
                                            <option value="ldaps://">LDAPS</option>
                                        </select>
                                    </div>

                                    <div class="flex items-center">
                                        <input type="checkbox" 
                                               x-model="formData.ldap_use_ssl" 
                                               id="ldap_use_ssl" 
                                               class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                        <label for="ldap_use_ssl" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">
                                            Use SSL
                                        </label>
                                    </div>

                                    <div class="flex items-center">
                                        <input type="checkbox" 
                                               x-model="formData.ldap_use_tls" 
                                               id="ldap_use_tls" 
                                               class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                        <label for="ldap_use_tls" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">
                                            Use TLS
                                        </label>
                                    </div>
                                </div>

                                <!-- Advanced Options -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="ldap_version" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                            LDAP Version
                                        </label>
                                        <select x-model="formData.ldap_version" 
                                                id="ldap_version" 
                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                            <option value="2">Version 2</option>
                                            <option value="3">Version 3</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label for="ldap_timeout" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                            Timeout (seconds)
                                        </label>
                                        <input type="number" 
                                               x-model="formData.ldap_timeout" 
                                               id="ldap_timeout" 
                                               class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                               min="1"
                                               max="60">
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex flex-col sm:flex-row gap-3 pt-6 border-t">
                                <button type="submit" 
                                        :disabled="isSubmitting" 
                                        class="flex-1 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 disabled:opacity-50">
                                    <span x-show="!isSubmitting">Save Settings</span>
                                    <span x-show="isSubmitting">Saving...</span>
                                </button>
                                
                                <button type="button" 
                                        @click="testConnection" 
                                        :disabled="isTestingConnection || !formData.ldap_enabled" 
                                        class="flex-1 sm:flex-none text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700 disabled:opacity-50">
                                    <span x-show="!isTestingConnection">Test Connection</span>
                                    <span x-show="isTestingConnection">Testing...</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('settingsForm', () => ({
        formData: {
            ldap_enabled: false,
            ldap_host: '',
            ldap_port: 389,
            ldap_base_dn: '',
            ldap_user_dn: '',
            ldap_use_ssl: false,
            ldap_use_tls: false,
            ldap_protocol: 'ldap://',
            ldap_version: 3,
            ldap_timeout: 5
        },
        isSubmitting: false,
        isTestingConnection: false,
        errors: [],
        message: '',
        hasError: false,

        loadSettings() {
            // Load current settings from PHP data
            <?php if(isset($data['ldap_settings'])): ?>
            this.formData = <?php echo json_encode($data['ldap_settings']); ?>;
            <?php endif; ?>
        },

        async submitForm() {
            this.isSubmitting = true;
            this.errors = [];
            this.message = '';
            this.hasError = false;
            
            try {
                const response = await fetch('config/update', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(this.formData)
                });
                
                const result = await response.json();
                
                if (result.success) {
                    this.message = result.message;
                    this.hasError = false;
                } else {
                    this.hasError = true;
                    if (result.errors) {
                        this.errors = Array.isArray(result.errors) ? result.errors : [result.errors];
                    }
                    this.message = result.message || 'An error occurred';
                }
            } catch (error) {
                console.error('Error:', error);
                this.hasError = true;
                this.message = 'An error occurred while saving settings';
            } finally {
                this.isSubmitting = false;
            }
        },

        async testConnection() {
            this.isTestingConnection = true;
            this.errors = [];
            this.message = '';
            this.hasError = false;
            
            try {
                const response = await fetch('config/test', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    }
                });
                
                const result = await response.json();
                
                if (result.success) {
                    this.message = result.message;
                    this.hasError = false;
                } else {
                    this.hasError = true;
                    this.message = result.message || 'Connection test failed';
                }
            } catch (error) {
                console.error('Error:', error);
                this.hasError = true;
                this.message = 'An error occurred while testing the connection';
            } finally {
                this.isTestingConnection = false;
            }
        }
    }));
});
</script>

<?php include 'partials/footer.view.php' ?>