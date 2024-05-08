<div class="container mx-auto mt-8">
    <div class="flex justify-center">
        <div class="border border-gray-700 rounded-lg w-full ">
            <div class="p-4">
                <div class="mb-8" id="tab1">
                    <p class="font-bold">
                        First step: Admission Prerequisites
                    </p>
                    <p>
                        Admission will be revoked if the student's status is inadmissible in any of the following
                        sections.
                    </p>
                    <div class="mt-5">
                        <div class="flex mb-5">
                            <label for="s1_1_s" class="block mb-2 font-bold text-gray-900 dark:text-white">
                                1- GPA of Previous year:
                            </label>
                            <input type="text" id="gpa_p_y" name="gpa_p_y" value="{{$interviewFields['gpa_p_y']}}"
                                   placeholder="Enter GPA of previous year" required
                                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full sm:w-48 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        </div>
                        <select id="s1_1_s" name="s1_1_s" required
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                title="Select an option">
                            <option @if($interviewFields['s1_1_s']=='Admissible') selected @endif value="Admissible">
                                Admissible
                            </option>
                            <option @if($interviewFields['s1_1_s']=='Inadmissible') selected
                                    @endif value="Inadmissible">Inadmissible
                            </option>
                        </select>
                        <div class="mt-3">
                            <label for="s1_1_d"
                                   class="block mb-2  font-bold text-gray-900 dark:text-white">
                                Description</label>
                            <textarea id="s1_1_d" placeholder="Enter description"
                                      name="s1_1_d" required
                                      class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">{{$interviewFields['s1_1_d']}}</textarea>
                        </div>
                    </div>
                    <div class="mt-5">
                        <label for="s1_2_s"
                               class="block mb-2  font-bold text-gray-900 dark:text-white">
                            2-General knowledge of English:
                        </label>
                        <select id="s1_2_s" name="s1_2_s" required
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                title="Select an option">
                            <option @if($interviewFields['s1_2_s']=='Admissible') selected @endif value="Admissible">
                                Admissible
                            </option>
                            <option @if($interviewFields['s1_2_s']=='Inadmissible') selected
                                    @endif value="Inadmissible">Inadmissible
                            </option>
                        </select>
                        <div class="mt-3">
                            <label for="s1_2_d"
                                   class="block mb-2  font-bold text-gray-900 dark:text-white">
                                Description</label>
                            <textarea id="s1_2_d" placeholder="Enter description"
                                      name="s1_2_d" required
                                      class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">{{$interviewFields['s1_2_d']}}</textarea>
                        </div>
                    </div>
                    <div class="mt-5">
                        <label for="s1_3_s"
                               class="block mb-2  font-bold text-gray-900 dark:text-white">
                            3- Special cases (disciplinary / ideological / family, etc.):
                        </label>
                        <select id="s1_3_s" name="s1_3_s" required
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                title="Select an option">
                            <option @if($interviewFields['s1_3_s']=='Admissible') selected @endif value="Admissible">
                                Admissible
                            </option>
                            <option @if($interviewFields['s1_3_s']=='Inadmissible') selected
                                    @endif value="Inadmissible">Inadmissible
                            </option>
                        </select>
                        <div class="mt-3">
                            <label for="s1_3_d"
                                   class="block mb-2  font-bold text-gray-900 dark:text-white">
                                Description</label>
                            <textarea id="s1_3_d" placeholder="Enter description"
                                      name="s1_3_d" required
                                      class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">{{$interviewFields['s1_3_d']}}</textarea>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="mb-8" id="tab2">
                    <p class="font-bold mb-4">
                        Second step: Learning and Education
                    </p>
                    <div class="overflow-x-auto">
                        <table id="table2" class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
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
                                    Student's Educational Status (Academic Achievement and Transcript Review)
                                </td>
                                <td class="w-2 p-4 text-center">
                                    <p id="s2_1_c">
                                        2
                                    </p>
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <input type="text" id="s2_1" name="s2_1" value="{{$interviewFields['s2_1']}}"
                                           min="0" max="5" required
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    >
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <label for="s2_1_rs"
                                           class="block mb-2  font-bold text-gray-900 dark:text-white">{{$interviewFields['s2_1']*2}}
                                    </label>
                                </td>
                            </tr>
                            <tr
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td class="w-4 p-4 text-center">
                                    2
                                </td>
                                <td class="w-4 p-4 text-center">
                                    Homework and class exercises (timely presentation of homework, class and educational
                                    projects, class presentation and participation in scientific and educational
                                    activities)
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <p id="s2_2_c">
                                        2
                                    </p>
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <input type="text" id="s2_2" name="s2_2" value="{{$interviewFields['s2_2']}}"
                                           min="0" max="5" required
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    >
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <label for="s2_2_rs"
                                           class="block mb-2  font-bold text-gray-900 dark:text-white">{{$interviewFields['s2_2']*2}}
                                    </label>
                                </td>
                            </tr>
                            <tr
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td class="w-4 p-4 text-center">
                                    3
                                </td>
                                <td class="w-4 p-4 text-center">
                                    Attendance of compensatory-reinforcement classes
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <p id="s2_3_c">
                                        1
                                    </p>
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <input type="text" id="s2_3" name="s2_3" value="{{$interviewFields['s2_3']}}"
                                           min="0" max="3" required
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    >
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <label for="s2_3_rs"
                                           class="block mb-2  font-bold text-gray-900 dark:text-white">{{$interviewFields['s2_3']*1}}
                                    </label>
                                </td>
                            </tr>
                            <tr
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td class="w-4 p-4 text-center">
                                    4
                                </td>
                                <td class="w-4 p-4 text-center">
                                    Assessing the level of specialized English according to the student's educational
                                    level (reading a part of the text of the book and checking the student's
                                    comprehension and familiarity with specialized vocabulary, asking a few specialized
                                    questions from the student's favorite subject)
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <p id="s2_4_c">
                                        1
                                    </p>
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <input type="text" id="s2_4" name="s2_4" value="{{$interviewFields['s2_4']}}"
                                           min="0" max="5" required
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    >
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <label for="s2_4_rs"
                                           class="block mb-2  font-bold text-gray-900 dark:text-white">{{$interviewFields['s2_4']*1}}
                                    </label>
                                </td>
                            </tr>
                            <tr
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td class="w-4 p-4 text-center">
                                    5
                                </td>
                                <td class="w-4 p-4 text-center">
                                    Checking the level of Persian language (knowledge ability in this course at the
                                    basic level is - conversation, comprehension)
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <p id="s2_5_c">
                                        2
                                    </p>
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <input type="text" id="s2_5" name="s2_5" value="{{$interviewFields['s2_5']}}"
                                           min="0" max="5" required
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    >
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <label for="s2_5_rs"
                                           class="block mb-2  font-bold text-gray-900 dark:text-white">{{$interviewFields['s2_5']*2}}
                                    </label>
                                </td>
                            </tr>
                            <tr
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td class="w-4 p-4 text-center">
                                    6
                                </td>
                                <td class="w-4 p-4 text-center">
                                    Assessing the level of Arabic language (knowledge ability in this course at the
                                    basic level is - Conversation, comprehension)
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <p id="s2_6_c">
                                        2
                                    </p>
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <input type="text" id="s2_6" name="s2_6" value="{{$interviewFields['s2_6']}}"
                                           min="0" max="3" required
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    >
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <label for="s2_6_rs"
                                           class="block mb-2  font-bold text-gray-900 dark:text-white">{{$interviewFields['s2_6']*2}}
                                    </label>
                                </td>
                            </tr>
                            <tr
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 ">
                                <td class="w-4 p-4 text-right" colspan="4">
                                    Total
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <label id="s2_total"
                                           class="block mb-2  font-bold text-gray-900 dark:text-white">{{($interviewFields['s2_1']*2)+($interviewFields['s2_2']*2)+($interviewFields['s2_3']*1)+($interviewFields['s2_4']*1)+($interviewFields['s2_5']*2)+($interviewFields['s2_6']*2)}}
                                    </label>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <!-- Script for Table 2 -->
                        <script>
                            // Function to calculate product and update cell for Table 2
                            function updateProductTable2(row) {
                                // Get the coefficient value from the third column
                                const coefficient = parseInt(row.querySelector('td:nth-child(3) p').textContent.trim());

                                // Get the user-entered rating from the input field in the fourth column
                                const ratingInput = row.querySelector('td:nth-child(4) input');
                                const rating = ratingInput.value.trim() === '' ? 0 : parseInt(ratingInput.value.trim());

                                // Calculate the product of coefficient and rating
                                // Display the product in the fifth column
                                row.querySelector('td:nth-child(5) label').textContent = coefficient * rating;

                                // Update total for Table 2
                                updateTotalTable2();
                            }

                            // Function to update total for Table 2
                            function updateTotalTable2() {
                                // Get all the product values from the fifth column
                                const products = Array.from(document.querySelectorAll('#table2 tbody td:nth-child(5) label')).map(label => {
                                    const value = parseInt(label.textContent);
                                    return isNaN(value) ? 0 : value;
                                });

                                // Calculate total for Table 2
                                // Display total in the last row for Table 2
                                document.getElementById('s2_total').textContent = products.reduce((acc, value) => acc + value, 0);
                            }

                            // Get all the rows in Table 2, excluding the header row
                            const rowsTable2 = document.querySelectorAll('#table2 tbody tr');

                            // Loop through each row of Table 2
                            rowsTable2.forEach(row => {
                                // Add event listener to input field in the fourth column
                                const input = row.querySelector('td:nth-child(4) input');
                                input.addEventListener('input', () => {
                                    updateProductTable2(row);
                                });
                            });

                            // Call updateTotalTable2 function initially
                            updateTotalTable2();
                        </script>

                        <div class="mt-3">
                            <label for="s2_d"
                                   class="block mb-2  font-bold text-gray-900 dark:text-white">
                                Description</label>
                            <textarea id="s2_d" placeholder="Enter description"
                                      name="s2_d" required
                                      class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">{{$interviewFields['s2_d']}}</textarea>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="mb-8" id="tab3">
                    <p class="font-bold mb-4">
                        Third step: Educational-Disciplinary
                    </p>
                    <div class="overflow-x-auto">
                        <table id="table3" class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
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
                                    Disciplinary warnings (referral to the office, verbal and written warning, violation
                                    of the rules ...)
                                </td>
                                <td class="w-2 p-4 text-center">
                                    <p id="s3_1_c">
                                        2
                                    </p>
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <input type="text" id="s3_1" name="s3_1" value="{{$interviewFields['s3_1']}}"
                                           min="0" max="5" required
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    >
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <label for="s3_1_rs"
                                           class="block mb-2  font-bold text-gray-900 dark:text-white">{{$interviewFields['s3_1']*2}}
                                    </label>
                                </td>
                            </tr>
                            <tr
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td class="w-4 p-4 text-center">
                                    2
                                </td>
                                <td class="w-4 p-4 text-center">
                                    How the student interacts with teachers, classmates, administrative teaching staff
                                </td>
                                <td class="w-2 p-4 text-center">
                                    <p id="s3_2_c">
                                        2
                                    </p>
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <input type="text" id="s3_2" name="s3_2" value="{{$interviewFields['s3_2']}}"
                                           min="0" max="5" required
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    >
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <label for="s3_2_rs"
                                           class="block mb-2  font-bold text-gray-900 dark:text-white">{{$interviewFields['s3_2']*2}}
                                    </label>
                                </td>
                            </tr>
                            <tr
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td class="w-4 p-4 text-center">
                                    3
                                </td>
                                <td class="w-4 p-4 text-center">
                                    Attendance Status
                                </td>
                                <td class="w-2 p-4 text-center">
                                    <p id="s3_3_c">
                                        2
                                    </p>
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <input type="text" id="s3_3" name="s3_3" value="{{$interviewFields['s3_3']}}"
                                           min="0" max="5" required
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    >
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <label for="s3_3_rs"
                                           class="block mb-2  font-bold text-gray-900 dark:text-white">{{$interviewFields['s3_3']*2}}
                                    </label>
                                </td>
                            </tr>
                            <tr
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td class="w-4 p-4 text-center">
                                    4
                                </td>
                                <td class="w-4 p-4 text-center">
                                    Order of School and Classes Attendance
                                </td>
                                <td class="w-2 p-4 text-center">
                                    <p id="s3_4_c">
                                        2
                                    </p>
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <input type="text" id="s3_4" name="s3_4" value="{{$interviewFields['s3_4']}}"
                                           min="0" max="5" required
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    >
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <label for="s3_4_rs"
                                           class="block mb-2  font-bold text-gray-900 dark:text-white">{{$interviewFields['s3_4']*2}}
                                    </label>
                                </td>
                            </tr>
                            <tr
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td class="w-4 p-4 text-center">
                                    5
                                </td>
                                <td class="w-4 p-4 text-center">
                                    Student dress and appearance (student hairstyle (boys), hijab and makeup (girls))
                                </td>
                                <td class="w-2 p-4 text-center">
                                    <p id="s3_5_c">
                                        1
                                    </p>
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <input type="text" id="s3_5" name="s3_5" value="{{$interviewFields['s3_5']}}"
                                           min="0" max="5" required
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    >
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <label for="s3_5_rs"
                                           class="block mb-2  font-bold text-gray-900 dark:text-white">{{$interviewFields['s3_5']*1}}
                                    </label>
                                </td>
                            </tr>
                            <tr
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td class="w-4 p-4 text-center">
                                    6
                                </td>
                                <td class="w-4 p-4 text-center">
                                    Degree of adherence to religious issues and sharia rules
                                </td>
                                <td class="w-2 p-4 text-center">
                                    <p id="s3_6_c">
                                        1
                                    </p>
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <input type="text" id="s3_6" name="s3_6" value="{{$interviewFields['s3_6']}}"
                                           min="0" max="5" required
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    >
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <label for="s3_6_rs"
                                           class="block mb-2  font-bold text-gray-900 dark:text-white">{{$interviewFields['s3_6']*1}}
                                    </label>
                                </td>
                            </tr>
                            <tr
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td class="w-4 p-4 text-center">
                                    7
                                </td>
                                <td class="w-4 p-4 text-center">
                                    Participation in school programs (ceremonies and occasions
                                </td>
                                <td class="w-2 p-4 text-center">
                                    <p id="s3_7_c">
                                        1
                                    </p>
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <input type="text" id="s3_7" name="s3_7" value="{{$interviewFields['s3_7']}}"
                                           min="0" max="5" required
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    >
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <label for="s3_7_rs"
                                           class="block mb-2  font-bold text-gray-900 dark:text-white">{{$interviewFields['s3_7']*1}}
                                    </label>
                                </td>
                            </tr>
                            <tr
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td class="w-4 p-4 text-center">
                                    8
                                </td>
                                <td class="w-4 p-4 text-center">
                                    Level of moral development (presenting moral challenges and dipoles, emotion control
                                    in interpersonal relationships
                                </td>
                                <td class="w-2 p-4 text-center">
                                    <p id="s3_8_c">
                                        1
                                    </p>
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <input type="text" id="s3_8" name="s3_8" value="{{$interviewFields['s3_8']}}"
                                           min="0" max="5" required
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    >
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <label for="s3_8_rs"
                                           class="block mb-2  font-bold text-gray-900 dark:text-white">{{$interviewFields['s3_8']*1}}
                                    </label>
                                </td>
                            </tr>
                            <tr
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td class="w-4 p-4 text-center">
                                    9
                                </td>
                                <td class="w-4 p-4 text-center">
                                    Motivation for personal growth and development (goals and plans)
                                </td>
                                <td class="w-2 p-4 text-center">
                                    <p id="s3_9_c">
                                        1
                                    </p>
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <input type="text" id="s3_9" name="s3_9" value="{{$interviewFields['s3_9']}}"
                                           min="0" max="5" required
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    >
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <label for="s3_9_rs"
                                           class="block mb-2  font-bold text-gray-900 dark:text-white">{{$interviewFields['s3_9']*1}}
                                    </label>
                                </td>
                            </tr>
                            <tr
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td class="w-4 p-4 text-center">
                                    10
                                </td>
                                <td class="w-4 p-4 text-center">
                                    Observance of the official language of the school (English conversation while
                                    attending school)
                                </td>
                                <td class="w-2 p-4 text-center">
                                    <p id="s3_10_c">
                                        2
                                    </p>
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <input type="text" id="s3_10" name="s3_10" value="{{$interviewFields['s3_10']}}"
                                           min="0" max="5" required
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    >
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <label for="s3_10_rs"
                                           class="block mb-2  font-bold text-gray-900 dark:text-white">{{$interviewFields['s3_10']*2}}
                                    </label>
                                </td>
                            </tr>
                            <tr
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td class="w-4 p-4 text-center">
                                    11
                                </td>
                                <td class="w-4 p-4 text-center">
                                    Uniform
                                </td>
                                <td class="w-2 p-4 text-center">
                                    <p id="s3_11_c">
                                        1
                                    </p>
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <input type="text" id="s3_11" name="s3_11" value="{{$interviewFields['s3_11']}}"
                                           min="0" max="5" required
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    >
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <label for="s3_11_rs"
                                           class="block mb-2  font-bold text-gray-900 dark:text-white">{{$interviewFields['s3_11']*1}}
                                    </label>
                                </td>
                            </tr>
                            <tr
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td class="w-4 p-4 text-center">
                                    12
                                </td>
                                <td class="w-4 p-4 text-center">
                                    Use of electronic and digital devices (Prohibition of carrying and using any digital
                                    devices without school permit)
                                </td>
                                <td class="w-2 p-4 text-center">
                                    <p id="s3_12_c">
                                        2
                                    </p>
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <input type="text" id="s3_12" name="s3_12" value="{{$interviewFields['s3_12']}}"
                                           min="0" max="5" required
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    >
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <label for="s3_12_rs"
                                           class="block mb-2  font-bold text-gray-900 dark:text-white">{{$interviewFields['s3_12']*2}}
                                    </label>
                                </td>
                            </tr>
                            <tr
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td class="w-4 p-4 text-center">
                                    13
                                </td>
                                <td class="w-4 p-4 text-center">
                                    Correct observance and use of school property and facilities (in case of any damage,
                                    the family is obliged to compensate the damage)
                                </td>
                                <td class="w-2 p-4 text-center">
                                    <p id="s3_13_c">
                                        1
                                    </p>
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <input type="text" id="s3_13" name="s3_13" value="{{$interviewFields['s3_13']}}"
                                           min="0" max="5" required
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    >
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <label for="s3_13_rs"
                                           class="block mb-2  font-bold text-gray-900 dark:text-white">{{$interviewFields['s3_13']*1}}
                                    </label>
                                </td>
                            </tr>
                            <tr
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 ">
                                <td class="w-4 p-4 text-right" colspan="4">
                                    Total
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <label id="s3_total"
                                           class="block mb-2  font-bold text-gray-900 dark:text-white">{{($interviewFields['s3_1']*2)+($interviewFields['s3_2']*2)+($interviewFields['s3_3']*2)+($interviewFields['s3_4']*2)+($interviewFields['s3_5']*1)+($interviewFields['s3_6']*1)+($interviewFields['s3_7']*1)+($interviewFields['s3_8']*1)+($interviewFields['s3_9']*1)+($interviewFields['s3_10']*2)+($interviewFields['s3_11']*1)+($interviewFields['s3_12']*2)+($interviewFields['s3_13']*1)}}
                                    </label>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <!-- Script for Table 3 -->
                        <script>
                            // Function to calculate product and update cell for Table 3
                            function updateProductTable3(row) {
                                // Get the coefficient value from the third column
                                const coefficient = parseInt(row.querySelector('td:nth-child(3) p').textContent.trim());

                                // Get the user-entered rating from the input field in the fourth column
                                const ratingInput = row.querySelector('td:nth-child(4) input');
                                const rating = ratingInput.value.trim() === '' ? 0 : parseInt(ratingInput.value.trim());

                                // Calculate the product of coefficient and rating
                                // Display the product in the fifth column
                                row.querySelector('td:nth-child(5) label').textContent = coefficient * rating;

                                // Update total for Table 3
                                updateTotalTable3();
                            }

                            // Function to update total for Table 3
                            function updateTotalTable3() {
                                // Get all the product values from the fifth column
                                const products = Array.from(document.querySelectorAll('#table3 tbody td:nth-child(5) label')).map(label => {
                                    const value = parseInt(label.textContent);
                                    return isNaN(value) ? 0 : value;
                                });

                                // Calculate total for Table 3
                                // Display total in the last row for Table 3
                                document.getElementById('s3_total').textContent = products.reduce((acc, value) => acc + value, 0);
                            }

                            // Get all the rows in Table 3, excluding the header row
                            const rowsTable3 = document.querySelectorAll('#table3 tbody tr');

                            // Loop through each row of Table 3
                            rowsTable3.forEach(row => {
                                // Add event listener to input field in the fourth column
                                const input = row.querySelector('td:nth-child(4) input');
                                input.addEventListener('input', () => {
                                    updateProductTable3(row);
                                });
                            });

                            // Call updateTotalTable3 function initially
                            updateTotalTable3();
                        </script>

                        <div class="mt-5">
                            <label for="s3_q1"
                                   class="block mb-2  font-bold text-gray-900 dark:text-white">
                                1. Express your areas of interest in case you want to collaborate with the
                                school.</label>
                            <textarea id="s3_q1" placeholder="Enter description"
                                      name="s3_q1" required
                                      class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">{{$interviewFields['s3_q1']}}</textarea>
                        </div>
                        <div class="mt-5">
                            <label for="s3_q2"
                                   class="block mb-2  font-bold text-gray-900 dark:text-white">
                                2. Description of the student's scientific, educational and cultural personality from
                                the interviewer’s viewpoint: (3 short sentences)
                            </label>
                            <textarea id="s3_q2" placeholder="Enter description"
                                      name="s3_q2" required
                                      class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">{{$interviewFields['s3_q2']}}</textarea>
                        </div>
                        <div class="mt-5">
                            <label for="s3_q3"
                                   class="block mb-2  font-bold text-gray-900 dark:text-white">
                                3. Special skills and talents (art, Quran, sports, technology, etc.)
                            </label>
                            <textarea id="s3_q3" placeholder="Enter description"
                                      name="s3_q3" required
                                      class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">{{$interviewFields['s3_q3']}}</textarea>
                        </div>
                        <div class="mt-3">
                            <label for="s3_d"
                                   class="block mb-2  font-bold text-gray-900 dark:text-white">
                                Description</label>
                            <textarea id="s3_d" placeholder="Enter description"
                                      name="s3_d" required
                                      class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">{{$interviewFields['s3_d']}}</textarea>
                        </div>
                    </div>
                </div>
                <div>
                    <input type="hidden" name="application_id" id="application_id"
                           value="{{ $interview->id }}">
                    <input type="hidden" name="interview_id" id="interview_id"
                           value="{{ $interviewID }}">
                    <input type="hidden" name="form_type" id="form_type"
                           value="l1">
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
