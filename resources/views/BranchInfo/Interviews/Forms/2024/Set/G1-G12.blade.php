<div class="container mx-auto mt-8">
    <div class="flex justify-center">
        <div class="border border-gray-700 rounded-lg w-full ">
            <!-- Tab contents -->
            <div class="p-4">
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
                                    <p id="sum-of-points"></p>
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

    function isNumber(value) {
        return !isNaN(value) && value !== '';
    }

    // Calculate the total sum of Rating Sum with Coefficient for all rows of each table
    function calculateTotal(tableId, totalId) {
        var total = 0;
        $('#' + tableId + ' td:last-child label').each(function () {
            var value = parseFloat($(this).text());
            if (!isNaN(value)) {
                total += value;
            }
        });
        $('#' + totalId).text(total);
    }

    // For each row in each table
    $('input[type="text"]').on('input', function () {
        var value = $(this).val();
        if (!isNumber(value)) {
            $(this).val('');
        }
        var tableId = $(this).closest('table').attr('id'); // Get the id of the current table
        var totalId = 's' + tableId.slice(-1) + '_total'; // Construct the id of the total column for the current table

        // Get the input score
        var inputScore = parseFloat($(this).val());

        // Get the coefficient from the corresponding row
        var coefficient = parseFloat($(this).closest('tr').find('p').text().trim());

        // Calculate the result with coefficient
        var result = inputScore * coefficient;

        // Display the result in the last column of each row
        $(this).closest('tr').find('label').text(result);

        // Calculate the total sum of Rating Sum with Coefficient for each row of each table
        var rowTotal = 0;
        $(this).closest('table').find('td:last-child:not(:last-child) label').each(function () { // Exclude the last column
            var value = parseFloat($(this).text());
            if (!isNaN(value)) {
                rowTotal += value;
            }
        });
        $(this).closest('table').find('tr:last-child td:last-child label').text(rowTotal);

        // Display the total sum in the Total column for each table
        calculateTotal(tableId, totalId);
    });

    // $('.tab-btn').click(function () {
    //     let tabId = $(this).data('tab');
    //     currentTab = Number(tabId.replace('tab', ''));
    //     showTab(currentTab);
    //
    //     if (currentTab === 6) {
    //         $('#sum-of-points').text(
    //             parseFloat($('#s2_total').text()) + parseFloat($('#s3_total').text()) + parseFloat($('#s4_total').text()) + parseFloat($('#s5_total').text())
    //         );
    //     }
    // });

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
        let hasEmptyField = false;

        $('#tab' + currentTab).find('input, textarea, select').each(function () {
            if ($.trim($(this).val()) === '') {
                hasEmptyField = true;
            }
        });
        if (hasEmptyField) {
            swalFire('Error', "Some fields are empty", 'error', 'Try again');
        } else {
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
