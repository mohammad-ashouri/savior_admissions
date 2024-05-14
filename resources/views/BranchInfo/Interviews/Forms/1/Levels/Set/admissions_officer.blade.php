<div class="container mx-auto mt-8">
    <div class="flex justify-center">
        <div class="border border-gray-700 rounded-lg w-full ">
            <div class="p-4">
                <div class="mb-8" id="tab5">
                    <p class="font-bold mb-4">
                        Fifth step: Administrative-financial evaluation of the family (to be completed by the admission
                        expert)
                    </p>
                    <div class="overflow-x-auto">
                        <table id="table5" class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
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
                                    Previous year’s financial discipline and good accountability
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <p id="s5_1_c">
                                        2
                                    </p>
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <input type="text" id="s5_1" name="s5_1" value="{{old('s5_1')}}" min="0" max="5"
                                           required
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    >
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <label for="s5_1_rs"
                                           class="block mb-2  font-bold text-gray-900 dark:text-white">
                                    </label>
                                </td>
                            </tr>
                            <tr
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td class="w-4 p-4 text-center">
                                    2
                                </td>
                                <td class="w-4 p-4 text-center">
                                    Discipline in documents’ submission (timely delivery, completion of documents, etc.)
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <p id="s5_2_c">
                                        2
                                    </p>
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <input type="text" id="s5_2" name="s5_2" value="" min="0" max="5" required
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    >
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <label for="s5_2_rs"
                                           class="block mb-2  font-bold text-gray-900 dark:text-white">
                                    </label>
                                </td>
                            </tr>
                            <tr
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td class="w-4 p-4 text-center">
                                    3
                                </td>
                                <td class="w-4 p-4 text-center">
                                    Timely registration (registration period, interview)
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <p id="s5_3_c">
                                        1
                                    </p>
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <input type="text" id="s5_3" name="s5_3" value="" min="0" max="5" required
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    >
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <label for="s5_3_rs"
                                           class="block mb-2  font-bold text-gray-900 dark:text-white">
                                    </label>
                                </td>
                            </tr>
                            <tr
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 ">
                                <td class="w-4 p-4 text-right" colspan="4">
                                    Total
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <label id="s5_total"
                                           class="block mb-2  font-bold text-gray-900 dark:text-white">0
                                    </label>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <!-- Script for Table 5 -->
                        <script>
                            // Function to calculate product and update cell for Table 5
                            function updateProductTable5(row) {
                                // Get the coefficient value from the third column
                                const coefficient = parseInt(row.querySelector('td:nth-child(3) p').textContent.trim());

                                // Get the user-entered rating from the input field in the fourth column
                                const ratingInput = row.querySelector('td:nth-child(4) input');
                                const rating = ratingInput.value.trim() === '' ? 0 : parseInt(ratingInput.value.trim());

                                // Calculate the product of coefficient and rating
                                const product = coefficient * rating;

                                // Display the product in the fifth column
                                row.querySelector('td:nth-child(5) label').textContent = product;

                                // Update total for Table 5
                                updateTotalTable5();
                            }

                            // Function to update total for Table 5
                            function updateTotalTable5() {
                                // Get all the product values from the fifth column
                                const products = Array.from(document.querySelectorAll('#table5 tbody td:nth-child(5) label')).map(label => {
                                    const value = parseInt(label.textContent);
                                    return isNaN(value) ? 0 : value;
                                });

                                // Calculate total for Table 5
                                const total = products.reduce((acc, value) => acc + value, 0);

                                // Display total in the last row for Table 5
                                document.getElementById('s5_total').textContent = total;
                            }

                            // Get all the rows in Table 5, excluding the header row
                            const rowsTable5 = document.querySelectorAll('#table5 tbody tr');

                            // Loop through each row of Table 5
                            rowsTable5.forEach(row => {
                                // Add event listener to input field in the fourth column
                                const input = row.querySelector('td:nth-child(4) input');
                                input.addEventListener('input', () => {
                                    updateProductTable5(row);
                                });
                            });

                            // Call updateTotalTable5 function initially
                            updateTotalTable5();
                        </script>

                        <div class="mt-5">
                            <label for="s5_q1"
                                   class="block mb-2  font-bold text-gray-900 dark:text-white">
                                1. Economic status of the family:
                            </label>
                            <select id="s5_q1" name="s5_q1" required
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    title="Select an option">
                                <option selected disabled value="">Select an option</option>
                                <option value="poor">poor</option>
                                <option value="average">average</option>
                                <option value="good">good</option>
                                <option value="very good">very good</option>
                            </select>
                        </div>
                        <div class="mt-5">
                            <label for="s5_q2"
                                   class="block mb-2  font-bold text-gray-900 dark:text-white">
                                2. Is the family covered by the Relief and Charitable Institution?
                            </label>
                        </div>
                        <div class="flex">
                            <select id="s5_q2" name="s5_q2" required
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    title="Select an option">
                                <option selected disabled value="">Select an option</option>
                                <option value="No">No</option>
                                <option value="Yes">Yes</option>
                            </select>
                            <input type="text" id="s5_q2_d" name="s5_q2_d" value=""
                                   placeholder="Name of the institution (If Yes)"
                                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        </div>
                        <div class="mt-5">
                            <label for="s5_q3"
                                   class="block mb-2  font-bold text-gray-900 dark:text-white">
                                3. Is the family willing to donate money to needy students?
                            </label>
                            <select id="s5_q3" name="s5_q3" required
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    title="Select an option">
                                <option selected disabled value="">Select an option</option>
                                <option value="No">No</option>
                                <option value="Yes">Yes</option>
                            </select>
                        </div>
                        <div class="mt-5">
                            <label for="s5_q4"
                                   class="block mb-2  font-bold text-gray-900 dark:text-white">
                                4. Would you like to contribute financially to the school's extracurricular activities
                                and activities?
                            </label>
                            <select id="s5_q4" name="s5_q4" required
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    title="Select an option">
                                <option selected disabled value="">Select an option</option>
                                <option value="No">No</option>
                                <option value="Yes">Yes</option>
                            </select>
                        </div>
                        <div class="mt-5">
                            <label for="s5_q5_d"
                                   class="block mb-2  font-bold text-gray-900 dark:text-white">
                                5. Financial administrative suggestions and criticisms
                            </label>
                            <textarea id="s5_q5_d" placeholder="Enter description"
                                      name="s5_q5_d" required
                                      class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"></textarea>
                        </div>
                        <div class="mt-3">
                            <label for="s5_d"
                                   class="block mb-2  font-bold text-gray-900 dark:text-white">
                                Description</label>
                            <textarea id="s5_d" placeholder="Enter description"
                                      name="s5_d" required
                                      class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"></textarea>
                        </div>
                    </div>
                </div>

                <div id="last-step" class="text-center">
                    <div class="text-left mb-4">
                        <div>
                            <p class="font-bold mt-4">
                                Discount <u>(Check only when needed)</u>
                            </p>
                            <div class="overflow-x-auto mb-3">
                                @if($discounts->isEmpty())
                                    <div
                                        class="bg-teal-100 border-t-4 border-teal-500 mt-4 rounded-b text-teal-900 px-4 py-3 shadow-md"
                                        role="alert">
                                        <div class="flex">
                                            <div class="py-1">
                                                <svg class="fill-current h-6 w-6 text-teal-500 mr-4"
                                                     xmlns="http://www.w3.org/2000/svg"
                                                     viewBox="0 0 20 20">
                                                    <path
                                                        d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM9 11V9h2v6H9v-4zm0-6h2v2H9V5z"/>
                                                </svg>
                                            </div>
                                            <div>
                                                There is not any discount items to show! Please contact the
                                                financial manager of your department and raise the issue.
                                                <br/>
                                                Note: If the student needs to register a discount and you do not
                                                choose, your interview will no longer be editable.
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                        <thead
                                            class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                        <tr>
                                            <th scope="col" class="p-4 text-center">
                                                ID
                                            </th>
                                            <th scope="col" class="p-4 text-center">
                                                Title
                                            </th>
                                            <th scope="col" class="p-4 text-center">
                                                Percentage
                                            </th>
                                            <th scope="col" class="p-4 text-center">
                                                Select
                                            </th>
                                        </tr>
                                        </thead>
                                        <tbody
                                            class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                        @foreach($discounts as $discount)
                                            <tr>
                                                <td class="w-10 font-bold p-4 text-center">
                                                    {{ $loop->iteration }}
                                                </td>
                                                <td class="w-1/3 font-bold p-4 text-center">
                                                    {{ $discount->name }}
                                                </td>
                                                <td class="font-bold p-4 text-center">
                                                    {{ $discount->percentage }}%
                                                </td>
                                                <td class="font-bold p-4 text-center">
                                                    <input class="discount-checks" type="checkbox"
                                                           value="{{ $discount->id }}"
                                                           name="discount[]">
                                                </td>
                                            </tr>
                                        @endforeach

                                        </tbody>
                                    </table>
                                @endif
                            </div>
                        </div>
                        <div>
                            <p class="font-bold mt-4">
                                If needed, you can upload files related to financial affairs here.
                            </p>
                            <div>
                                <div>
                                    <label class="block mb-2 mt-4 text-sm font-medium text-gray-900 dark:text-white"
                                           for="document_file1">Select your document </label>
                                    <input
                                        class="mb-4 block w-96 text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-600 dark:border-gray-600 dark:placeholder-gray-400"
                                        id="document_file1" name="document_file1" type="file"
                                        accept=".png,.jpg,.jpeg,.pdf,.bmp">
                                </div>
                                <div>
                                    <label for="document_description1"
                                           class="block mb-2  font-bold text-gray-900 dark:text-white">
                                        Description for document</label>
                                    <textarea id="document_description1"
                                              placeholder="Write your description if needed"
                                              name="document_description1"
                                              class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"></textarea>
                                </div>
                            </div>
                            <div>
                                <div>
                                    <label class="block mb-2 mt-4 text-sm font-medium text-gray-900 dark:text-white"
                                           for="document_file2">Select your document </label>
                                    <input
                                        class="mb-4 block w-96 text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-600 dark:border-gray-600 dark:placeholder-gray-400"
                                        id="document_file2" name="document_file2" type="file"
                                        accept=".png,.jpg,.jpeg,.pdf,.bmp">
                                </div>
                                <div>
                                    <label for="document_description2"
                                           class="block mb-2  font-bold text-gray-900 dark:text-white">
                                        Description for document</label>
                                    <textarea id="document_description2"
                                              placeholder="Write your description if needed"
                                              name="document_description2"
                                              class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"></textarea>
                                </div>
                            </div>
                            <div>
                                <div>
                                    <label class="block mb-2 mt-4 text-sm font-medium text-gray-900 dark:text-white"
                                           for="document_file3">Select your document </label>
                                    <input
                                        class="mb-4 block w-96 text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-600 dark:border-gray-600 dark:placeholder-gray-400"
                                        id="document_file3" name="document_file3" type="file"
                                        accept=".png,.jpg,.jpeg,.pdf,.bmp">
                                </div>
                                <div>
                                    <label for="document_description3"
                                           class="block mb-2  font-bold text-gray-900 dark:text-white">
                                        Description for document</label>
                                    <textarea id="document_description3"
                                              placeholder="Write your description if needed"
                                              name="document_description3"
                                              class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"></textarea>
                                </div>
                            </div>
                            <div>
                                <div>
                                    <label class="block mb-2 mt-4 text-sm font-medium text-gray-900 dark:text-white"
                                           for="document_file4">Select your document </label>
                                    <input
                                        class="mb-4 block w-96 text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-600 dark:border-gray-600 dark:placeholder-gray-400"
                                        id="document_file4" name="document_file4" type="file"
                                        accept=".png,.jpg,.jpeg,.pdf,.bmp">
                                </div>
                                <div>
                                    <label for="document_description4"
                                           class="block mb-2  font-bold text-gray-900 dark:text-white">
                                        Description for document</label>
                                    <textarea id="document_description4"
                                              placeholder="Write your description if needed"
                                              name="document_description4"
                                              class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"></textarea>
                                </div>
                            </div>
                            <div>
                                <div>
                                    <label class="block mb-2 mt-4 text-sm font-medium text-gray-900 dark:text-white"
                                           for="document_file5">Select your document </label>
                                    <input
                                        class="mb-4 block w-96 text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-600 dark:border-gray-600 dark:placeholder-gray-400"
                                        id="document_file5" name="document_file5" type="file"
                                        accept=".png,.jpg,.jpeg,.pdf,.bmp">
                                </div>
                                <div>
                                    <label for="document_description5"
                                           class="block mb-2  font-bold text-gray-900 dark:text-white">
                                        Description for document</label>
                                    <textarea id="document_description5"
                                              placeholder="Write your description if needed"
                                              name="document_description5"
                                              class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="mt-3">
                            <input type="hidden" name="application_id" id="application_id"
                                   value="{{ $interview->id }}">
                            <input type="hidden" name="form_type" id="form_type"
                                   value="la">
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
    </div>
</div>
