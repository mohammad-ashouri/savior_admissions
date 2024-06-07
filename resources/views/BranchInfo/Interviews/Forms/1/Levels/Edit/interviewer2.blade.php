<div class="container mx-auto mt-8">
    <div class="flex justify-center">
        <div class="border border-gray-700 rounded-lg w-full ">
            <div class="p-4">
                <div class="mb-8" id="tab4">
                    <p class="font-bold mb-4">
                        Fourth step: Educational Upbringing (family)
                    </p>
                    <div class="overflow-x-auto">
                        <table id="table4" class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead
                                class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="p-4 text-center">
                                    Series
                                </th>
                                <th scope="col" class="p-4 text-center">
                                    Evaluation Criteria
                                </th>
                                <th scope="col" class="p-4 text-center">
                                    Coefficient
                                </th>
                                <th scope="col" class="p-4 text-center">
                                    Rating (0 - 5)
                                </th>
                                <th scope="col" class="p-4 text-center">
                                    Rating Sum with Coefficient
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td class="w-4 p-4 text-center">
                                    1
                                </td>
                                <td class="w-4 p-4 text-center">
                                    Attendance in parent-teacher meetings
                                </td>
                                <td class="w-2 p-4 text-center">
                                    <p id="s4_1_c">
                                        2
                                    </p>
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <input type="number" id="s4_1" name="s4_1" value="{{$interviewFields['s4_1']}}"
                                           min="0" max="5" required
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    >
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <label for="s4_1_rs"
                                           class="block mb-2  font-bold text-gray-900 dark:text-white">{{$interviewFields['s4_1']*2}}
                                    </label>
                                </td>
                            </tr>
                            <tr
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td class="w-4 p-4 text-center">
                                    2
                                </td>
                                <td class="w-4 p-4 text-center">
                                    How to interact and cooperate with the school (teachers, staff and students)
                                <td class="w-2 p-4 text-center">
                                    <p id="s4_2_c">
                                        2
                                    </p>
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <input type="number" id="s4_2" name="s4_2" value="{{$interviewFields['s4_2']}}"
                                           min="0" max="5" required
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    >
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <label for="s4_2_rs"
                                           class="block mb-2  font-bold text-gray-900 dark:text-white">{{$interviewFields['s4_2']*2}}
                                    </label>
                                </td>
                            </tr>
                            <tr
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td class="w-4 p-4 text-center">
                                    3
                                </td>
                                <td class="w-4 p-4 text-center">
                                    Timely notification of emergencies (travel, illness, early exams, etc.)
                                <td class="w-2 p-4 text-center">
                                    <p id="s4_3_c">
                                        1
                                    </p>
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <input type="number" id="s4_3" name="s4_3" value="{{$interviewFields['s4_3']}}"
                                           min="0" max="5" required
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    >
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <label for="s4_3_rs"
                                           class="block mb-2  font-bold text-gray-900 dark:text-white">{{$interviewFields['s4_3']*1}}
                                    </label>
                                </td>
                            </tr>
                            <tr
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td class="w-4 p-4 text-center">
                                    4
                                </td>
                                <td class="w-4 p-4 text-center">
                                    Subscribe to school and educational communication channels – class to follow the
                                    news and information
                                <td class="w-2 p-4 text-center">
                                    <p id="s4_4_c">
                                        1
                                    </p>
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <input type="number" id="s4_4" name="s4_4" value="{{$interviewFields['s4_4']}}"
                                           min="0" max="5" required
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    >
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <label for="s4_4_rs"
                                           class="block mb-2  font-bold text-gray-900 dark:text-white">{{$interviewFields['s4_4']*1}}
                                    </label>
                                </td>
                            </tr>
                            <tr
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 ">
                                <td class="w-4 p-4 text-right" colspan="4">
                                    Total
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <label id="s4_total"
                                           class="block mb-2  font-bold text-gray-900 dark:text-white">{{($interviewFields['s4_1']*2)+($interviewFields['s4_2']*2)+($interviewFields['s4_3']*1)+($interviewFields['s4_4']*1)}}
                                    </label>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <!-- Script for Table 4 -->
                        <script>
                            // Function to calculate product and update cell for Table 4
                            function updateProductTable4(row) {
                                // Get the coefficient value from the third column
                                const coefficient = parseInt(row.querySelector('td:nth-child(3) p').textContent.trim());

                                // Get the user-entered rating from the input field in the fourth column
                                const ratingInput = row.querySelector('td:nth-child(4) input');
                                const rating = ratingInput.value.trim() === '' ? 0 : parseInt(ratingInput.value.trim());

                                // Calculate the product of coefficient and rating
                                // Display the product in the fifth column
                                row.querySelector('td:nth-child(5) label').textContent = coefficient * rating;

                                // Update total for Table 4
                                updateTotalTable4();
                            }

                            // Function to update total for Table 4
                            function updateTotalTable4() {
                                // Get all the product values from the fifth column
                                const products = Array.from(document.querySelectorAll('#table4 tbody td:nth-child(5) label')).map(label => {
                                    const value = parseInt(label.textContent);
                                    return isNaN(value) ? 0 : value;
                                });

                                // Calculate total for Table 4
                                // Display total in the last row for Table 4
                                document.getElementById('s4_total').textContent = products.reduce((acc, value) => acc + value, 0);
                            }

                            // Get all the rows in Table 4, excluding the header row
                            const rowsTable4 = document.querySelectorAll('#table4 tbody tr');

                            // Loop through each row of Table 4
                            rowsTable4.forEach(row => {
                                // Add event listener to input field in the fourth column
                                const input = row.querySelector('td:nth-child(4) input');
                                input.addEventListener('input', () => {
                                    updateProductTable4(row);
                                });
                            });

                            // Call updateTotalTable4 function initially
                            updateTotalTable4();
                        </script>
                        <div class="mt-5">
                            <label for="s4_q1"
                                   class="block mb-2  font-bold text-gray-900 dark:text-white">
                                1. Express your areas of interest in case you want to collaborate with the school.
                            </label>
                            <textarea id="s4_q1" placeholder="Enter description"
                                      name="s4_q1" required
                                      class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">{{$interviewFields['s4_q1']}}</textarea>
                        </div>
                        <div class="mt-5">
                            <label for="s4_q2"
                                   class="block mb-2  font-bold text-gray-900 dark:text-white">
                                2. Feature highlights, and points of student’s strengths and weaknesses from the
                                family’s perspective: (in 3 short sentences)
                            </label>
                            <textarea id="s4_q2" placeholder="Enter description"
                                      name="s4_q2" required
                                      class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">{{$interviewFields['s4_q2']}}</textarea>
                        </div>
                        <div class="mt-5">
                            <label for="s4_q3"
                                   class="block mb-2  font-bold text-gray-900 dark:text-white">
                                3. Educational expectations and concerns, cultural, student’s family upbringing: (in 3
                                short sentences)
                            </label>
                            <textarea id="s4_q3" placeholder="Enter description"
                                      name="s4_q3" required
                                      class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">{{$interviewFields['s4_q3']}}</textarea>
                        </div>
                        <div class="mt-5">
                            <label for="s4_q4"
                                   class="block mb-2  font-bold text-gray-900 dark:text-white">
                                4. Supervision of children's activities (note on monitoring student communication, and
                                real and virtual activities by the family)
                            </label>
                            <textarea id="s4_q4" placeholder="Enter description"
                                      name="s4_q4" required
                                      class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">{{$interviewFields['s4_q4']}}</textarea>
                        </div>
                        <div class="mt-5">
                            <label for="s4_q5"
                                   class="block mb-2  font-bold text-gray-900 dark:text-white">
                                5. Dominance of a serene atmosphere on the family environment (requesting parents to
                                provide educational needs and a favorable psychological environment)
                            </label>
                            <textarea id="s4_q5" placeholder="Enter description"
                                      name="s4_q5" required
                                      class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">{{$interviewFields['s4_q5']}}</textarea>
                        </div>
                        <div class="mt-5">
                            <label for="s4_q6"
                                   class="block mb-2  font-bold text-gray-900 dark:text-white">
                                6. Level of knowledge of Arabic, Persian, English (emphasis on the requirement of
                                knowledge ability in language courses at the basic level)
                            </label>
                            <textarea id="s4_q6" placeholder="Enter description"
                                      name="s4_q6" required
                                      class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">{{$interviewFields['s4_q6']}}</textarea>
                        </div>
                        <div class="mt-5">
                            <label for="s4_q7"
                                   class="block mb-2  font-bold text-gray-900 dark:text-white">
                                7. Study and complete familiarity with the disciplinary, educational, administrative,
                                financial laws of the complex (request from parents to carefully study the rules and
                                regulations of the school and warning about the responsibility of families if the laws
                                are not carefully studied)
                            </label>
                            <textarea id="s4_q7" placeholder="Enter description"
                                      name="s4_q7" required
                                      class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">{{$interviewFields['s4_q7']}}</textarea>
                        </div>
                        <div class="mt-3">
                            <label for="s4_d"
                                   class="block mb-2  font-bold text-gray-900 dark:text-white">
                                Description</label>
                            <textarea id="s4_d" placeholder="Enter description"
                                      name="s4_d" required
                                      class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">{{$interviewFields['s4_d']}}</textarea>
                        </div>
                    </div>
                </div>

                <div>
                    <input type="hidden" name="application_id" id="application_id"
                           value="{{ $interview->id }}">
                    <input type="hidden" name="interview_id" id="interview_id"
                           value="{{ $interviewID }}">
                    <input type="hidden" name="form_type" id="form_type"
                           value="l2">
                    <button type="submit"
                            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                        Submit
                    </button>
                    <a href="{{ route('interviews.index') }}">
                        <button type="button"
                                class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800">
                            Back
                        </button>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
