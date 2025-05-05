<div class="container mx-auto mt-8">
    <div class="flex justify-center">
        <div class="border border-gray-700 rounded-lg w-full ">
            <div class="p-4">
                <div class="mb-8" id="tab1">
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
                        <select id="s1_1_s" name="s1_1_s" disabled
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                title="Select an option" required>
                            <option @if($interviewFields['s1_1_s']=='Admitted') selected @endif value="Admitted">
                                Admitted
                            </option>
                            <option @if($interviewFields['s1_1_s']=='Rejected') selected @endif value="Rejected">
                                Rejected
                            </option>
                        </select>
                        <div class="mt-3">
                            <label for="s1_1_r"
                                   class="block mb-2  font-bold text-gray-900 dark:text-white">
                                Remarks</label>
                            <textarea id="s1_1_r" required placeholder="Enter remarks" disabled
                                      name="s1_1_r"
                                      class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">{{$interviewFields['s1_1_r']}}</textarea>
                        </div>
                    </div>
                    <div class="mt-5">
                        <label for="s1_2_s"
                               class="block mb-2  font-bold text-gray-900 dark:text-white">
                            2-Severe emotional dependence:
                        </label>
                        <select id="s1_2_s" name="s1_2_s" disabled
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                title="Select an option" required>
                            <option @if($interviewFields['s1_2_s']=='Admitted') selected @endif value="Admitted">
                                Admitted
                            </option>
                            <option @if($interviewFields['s1_2_s']=='Rejected') selected @endif value="Rejected">
                                Rejected
                            </option>
                        </select>
                        <div class="mt-3">
                            <label for="s1_2_r"
                                   class="block mb-2  font-bold text-gray-900 dark:text-white">
                                Remarks</label>
                            <textarea id="s1_2_r" required placeholder="Enter remarks"
                                      name="s1_2_r" disabled
                                      class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">{{$interviewFields['s1_2_r']}}</textarea>
                        </div>
                    </div>
                    <div class="mt-5">
                        <label for="s1_3_s"
                               class="block mb-2  font-bold text-gray-900 dark:text-white">
                            3- Severe physical disability:
                        </label>
                        <select id="s1_3_s" name="s1_3_s" disabled
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                title="Select an option" required>
                            <option @if($interviewFields['s1_3_s']=='Admitted') selected @endif value="Admitted">
                                Admitted
                            </option>
                            <option @if($interviewFields['s1_3_s']=='Rejected') selected @endif value="Rejected">
                                Rejected
                            </option>
                        </select>
                        <div class="mt-3">
                            <label for="s1_3_r"
                                   class="block mb-2  font-bold text-gray-900 dark:text-white">
                                Remarks</label>
                            <textarea id="s1_3_r" required placeholder="Enter remarks" disabled
                                      name="s1_3_r"
                                      class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">{{$interviewFields['s1_3_r']}}</textarea>
                        </div>
                    </div>
                    <div class="mt-5">
                        <label for="s1_4_s"
                               class="block mb-2  font-bold text-gray-900 dark:text-white">
                            4.Special cases (disciplinary/ideological/family etc.):
                        </label>
                        <select id="s1_4_s" name="s1_4_s" disabled
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                title="Select an option" required>
                            <option @if($interviewFields['s1_4_s']=='Admitted') selected @endif value="Admitted">
                                Admitted
                            </option>
                            <option @if($interviewFields['s1_4_s']=='Rejected') selected @endif value="Rejected">
                                Rejected
                            </option>
                        </select>
                        <div class="mt-3">
                            <label for="s1_4_r"
                                   class="block mb-2  font-bold text-gray-900 dark:text-white">
                                Remarks</label>
                            <textarea id="s1_4_r" required placeholder="Enter remarks"
                                      name="s1_4_r" disabled
                                      class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">{{$interviewFields['s1_4_r']}}</textarea>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="mb-8" id="tab2">
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
                                        2
                                    </p>
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <input type="text" id="s2_1" name="s2_1" value="{{$interviewFields['s2_1']}}"
                                           min="0" max="5" disabled
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                           required>
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
                                    Age expression (knowing age)
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <p id="s2_2_c">
                                        2
                                    </p>
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <input type="text" id="s2_2" name="s2_2" value="{{$interviewFields['s2_2']}}"
                                           min="0" max="5" disabled
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                           required>
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
                                    Expressing ones gender
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <p id="s2_3_c">
                                        2
                                    </p>
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <input type="text" id="s2_3" name="s2_3" value="{{$interviewFields['s2_3']}}"
                                           min="0" max="3" disabled
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                           required>
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <label for="s2_3_rs"
                                           class="block mb-2  font-bold text-gray-900 dark:text-white">{{$interviewFields['s2_3']*2}}
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
                                        2
                                    </p>
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <input type="text" id="s2_4" name="s2_4" value="{{$interviewFields['s2_4']}}"
                                           min="0" max="5" disabled
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                           required>
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <label for="s2_4_rs"
                                           class="block mb-2  font-bold text-gray-900 dark:text-white">{{$interviewFields['s2_4']*2}}
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
                                        1
                                    </p>
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <input type="text" id="s2_5" name="s2_5" value="{{$interviewFields['s2_5']}}"
                                           min="0" max="5" disabled
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                           required>
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <label for="s2_5_rs"
                                           class="block mb-2  font-bold text-gray-900 dark:text-white">{{$interviewFields['s2_5']*1}}
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
                                        1
                                    </p>
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <input type="text" id="s2_6" name="s2_6" value="{{$interviewFields['s2_6']}}"
                                           min="0" max="3" disabled
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                           required>
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <label for="s2_6_rs"
                                           class="block mb-2  font-bold text-gray-900 dark:text-white">{{$interviewFields['s2_6']*1}}
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
                                    <input type="text" id="s2_7" name="s2_7" value="{{$interviewFields['s2_7']}}"
                                           min="0" max="2" disabled
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                           required>
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <label for="s2_7_rs"
                                           class="block mb-2  font-bold text-gray-900 dark:text-white">{{$interviewFields['s2_7']*2}}
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
                                           class="block mb-2  font-bold text-gray-900 dark:text-white">
                                        {{($interviewFields['s2_1']*2)+($interviewFields['s2_2']*2)+($interviewFields['s2_3']*2)+($interviewFields['s2_4']*2)+($interviewFields['s2_5']*1)+($interviewFields['s2_6']*1)+($interviewFields['s2_7']*2)}}
                                    </label>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <hr>
                <div class="mb-8" id="tab3">
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
                                        2
                                    </p>
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <input type="text" id="s3_1" name="s3_1" value="{{$interviewFields['s3_1']}}"
                                           min="0" max="5" disabled
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                           required>
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
                                    Saying the names of household items (by showing the texts, the novice should say the
                                    names of the items)
                                </td>
                                <td class="w-2 p-4 text-center">
                                    <p id="s3_2_c">
                                        2
                                    </p>
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <input type="text" id="s3_2" name="s3_2" value="{{$interviewFields['s3_2']}}"
                                           min="0" max="5" disabled
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                           required>
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
                                    Sorting the cards of the same color (the novice separates the cards of the same
                                    color and puts them together)
                                </td>
                                <td class="w-2 p-4 text-center">
                                    <p id="s3_3_c">
                                        2
                                    </p>
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <input type="text" id="s3_3" name="s3_3" value="{{$interviewFields['s3_3']}}"
                                           min="0" max="5" disabled
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                           required>
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
                                    Completing an incomplete drawing (the learner completes an incomplete drawing, for
                                    example, a car without wheels)
                                </td>
                                <td class="w-2 p-4 text-center">
                                    <p id="s3_4_c">
                                        2
                                    </p>
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <input type="text" id="s3_4" name="s3_4" value="{{$interviewFields['s3_4']}}"
                                           min="0" max="5" disabled
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                           required>
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
                                    Knowing the body parts (showing the body parts card and asking their names)
                                </td>
                                <td class="w-2 p-4 text-center">
                                    <p id="s3_5_c">
                                        2
                                    </p>
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <input type="text" id="s3_5" name="s3_5" value="{{$interviewFields['s3_5']}}"
                                           min="0" max="5" disabled
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                           required>
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <label for="s3_5_rs"
                                           class="block mb-2  font-bold text-gray-900 dark:text-white">{{$interviewFields['s3_5']*2}}
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
                                        2
                                    </p>
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <input type="text" id="s3_6" name="s3_6" value="{{$interviewFields['s3_6']}}"
                                           min="0" max="5" disabled
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                           required>
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <label for="s3_6_rs"
                                           class="block mb-2  font-bold text-gray-900 dark:text-white">{{$interviewFields['s3_6']*2}}
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
                                           class="block mb-2  font-bold text-gray-900 dark:text-white">{{($interviewFields['s3_1']*2)+($interviewFields['s3_2']*2)+($interviewFields['s3_3']*2)+($interviewFields['s3_4']*2)+($interviewFields['s3_5']*2)+($interviewFields['s3_6']*2)}}
                                    </label>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <hr>
                <div class="mb-8" id="tab4">
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
                                        2
                                    </p>
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <input type="text" id="s4_1" name="s4_1" value="{{$interviewFields['s4_1']}}"
                                           min="0" max="5" disabled
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                           required>
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
                                    Vision test
                                <td class="w-2 p-4 text-center">
                                    <p id="s4_2_c">
                                        2
                                    </p>
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <input type="text" id="s4_2" name="s4_2" value="{{$interviewFields['s4_2']}}"
                                           min="0" max="5" disabled
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                           required>
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
                                    Hearing test
                                <td class="w-2 p-4 text-center">
                                    <p id="s4_3_c">
                                        2
                                    </p>
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <input type="text" id="s4_3" name="s4_3" value="{{$interviewFields['s4_3']}}"
                                           min="0" max="5" disabled
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                           required>
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <label for="s4_3_rs"
                                           class="block mb-2  font-bold text-gray-900 dark:text-white">{{$interviewFields['s4_3']*2}}
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
                                           class="block mb-2  font-bold text-gray-900 dark:text-white">{{($interviewFields['s4_1']*2)+($interviewFields['s4_2']*2)+($interviewFields['s4_3']*2)}}
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
                                      name="s4_r" disabled
                                      class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">{{$interviewFields['s4_r']}}</textarea>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="mb-8" id="tab5">
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
                                        2
                                    </p>
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <input type="text" id="s5_1" name="s5_1" value="{{$interviewFields['s5_1']}}"
                                           min="0" max="5" disabled
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                           required>
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <label for="s5_1_rs"
                                           class="block mb-2  font-bold text-gray-900 dark:text-white">{{$interviewFields['s5_1']*2}}
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
                                        2
                                    </p>
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <input type="text" id="s5_2" name="s5_2" value="{{$interviewFields['s5_2']}}"
                                           min="0" max="5" disabled
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                           required>
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <label for="s5_2_rs"
                                           class="block mb-2  font-bold text-gray-900 dark:text-white">{{$interviewFields['s5_2']*2}}
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
                                    <input type="text" id="s5_3" name="s5_3" value="{{$interviewFields['s5_3']}}"
                                           min="0" max="5" disabled
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                           required>
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <label for="s5_3_rs"
                                           class="block mb-2  font-bold text-gray-900 dark:text-white">{{$interviewFields['s5_3']*2}}
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
                                        2
                                    </p>
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <input type="text" id="s5_4" name="s5_4" value="{{$interviewFields['s5_4']}}"
                                           min="0" max="5" disabled
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                           required>
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <label for="s5_4_rs"
                                           class="block mb-2  font-bold text-gray-900 dark:text-white">{{$interviewFields['s5_4']*2}}
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
                                    <input type="text" id="s5_5" name="s5_5" value="{{$interviewFields['s5_5']}}"
                                           min="0" max="5" disabled
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                           required>
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <label for="s5_5_rs"
                                           class="block mb-2  font-bold text-gray-900 dark:text-white">{{$interviewFields['s5_5']*2}}
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
                                           class="block mb-2  font-bold text-gray-900 dark:text-white">{{($interviewFields['s5_1']*2)+($interviewFields['s5_2']*2)+($interviewFields['s5_3']*2)+($interviewFields['s5_4']*2)+($interviewFields['s5_5']*2)}}
                                    </label>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <script>
                            // Function to calculate product and update cell
                            function updateProduct(row) {
                                // Get the coefficient value from the third column
                                const coefficient = parseInt(row.querySelector('td:nth-child(3) p').textContent.trim());

                                // Get the user-entered rating from the input field in the fourth column
                                const ratingInput = row.querySelector('td:nth-child(4) input');
                                const rating = ratingInput.value.trim() === '' ? 0 : parseInt(ratingInput.value.trim());

                                // Calculate the product of coefficient and rating
                                // Display the product in the fifth column
                                row.querySelector('td:nth-child(5) label').textContent = coefficient * rating;

                                // Update total
                                updateTotal();
                            }

                            // Function to update total
                            function updateTotal() {
                                // Get all the product values from the fifth column
                                const products = Array.from(document.querySelectorAll('#table5 tbody td:nth-child(5) label')).map(label => {
                                    const value = parseInt(label.textContent);
                                    return isNaN(value) ? 0 : value;
                                });

                                // Calculate total
                                // Display total in the last row
                                document.getElementById('s5_total').textContent = products.reduce((acc, value) => acc + value, 0);

                                // Update column totals
                                const columnTotals = Array.from(document.querySelectorAll('#table5 tbody tr:not(:last-child) td:not(:last-child):not(:first-child)')).reduce((acc, cell, index) => {
                                    const value = cell.textContent.trim() === '' ? 0 : parseInt(cell.textContent);
                                    const columnIndex = index % 4;
                                    acc[columnIndex] = (acc[columnIndex] || 0) + value;
                                    return acc;
                                }, []);

                                // Display column totals
                                columnTotals.forEach((total, index) => {
                                    document.getElementById('column_total_' + (index + 1)).textContent = total;
                                });
                            }

                            // Get all the rows in the table, excluding the header row
                            const rows = document.querySelectorAll('#table5 tbody tr');

                            // Loop through each row
                            rows.forEach(row => {
                                // Add event listener to input field in the fourth column
                                const input = row.querySelector('td:nth-child(4) input');
                                input.addEventListener('input', () => {
                                    updateProduct(row);
                                });
                            });

                            // Call updateTotal function initially
                            updateTotal();
                        </script>

                    </div>
                </div>
                <hr>
                <div class="mb-8" id="tab6">
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
                                <td rowspan="2" class="w-4 p-4 text-center">
                                    1
                                </td>
                                <td class="w-full p-4 text-center">
                                    Preparing students to enter preschool (discussion, simulation, encouragement, etc.)
                                </td>
                            </tr>
                            <tr
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td colspan="2" class="w-full p-4 text-center">
                                <textarea id="s6_table_1" required placeholder="Enter remarks"
                                          name="s6_table_1" disabled
                                          class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">{{$interviewFields['s6_table_1']}}</textarea>
                                </td>
                            </tr>
                            <tr
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td rowspan="2" class="w-4 p-4 text-center">
                                    2
                                </td>
                                <td class="w-full p-4 text-center">
                                    Basic training of friendship skills and correct interaction with peers and
                                    administrative staff
                                </td>
                            </tr>
                            <tr
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td colspan="2" class="w-full p-4 text-center">
                                <textarea id="s6_table_2" required placeholder="Enter remarks"
                                          name="s6_table_2" disabled
                                          class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">{{$interviewFields['s6_table_2']}}</textarea>
                                </td>
                            </tr>
                            <tr
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td rowspan="2" class="w-4 p-4 text-center">
                                    3
                                </td>
                                <td class="w-full p-4 text-center">
                                    Training in the correct use of the toilet and how to notify the need to use the
                                    service
                                </td>
                            </tr>
                            <tr
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td colspan="2" class="w-full p-4 text-center">
                                <textarea id="s6_table_3" required placeholder="Enter remarks"
                                          name="s6_table_3" disabled
                                          class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">{{$interviewFields['s6_table_3']}}</textarea>
                                </td>
                            </tr>
                            <tr
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td rowspan="2" class="w-4 p-4 text-center">
                                    4
                                </td>
                                <td class="w-full p-4 text-center">
                                    Personal hygiene (bathing, cutting nails, brushing teeth, washing hands and face,
                                    etc.)
                                </td>
                            </tr>
                            <tr
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td colspan="2" class="w-full p-4 text-center">
                                <textarea id="s6_table_4" required placeholder="Enter remarks"
                                          name="s6_table_4" disabled
                                          class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">{{$interviewFields['s6_table_4']}}</textarea>
                                </td>
                            </tr>
                            <tr
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td rowspan="2" class="w-4 p-4 text-center">
                                    5
                                </td>
                                <td class="w-full p-4 text-center">
                                    Dress and appearance (clean and neat clothes, short and simple hairstyle, clean
                                    socks, etc.)
                                </td>
                            </tr>
                            <tr
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td colspan="2" class="w-full p-4 text-center">
                                <textarea id="s6_table_5" required placeholder="Enter remarks"
                                          name="s6_table_5" disabled
                                          class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">{{$interviewFields['s6_table_5']}}</textarea>
                                </td>
                            </tr>
                            <tr
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td rowspan="2" class="w-4 p-4 text-center">
                                    6
                                </td>
                                <td class="w-full p-4 text-center">
                                    Set of replacement clothes (fixed or portable) and personal accessories (cups, hand
                                    towels, etc.)
                                </td>
                            </tr>
                            <tr
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td colspan="2" class="w-full p-4 text-center">
                                <textarea id="s6_table_6" required placeholder="Enter remarks"
                                          name="s6_table_6" disabled
                                          class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">{{$interviewFields['s6_table_6']}}</textarea>
                                </td>
                            </tr>
                            <tr
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td rowspan="2" class="w-4 p-4 text-center">
                                    7
                                </td>
                                <td class="w-full p-4 text-center">
                                    Preparation of stationery bundles and labeling of all student supplies and
                                    stationery, daily checking of student's supplies and equipment
                                </td>
                            </tr>
                            <tr
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td colspan="2" class="w-full p-4 text-center">
                                <textarea id="s6_table_7" required placeholder="Enter remarks"
                                          name="s6_table_7" disabled
                                          class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">{{$interviewFields['s6_table_7']}}</textarea>
                                </td>
                            </tr>
                            <tr
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td rowspan="2" class="w-4 p-4 text-center">
                                    8
                                </td>
                                <td class="w-full p-4 text-center">
                                    Not bringing and using unauthorized devices and equipment (mobile phones, digital
                                    devices, prohibited toys, sharp and dangerous devices, etc.)
                                </td>
                            </tr>
                            <tr
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td colspan="2" class="w-full p-4 text-center">
                                <textarea id="s6_table_8" required placeholder="Enter remarks"
                                          name="s6_table_8" disabled
                                          class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">{{$interviewFields['s6_table_8']}}</textarea>
                                </td>
                            </tr>
                            <tr
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td rowspan="2" class="w-4 p-4 text-center">
                                    9
                                </td>
                                <td class="w-full p-4 text-center">
                                    Granting authority to the school regarding possession of objects and supplies left
                                    by the novice in preschool
                                </td>
                            </tr>
                            <tr
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td colspan="2" class="w-full p-4 text-center">
                                <textarea id="s6_table_9" required placeholder="Enter remarks"
                                          name="s6_table_9" disabled
                                          class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">{{$interviewFields['s6_table_9']}}</textarea>
                                </td>
                            </tr>
                            <tr
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td rowspan="2" class="w-4 p-4 text-center">
                                    10
                                </td>
                                <td class="w-full p-4 text-center">
                                    Setting the sleep and food schedule (putting the child to sleep and waking up on
                                    time, serving a full breakfast)
                                </td>
                            </tr>
                            <tr
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td colspan="2" class="w-full p-4 text-center">
                                <textarea id="s6_table_10" required placeholder="Enter remarks"
                                          name="s6_table_10" disabled
                                          class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">{{$interviewFields['s6_table_10']}}</textarea>
                                </td>
                            </tr>
                            <tr
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td rowspan="2" class="w-4 p-4 text-center">
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
                                <td colspan="2" class="w-full p-4 text-center">
                                <textarea id="s6_table_11" required placeholder="Enter remarks"
                                          name="s6_table_11" disabled
                                          class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">{{$interviewFields['s6_table_11']}}</textarea>
                                </td>
                            </tr>
                            <tr
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td rowspan="2" class="w-4 p-4 text-center">
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
                                <td colspan="2" class="w-full p-4 text-center">
                                <textarea id="s6_table_12" required placeholder="Enter remarks"
                                          name="s6_table_12" disabled
                                          class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">{{$interviewFields['s6_table_12']}}</textarea>
                                </td>
                            </tr>
                            <tr
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td rowspan="2" class="w-4 p-4 text-center">
                                    13
                                </td>
                                <td class="w-full p-4 text-center">
                                    Special notification in case of inability to be present on time to deliver the child
                                    or delivery by a third party
                                </td>
                            </tr>
                            <tr
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td colspan="2" class="w-full p-4 text-center">
                                <textarea id="s6_table_13" required placeholder="Enter remarks"
                                          name="s6_table_13" disabled
                                          class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">{{$interviewFields['s6_table_13']}}</textarea>
                                </td>
                            </tr>
                            <tr
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td rowspan="2" class="w-4 p-4 text-center">
                                    14
                                </td>
                                <td class="w-full p-4 text-center">
                                    Responsiveness and availability of parents when the new student is in school or
                                    programs (immediate response to all school communications)
                                </td>
                            </tr>
                            <tr
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td colspan="2" class="w-full p-4 text-center">
                                <textarea id="s6_table_14" required placeholder="Enter remarks"
                                          name="s6_table_14" disabled
                                          class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">{{$interviewFields['s6_table_14']}}</textarea>
                                </td>
                            </tr>
                            <tr
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td rowspan="2" class="w-4 p-4 text-center">
                                    15
                                </td>
                                <td class="w-full p-4 text-center">
                                    Informing the preschool authorities about special physical and mental issues of the
                                    student (illness, family problems, etc.)
                                </td>
                            </tr>
                            <tr
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td colspan="2" class="w-full p-4 text-center">
                                <textarea id="s6_table_15" required placeholder="Enter remarks"
                                          name="s6_table_15" disabled
                                          class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">{{$interviewFields['s6_table_15']}}</textarea>
                                </td>
                            </tr>
                            <tr
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td rowspan="2" class="w-4 p-4 text-center">
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
                                <td colspan="2" class="w-full p-4 text-center">
                                <textarea id="s6_table_16" required placeholder="Enter remarks"
                                          name="s6_table_16" disabled
                                          class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">{{$interviewFields['s6_table_16']}}</textarea>
                                </td>
                            </tr>
                            <tr
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td rowspan="2" class="w-4 p-4 text-center">
                                    17
                                </td>
                                <td class="w-full p-4 text-center">
                                    The rule of a calm atmosphere over the family environment (providing educational
                                    needs and a favorable psychological atmosphere and companionship with the novice)
                                </td>
                            </tr>
                            <tr
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td colspan="2" class="w-full p-4 text-center">
                                <textarea id="s6_table_17" required placeholder="Enter remarks"
                                          name="s6_table_17" disabled
                                          class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">{{$interviewFields['s6_table_17']}}</textarea>
                                </td>
                            </tr>
                            <tr
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td rowspan="2" class="w-4 p-4 text-center">
                                    18
                                </td>
                                <td class="w-full p-4 text-center">
                                    Knowledge and skill prerequisites for preschool 1 (especially for new students
                                    without educational background and applicants for preschool 2)
                                </td>
                            </tr>
                            <tr
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td colspan="2" class="w-full p-4 text-center">
                                <textarea id="s6_table_18" required placeholder="Enter remarks"
                                          name="s6_table_18" disabled
                                          class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">{{$interviewFields['s6_table_18']}}</textarea>
                                </td>
                            </tr>
                            <tr
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td rowspan="2" class="w-4 p-4 text-center">
                                    19
                                </td>
                                <td class="w-full p-4 text-center">
                                    Health quarantine (not sending new students to school during illness and infectious
                                    health issues (skin fungus, etc.)
                                </td>
                            </tr>
                            <tr
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td colspan="2" class="w-full p-4 text-center">
                                <textarea id="s6_table_19" required placeholder="Enter remarks"
                                          name="s6_table_19" disabled
                                          class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">{{$interviewFields['s6_table_19']}}</textarea>
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
                                      name="s6_1_r" disabled
                                      class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">{{$interviewFields['s6_1_r']}}</textarea>
                        </div>
                        <div class="mt-5">
                            <label for="s6_2_r"
                                   class="block mb-2  font-bold text-gray-900 dark:text-white">
                                2- Salient features and strengths and weaknesses of the novice from the family point of
                                view: (in 3 short sentences)
                            </label>
                            <textarea id="s6_2_r" required placeholder="Enter remarks"
                                      name="s6_2_r" disabled
                                      class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">{{$interviewFields['s6_2_r']}}</textarea>
                        </div>
                        <div class="mt-5">
                            <label for="s6_3_r"
                                   class="block mb-2  font-bold text-gray-900 dark:text-white">
                                3- Educational, cultural and educational expectations and concerns of the new family
                                from the school: (in 3 short sentences)
                            </label>
                            <textarea id="s6_3_r" required placeholder="Enter remarks"
                                      name="s6_3_r" disabled
                                      class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">{{$interviewFields['s6_3_r']}}</textarea>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="mb-8" id="tab7">
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
                                <td rowspan="2" class="w-4 p-4 text-center">
                                    1
                                </td>
                                <td class="w-full p-4 text-center">
                                    Attending parents' and teachers’ meetings and special skill courses for parents
                                </td>
                            </tr>
                            <tr
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td colspan="2" class="w-full p-4 text-center">
                                <textarea id="s7_table_1" required placeholder="Enter remarks" disabled
                                          name="s7_table_1"
                                          class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">{{$interviewFields['s7_table_1']}}</textarea>
                                </td>
                            </tr>
                            <tr
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td rowspan="2" class="w-4 p-4 text-center">
                                    2
                                </td>
                                <td class="w-full p-4 text-center">
                                    parents interaction and cooperation with the school (teachers, staff and students)
                                </td>
                            </tr>
                            <tr
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td colspan="2" class="w-full p-4 text-center">
                                <textarea id="s7_table_2" required placeholder="Enter remarks" disabled
                                          name="s7_table_2"
                                          class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">{{$interviewFields['s7_table_2']}}</textarea>
                                </td>
                            </tr>
                            <tr
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td rowspan="2" class="w-4 p-4 text-center">
                                    3
                                </td>
                                <td class="w-full p-4 text-center">
                                    Timely notification of emergency cases (travel, illness, absence, early exams, etc.)
                                </td>
                            </tr>
                            <tr
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td colspan="2" class="w-full p-4 text-center">
                                <textarea id="s7_table_3" required placeholder="Enter remarks" disabled
                                          name="s7_table_3"
                                          class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">{{$interviewFields['s7_table_3']}}</textarea>
                                </td>
                            </tr>
                            <tr
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td rowspan="2" class="w-4 p-4 text-center">
                                    4
                                </td>
                                <td class="w-full p-4 text-center">
                                    Joining school and class communication channels and following news and information
                                </td>
                            </tr>
                            <tr
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td colspan="2" class="w-full p-4 text-center">
                                <textarea id="s7_table_4" required placeholder="Enter remarks" disabled
                                          name="s7_table_4"
                                          class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">{{$interviewFields['s7_table_4']}}</textarea>
                                </td>
                            </tr>
                            <tr
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td rowspan="2" class="w-4 p-4 text-center">
                                    5
                                </td>
                                <td class="w-full p-4 text-center">
                                    Financial discipline and good accounting (tuition, breakfast, services, books,
                                    programs and ceremonies, etc.)
                                </td>
                            </tr>
                            <tr
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td colspan="2" class="w-full p-4 text-center">
                                <textarea id="s7_table_5" required placeholder="Enter remarks" disabled
                                          name="s7_table_5"
                                          class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">{{$interviewFields['s7_table_5']}}</textarea>
                                </td>
                            </tr>
                            <tr
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td rowspan="2" class="w-4 p-4 text-center">
                                    6
                                </td>
                                <td class="w-full p-4 text-center">
                                    Discipline in presenting documents (delivery on time, completing documents,
                                    updating, etc.)
                                </td>
                            </tr>
                            <tr
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td colspan="2" class="w-full p-4 text-center">
                                <textarea id="s7_table_6" required placeholder="Enter remarks" disabled
                                          name="s7_table_6"
                                          class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">{{$interviewFields['s7_table_6']}}</textarea>
                                </td>
                            </tr>
                            <tr
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td rowspan="2" class="w-4 p-4 text-center">
                                    7
                                </td>
                                <td class="w-full p-4 text-center">
                                    Timely registration (registration period, interview, camps and ceremonies)
                                </td>
                            </tr>
                            <tr
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td colspan="2" class="w-full p-4 text-center">
                                <textarea id="s7_table_7" required placeholder="Enter remarks" disabled
                                          name="s7_table_7"
                                          class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">{{$interviewFields['s7_table_7']}}</textarea>
                                </td>
                            </tr>
                            <tr
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td rowspan="2" class="w-4 p-4 text-center">
                                    8
                                </td>
                                <td class="w-full p-4 text-center">
                                    Studying and familiarizing with the disciplinary, educational, administrative, and
                                    financial rules of the complex (requesting parents to carefully study the
                                    regulations and rules of the school and warning about the responsibility of families
                                    if they do not read the rules carefully)
                                </td>
                            </tr>
                            <tr
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td colspan="2" class="w-full p-4 text-center">
                                <textarea id="s7_table_8" required placeholder="Enter remarks" disabled
                                          name="s7_table_8"
                                          class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">{{$interviewFields['s7_table_8']}}</textarea>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <div class="mt-5">
                            <label for="foreign_school"
                                   class="block mb-2  font-bold text-gray-900 dark:text-white">
                                * Did the student study at a foreign school the previous year?
                            </label>
                            <select id="foreign_school" name="foreign_school" disabled
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    title="Select an option">
                                <option @if(@$interviewFields['foreign_school']=='No') selected @endif value="No">No
                                </option>
                                <option @if(@$interviewFields['foreign_school']=='Yes') selected @endif value="Yes">
                                    Yes
                                </option>
                            </select>
                        </div>
                        <div class="mt-5">
                            <label for="s7_1"
                                   class="block mb-2  font-bold text-gray-900 dark:text-white">
                                1- The economic status of the family:
                            </label>
                            <select id="s7_1" name="s7_1" disabled
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    title="Select an option" required>
                                <option @if($interviewFields['s7_1']=='poor') selected @endif value="poor">poor</option>
                                <option @if($interviewFields['s7_1']=='average') selected @endif value="average">
                                    average
                                </option>
                                <option @if($interviewFields['s7_1']=='good') selected @endif value="good">good</option>
                                <option @if($interviewFields['s7_1']=='very good') selected @endif value="very good">
                                    very good
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
                            <select id="s7_2" name="s7_2" disabled
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    title="Select an option" required>
                                <option @if($interviewFields['s7_2']=='No') selected @endif value="No">No</option>
                                <option @if($interviewFields['s7_2']=='Yes') selected @endif value="Yes">Yes</option>
                            </select>
                            <textarea id="s7_2_r" required placeholder="Name of the institution (If Yes)"
                                      name="s7_2_r" disabled
                                      class="bg-gray-50 border border-gray-300 h-11 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"></textarea>
                        </div>

                        <div class="mt-5">
                            <label for="s7_3"
                                   class="block mb-2  font-bold text-gray-900 dark:text-white">
                                3- Is the family willing to help poor students financially?
                            </label>
                            <select id="s7_3" name="s7_3" disabled
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    title="Select an option" required>
                                <option @if($interviewFields['s7_3']=='No') selected @endif value="No">No</option>
                                <option @if($interviewFields['s7_3']=='Yes') selected @endif value="Yes">Yes</option>
                            </select>
                        </div>
                        <div class="mt-5">
                            <label for="s7_4"
                                   class="block mb-2  font-bold text-gray-900 dark:text-white">
                                4- Is the family interested to participate financially in holding ceremonies and
                                extracurricular activities of the school?
                            </label>
                            <select id="s7_4" name="s7_4" disabled
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    title="Select an option" required>
                                <option @if($interviewFields['s7_4']=='No') selected @endif value="No">No</option>
                                <option @if($interviewFields['s7_4']=='Yes') selected @endif value="Yes">Yes</option>
                            </select>
                        </div>
                        <div class="mt-5">
                            <label for="s7_5"
                                   class="block mb-2  font-bold text-gray-900 dark:text-white">
                                5- Financial administrative suggestions and criticisms:</label>
                            <textarea id="s7_5" required placeholder="Enter remarks"
                                      name="s7_5" disabled
                                      class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">{{$interviewFields['s7_5']}}</textarea>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="mb-8" id="tab8">
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
                                    <p id="sum-of-points"></p>
                                </td>
                            </tr>
                            <tr>
                                <th scope="col" class="w-1/3 p-4 text-center">
                                    Result (admitted, rejected)
                                </th>
                                <td class="font-bold p-4 text-center">
                                    <p id="interview-result"></p>
                                </td>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <hr>
                <div id="last-step" class="text-center">
                    <div class="text-left mb-4">
                        <div>
                            <p class="font-bold mt-4">
                                Discount <u>(Check only when needed)</u>
                            </p>
                            <div class="overflow-x-auto">
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
                                                Action
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
                                                           value="{{ $discount->id }}" disabled
                                                           @if(isset($interviewFields['discount']) and in_array($discount->id,$interviewFields['discount'])) checked
                                                           @endif
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
                            @if(isset($interviewFiles['file1']['src1']))
                                <div>
                                    <div>
                                        <label for="document_description1"
                                               class="block mb-2  font-bold text-gray-900 dark:text-white">
                                            Description for document</label>
                                        <textarea id="document_description1" readonly
                                                  placeholder="Write your description if needed"
                                                  name="document_description1"
                                                  class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"></textarea>
                                    </div>
                                    <div class="mt-3">
                                        <label for="uploaded_file1_preview"
                                               class="block mb-2 font-bold text-gray-900 dark:text-white">
                                            Uploaded file1 preview</label>
                                        @if(substr($interviewFiles['file1']['src1'],-4)=='.pdf')
                                            <div class="flex justify-center items-center">
                                                <a target="_blank"
                                                   href="{{ env('APP_URL').'/'. str_replace( 'public','storage', $interviewFiles['file1']['src1']) }}">
                                                    <img class="pdf-documents-icons">
                                                </a>
                                            </div>
                                        @else
                                            <div class="cursor-pointer img-hover-zoom img-hover-zoom--xyz "
                                            >
                                                <button data-modal-target="openImage" data-modal-toggle="openImage"
                                                        data-image-src="{{ env('APP_URL').'/'. str_replace( 'public','storage', $interviewFiles['file1']['src1']) }}"
                                                        class="block w-full md:w-auto text-white  focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm text-center show-image"
                                                        type="button">
                                                    <img
                                                        class="h-auto text-blue-500 align-center max-w-full rounded-lg"
                                                        style="width: 400px; height: 200px"
                                                        id="uploaded_file1_preview"
                                                        src="{{ env('APP_URL').'/'. str_replace( 'public','storage', $interviewFiles['file1']['src1']) }}"
                                                        alt="Document Not Found!">
                                                </button>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif
                            @if(isset($interviewFiles['file2']['src2']))
                                <div>
                                    <div>
                                        <label for="document_description2"
                                               class="block mb-2  font-bold text-gray-900 dark:text-white">
                                            Description for document</label>
                                        <textarea id="document_description2" readonly
                                                  placeholder="Write your description if needed"
                                                  name="document_description2"
                                                  class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"></textarea>
                                    </div>
                                    <div class="mt-3">
                                        <label for="uploaded_file2_preview"
                                               class="block mb-2 font-bold text-gray-900 dark:text-white">
                                            Uploaded file2 preview</label>
                                        @if(substr($interviewFiles['file2']['src2'],-4)=='.pdf')
                                            <div class="flex justify-center items-center">
                                                <a target="_blank"
                                                   href="{{ env('APP_URL').'/'. str_replace( 'public','storage', $interviewFiles['file2']['src2']) }}">
                                                    <img class="pdf-documents-icons">
                                                </a>
                                            </div>
                                        @else
                                            <div class="cursor-pointer img-hover-zoom img-hover-zoom--xyz "
                                            >
                                                <button data-modal-target="openImage" data-modal-toggle="openImage"
                                                        data-image-src="{{ env('APP_URL').'/'. str_replace( 'public','storage', $interviewFiles['file2']['src2']) }}"
                                                        class="block w-full md:w-auto text-white  focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm text-center show-image"
                                                        type="button">
                                                    <img
                                                        class="h-auto text-blue-500 align-center max-w-full rounded-lg"
                                                        style="width: 400px; height: 200px"
                                                        id="uploaded_file2_preview"
                                                        src="{{ env('APP_URL').'/'. str_replace( 'public','storage', $interviewFiles['file2']['src2']) }}"
                                                        alt="Document Not Found!">
                                                </button>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif
                            @if(isset($interviewFiles['file3']['src3']))
                                <div>
                                    <div>
                                        <label for="document_description3"
                                               class="block mb-2  font-bold text-gray-900 dark:text-white">
                                            Description for document</label>
                                        <textarea id="document_description3" readonly
                                                  placeholder="Write your description if needed"
                                                  name="document_description3"
                                                  class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"></textarea>
                                    </div>
                                    <div class="mt-3">
                                        <label for="uploaded_file3_preview"
                                               class="block mb-2 font-bold text-gray-900 dark:text-white">
                                            Uploaded file3 preview</label>
                                        @if(substr($interviewFiles['file3']['src3'],-4)=='.pdf')
                                            <div class="flex justify-center items-center">
                                                <a target="_blank"
                                                   href="{{ env('APP_URL').'/'. str_replace( 'public','storage', $interviewFiles['file3']['src3']) }}">
                                                    <img class="pdf-documents-icons">
                                                </a>
                                            </div>
                                        @else
                                            <div class="cursor-pointer img-hover-zoom img-hover-zoom--xyz "
                                            >
                                                <button data-modal-target="openImage" data-modal-toggle="openImage"
                                                        data-image-src="{{ env('APP_URL').'/'. str_replace( 'public','storage', $interviewFiles['file3']['src3']) }}"
                                                        class="block w-full md:w-auto text-white  focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm text-center show-image"
                                                        type="button">
                                                    <img
                                                        class="h-auto text-blue-500 align-center max-w-full rounded-lg"
                                                        style="width: 400px; height: 200px"
                                                        id="uploaded_file3_preview"
                                                        src="{{ env('APP_URL').'/'. str_replace( 'public','storage', $interviewFiles['file3']['src3']) }}"
                                                        alt="Document Not Found!">
                                                </button>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif
                            @if(isset($interviewFiles['file4']['src4']))
                                <div>
                                    <div>
                                        <label for="document_description4"
                                               class="block mb-2  font-bold text-gray-900 dark:text-white">
                                            Description for document</label>
                                        <textarea id="document_description4" readonly
                                                  placeholder="Write your description if needed"
                                                  name="document_description4"
                                                  class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"></textarea>
                                    </div>
                                    <div class="mt-3">
                                        <label for="uploaded_file4_preview"
                                               class="block mb-2 font-bold text-gray-900 dark:text-white">
                                            Uploaded file4 preview</label>
                                        @if(substr($interviewFiles['file4']['src4'],-4)=='.pdf')
                                            <div class="flex justify-center items-center">
                                                <a target="_blank"
                                                   href="{{ env('APP_URL').'/'. str_replace( 'public','storage', $interviewFiles['file4']['src4']) }}">
                                                    <img class="pdf-documents-icons">
                                                </a>
                                            </div>
                                        @else
                                            <div class="cursor-pointer img-hover-zoom img-hover-zoom--xyz "
                                            >
                                                <button data-modal-target="openImage" data-modal-toggle="openImage"
                                                        data-image-src="{{ env('APP_URL').'/'. str_replace( 'public','storage', $interviewFiles['file4']['src4']) }}"
                                                        class="block w-full md:w-auto text-white  focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm text-center show-image"
                                                        type="button">
                                                    <img
                                                        class="h-auto text-blue-500 align-center max-w-full rounded-lg"
                                                        style="width: 400px; height: 200px"
                                                        id="uploaded_file4_preview"
                                                        src="{{ env('APP_URL').'/'. str_replace( 'public','storage', $interviewFiles['file4']['src4']) }}"
                                                        alt="Document Not Found!">
                                                </button>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif
                            @if(isset($interviewFiles['file5']['src5']))
                                <div>
                                    <div>
                                        <label for="document_description5"
                                               class="block mb-2  font-bold text-gray-900 dark:text-white">
                                            Description for document</label>
                                        <textarea id="document_description5" readonly
                                                  placeholder="Write your description if needed"
                                                  name="document_description5"
                                                  class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"></textarea>
                                    </div>
                                    <div class="mt-3">
                                        <label for="uploaded_file1_preview"
                                               class="block mb-2 font-bold text-gray-900 dark:text-white">
                                            Uploaded file5 preview</label>
                                        @if(substr($interviewFiles['file5']['src5'],-4)=='.pdf')
                                            <div class="flex justify-center items-center">
                                                <a target="_blank"
                                                   href="{{ env('APP_URL').'/'. str_replace( 'public','storage', $interviewFiles['file5']['src5']) }}">
                                                    <img class="pdf-documents-icons">
                                                </a>
                                            </div>
                                        @else
                                            <div class="cursor-pointer img-hover-zoom img-hover-zoom--xyz "
                                            >
                                                <button data-modal-target="openImage" data-modal-toggle="openImage"
                                                        data-image-src="{{ env('APP_URL').'/'. str_replace( 'public','storage', $interviewFiles['file5']['src5']) }}"
                                                        class="block w-full md:w-auto text-white  focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm text-center show-image"
                                                        type="button">
                                                    <img
                                                        class="h-auto text-blue-500 align-center max-w-full rounded-lg"
                                                        style="width: 400px; height: 200px"
                                                        id="uploaded_file5_preview"
                                                        src="{{ env('APP_URL').'/'. str_replace( 'public','storage', $interviewFiles['file5']['src5']) }}"
                                                        alt="Document Not Found!">
                                                </button>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>
                        <input type="hidden" id="form-type" value="kg">
                        <div>
                            <a href="{{ route('Application.ConfirmApplicationList') }}">
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
