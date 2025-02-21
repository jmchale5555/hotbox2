<?php include 'partials/header.view.php' ?>

<h4 class="text-center mb-4 text-4xl font-extrabold leading-none tracking-tight text-gray-900 md:text-5xl lg:text-6xl dark:text-white">Admin panel</h4>
<p class="text-center mb-6 text-lg font-normal text-gray-500 lg:text-xl sm:px-16 xl:px-48 dark:text-gray-400">Welcome <?= $name ?></p>


<div x-data="{ 
        rooms: <?php echo htmlspecialchars($rooms) ?>,
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
            <template x-for="room in rooms" :key="room.id">
                <tbody class="even:bg-gray-50 odd:bg-gray-300 even:dark:bg-slate-700 odd:dark:bg-slate-800 border-gray-200 dark:border-gray-700">

                    <tr class="table-row rounded-lg">
                        <td scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white" x-text="room.id">
                        </td>
                        <td scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white" x-text="room.room_name">
                        </td>
                        <td class="px-6 py-4" x-text="room.created_at">

                        </td>
                        <td class="px-6 py-4">
                            <a href="#" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Edit</a>
                        </td>
                        <td class="px-6 py-4">
                            <a href="#" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Delete</a>
                        </td>
                    </tr>
                </tbody>
            </template>
        </table>

    </div>

</div>


<?php include 'partials/footer.view.php' ?>