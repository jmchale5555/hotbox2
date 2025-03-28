<?php include 'partials/header.view.php' ?>

<h4 class="text-center mb-4 text-4xl font-extrabold leading-none tracking-tight text-gray-900 md:text-5xl lg:text-6xl dark:text-white">Admin panel</h4>
<p class="text-center mb-6 text-lg font-normal text-gray-500 lg:text-xl sm:px-16 xl:px-48 dark:text-gray-400">Welcome <?= $name ?></p>


<div x-data="{ 
  rooms: <?php echo htmlspecialchars($rooms) ?>,
  selectedRoom: null,
  deskCount: null,

  async selectRoom(room) {
        this.selectedRoom = {...room}; // Create a copy of the rooms
        // ${room.id}
    },
    
    async updateRoom(input) {
      //nothing
    },
    }">

    <div class="relative overflow-x-auto shadow-md rounded-lg">
        <table class="table w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400 mb-12 ">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        Room ID
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Room name
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Date added
                    </th>
                    <th scope="col" class="col-span-2 px-6 py-3">
                        Actions
                    </th>
                    <th scope="col" class="col-span-2 px-6 py-3">
                        
                    </th>
                </tr>
            </thead>
            <template x-for="room in rooms.filter((_, index) => index > 0)" :key="room.id">
                <tbody class="even:bg-gray-50 odd:bg-gray-300 even:dark:bg-slate-700 odd:dark:bg-slate-800 border-gray-200 dark:border-gray-700">

                    <tr class="table-row rounded-lg">
                        <td scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white" x-text="room.id">
                        </td>
                        <td scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white" x-text="room.room_name">
                        </td>
                        <td class="px-6 py-4" x-text="room.created_at">

                        </td>
                        <td class="px-6 py-4">
                            <a href="#" @click.prevent="selectRoom(room)" button data-modal-target="crud-modal" data-modal-toggle="crud-modal" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Edit</a>
                        </td>
                        <td class="px-6 py-4">
                            <a href="#" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Delete</a>
                        </td>
                    </tr>
                </tbody>
            </template>
        </table>
  <!-- Modal starts -->
  <div id="crud-modal" 
      x-show="selectedRoom !== null"
      tabindex="-1" 
      aria-hidden="true" 
      class="overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
      
      <!-- Modal content -->
      <div class="relative p-4 w-full max-w-md max-h-full">
          <div class="relative bg-white rounded-lg shadow-sm dark:bg-gray-700">
              <!-- Modal header -->
              <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200">
                  <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                      Edit Room
                  </h3>
                  <button type="button" 
                          @click="selectedRoom = null"
                          class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-toggle="crud-modal">
                      <svg class="w-3 h-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                      </svg>
                      <span class="sr-only">Close</span>
                  </button>
              </div>
              
              <!-- Form only rendered when selectedRoom is not null -->
              <template x-if="selectedRoom">
                  <form class="p-4 md:p-5" @submit.prevent="submitForm()">
                      <div class="grid gap-4 mb-4 grid-cols-2">
                          <div class="col-span-2">
                              <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Name</label>
                              <input type="text" name="name" id="name" 
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" 
                                    placeholder="Room name" required=""
                                    x-model="selectedRoom.room_name">
                          </div>
                           <!-- Other form fields... -->
                              <div class="col-span-2">
                                <label for="deskCount" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Number of Desks</label>
                                <input type="text" name="deskCount" id="deskCount" 
                                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" 
                                   x-model="selectedRoom.desk_total" >
                              </div>
                      </div>
                      <button type="submit" class="text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                          Update Room
                      </button>
                  </form>
              </template>
          </div>
      </div>
  </div>
  <!-- </div> -->
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('adminForm', () => ({
            formData: {
                room_id: "",
                total_desks: "",
            },
            errors: {},
            message: '',
            hasError: false,
            isSubmitting: false,
            showDeleteModal: false,
            roomToDelete: null,

            openDeleteModal(bookingId) {
                this.bookingToDelete = bookingId;
                this.showDeleteModal = true;
            },

            async confirmDelete() {
                try {
                    await this.deleteRoom(this.roomToDelete);
                    this.showDeleteModal = false;
                    this.roomToDelete = null;
                    this.message = 'Room Deleted successfully!';
                    this.hasError = false;

                    // Refresh the bookings data after successful deletion
                    if (this.selectedRoomId) {
                        await this.getBookings(this.selectedRoomId);
                    }
                } catch (error) {
                    console.error('Error:', error);
                    this.message = 'An error occurred while deleting the room.';
                    this.hasError = true;
                }
            },

            async deleteRoom(roomId) {
                const response = await fetch(`admin/deleteRoomById/${roomId}`);
                if (!response.ok) {
                    throw new Error('Failed to delete room');
                }
                const result = await response.json();

                // Use the room_id from formData to fetch updated list of rooms
                if (this.formData.room_id) {
                    this.$dispatch('refresh-room-data', {
                        roomId: this.formData.room_id,
                    });
                }

                // Immediately update local state for UI responsiveness
                if (Array.isArray(this.rooms)) {
                    this.rooms = this.rooms.filter(room => room.id !== roomId);
                }

                // Fetch fresh data using getBookings
                return result;
            },

            async submitForm() {
                this.isSubmitting = true;
                this.errors = {};
                this.message = '';
                if (!this.formData.res_date) {
                    this.hasError = true;
                    this.message = 'Please select a date';
                    this.isSubmitting = false;
                    return;
                }
                try {
                    const response = await fetch('admin/store', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            // Add CSRF token if needed
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                        },
                        body: JSON.stringify(this.formData)
                    });

                    const result = await response.json();

                    if (!response.ok) {
                        if (result.errors) {
                            this.errors = result.errors;
                        }
                        this.hasError = true;
                        this.message = result.message || 'An error occurred';
                        return;
                    }

                    // Use the room_id from formData to fetch updated bookings
                    if (this.formData.room_id) {
                        this.$dispatch('refresh-room-data', {
                            roomId: this.formData.room_id,
                        });
                    }

                    this.hasError = false;
                    this.message = 'Room saved successfully!';


                } catch (error) {
                    console.error('Error:', error);
                    this.hasError = true;
                    this.message = 'An error occurred while saving the booking.';
                } finally {
                    this.isSubmitting = false;
                }
            },

        }));
    });
</script>



<?php include 'partials/footer.view.php' ?>
