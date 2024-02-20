<div class="container mx-auto mt-8">
    <div class="flex justify-center">
        <div class="border border-gray-700 rounded-lg w-full ">
            <!-- Tabs -->
            <div class="flex flex-wrap">
                <button type="button" id="step-1-btn"
                        class="tab-btn w-full md:w-2/4 lg:w-1/7 py-2 px-4 bg-gray-700 hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-400 rounded-tl-lg rounded-tr-lg"
                        data-tab="tab1">Step 1
                </button>
                <button type="button" id="step-2-btn"
                        class="tab-btn w-full md:w-2/4 lg:w-1/7 py-2 px-4 bg-gray-700 hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-400"
                        data-tab="tab2">Step 2
                </button>
                <button type="button" id="step-3-btn"
                        class="tab-btn w-full md:w-2/4 lg:w-1/7 py-2 px-4 bg-gray-700 hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-400"
                        data-tab="tab3">Step 3
                </button>
                <button type="button" id="step-4-btn"
                        class="tab-btn w-full md:w-2/4 lg:w-1/7 py-2 px-4 bg-gray-700 hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-400"
                        data-tab="tab4">Step 4
                </button>
                <button type="button" id="step-5-btn"
                        class="tab-btn w-full md:w-2/4 lg:w-1/7 py-2 px-4 bg-gray-700 hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-400"
                        data-tab="tab5">Step 5
                </button>
                <button type="button" id="step-6-btn"
                        class="tab-btn w-full md:w-2/4 lg:w-1/7 py-2 px-4 bg-gray-700 hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-400"
                        data-tab="tab6">Step 6
                </button>
                <button type="button" id="step-7-btn"
                        class="tab-btn w-full md:w-2/4 lg:w-1/7 py-2 px-4 bg-gray-700 hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-400"
                        data-tab="tab7">Step 7
                </button>
                <button type="button" id="step-8-btn"
                        class="tab-btn w-full md:w-2/4 lg:w-1/7 py-2 px-4 bg-gray-700 hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-400 rounded-bl-lg rounded-br-lg"
                        data-tab="tab8">Final
                </button>
            </div>
            <!-- Tab contents -->
            <div class="p-4">
                <div id="tab1" class="hidden">
                    <p class="font-bold">
                        First step: admission prerequisites
                    </p>
                    <p>
                        If the student's status is not allowed in any of the following clauses, the admission is
                        rejected.
                    </p>
                    <div class="mt-5">
                        <label for="s1_1_s"
                               class="block mb-2  font-bold text-gray-900 dark:text-white">
                            1- Control and independence in using the bathroom, incontinence:</label>
                        <select id="s1_1_s" name="s1_1_s"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                title="Select an option" required>
                            <option selected disabled value="">Select an option</option>
                            <option @if($interviewForm['s1_1_s']=='Admitted') selected @endif value="Admitted">
                                Admitted
                            </option>
                            <option @if($interviewForm['s1_1_s']=='Conditional') selected @endif value="Conditional">
                                Conditional
                            </option>
                            <option @if($interviewForm['s1_1_s']=='Rejected') selected @endif value="Rejected">
                                Rejected
                            </option>
                        </select>
                        <div class="mt-3">
                            <label for="s1_1_r"
                                   class="block mb-2  font-bold text-gray-900 dark:text-white">
                                Remarks</label>
                            <textarea id="s1_1_r" required placeholder="Enter remarks"
                                      name="s1_1_r"
                                      class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">{{ $interviewForm['s1_1_r'] }}</textarea>
                        </div>
                    </div>
                    <div class="mt-5">
                        <label for="s1_2_s"
                               class="block mb-2  font-bold text-gray-900 dark:text-white">
                            2-Severe emotional dependence:
                        </label>
                        <select id="s1_2_s" name="s1_2_s"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                title="Select an option" required>
                            <option selected disabled value="">Select an option</option>
                            <option @if($interviewForm['s1_2_s']=='Admitted') selected @endif value="Admitted">
                                Admitted
                            </option>
                            <option @if($interviewForm['s1_2_s']=='Conditional') selected @endif value="Conditional">
                                Conditional
                            </option>
                            <option @if($interviewForm['s1_2_s']=='Rejected') selected @endif value="Rejected">
                                Rejected
                            </option>
                        </select>
                        <div class="mt-3">
                            <label for="s1_2_r"
                                   class="block mb-2  font-bold text-gray-900 dark:text-white">
                                Remarks</label>
                            <textarea id="s1_2_r" required placeholder="Enter remarks"
                                      name="s1_2_r"
                                      class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">{{ $interviewForm['s1_2_r'] }}</textarea>
                        </div>
                    </div>
                    <div class="mt-5">
                        <label for="s1_3_s"
                               class="block mb-2  font-bold text-gray-900 dark:text-white">
                            3- Severe physical disability:
                        </label>
                        <select id="s1_3_s" name="s1_3_s"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                title="Select an option" required>
                            <option selected disabled value="">Select an option</option>
                            <option @if($interviewForm['s1_3_s']=='Admitted') selected @endif value="Admitted">
                                Admitted
                            </option>
                            <option @if($interviewForm['s1_3_s']=='Conditional') selected @endif value="Conditional">
                                Conditional
                            </option>
                            <option @if($interviewForm['s1_3_s']=='Rejected') selected @endif value="Rejected">
                                Rejected
                            </option>
                        </select>
                        <div class="mt-3">
                            <label for="s1_3_r"
                                   class="block mb-2  font-bold text-gray-900 dark:text-white">
                                Remarks</label>
                            <textarea id="s1_3_r" required placeholder="Enter remarks"
                                      name="s1_3_r"
                                      class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">{{ $interviewForm['s1_3_r'] }}</textarea>
                        </div>
                    </div>
                    <div class="mt-5">
                        <label for="s1_4_s"
                               class="block mb-2  font-bold text-gray-900 dark:text-white">
                            4.Special cases (disciplinary/ideological/family etc.):
                        </label>
                        <select id="s1_4_s" name="s1_4_s"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                title="Select an option" required>
                            <option selected disabled value="">Select an option</option>
                            <option @if($interviewForm['s1_4_s']=='Admitted') selected @endif value="Admitted">
                                Admitted
                            </option>
                            <option @if($interviewForm['s1_4_s']=='Conditional') selected @endif value="Conditional">
                                Conditional
                            </option>
                            <option @if($interviewForm['s1_4_s']=='Rejected') selected @endif value="Rejected">
                                Rejected
                            </option>
                        </select>
                        <div class="mt-3">
                            <label for="s1_4_r"
                                   class="block mb-2  font-bold text-gray-900 dark:text-white">
                                Remarks</label>
                            <textarea id="s1_4_r" required placeholder="Enter remarks"
                                      name="s1_4_r"
                                      class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">{{ $interviewForm['s1_4_r'] }}</textarea>
                        </div>
                    </div>
                </div>
                <div id="tab2" class="hidden">
                    <p class="font-bold mb-4">
                        Second step: Social Domain
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
                                    Brief introduction (name and surname)
                                </td>
                                <td class="w-2 p-4 text-center">
                                    <p id="s2_1_c">
                                        5
                                    </p>
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <input type="text" id="s2_1" name="s2_1" value="{{$interviewForm['s2_1']}}" min="0"
                                           max="5"
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                           required>
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <label for="s2_1_rs"
                                           class="block mb-2  font-bold text-gray-900 dark:text-white">{{$interviewForm['s2_1']*5}}
                                    </label>
                                </td>
                            </tr>
                            <tr
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td class="w-4 p-4 text-center">
                                    2
                                </td>
                                <td class="w-4 p-4 text-center">
                                    Age expression (knowing age)
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <p id="s2_2_c">
                                        4
                                    </p>
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <input type="text" id="s2_2" name="s2_2" value="{{$interviewForm['s2_2']}}" min="0"
                                           max="5"
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                           required>
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <label for="s2_2_rs"
                                           class="block mb-2  font-bold text-gray-900 dark:text-white">{{$interviewForm['s2_2']*4}}
                                    </label>
                                </td>
                            </tr>
                            <tr
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td class="w-4 p-4 text-center">
                                    3
                                </td>
                                <td class="w-4 p-4 text-center">
                                    Expressing ones gender
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <p id="s2_3_c">
                                        3
                                    </p>
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <input type="text" id="s2_3" name="s2_3" value="{{$interviewForm['s2_3']}}" min="0"
                                           max="3"
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                           required>
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <label for="s2_3_rs"
                                           class="block mb-2  font-bold text-gray-900 dark:text-white">{{$interviewForm['s2_3']*3}}
                                    </label>
                                </td>
                            </tr>
                            <tr
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td class="w-4 p-4 text-center">
                                    4
                                </td>
                                <td class="w-4 p-4 text-center">
                                    Knowing family members and family roles (asking the name and number of people in
                                    family)
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <p id="s2_4_c">
                                        4
                                    </p>
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <input type="text" id="s2_4" name="s2_4" value="{{$interviewForm['s2_4']}}" min="0"
                                           max="5"
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                           required>
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <label for="s2_4_rs"
                                           class="block mb-2  font-bold text-gray-900 dark:text-white">{{$interviewForm['s2_4']*4}}
                                    </label>
                                </td>
                            </tr>
                            <tr
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td class="w-4 p-4 text-center">
                                    5
                                </td>
                                <td class="w-4 p-4 text-center">
                                    Ability to communicate with others (greeting when entering a new environment)
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <p id="s2_5_c">
                                        4
                                    </p>
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <input type="text" id="s2_5" name="s2_5" value="{{$interviewForm['s2_5']}}" min="0"
                                           max="5"
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                           required>
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <label for="s2_5_rs"
                                           class="block mb-2  font-bold text-gray-900 dark:text-white">{{$interviewForm['s2_5']*4}}
                                    </label>
                                </td>
                            </tr>
                            <tr
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td class="w-4 p-4 text-center">
                                    6
                                </td>
                                <td class="w-4 p-4 text-center">
                                    Getting to know some jobs and social roles (teacher, police, student, cleric,
                                    doctor, etc.)
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <p id="s2_6_c">
                                        3
                                    </p>
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <input type="text" id="s2_6" name="s2_6" value="{{$interviewForm['s2_6']}}" min="0"
                                           max="3"
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                           required>
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <label for="s2_6_rs"
                                           class="block mb-2  font-bold text-gray-900 dark:text-white">{{$interviewForm['s2_6']*3}}
                                    </label>
                                </td>
                            </tr>
                            <tr
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td class="w-4 p-4 text-center">
                                    7
                                </td>
                                <td class="w-4 p-4 text-center">
                                    Knowing the address of the residence
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <p id="s2_7_c">
                                        2
                                    </p>
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <input type="text" id="s2_7" name="s2_7" value="{{$interviewForm['s2_7']}}" min="0"
                                           max="2"
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                           required>
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <label for="s2_7_rs"
                                           class="block mb-2  font-bold text-gray-900 dark:text-white">{{$interviewForm['s2_7']*2}}
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
                                           class="block mb-2  font-bold text-gray-900 dark:text-white">{{($interviewForm['s2_1']*5)+($interviewForm['s2_2']*4)+($interviewForm['s2_3']*3)+($interviewForm['s2_4']*4)+($interviewForm['s2_5']*4)+($interviewForm['s2_6']*3)+($interviewForm['s2_7']*2)}}
                                    </label>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div id="tab3" class="hidden">
                    <p class="font-bold mb-4">
                        Third step: Cognitive Domain
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
                                    Sorting objects from small to large (the novice should be asked to arrange objects
                                    of different sizes in proportion to small and large)
                                </td>
                                <td class="w-2 p-4 text-center">
                                    <p id="s3_1_c">
                                        5
                                    </p>
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <input type="text" id="s3_1" name="s3_1" value="{{$interviewForm['s3_1']}}" min="0"
                                           max="5"
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                           required>
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <label for="s3_1_rs"
                                           class="block mb-2  font-bold text-gray-900 dark:text-white">{{$interviewForm['s3_1']*5}}
                                    </label>
                                </td>
                            </tr>
                            <tr
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td class="w-4 p-4 text-center">
                                    2
                                </td>
                                <td class="w-4 p-4 text-center">
                                    Saying the names of household items (by showing the texts, the novice should say the
                                    names of the items)
                                </td>
                                <td class="w-2 p-4 text-center">
                                    <p id="s3_2_c">
                                        3
                                    </p>
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <input type="text" id="s3_2" name="s3_2" value="{{$interviewForm['s3_2']}}" min="0"
                                           max="5"
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                           required>
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <label for="s3_2_rs"
                                           class="block mb-2  font-bold text-gray-900 dark:text-white">{{$interviewForm['s3_2']*3}}
                                    </label>
                                </td>
                            </tr>
                            <tr
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td class="w-4 p-4 text-center">
                                    3
                                </td>
                                <td class="w-4 p-4 text-center">
                                    Sorting the cards of the same color (the novice separates the cards of the same
                                    color and puts them together)
                                </td>
                                <td class="w-2 p-4 text-center">
                                    <p id="s3_3_c">
                                        2
                                    </p>
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <input type="text" id="s3_3" name="s3_3" value="{{$interviewForm['s3_3']}}" min="0"
                                           max="5"
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                           required>
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <label for="s3_3_rs"
                                           class="block mb-2  font-bold text-gray-900 dark:text-white">{{$interviewForm['s3_3']*2}}
                                    </label>
                                </td>
                            </tr>
                            <tr
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td class="w-4 p-4 text-center">
                                    4
                                </td>
                                <td class="w-4 p-4 text-center">
                                    Completing an incomplete drawing (the learner completes an incomplete drawing, for
                                    example, a car without wheels)
                                </td>
                                <td class="w-2 p-4 text-center">
                                    <p id="s3_4_c">
                                        3
                                    </p>
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <input type="text" id="s3_4" name="s3_4" value="{{$interviewForm['s3_4']}}" min="0"
                                           max="5"
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                           required>
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <label for="s3_4_rs"
                                           class="block mb-2  font-bold text-gray-900 dark:text-white">{{$interviewForm['s3_4']*3}}
                                    </label>
                                </td>
                            </tr>
                            <tr
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td class="w-4 p-4 text-center">
                                    5
                                </td>
                                <td class="w-4 p-4 text-center">
                                    Knowing the body parts (showing the body parts card and asking their names)
                                </td>
                                <td class="w-2 p-4 text-center">
                                    <p id="s3_5_c">
                                        3
                                    </p>
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <input type="text" id="s3_5" name="s3_5" value="{{$interviewForm['s3_5']}}" min="0"
                                           max="5"
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                           required>
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <label for="s3_5_rs"
                                           class="block mb-2  font-bold text-gray-900 dark:text-white">{{$interviewForm['s3_5']*3}}
                                    </label>
                                </td>
                            </tr>
                            <tr
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td class="w-4 p-4 text-center">
                                    6
                                </td>
                                <td class="w-4 p-4 text-center">
                                    Recognition of colors, geometric shapes, animals and fruits
                                </td>
                                <td class="w-2 p-4 text-center">
                                    <p id="s3_6_c">
                                        4
                                    </p>
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <input type="text" id="s3_6" name="s3_6" value="{{$interviewForm['s3_6']}}" min="0"
                                           max="5"
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                           required>
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <label for="s3_6_rs"
                                           class="block mb-2  font-bold text-gray-900 dark:text-white">{{$interviewForm['s3_6']*4}}
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
                                           class="block mb-2  font-bold text-gray-900 dark:text-white">{{($interviewForm['s3_1']*5)+($interviewForm['s3_2']*3)+($interviewForm['s3_3']*2)+($interviewForm['s3_4']*3)+($interviewForm['s3_5']*3)+($interviewForm['s3_6']*4)}}
                                    </label>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div id="tab4" class="hidden">
                    <p class="font-bold mb-4">
                        Fourth step: Physical Health
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
                                    Measuring physical motor ability (checking the health of hands and feet, picking up
                                    an object with the hand, opening and closing a button, etc.)
                                </td>
                                <td class="w-2 p-4 text-center">
                                    <p id="s4_1_c">
                                        5
                                    </p>
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <input type="text" id="s4_1" name="s4_1" value="{{$interviewForm['s4_1']}}" min="0"
                                           max="5"
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                           required>
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <label for="s4_1_rs"
                                           class="block mb-2  font-bold text-gray-900 dark:text-white">{{$interviewForm['s4_1']*5}}
                                    </label>
                                </td>
                            </tr>
                            <tr
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td class="w-4 p-4 text-center">
                                    2
                                </td>
                                <td class="w-4 p-4 text-center">
                                    Vision test
                                <td class="w-2 p-4 text-center">
                                    <p id="s4_2_c">
                                        5
                                    </p>
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <input type="text" id="s4_2" name="s4_2" value="{{$interviewForm['s4_2']}}" min="0"
                                           max="5"
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                           required>
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <label for="s4_2_rs"
                                           class="block mb-2  font-bold text-gray-900 dark:text-white">{{$interviewForm['s4_2']*5}}
                                    </label>
                                </td>
                            </tr>
                            <tr
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td class="w-4 p-4 text-center">
                                    3
                                </td>
                                <td class="w-4 p-4 text-center">
                                    Hearing test
                                <td class="w-2 p-4 text-center">
                                    <p id="s4_3_c">
                                        4
                                    </p>
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <input type="text" id="s4_3" name="s4_3" value="{{$interviewForm['s4_3']}}" min="0"
                                           max="5"
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                           required>
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <label for="s4_3_rs"
                                           class="block mb-2  font-bold text-gray-900 dark:text-white">{{$interviewForm['s4_3']*4}}
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
                                           class="block mb-2  font-bold text-gray-900 dark:text-white">{{($interviewForm['s4_1']*5)+($interviewForm['s4_2']*5)+($interviewForm['s4_3']*4)}}
                                    </label>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <div class="mt-3">
                            <label for="s4_r"
                                   class="block mb-2  font-bold text-gray-900 dark:text-white">
                                Remarks</label>
                            <textarea id="s4_r" required placeholder="Enter remarks"
                                      name="s4_r"
                                      class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">{{$interviewForm['s4_r']}}</textarea>
                        </div>
                    </div>
                </div>
                <div id="tab5" class="hidden">
                    <p class="font-bold mb-4">
                        Fifth step: Communication, Literacy and learning
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
                                    Interest in discovering objects, people and the surrounding environment (play
                                    bricks, solve simple puzzles, sorting)
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <p id="s5_1_c">
                                        3
                                    </p>
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <input type="text" id="s5_1" name="s5_1" value="{{$interviewForm['s5_1']}}" min="0"
                                           max="5"
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                           required>
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <label for="s5_1_rs"
                                           class="block mb-2  font-bold text-gray-900 dark:text-white">{{$interviewForm['s5_1']*3}}
                                    </label>
                                </td>
                            </tr>
                            <tr
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td class="w-4 p-4 text-center">
                                    2
                                </td>
                                <td class="w-4 p-4 text-center">
                                    Interest in listening to stories and understanding the correct content (the initial
                                    part of a story should be told by the interviewer and then the novice should
                                    creatively and freely tell the rest of the story)
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <p id="s5_2_c">
                                        3
                                    </p>
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <input type="text" id="s5_2" name="s5_2" value="{{$interviewForm['s5_2']}}" min="0"
                                           max="5"
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                           required>
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <label for="s5_2_rs"
                                           class="block mb-2  font-bold text-gray-900 dark:text-white">{{$interviewForm['s5_2']*3}}
                                    </label>
                                </td>
                            </tr>
                            <tr
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td class="w-4 p-4 text-center">
                                    3
                                </td>
                                <td class="w-4 p-4 text-center">
                                    Ability to recite children's poetry or retell stories
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <p id="s5_3_c">
                                        2
                                    </p>
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <input type="text" id="s5_3" name="s5_3" value="{{$interviewForm['s5_3']}}" min="0"
                                           max="5"
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                           required>
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <label for="s5_3_rs"
                                           class="block mb-2  font-bold text-gray-900 dark:text-white">{{$interviewForm['s5_3']*2}}
                                    </label>
                                </td>
                            </tr>
                            <tr
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td class="w-4 p-4 text-center">
                                    4
                                </td>
                                <td class="w-4 p-4 text-center">
                                    The skill of using writing tools (coloring a picture)
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <p id="s5_4_c">
                                        1
                                    </p>
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <input type="text" id="s5_4" name="s5_4" value="{{$interviewForm['s5_4']}}" min="0"
                                           max="5"
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                           required>
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <label for="s5_4_rs"
                                           class="block mb-2  font-bold text-gray-900 dark:text-white">{{$interviewForm['s5_4']*1}}
                                    </label>
                                </td>
                            </tr>
                            <tr
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td class="w-4 p-4 text-center">
                                    5
                                </td>
                                <td class="w-4 p-4 text-center">
                                    familiarity with English language
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <p id="s5_5_c">
                                        2
                                    </p>
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <input type="text" id="s5_5" name="s5_5" value="{{$interviewForm['s5_5']}}" min="0"
                                           max="5"
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                           required>
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <label for="s5_5_rs"
                                           class="block mb-2  font-bold text-gray-900 dark:text-white">{{$interviewForm['s5_5']*2}}
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
                                           class="block mb-2  font-bold text-gray-900 dark:text-white">{{($interviewForm['s5_1']*3)+($interviewForm['s5_2']*3)+($interviewForm['s5_3']*2)+($interviewForm['s5_4']*1)+($interviewForm['s5_5']*2)}}
                                    </label>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div id="tab6" class="hidden">
                    <p class="font-bold mb-4">
                        Sixth step: Educational and Disciplinary Education (Family)
                    </p>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead
                                class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="p-4 text-center">
                                    Series
                                </th>
                                <th scope="col" class="p-4 text-center">
                                    Special points
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td class="w-4 p-4 text-center">
                                    1
                                </td>
                                <td class="w-full p-4 text-center">
                                    Preparing students to enter preschool (discussion, simulation, encouragement, etc.)
                                </td>
                            </tr>
                            <tr
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td class="w-4 p-4 text-center">
                                    2
                                </td>
                                <td class="w-full p-4 text-center">
                                    Basic training of friendship skills and correct interaction with peers and
                                    administrative staff
                                </td>
                            </tr>
                            <tr
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td class="w-4 p-4 text-center">
                                    3
                                </td>
                                <td class="w-full p-4 text-center">
                                    Training in the correct use of the toilet and how to notify the need to use the
                                    service
                                </td>
                            </tr>
                            <tr
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td class="w-4 p-4 text-center">
                                    4
                                </td>
                                <td class="w-full p-4 text-center">
                                    Personal hygiene (bathing, cutting nails, brushing teeth, washing hands and face,
                                    etc.)
                                </td>
                            </tr>
                            <tr
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td class="w-4 p-4 text-center">
                                    5
                                </td>
                                <td class="w-full p-4 text-center">
                                    Dress and appearance (clean and neat clothes, short and simple hairstyle, clean
                                    socks, etc.)
                                </td>
                            </tr>
                            <tr
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td class="w-4 p-4 text-center">
                                    6
                                </td>
                                <td class="w-full p-4 text-center">
                                    Set of replacement clothes (fixed or portable) and personal accessories (cups, hand
                                    towels, etc.)
                                </td>
                            </tr>
                            <tr
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td class="w-4 p-4 text-center">
                                    7
                                </td>
                                <td class="w-full p-4 text-center">
                                    Preparation of stationery bundles and labeling of all student supplies and
                                    stationery, daily checking of student's supplies and equipment
                                </td>
                            </tr>
                            <tr
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td class="w-4 p-4 text-center">
                                    8
                                </td>
                                <td class="w-full p-4 text-center">
                                    Not bringing and using unauthorized devices and equipment (mobile phones, digital
                                    devices, prohibited toys, sharp and dangerous devices, etc.)
                                </td>
                            </tr>
                            <tr
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td class="w-4 p-4 text-center">
                                    9
                                </td>
                                <td class="w-full p-4 text-center">
                                    Granting authority to the school regarding possession of objects and supplies left
                                    by the novice in preschool
                                </td>
                            </tr>
                            <tr
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td class="w-4 p-4 text-center">
                                    10
                                </td>
                                <td class="w-full p-4 text-center">
                                    Setting the sleep and food schedule (putting the child to sleep and waking up on
                                    time, serving a full breakfast)
                                </td>
                            </tr>
                            <tr
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td class="w-4 p-4 text-center">
                                    11
                                </td>
                                <td class="w-full p-4 text-center">
                                    Preparation of healthy and nutritious foods and juices (fruits, pieces of bread and
                                    cheese and vegetables, biscuits, natural juices, etc.) and avoiding harmful and
                                    bulky foods (chips, puffs, pastilles, etc.)
                                </td>
                            </tr>
                            <tr
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td class="w-4 p-4 text-center">
                                    12
                                </td>
                                <td class="w-full p-4 text-center">
                                    Delivery and departure on time (arrival before 8 and delivery at 12 for students
                                    without service and full coordination with the service for boarding and delivery
                                    from the service)
                                </td>
                            </tr>
                            <tr
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td class="w-4 p-4 text-center">
                                    13
                                </td>
                                <td class="w-full p-4 text-center">
                                    Special notification in case of inability to be present on time to deliver the child
                                    or delivery by a third party
                                </td>
                            </tr>
                            <tr
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td class="w-4 p-4 text-center">
                                    14
                                </td>
                                <td class="w-full p-4 text-center">
                                    Responsiveness and availability of parents when the new student is in school or
                                    programs (immediate response to all school communications)
                                </td>
                            </tr>
                            <tr
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td class="w-4 p-4 text-center">
                                    15
                                </td>
                                <td class="w-full p-4 text-center">
                                    Informing the preschool authorities about special physical and mental issues of the
                                    student (illness, family problems, etc.)
                                </td>
                            </tr>
                            <tr
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td class="w-4 p-4 text-center">
                                    16
                                </td>
                                <td class="w-full p-4 text-center">
                                    Supervision and monitoring of children's activities (tracking the completion of
                                    homework and studying lessons and controlling the amount of playing hours and the
                                    use of television and virtual space of the novice)
                                </td>
                            </tr>
                            <tr
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td class="w-4 p-4 text-center">
                                    17
                                </td>
                                <td class="w-full p-4 text-center">
                                    The rule of a calm atmosphere over the family environment (providing educational
                                    needs and a favorable psychological atmosphere and companionship with the novice)
                                </td>
                            </tr>
                            <tr
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td class="w-4 p-4 text-center">
                                    18
                                </td>
                                <td class="w-full p-4 text-center">
                                    Knowledge and skill prerequisites for preschool 1 (especially for new students
                                    without educational background and applicants for preschool 2)
                                </td>
                            </tr>
                            <tr
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td class="w-4 p-4 text-center">
                                    19
                                </td>
                                <td class="w-full p-4 text-center">
                                    Health quarantine (not sending new students to school during illness and infectious
                                    health issues (skin fungus, etc.)
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <div class="mt-5">
                            <label for="s6_1_r"
                                   class="block mb-2  font-bold text-gray-900 dark:text-white">
                                1- In case of interest to cooperate with the school, kindly mention the fields of
                                interest </label>
                            <textarea id="s6_1_r" required placeholder="Enter remarks"
                                      name="s6_1_r"
                                      class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">{{$interviewForm['s6_1_r']}}</textarea>
                        </div>
                        <div class="mt-5">
                            <label for="s6_2_r"
                                   class="block mb-2  font-bold text-gray-900 dark:text-white">
                                2- Salient features and strengths and weaknesses of the novice from the family point of
                                view: (in 3 short sentences)
                            </label>
                            <textarea id="s6_2_r" required placeholder="Enter remarks"
                                      name="s6_2_r"
                                      class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">{{$interviewForm['s6_2_r']}}</textarea>
                        </div>
                        <div class="mt-5">
                            <label for="s6_3_r"
                                   class="block mb-2  font-bold text-gray-900 dark:text-white">
                                3- Educational, cultural and educational expectations and concerns of the new family
                                from the school: (in 3 short sentences)
                            </label>
                            <textarea id="s6_3_r" required placeholder="Enter remarks"
                                      name="s6_3_r"
                                      class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">{{$interviewForm['s6_3_r']}}</textarea>
                        </div>
                    </div>
                </div>
                <div id="tab7" class="hidden">
                    <p class="font-bold mb-4">
                        Seventh step: Administrative-Financial Issues for Families (to be stated by the admission
                        officer)
                    </p>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead
                                class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="p-4 text-center">
                                    Series
                                </th>
                                <th scope="col" class="p-4 text-center">
                                    Special points
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td class="w-4 p-4 text-center">
                                    1
                                </td>
                                <td class="w-full p-4 text-center">
                                    Attending parents' and teachers’ meetings and special skill courses for parents
                                </td>
                            </tr>
                            <tr
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td class="w-4 p-4 text-center">
                                    2
                                </td>
                                <td class="w-full p-4 text-center">
                                    parents interaction and cooperation with the school (teachers, staff and students)
                                </td>
                            </tr>
                            <tr
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td class="w-4 p-4 text-center">
                                    3
                                </td>
                                <td class="w-full p-4 text-center">
                                    Timely notification of emergency cases (travel, illness, absence, early exams, etc.)
                                </td>
                            </tr>
                            <tr
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td class="w-4 p-4 text-center">
                                    4
                                </td>
                                <td class="w-full p-4 text-center">
                                    Joining school and class communication channels and following news and information
                                </td>
                            </tr>
                            <tr
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td class="w-4 p-4 text-center">
                                    5
                                </td>
                                <td class="w-full p-4 text-center">
                                    Financial discipline and good accounting (tuition, breakfast, services, books,
                                    programs and ceremonies, etc.)
                                </td>
                            </tr>
                            <tr
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td class="w-4 p-4 text-center">
                                    6
                                </td>
                                <td class="w-full p-4 text-center">
                                    Discipline in presenting documents (delivery on time, completing documents,
                                    updating, etc.)
                                </td>
                            </tr>
                            <tr
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td class="w-4 p-4 text-center">
                                    7
                                </td>
                                <td class="w-full p-4 text-center">
                                    Timely registration (registration period, interview, camps and ceremonies)
                                </td>
                            </tr>
                            <tr
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td class="w-4 p-4 text-center">
                                    8
                                </td>
                                <td class="w-full p-4 text-center">
                                    Studying and familiarizing with the disciplinary, educational, administrative, and
                                    financial rules of the complex (requesting parents to carefully study the
                                    regulations and rules of the school and warning about the responsibility of families
                                    if they do not read the rules carefully)
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <div class="mt-5">
                            <label for="s7_1"
                                   class="block mb-2  font-bold text-gray-900 dark:text-white">
                                1- The economic status of the family:
                            </label>
                            <select id="s7_1" name="s7_1"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    title="Select an option" required>
                                <option selected disabled value="">Select an option</option>
                                <option @if($interviewForm['s7_1']=='poor') selected @endif value="poor">poor</option>
                                <option @if($interviewForm['s7_1']=='average') selected @endif value="average">average
                                </option>
                                <option @if($interviewForm['s7_1']=='good') selected @endif value="good">good</option>
                                <option @if($interviewForm['s7_1']=='very good') selected @endif value="very good">very
                                    good
                                </option>
                            </select>
                        </div>

                        <div class="mt-5">
                            <label for="s7_2"
                                   class="block mb-2  font-bold text-gray-900 dark:text-white">
                                2- Is the family covered by the relief committee and charities:
                            </label>
                        </div>
                        <div class="flex">
                            <select id="s7_2" name="s7_2"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    title="Select an option" required>
                                <option selected disabled value="">Select an option</option>
                                <option @if($interviewForm['s7_2']=='No') selected @endif value="No">No</option>
                                <option @if($interviewForm['s7_2']=='Yes') selected @endif value="Yes">Yes</option>
                            </select>
                            <textarea id="s7_2_r" required placeholder="Name of the institution (If Yes)"
                                      name="s7_2_r"
                                      class="bg-gray-50 border border-gray-300 h-11 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">@if($interviewForm['s7_2_r']){{$interviewForm['s7_2_r']}}@endif</textarea>
                        </div>

                        <div class="mt-5">
                            <label for="s7_3"
                                   class="block mb-2  font-bold text-gray-900 dark:text-white">
                                3- Is the family willing to help poor students financially?
                            </label>
                            <select id="s7_3" name="s7_3"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    title="Select an option" required>
                                <option selected disabled value="">Select an option</option>
                                <option @if($interviewForm['s7_3']=='No') selected @endif value="No">No</option>
                                <option @if($interviewForm['s7_3']=='Yes') selected @endif value="Yes">Yes</option>
                            </select>
                        </div>
                        <div class="mt-5">
                            <label for="s7_4"
                                   class="block mb-2  font-bold text-gray-900 dark:text-white">
                                4- Is the family interested to participate financially in holding ceremonies and
                                extracurricular activities of the school?
                            </label>
                            <select id="s7_4" name="s7_4"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    title="Select an option" required>
                                <option selected disabled value="">Select an option</option>
                                <option @if($interviewForm['s7_4']=='No') selected @endif value="No">No</option>
                                <option @if($interviewForm['s7_4']=='Yes') selected @endif value="Yes">Yes</option>
                            </select>
                        </div>
                        <div class="mt-5">
                            <label for="s7_5"
                                   class="block mb-2  font-bold text-gray-900 dark:text-white">
                                5- Financial administrative suggestions and criticisms:</label>
                            <textarea id="s7_5" required placeholder="Enter remarks"
                                      name="s7_5"
                                      class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">@if($interviewForm['s7_5']){{$interviewForm['s7_5']}}@endif</textarea>
                        </div>
                    </div>
                </div>
                <div id="tab8" class="hidden">
                    <p class="font-bold mb-4">
                        Final step: Interview result
                    </p>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead
                                class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="w-1/3 p-4 text-center">
                                    Sum of points with coefficient
                                </th>
                                <td class="font-bold p-4 text-center">
                                    <p id="sum-of-points">
                                        {{
    ($interviewForm['s2_1']*5)+($interviewForm['s2_2']*4)+($interviewForm['s2_3']*3)+($interviewForm['s2_4']*4)+($interviewForm['s2_5']*4)+($interviewForm['s2_6']*3)+($interviewForm['s2_7']*2)+
    ($interviewForm['s3_1']*5)+($interviewForm['s3_2']*3)+($interviewForm['s3_3']*2)+($interviewForm['s3_4']*3)+($interviewForm['s3_5']*3)+($interviewForm['s3_6']*4)+
    ($interviewForm['s4_1']*5)+($interviewForm['s4_2']*5)+($interviewForm['s4_3']*4)+
    ($interviewForm['s5_1']*3)+($interviewForm['s5_2']*3)+($interviewForm['s5_3']*2)+($interviewForm['s5_4']*1)+($interviewForm['s5_5']*2)
    }}
                                    </p>
                                </td>
                                <td class=" p-4 text-center" rowspan="2">
                                    Acceptance conditions (to be completed if the conditional option is selected)
                                </td>
                            </tr>
                            <tr>
                                <th scope="col" class="w-1/3 p-4 text-center">
                                    Result (admitted, conditional, rejected)
                                </th>
                                <td class="font-bold p-4 text-center">

                                </td>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
            <!-- Next and Previous buttons -->
            <div class="flex justify-between px-4 pb-4">
                <button type="button" id="prevBtn"
                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 hidden">
                    Previous
                </button>
                <button type="button" id="nextBtn"
                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    Next
                </button>
            </div>
        </div>
    </div>
</div>


<script>
    $(function () {
        $('textarea, input, select').prop('disabled', true);
    });

    let currentTab = 1;
    showTab(currentTab);

    $('.tab-btn').click(function () {
        let tabId = $(this).data('tab');
        currentTab = Number(tabId.replace('tab', ''));
        showTab(currentTab);

        if (currentTab === 8) {
            $('#sum-of-points').text(
                parseFloat($('#s2_total').text()) + parseFloat($('#s3_total').text()) + parseFloat($('#s4_total').text()) + parseFloat($('#s5_total').text())
            );
        }
    });

    $('#prevBtn').click(function () {
        if (currentTab > 1 && ($('#s1_1_s').val() === 'Rejected' || $('#s1_2_s').val() === 'Rejected' || $('#s1_3_s').val() === 'Rejected' || $('#s1_4_s').val() === 'Rejected')) {
            currentTab -= 7;
            showTab(currentTab);
        } else {
            currentTab -= 1;
            showTab(currentTab);
        }
    });

    $('#nextBtn').click(function () {
        if (currentTab < 8) {
            currentTab += 1;
            showTab(currentTab);
            $('#sum-of-points').text(
                parseFloat($('#s2_total').text()) + parseFloat($('#s3_total').text()) + parseFloat($('#s4_total').text()) + parseFloat($('#s5_total').text())
            );
        }
        if ($('#s1_1_s').val() === 'Rejected' || $('#s1_2_s').val() === 'Rejected' || $('#s1_3_s').val() === 'Rejected' || $('#s1_4_s').val() === 'Rejected') {
            $('#tab2, #tab3, #tab4, #tab5, #tab6, #tab7').addClass('hidden');
            $('#step-2-btn, #step-3-btn, #step-4-btn, #step-5-btn, #step-6-btn, #step-7-btn').addClass('hidden');
            currentTab = 8;
            showTab(currentTab);
        } else if ($('#s1_1_s').val() !== 'Rejected' && $('#s1_2_s').val() !== 'Rejected' && $('#s1_3_s').val() !== 'Rejected' && $('#s1_4_s').val() !== 'Rejected') {
            $('#tab2, #tab3, #tab4, #tab5, #tab6, #tab7').removeClass('hidden');
            $('#step-2-btn, #step-3-btn, #step-4-btn, #step-5-btn, #step-6-btn, #step-7-btn').removeClass('hidden');
            showTab(currentTab);
        }
    });

    function showTab(tabIndex) {
        $('.tab-btn').removeClass('bg-gray-900').addClass('bg-gray-700');
        $('#tab1, #tab2, #tab3, #tab4, #tab5, #tab6, #tab7, #tab8').addClass('hidden');
        $('#tab' + tabIndex).removeClass('hidden');
        $('[data-tab="tab' + tabIndex + '"]').removeClass('bg-gray-700').addClass('bg-gray-900');

        if (tabIndex === 1) {
            $('#nextBtn').removeClass('hidden');
            $('#prevBtn').addClass('hidden');
            $('#last-step').addClass('hidden');
        } else if (tabIndex === 8) {
            $('#nextBtn').addClass('hidden');
            $('#prevBtn').removeClass('hidden');
            $('#last-step').removeClass('hidden');
        } else {
            $('#nextBtn').removeClass('hidden');
            $('#prevBtn').removeClass('hidden');
            $('#last-step').addClass('hidden');
        }
    }
</script>
