<?php include 'partials/header.view.php' ?>

<!--h4 class="text-center mb-4 text-xl font-bold leading-none tracking-tight text-gray-900 md:text-5xl lg:text-2xl dark:text-white">Admin panel</h4-->
<p class="text-center mb-6 text-lg font-normal text-gray-500 lg:text-xl sm:px-16 xl:px-48 dark:text-gray-400">Welcome <?= $name ?></p>
<div x-data="adminForm">
    <div 
        tabindex="-1"
        aria-hidden="true"
        x-show="showDeleteModal"
        class="flex fixed inset-0 bg-black bg-opacity-50 items-center justify-center z-50"
        x-cloak>
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg max-w-sm w-full">
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Confirm Room Deletion</h3>
            <p class="text-gray-600 dark:text-gray-400 mb-6">Are you sure you want to delete this room?</p>
            <div class="flex justify-end space-x-3">
                <button @click="showDeleteModal = false"
                    class="px-4 py-2 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg"
                    >
                    Cancel
                </button>
                <button @click="confirmDelete()"
                    
                    class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                    Confirm
                </button>
            </div>
            <template x-if="message" class="col-span-2">
                  <div class="col-span-2" x-init="setTimeout(() => message = '', 3000)"
                      :class="{'text-green-500': !hasError, 'text-red-500': hasError}"
                      x-text="message">
                  </div>
            </template>
        </div>
    </div>

  <div x-data="{ 
    rooms: <?php echo htmlspecialchars($rooms) ?>,

    async getRooms() {
        try {
            const response = await fetch(`admin/getRooms`);
            
            if (!response.ok) {
                console.error('Failed to fetch rooms:', response.status);
                return;
            }
            
            const freshRooms = await response.json();
            console.log('Fresh rooms received:', freshRooms);
            this.rooms = freshRooms || []; // ensure rooms is always an array
        } catch (error) {
            console.error('Error fetching rooms:', error);
        }
    }

          //${room.id}
    //  },

      }">

      <div class="relative overflow-x-auto shadow-md rounded-lg">
          <table class="table w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400 mb-12" @refresh-room-data.window="getRooms()">
              <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                  <tr>

                      <th scope="col" class="px-6 py-3">
                          Room name
                      </th>
                      <th scope="col" class="px-6 py-3">
                          Number of Desks
                      </th>
                      <th scope="col" class="col-span-2 px-6 py-3">
                          Room Plan
                      </th>
                      <th scope="col" class="col-span-2 px-6 py-3">
                          Actions
                      </th>

                      <th scope="col" class="col-span-2 px-6 py-3">
 
                    <button type="button" @click="newRoom(rooms[0])" class="float-right min-w-max text-white !bg-gradient-to-br !from-purple-600 !to-blue-500 !hover:bg-gradient-to-bl focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 font-medium rounded-lg text-sm px-5 py-2 text-center my-3 me-2 mb-2">
                        <span>Add Room</span>
                        
                    </button>                         
                      </th>
                  </tr>
              </thead>
              <template x-for="room in rooms.filter((_, index) => index > 0)" :key="room.id">
                  <tbody class="even:bg-gray-50 odd:bg-gray-300 even:dark:bg-slate-700 odd:dark:bg-slate-800 border-gray-200 dark:border-gray-700" >

                      <tr class="table-row rounded-lg">

                          <td scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white" x-text="room.room_name">
                          </td>
                          <td class="px-6 py-4" x-text="room.desk_total">

                          </td>
                          <td class="px-6 py-4">
                            <img x-bind:src="room.room_image ? room.room_image : '/assets/images/no-image.png'" class="h-10 w-10 object-cover rounded" alt="Room image">
                          </td>
                          <td class="px-6 py-4">
                              <a href="#" @click.prevent="selectRoom(room)" button class="font-medium hover:underline p-2 text-purple-600 dark:text-purple-500">Edit</a>
                          </td>
                          <td class="px-6 py-4">
                              <a href="#" @click.prevent="openDeleteModal(room.id)"  class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Delete</a>
                          </td>
                      </tr>
                  </tbody>
              </template>
          </table>
    <!-- Modal starts -->
    <div  
        x-show="showCrudModal" 
        tabindex="-1"
        aria-hidden="true"
        class="flex bg-black bg-opacity-50 overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <!-- Modal content -->
        <div class="relative p-4 w-full max-w-md max-h-full">
            <div class="relative bg-white rounded-lg shadow-sm dark:bg-gray-700">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white" x-text=" formData.id == null ? 'Add Room' : 'Edit Room'">
                        
                    </h3>
                    <button type="button" 
                          @click="showCrudModal = false"
                          class="focus:outline-none text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white">
                        <svg aria-hidden="true" class="w-3 h-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                        </svg>
                        <span class="sr-only">Close</span>
                    </button>
                </div>
 
            <!-- Modal form with two-way binding -->
            <template x-if="showCrudModal == true">
                <form class="p-4 md:p-5" @submit.prevent="submitForm" data-modal-toggle="crud-modal">
                    <div class="grid gap-4 mb-4 grid-cols-2">
                        <!-- Hidden room id form field -->
                        <div class="col-span-2">
                            <input type="hidden" name="id" id="id" x-model="formData.id">
                        </div>
 
                        <!-- Room name form field -->
                        <div class="col-span-2">
                            <label for="room_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Room Name</label>
                            <input type="text" name="room_name" id="room_name" 
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" 
                                placeholder="Room name" required
                                x-model="formData.room_name">
                            <div x-show="errors.room_name" class="text-red-500 text-sm mt-1" x-text="errors.room_name"></div>
                        </div>

                        <!-- Desk total form field -->
                        <div class="col-span-2">
                            <label for="desk_total" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Number of Desks</label>
                            <input type="number" name="desk_total" id="desk_total" 
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" 
                                placeholder="Number of desks"
                                x-model="formData.desk_total">
                            <div x-show="errors.desk_total" class="text-red-500 text-sm mt-1" x-text="errors.desk_total"></div>
                        </div>
                        <!-- Add room image field -->
                        <div class="col-span-2">
                            <label for="room_image" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Room Image</label>
                            <div class="flex items-center space-x-4">
                                <img x-bind:src="imagePreview || (formData.room_image ? formData.room_image : '/assets/images/no-image.png')" 
                                    class="h-16 w-16 object-cover rounded" 
                                    alt="Room image preview">
                                
                                <div class="flex-1">
                                    <input type="file" id="image_upload" @change="handleImageUpload" 
                                          accept="image/*"
                                          class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400">
                                    <div x-show="errors.room_image" class="text-red-500 text-sm mt-1" x-text="errors.room_image"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Status message (shows success or error -->
                       <template x-if="message" class="col-span-2">
                            <div class="col-span-2" x-init="setTimeout(() => message = '', 3000)"
                                :class="{'text-green-500': !hasError, 'text-red-500': hasError}"
                                x-text="message">
                            </div>
                        </template>
 
                    <button type="submit" :disabled="isSubmitting"
                            button
                            x-init="setTimeout(() => isSubmitting = false, 1)"
                            class="float-right min-w-max text-white !bg-gradient-to-br !from-purple-600 !to-blue-500 !hover:bg-gradient-to-bl focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 font-medium rounded-lg text-sm px-5 py-2 text-center my-3 me-2 mb-2">
                           
                        <span x-show="!isSubmitting" x-text="formData.id == null ? 'Add Room' : 'Update Room'"></span>
                        <span x-show="isSubmitting">Saving...</span>
                    </button>
                </form>
            </template>
          </div>

    </div>
  </div>
</div>
<!-- </div> -->
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('adminForm', () => ({
        formData: {
            id: null,
            room_name: "",
            desk_total: null,
            room_image: null
        },
        errors: {},
        message: '',
        hasError: false,
        isSubmitting: false,
        showDeleteModal: false,
        showCrudModal: false,
        selectedRoom: null,
        roomToDelete: null,
        imageFile: null,  // To store the file object
        imagePreview: null, // For image preview
        
        async newRoom(room) {
            this.showCrudModal = true;
            
            // reset formData from previous selection to blankety blank
            this.formData = {
                id: null,
                room_name: "",
                desk_total: null,
                room_image: null
            };
            this.imageFile = null;
            this.imagePreview = null;
            console.log("Form data initialized:", this.formData);
        },

        // Initialize formData when a room is selected
        async selectRoom(room) {
            this.showCrudModal = true;
            this.selectedRoom = {...room}; // Create a copy of the room
            
            // Initialize formData with the selected room data
            this.formData = {
                id: room.id,
                room_name: room.room_name,
                desk_total: room.desk_total,
                room_image: room.room_image
            };
            
            this.imageFile = null;
            this.imagePreview = null;
            console.log("Form data initialized:", this.formData);
        },
        
        // Handle image upload and preview
        handleImageUpload(event) {
            const file = event.target.files[0];
            if (!file) return;
            
            this.imageFile = file;

            // Create preview
            const reader = new FileReader();
            reader.onload = (e) => {
                this.imagePreview = e.target.result;
            };
            reader.readAsDataURL(file);
        },

        async submitForm() {
            this.isSubmitting = true;
            this.errors = {};
            this.message = '';
            
            console.log("Submitting form data:", this.formData);
            
            try {
                // Create FormData object for file upload
                const formData = new FormData();
                formData.append('id', this.formData.id || '');
                formData.append('room_name', this.formData.room_name);
                formData.append('desk_total', this.formData.desk_total);
                
                // If there's a new image file, append it
                if (this.imageFile) {
                    formData.append('room_image', this.imageFile);
                }
                
                let response;
                if(this.formData.id !== null) {
                    console.log('attempting amendDesks fetch with image');
                    response = await fetch('admin/amendDesks', {
                        method: 'POST',
                        body: formData, // Use FormData instead of JSON
                        // Do not set Content-Type header, browser will set it with boundary for FormData
                    });
                } else {
                    console.log('attempting addRoom fetch with image');
                    response = await fetch('admin/addRoom', {
                        method: 'POST',
                        body: formData // Use FormData instead of JSON
                    });
                }
                
                // Refresh room data
                this.$dispatch('refresh-room-data');
                console.log("response is this:", response);
                const result = await response.json();
                console.log("Server response:", result);
                  
                if (result.success) {
                    this.hasError = false;
                    this.message = result.message || (this.formData.id === null ? 'Room added successfully' : 'Room updated successfully!');
                    
                    // Close the modal after success
                    setTimeout(() => {
                        this.showCrudModal = false;
                    }, 2000); // Close after 2 seconds so the user can see the success message
                } else {
                    this.hasError = true;
                    this.errors = result.errors || {};
                    this.message = result.message || 'An error occurred';
                }
            } catch (error) {
                console.error('Error:', error);
                this.hasError = true;
                this.message = 'An error occurred while updating the room.';
            } finally {
                //this.isSubmitting = false;
            }
        },
        
        // Other methods (openDeleteModal, confirmDelete) remain the same
        openDeleteModal(roomId) {
            this.roomToDelete = roomId;
            this.showDeleteModal = true;
        },

        async confirmDelete() {
            // Existing delete functionality
            if (!this.roomToDelete) return;
            
            try {
                const response = await fetch(`admin/deleteRoomById/${this.roomToDelete}`);
                const result = await response.json();
                
                if (result.success) {
                    this.message = 'Room deleted successfully!';
                    this.hasError = false;

                    // Refresh room data
                    this.$dispatch('refresh-room-data');

                    // Close the modal after success
                    setTimeout(() => {
                        this.showDeleteModal = false;
                        this.roomToDelete = null;
                    }, 2000);
                } else {
                    this.message = result.message || 'An error occurred while deleting the room.';
                    this.hasError = true;
                }
            } catch (error) {
                console.error('Error:', error);
                this.message = 'An error occurred while deleting the room.';
                this.hasError = true;
            }
        }
    }));
});
</script>

<?php include 'partials/footer.view.php' ?>
