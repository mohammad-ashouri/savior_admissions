<div class="container mx-auto mt-8">
    <div class="flex justify-center">
        <div class="border border-gray-700 rounded-lg w-full ">
            <!-- Tabs -->
            <div class="flex flex-wrap">
                <button type="button" id="step-1-btn"
                        class="tab-btn w-full md:w-2/4 lg:w-1/7 py-2 px-4 text-white bg-gray-700 hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-400 rounded-tl-lg rounded-tr-lg"
                        data-tab="tab1">Step 1
                </button>
                <button type="button" id="step-2-btn"
                        class="tab-btn w-full md:w-2/4 lg:w-1/7 py-2 px-4 text-white bg-gray-700 hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-400"
                        data-tab="tab2">Step 2
                </button>
                <button type="button" id="step-3-btn"
                        class="tab-btn w-full md:w-2/4 lg:w-1/7 py-2 px-4 text-white bg-gray-700 hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-400"
                        data-tab="tab3">Step 3
                </button>
                <button type="button" id="step-4-btn"
                        class="tab-btn w-full md:w-2/4 lg:w-1/7 py-2 px-4 text-white bg-gray-700 hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-400"
                        data-tab="tab4">Step 4
                </button>
                <button type="button" id="step-5-btn"
                        class="tab-btn w-full md:w-2/4 lg:w-1/7 py-2 px-4 text-white bg-gray-700 hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-400"
                        data-tab="tab5">Step 5
                </button>
                <button type="button"
                        class="tab-btn w-full md:w-2/4 lg:w-1/7 py-2 px-4 text-white bg-gray-700 hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-400 rounded-bl-lg rounded-br-lg"
                        data-tab="tab6">Final
                </button>
            </div>
            <!-- Tab contents -->
            <div class="p-4">
                <div id="tab1" class="hidden">
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
                            <input type="text" id="gpa_p_y" name="gpa_p_y" value="{{$interviewForm['gpa_p_y']}}"
                                   placeholder="Enter GPA of previous year"
                                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full sm:w-48 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        </div>
                        <select id="s1_1_s" name="s1_1_s"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                title="Select an option">
                            <option selected disabled value="">Select an option</option>
                            <option @if($interviewForm['s1_1_s']=='Admissible') selected @endif value="Admissible">Admissible</option>
                            <option @if($interviewForm['s1_1_s']=='Inadmissible') selected @endif value="Inadmissible">Inadmissible</option>
                            <option @if($interviewForm['s1_1_s']=='Conditional') selected @endif value="Conditional">Conditional</option>
                        </select>
                        <div class="mt-3">
                            <label for="s1_1_d"
                                   class="block mb-2  font-bold text-gray-900 dark:text-white">
                                Description</label>
                            <textarea id="s1_1_d" placeholder="Enter description"
                                      name="s1_1_d"
                                      class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">{{$interviewForm['s1_1_d']}}</textarea>
                        </div>
                    </div>
                    <div class="mt-5">
                        <label for="s1_2_s"
                               class="block mb-2  font-bold text-gray-900 dark:text-white">
                            2-General knowledge of English:
                        </label>
                        <select id="s1_2_s" name="s1_2_s"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                title="Select an option">
                            <option selected disabled value="">Select an option</option>
                            <option @if($interviewForm['s1_2_s']=='Admissible') selected @endif value="Admissible">Admissible</option>
                            <option @if($interviewForm['s1_2_s']=='Inadmissible') selected @endif value="Inadmissible">Inadmissible</option>
                            <option @if($interviewForm['s1_2_s']=='Conditional') selected @endif value="Conditional">Conditional</option>
                        </select>
                        <div class="mt-3">
                            <label for="s1_2_d"
                                   class="block mb-2  font-bold text-gray-900 dark:text-white">
                                Description</label>
                            <textarea id="s1_2_d" placeholder="Enter description"
                                      name="s1_2_d"
                                      class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">{{$interviewForm['s1_2_d']}}</textarea>
                        </div>
                    </div>
                    <div class="mt-5">
                        <label for="s1_3_s"
                               class="block mb-2  font-bold text-gray-900 dark:text-white">
                            3- Special cases (disciplinary / ideological / family, etc.):
                        </label>
                        <select id="s1_3_s" name="s1_3_s"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                title="Select an option">
                            <option selected disabled value="">Select an option</option>
                            <option @if($interviewForm['s1_3_s']=='Admissible') selected @endif value="Admissible">Admissible</option>
                            <option @if($interviewForm['s1_3_s']=='Inadmissible') selected @endif value="Inadmissible">Inadmissible</option>
                            <option @if($interviewForm['s1_3_s']=='Conditional') selected @endif value="Conditional">Conditional</option>
                        </select>
                        <div class="mt-3">
                            <label for="s1_3_d"
                                   class="block mb-2  font-bold text-gray-900 dark:text-white">
                                Description</label>
                            <textarea id="s1_3_d" placeholder="Enter description"
                                      name="s1_3_d"
                                      class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">{{$interviewForm['s1_3_d']}}</textarea>
                        </div>
                    </div>
                </div>
                <div id="tab2" class="hidden">
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
                                        5
                                    </p>
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <input type="text" id="s2_1" name="s2_1" value="{{$interviewForm['s2_1']}}" min="0" max="5"
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    >
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
                                    Homework and class exercises (timely presentation of homework, class and educational
                                    projects, class presentation and participation in scientific and educational
                                    activities)
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <p id="s2_2_c">
                                        4
                                    </p>
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <input type="text" id="s2_2" name="s2_2" value="{{$interviewForm['s2_2']}}" min="0" max="5"
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    >
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
                                    Attendance of compensatory-reinforcement classes
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <p id="s2_3_c">
                                        3
                                    </p>
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <input type="text" id="s2_3" name="s2_3" value="{{$interviewForm['s2_3']}}" min="0" max="3"
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    >
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
                                    Assessing the level of specialized English according to the student's educational
                                    level (reading a part of the text of the book and checking the student's
                                    comprehension and familiarity with specialized vocabulary, asking a few specialized
                                    questions from the student's favorite subject)
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <p id="s2_4_c">
                                        4
                                    </p>
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <input type="text" id="s2_4" name="s2_4" value="{{$interviewForm['s2_4']}}" min="0" max="5"
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    >
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
                                    Checking the level of Persian language (knowledge ability in this course at the
                                    basic level is - conversation, comprehension)
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <p id="s2_5_c">
                                        4
                                    </p>
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <input type="text" id="s2_5" name="s2_5" value="{{$interviewForm['s2_5']}}" min="0" max="5"
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    >
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
                                    Assessing the level of Arabic language (knowledge ability in this course at the
                                    basic level is - Conversation, comprehension)
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <p id="s2_6_c">
                                        4
                                    </p>
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <input type="text" id="s2_6" name="s2_6" value="{{$interviewForm['s2_6']}}" min="0" max="3"
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    >
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <label for="s2_6_rs"
                                           class="block mb-2  font-bold text-gray-900 dark:text-white">{{$interviewForm['s2_6']*4}}
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
                                           class="block mb-2  font-bold text-gray-900 dark:text-white">{{($interviewForm['s2_1']*5)+($interviewForm['s2_2']*4)+($interviewForm['s2_3']*3)+($interviewForm['s2_4']*4)+($interviewForm['s2_5']*4)+($interviewForm['s2_6']*4)}}
                                    </label>
                                </td>
                            </tr>
                            </tbody>
                        </table>

                        <div class="mt-3">
                            <label for="s2_d"
                                   class="block mb-2  font-bold text-gray-900 dark:text-white">
                                Description</label>
                            <textarea id="s2_d" placeholder="Enter description"
                                      name="s2_d"
                                      class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">{{$interviewForm['s2_d']}}</textarea>
                        </div>
                    </div>
                </div>
                <div id="tab3" class="hidden">
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
                                        5
                                    </p>
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <input type="text" id="s3_1" name="s3_1" value="{{$interviewForm['s3_1']}}" min="0" max="5"
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    >
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
                                    How the student interacts with teachers, classmates, administrative teaching staff
                                </td>
                                <td class="w-2 p-4 text-center">
                                    <p id="s3_2_c">
                                        3
                                    </p>
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <input type="text" id="s3_2" name="s3_2" value="{{$interviewForm['s3_2']}}" min="0" max="5"
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    >
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
                                    Attendance Status
                                </td>
                                <td class="w-2 p-4 text-center">
                                    <p id="s3_3_c">
                                        4
                                    </p>
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <input type="text" id="s3_3" name="s3_3" value="{{$interviewForm['s3_3']}}" min="0" max="5"
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    >
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <label for="s3_3_rs"
                                           class="block mb-2  font-bold text-gray-900 dark:text-white">{{$interviewForm['s3_3']*4}}
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
                                        4
                                    </p>
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <input type="text" id="s3_4" name="s3_4" value="{{$interviewForm['s3_4']}}" min="0" max="5"
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    >
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <label for="s3_4_rs"
                                           class="block mb-2  font-bold text-gray-900 dark:text-white">{{$interviewForm['s3_4']*4}}
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
                                        5
                                    </p>
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <input type="text" id="s3_5" name="s3_5" value="{{$interviewForm['s3_5']}}" min="0" max="5"
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    >
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <label for="s3_5_rs"
                                           class="block mb-2  font-bold text-gray-900 dark:text-white">{{$interviewForm['s3_5']*5}}
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
                                        5
                                    </p>
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <input type="text" id="s3_6" name="s3_6" value="{{$interviewForm['s3_6']}}" min="0" max="5"
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    >
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <label for="s3_6_rs"
                                           class="block mb-2  font-bold text-gray-900 dark:text-white">{{$interviewForm['s3_6']*5}}
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
                                        4
                                    </p>
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <input type="text" id="s3_7" name="s3_7" value="{{$interviewForm['s3_7']}}" min="0" max="5"
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    >
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <label for="s3_7_rs"
                                           class="block mb-2  font-bold text-gray-900 dark:text-white">{{$interviewForm['s3_7']*4}}
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
                                        3
                                    </p>
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <input type="text" id="s3_8" name="s3_8" value="{{$interviewForm['s3_8']}}" min="0" max="5"
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    >
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <label for="s3_8_rs"
                                           class="block mb-2  font-bold text-gray-900 dark:text-white">{{$interviewForm['s3_8']*3}}
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
                                        4
                                    </p>
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <input type="text" id="s3_9" name="s3_9" value="{{$interviewForm['s3_9']}}" min="0" max="5"
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    >
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <label for="s3_9_rs"
                                           class="block mb-2  font-bold text-gray-900 dark:text-white">{{$interviewForm['s3_9']*4}}
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
                                        4
                                    </p>
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <input type="text" id="s3_10" name="s3_10" value="{{$interviewForm['s3_10']}}" min="0" max="5"
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    >
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <label for="s3_10_rs"
                                           class="block mb-2  font-bold text-gray-900 dark:text-white">{{$interviewForm['s3_10']*4}}
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
                                        4
                                    </p>
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <input type="text" id="s3_11" name="s3_11" value="{{$interviewForm['s3_11']}}" min="0" max="5"
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    >
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <label for="s3_11_rs"
                                           class="block mb-2  font-bold text-gray-900 dark:text-white">{{$interviewForm['s3_11']*4}}
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
                                        5
                                    </p>
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <input type="text" id="s3_12" name="s3_12" value="{{$interviewForm['s3_12']}}" min="0" max="5"
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    >
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <label for="s3_12_rs"
                                           class="block mb-2  font-bold text-gray-900 dark:text-white">{{$interviewForm['s3_12']*5}}
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
                                        4
                                    </p>
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <input type="text" id="s3_13" name="s3_13" value="{{$interviewForm['s3_13']}}" min="0" max="5"
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    >
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <label for="s3_13_rs"
                                           class="block mb-2  font-bold text-gray-900 dark:text-white">{{$interviewForm['s3_13']*4}}
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
                                           class="block mb-2  font-bold text-gray-900 dark:text-white">{{ ($interviewForm['s3_1']*5)+($interviewForm['s3_2']*3)+($interviewForm['s3_3']*4)+($interviewForm['s3_4']*4)+($interviewForm['s3_5']*5)+($interviewForm['s3_6']*5)+($interviewForm['s3_7']*4)+($interviewForm['s3_8']*3)+($interviewForm['s3_9']*4)+($interviewForm['s3_10']*4)+($interviewForm['s3_11']*4)+($interviewForm['s3_12']*5)+($interviewForm['s3_13']*4) }}
                                    </label>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <div class="mt-5">
                            <label for="s3_q1"
                                   class="block mb-2  font-bold text-gray-900 dark:text-white">
                                1. Express your areas of interest in case you want to collaborate with the
                                school.</label>
                            <textarea id="s3_q1" placeholder="Enter description"
                                      name="s3_q1"
                                      class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">{{$interviewForm['s3_q1']}}</textarea>
                        </div>
                        <div class="mt-5">
                            <label for="s3_q2"
                                   class="block mb-2  font-bold text-gray-900 dark:text-white">
                                2. Description of the student's scientific, educational and cultural personality from
                                the interviewer’s viewpoint: (3 short sentences)
                            </label>
                            <textarea id="s3_q2" placeholder="Enter description"
                                      name="s3_q2"
                                      class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">{{$interviewForm['s3_q2']}}</textarea>
                        </div>
                        <div class="mt-5">
                            <label for="s3_q3"
                                   class="block mb-2  font-bold text-gray-900 dark:text-white">
                                3. Special skills and talents (art, Quran, sports, technology, etc.)
                            </label>
                            <textarea id="s3_q3" placeholder="Enter description"
                                      name="s3_q3"
                                      class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">{{$interviewForm['s3_q3']}}</textarea>
                        </div>
                        <div class="mt-3">
                            <label for="s3_d"
                                   class="block mb-2  font-bold text-gray-900 dark:text-white">
                                Description</label>
                            <textarea id="s3_d" placeholder="Enter description"
                                      name="s3_d"
                                      class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">{{$interviewForm['s3_q4']}}</textarea>
                        </div>
                    </div>
                </div>
                <div id="tab4" class="hidden">
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
                                        4
                                    </p>
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <input type="text" id="s4_1" name="s4_1" value="{{$interviewForm['s4_1']}}" min="0" max="5"
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    >
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <label for="s4_1_rs"
                                           class="block mb-2  font-bold text-gray-900 dark:text-white">{{$interviewForm['s4_1']*4}}
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
                                    <input type="text" id="s4_2" name="s4_2" value="{{$interviewForm['s4_2']}}" min="0" max="5"
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    >
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <label for="s4_2_rs"
                                           class="block mb-2  font-bold text-gray-900 dark:text-white">{{$interviewForm['s4_2']*2}}
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
                                        2
                                    </p>
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <input type="text" id="s4_3" name="s4_3" value="{{$interviewForm['s4_3']}}" min="0" max="5"
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    >
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <label for="s4_3_rs"
                                           class="block mb-2  font-bold text-gray-900 dark:text-white">{{$interviewForm['s4_3']*2}}
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
                                        3
                                    </p>
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <input type="text" id="s4_4" name="s4_4" value="{{$interviewForm['s4_4']}}" min="0" max="5"
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    >
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <label for="s4_4_rs"
                                           class="block mb-2  font-bold text-gray-900 dark:text-white">{{$interviewForm['s4_4']*3}}
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
                                           class="block mb-2  font-bold text-gray-900 dark:text-white">{{($interviewForm['s4_1']*4)+($interviewForm['s4_2']*2)+($interviewForm['s4_3']*2)+($interviewForm['s4_4']*3)}}
                                    </label>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <div class="mt-5">
                            <label for="s4_q1"
                                   class="block mb-2  font-bold text-gray-900 dark:text-white">
                                1. Express your areas of interest in case you want to collaborate with the school.
                            </label>
                            <textarea id="s4_q1" placeholder="Enter description"
                                      name="s4_q1"
                                      class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">{{$interviewForm['s4_q1']}}</textarea>
                        </div>
                        <div class="mt-5">
                            <label for="s4_q2"
                                   class="block mb-2  font-bold text-gray-900 dark:text-white">
                                2. Feature highlights, and points of student’s strengths and weaknesses from the
                                family’s perspective: (in 3 short sentences)
                            </label>
                            <textarea id="s4_q2" placeholder="Enter description"
                                      name="s4_q2"
                                      class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">{{$interviewForm['s4_q2']}}</textarea>
                        </div>
                        <div class="mt-5">
                            <label for="s4_q3"
                                   class="block mb-2  font-bold text-gray-900 dark:text-white">
                                3. Educational expectations and concerns, cultural, student’s family upbringing: (in 3
                                short sentences)
                            </label>
                            <textarea id="s4_q3" placeholder="Enter description"
                                      name="s4_q3"
                                      class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">{{$interviewForm['s4_q3']}}</textarea>
                        </div>
                        <div class="mt-5">
                            <label for="s4_q4"
                                   class="block mb-2  font-bold text-gray-900 dark:text-white">
                                4. Supervision of children's activities (note on monitoring student communication, and
                                real and virtual activities by the family)
                            </label>
                            <textarea id="s4_q4" placeholder="Enter description"
                                      name="s4_q4"
                                      class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">{{$interviewForm['s4_q4']}}</textarea>
                        </div>
                        <div class="mt-5">
                            <label for="s4_q5"
                                   class="block mb-2  font-bold text-gray-900 dark:text-white">
                                5. Dominance of a serene atmosphere on the family environment (requesting parents to
                                provide educational needs and a favorable psychological environment)
                            </label>
                            <textarea id="s4_q5" placeholder="Enter description"
                                      name="s4_q5"
                                      class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">{{$interviewForm['s4_q5']}}</textarea>
                        </div>
                        <div class="mt-5">
                            <label for="s4_q6"
                                   class="block mb-2  font-bold text-gray-900 dark:text-white">
                                6. Level of knowledge of Arabic, Persian, English (emphasis on the requirement of
                                knowledge ability in language courses at the basic level)
                            </label>
                            <textarea id="s4_q6" placeholder="Enter description"
                                      name="s4_q6"
                                      class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">{{$interviewForm['s4_q6']}}</textarea>
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
                                      name="s4_q7"
                                      class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">{{$interviewForm['s4_q7']}}</textarea>
                        </div>
                        <div class="mt-3">
                            <label for="s4_d"
                                   class="block mb-2  font-bold text-gray-900 dark:text-white">
                                Description</label>
                            <textarea id="s4_d" placeholder="Enter description"
                                      name="s4_d"
                                      class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">{{$interviewForm['s4_d']}}</textarea>
                        </div>
                    </div>
                </div>
                <div id="tab5" class="hidden">
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
                                        4
                                    </p>
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <input type="text" id="s5_1" name="s5_1" value="{{$interviewForm['s5_1']}}" min="0" max="5"
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    >
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <label for="s5_1_rs"
                                           class="block mb-2  font-bold text-gray-900 dark:text-white">{{$interviewForm['s5_1']*4}}
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
                                        4
                                    </p>
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <input type="text" id="s5_2" name="s5_2" value="{{$interviewForm['s5_2']}}" min="0" max="5"
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    >
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <label for="s5_2_rs"
                                           class="block mb-2  font-bold text-gray-900 dark:text-white">{{$interviewForm['s5_2']*4}}
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
                                        3
                                    </p>
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <input type="text" id="s5_3" name="s5_3" value="{{$interviewForm['s5_3']}}" min="0" max="5"
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    >
                                </td>
                                <td class="w-4 p-4 text-center">
                                    <label for="s5_3_rs"
                                           class="block mb-2  font-bold text-gray-900 dark:text-white">{{$interviewForm['s5_3']*3}}
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
                                           class="block mb-2  font-bold text-gray-900 dark:text-white">{{($interviewForm['s5_1']*4)+($interviewForm['s5_2']*4)+($interviewForm['s5_3']*3)}}
                                    </label>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <div class="mt-5">
                            <label for="s5_1"
                                   class="block mb-2  font-bold text-gray-900 dark:text-white">
                                1. Economic status of the family:
                            </label>
                            <select id="s5_1" name="s5_1"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    title="Select an option">
                                <option selected disabled value="">Select an option</option>
                                <option @if($interviewForm['s5_1']=='poor') selected @endif value="poor">poor</option>
                                <option @if($interviewForm['s5_1']=='average') selected @endif value="average">average</option>
                                <option @if($interviewForm['s5_1']=='good') selected @endif value="good">good</option>
                                <option @if($interviewForm['s5_1']=='very good') selected @endif value="very good">very good</option>
                            </select>
                        </div>
                        <div class="mt-5">
                            <label for="s5_2"
                                   class="block mb-2  font-bold text-gray-900 dark:text-white">
                                2. Is the family covered by the Relief and Charitable Institution?
                            </label>
                        </div>
                        <div class="flex">
                            <select id="s5_2" name="s5_2"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    title="Select an option">
                                <option selected disabled value="">Select an option</option>
                                <option @if($interviewForm['s5_2']=='No') selected @endif value="No">No</option>
                                <option @if($interviewForm['s5_2']=='Yes') selected @endif value="Yes">Yes</option>
                            </select>
                            <input type="text" id="s5_2_d" name="s5_2_d" value=""
                                   placeholder="Name of the institution (If Yes)"
                                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        </div>
                        <div class="mt-5">
                            <label for="s5_3"
                                   class="block mb-2  font-bold text-gray-900 dark:text-white">
                                3. Is the family willing to donate money to needy students?
                            </label>
                            <select id="s5_3" name="s5_3"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    title="Select an option">
                                <option selected disabled value="">Select an option</option>
                                <option @if($interviewForm['s5_3']=='No') selected @endif value="No">No</option>
                                <option @if($interviewForm['s5_3']=='Yes') selected @endif value="Yes">Yes</option>
                            </select>
                        </div>
                        <div class="mt-5">
                            <label for="s5_4"
                                   class="block mb-2  font-bold text-gray-900 dark:text-white">
                                4. Would you like to contribute financially to the school's extracurricular activities
                                and activities?
                            </label>
                            <select id="s5_4" name="s5_4"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    title="Select an option">
                                <option selected disabled value="">Select an option</option>
                                <option @if($interviewForm['s5_4']=='No') selected @endif value="No">No</option>
                                <option @if($interviewForm['s5_4']=='Yes') selected @endif value="Yes">Yes</option>
                            </select>
                        </div>
                        <div class="mt-5">
                            <label for="s5_5_d"
                                   class="block mb-2  font-bold text-gray-900 dark:text-white">
                                5. Financial administrative suggestions and criticisms
                            </label>
                            <textarea id="s5_5_d" placeholder="Enter description"
                                      name="s5_5_d"
                                      class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">{{$interviewForm['s5_5_d']}}</textarea>
                        </div>
                        <div class="mt-3">
                            <label for="s5_d"
                                   class="block mb-2  font-bold text-gray-900 dark:text-white">
                                Description</label>
                            <textarea id="s5_d" placeholder="Enter description"
                                      name="s5_d"
                                      class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">{{$interviewForm['s5_d']}}</textarea>
                        </div>
                    </div>
                </div>
                <div id="tab6" class="hidden">
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
    ($interviewForm['s2_1']*5)+($interviewForm['s2_2']*4)+($interviewForm['s2_3']*3)+($interviewForm['s2_4']*4)+($interviewForm['s2_5']*4)+($interviewForm['s2_6']*4)+
    ($interviewForm['s3_1']*5)+($interviewForm['s3_2']*3)+($interviewForm['s3_3']*4)+($interviewForm['s3_4']*4)+($interviewForm['s3_5']*5)+($interviewForm['s3_6']*5)+($interviewForm['s3_7']*4)+($interviewForm['s3_8']*3)+($interviewForm['s3_9']*4)+($interviewForm['s3_10']*4)+($interviewForm['s3_11']*4)+($interviewForm['s3_12']*5)+($interviewForm['s3_13']*4)+
    ($interviewForm['s4_1']*4)+($interviewForm['s4_2']*2)+($interviewForm['s4_3']*2)+($interviewForm['s4_4']*3)+
    ($interviewForm['s5_1']*4)+($interviewForm['s5_2']*4)+($interviewForm['s5_3']*3)
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
    let currentTab = 1;
    showTab(currentTab);

    $(function () {
        $('textarea, input, select').prop('disabled', true);
    });

    $('.tab-btn').click(function () {
        let tabId = $(this).data('tab');
        currentTab = Number(tabId.replace('tab', ''));
        showTab(currentTab);

        if (currentTab === 6) {
            $('#sum-of-points').text(
                parseFloat($('#s2_total').text()) + parseFloat($('#s3_total').text()) + parseFloat($('#s4_total').text()) + parseFloat($('#s5_total').text())
            );
        }
    });

    $('#prevBtn').click(function () {
        if (currentTab > 1 && ($('#s1_1_s').val() === 'Inadmissible' || $('#s1_2_s').val() === 'Inadmissible' || $('#s1_3_s').val() === 'Inadmissible')) {
            currentTab -= 5;
            showTab(currentTab);
        } else {
            currentTab -= 1;
            showTab(currentTab);
        }
    });

    $('#nextBtn').click(function () {
        if (currentTab < 6) {
            currentTab += 1;
            showTab(currentTab);
            $('#sum-of-points').text(
                parseFloat($('#s2_total').text()) + parseFloat($('#s3_total').text()) + parseFloat($('#s4_total').text()) + parseFloat($('#s5_total').text())
            );
        }
        if ($('#s1_1_s').val() === 'Inadmissible' || $('#s1_2_s').val() === 'Inadmissible' || $('#s1_3_s').val() === 'Inadmissible' || $('#s1_4_s').val() === 'Inadmissible') {
            $('#tab2, #tab3, #tab4, #tab5').addClass('hidden');
            $('#step-2-btn, #step-3-btn, #step-4-btn, #step-5-btn').addClass('hidden');
            currentTab = 6;
            showTab(currentTab);
        } else if ($('#s1_1_s').val() !== 'Inadmissible' && $('#s1_2_s').val() !== 'Inadmissible' && $('#s1_3_s').val() !== 'Inadmissible' && $('#s1_4_s').val() !== 'Inadmissible') {
            $('#tab2, #tab3, #tab4, #tab5').removeClass('hidden');
            $('#step-2-btn, #step-3-btn, #step-4-btn, #step-5-btn').removeClass('hidden');
            showTab(currentTab);
        }
    });


    function showTab(tabIndex) {
        $('.tab-btn').removeClass('bg-gray-900').addClass('bg-gray-700');
        $('#tab1, #tab2, #tab3, #tab4, #tab5, #tab6').addClass('hidden');
        $('#tab' + tabIndex).removeClass('hidden');
        $('[data-tab="tab' + tabIndex + '"]').removeClass('bg-gray-700').addClass('bg-gray-900');

        if (tabIndex === 1) {
            $('#nextBtn').removeClass('hidden');
            $('#prevBtn').addClass('hidden');
            $('#last-step').addClass('hidden');
        } else if (tabIndex === 6) {
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
