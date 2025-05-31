<?php include 'partials/header.view.php' ?>

<section class="bg-gray-50 dark:bg-gray-900">
    <div class="flex flex-col items-center justify-center px-6 py-8 mt-8 mx-auto lg:py-0">
        <div class="w-full bg-white rounded-lg shadow dark:border md:mt-0 sm:max-w-md xl:p-0 dark:bg-gray-800 dark:border-gray-700">
            <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
                <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl dark:text-white">
                    Reset Password
                </h1>
                <div x-data="resetPasswordForm">
                    <form class="space-y-4 md:space-y-6" @submit.prevent="submitForm">
                        <template x-if="errors.length > 0">
                            <div class="bg-red-300 px-3 py-3">
                                <template x-for="error in errors" :key="error">
                                    <div x-text="error"></div>
                                </template>
                            </div>
                        </template>
                        
                        <template x-if="message" class="col-span-2">
                              <div class="col-span-2" x-init="setTimeout(() => message = '', 3000)"
                                  :class="{'text-green-500': !hasError, 'text-red-500': hasError}"
                                  x-text="message">
                              </div>
                        </template>
                        
                        <div>
                            <label for="current_password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Current Password</label>
                            <input type="password" x-model="formData.current_password" id="current_password" placeholder="••••••••" class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                        </div>

                        <div>
                            <label for="new_password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">New Password</label>
                            <input type="password" x-model="formData.new_password" id="new_password" placeholder="••••••••" class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                        </div>

                        <div>
                            <label for="confirm_password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Confirm New Password</label>
                            <input type="password" x-model="formData.confirm_password" id="confirm_password" placeholder="••••••••" class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                        </div>

                        <button type="submit" :disabled="isSubmitting" class="w-full text-white bg-primary-600 hover:bg-primary-700 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                            <span x-show="!isSubmitting">Reset Password</span>
                            <span x-show="isSubmitting">Processing...</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('resetPasswordForm', () => ({
        formData: {
            current_password: '',
            new_password: '',
            confirm_password: ''
        },
        isSubmitting: false,
        errors: [],
        message: '',
        hasError: false,

        async submitForm() {
            this.isSubmitting = true;
            this.errors = [];
            this.message = '';
            this.hasError = false;
            
            try {
                const response = await fetch('resetpassword/process', {
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
                    this.formData = {
                        current_password: '',
                        new_password: '',
                        confirm_password: ''
                    };
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
                this.message = 'An error occurred while processing your request';
            } finally {
                this.isSubmitting = false;
            }
        }
    }));
});
</script>

<?php include 'partials/footer.view.php' ?>
