@extends('layout/main')

@section('content')
    <!-- Main Menu -->
    <section id="pendaftaran-menu">
        <div class="container mt-4 mb-4 shadow">
            <div class="row pb-0 justify-content-between bg-light p-3">
                <h4>Semua Pemeriksaan</h4>

                <hr class="mt-2">
            </div>
            <div class="row">
                <div class="col">
                    <div id="data-table" class="container">
                        <table class="table table-hover table-bordered">
                            <thead class="table-secondary">
                                <tr>
                                    <th scope="col" style="width: 7%" class="text-center">#</th>
                                    <th scope="col" style="width: 10%">No. Lab</th>
                                    <th scope="col" style="width: 23%">Nama Pasien</th>
                                    <th scope="col" style="width: 15%">Tgl. Pemeriksaan</th>
                                    <th scope="col" style="width: 10%">JK</th>
                                    <th scope="col" style="width: 15%">Usia</th>
                                    <th scope="col" style="width: 25%">Dokter Pengirim</th>
                                </tr>
                            </thead>
                            <tbody class="table-secondary">
                                <tr>
                                    <td class="text-center" colspan="7">Loading...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <script>
        let search_value = '';
        let sort_value_input = 'DESC';
        let sort_status = 'Latest';

        let filter_datetime = '';
        let filter_gender = '';
        let filter_doctor = '';

        let d = '';
        let m = '';
        let y = '';

        // Default Value 
        // $searchVal = '' 
        // $sortOpt = 'latest'
        // $date = ''
        // $genderFilt = ''
        // $doctorFilt = ''

        $(document).ready(function() {
            // Show Table Data for the First Time
            fetch_cust_data();

            // Live Data Table AJAX
            function fetch_cust_data() {
                $('tbody').html('<tr><td class="text-center" colspan="7">Loading...</td></tr>');
                $.ajax({
                    url: "/pendaftaran/func/searchResTable",
                    method: 'GET',
                    data: {
                        searchValue: search_value,
                        sortOption: sort_value_input,
                        dateTime: filter_datetime,
                        genderFilter: filter_gender,
                        doctorFilter: filter_doctor
                    },
                    dataType: 'json',
                    success: function(data) {
                        $('tbody').html(data.table_data);
                    }
                })
            }
            // Live Data Table AJAX

            // Search Field
            $(document).on('keydown', '.search-field', function(event) {
                if (event.keyCode == 13) {
                    event.preventDefault();

                    search_value = $(this).val();
                    fetch_cust_data();
                }
            });
            // Search Field

            // Reset Search
            $(document).on('click', '.btn-reset', function() {
                $(this).prev().val('');
                search_value = $(this).prev().val();
                fetch_cust_data();
            });
            // Reset Search

            // Sort Data
            $('#btn-sort').addClass('disabled');
            $(document).on('click', '#btn-sort', function() {
                $(this).parent().children().removeClass('disabled');
                $(this).addClass('disabled');

                sort_value_input = $(this).attr('value');
                sort_status = $(this).html();
                $(this).parent().prev().html(sort_status);

                fetch_cust_data();
            });
            // Sort Data

            // Filter Modal
            // --> Date Select
            $('.filter-datetime-d').prop('disabled', true);
            $('.filter-datetime-m').prop('disabled', true);
            $('.filter-datetime-y').prop('disabled', true);
            $(document).on('change', '.filter-datetime-d', function() {
                d = this.value;
            });
            $(document).on('change', '.filter-datetime-m', function() {
                m = this.value;
            });
            $(document).on('change', '.filter-datetime-y', function() {
                y = this.value;
            });

            // --> All Time Button
            $('#allTimeCheck').prop('checked', true);
            $(document).on('change', '#allTimeCheck', function() {
                if ($('#allTimeCheck').is(':checked')) {
                    $('.filter-datetime-d').prop('disabled', true);
                    $('.filter-datetime-m').prop('disabled', true);
                    $('.filter-datetime-y').prop('disabled', true);
                } else {
                    d = $('.filter-datetime-d').val();
                    m = $('.filter-datetime-m').val();
                    y = $('.filter-datetime-y').val();

                    $('.filter-datetime-d').prop('disabled', false);
                    $('.filter-datetime-m').prop('disabled', false);
                    $('.filter-datetime-y').prop('disabled', false);
                }
            });

            // --> Gender Filter
            $('#gender-check-1').prop('checked', true);
            $(document).on('change', '#gender-check-1', function() {
                let errorMessage = $(this).next().next().next().next();
                if ($(this).is(':checked')) {
                    if (filter_gender == 'null') {
                        $('.btn-apply-filter').attr('disabled', false);
                        filter_gender = $(this).val();
                        errorMessage.html('');
                    } else if (filter_gender == 'Perempuan') {
                        $('.btn-apply-filter').attr('disabled', false);
                        filter_gender = '';
                        errorMessage.html('');
                    }
                } else {
                    if (filter_gender == '') {
                        $('.btn-apply-filter').attr('disabled', false);
                        filter_gender = 'Perempuan';
                        errorMessage.html('');
                    } else if (filter_gender == 'Laki-laki') {
                        $('.btn-apply-filter').attr('disabled', true);
                        filter_gender = 'null';
                        errorMessage.html('Should select minimal one of these option.');
                    }
                }
            });
            $('#gender-check-2').prop('checked', true);
            $(document).on('change', '#gender-check-2', function() {
                let errorMessage = $(this).next().next();
                if ($(this).is(':checked')) {
                    if (filter_gender == 'null') {
                        $('.btn-apply-filter').attr('disabled', false);
                        filter_gender = $(this).val();
                        errorMessage.html('');
                    } else if (filter_gender == 'Laki-laki') {
                        $('.btn-apply-filter').attr('disabled', false);
                        filter_gender = '';
                        errorMessage.html('');
                    }
                } else {
                    if (filter_gender == '') {
                        $('.btn-apply-filter').attr('disabled', false);
                        filter_gender = 'Laki-laki';
                        errorMessage.html('');
                    } else if (filter_gender == 'Perempuan') {
                        $('.btn-apply-filter').attr('disabled', true);
                        filter_gender = 'null';
                        errorMessage.html('Should select minimal one of these option.');
                    }
                }
            });

            // --> Doctor Filter
            $('.filter-doctor option[value=""]').prop('selected', 'selected');
            $(document).on('change', '.filter-doctor', function() {
                filter_doctor = this.value;
            });

            // --> Apply Button
            $(document).on('click', '.btn-apply-filter', function() {
                if ($('#allTimeCheck').is(':checked')) {
                    filter_datetime = '';
                } else {
                    filter_datetime = ''.concat(y, '-', m, '-', d);
                }

                if (filter_datetime == '' && filter_doctor == '' && filter_gender == '') {
                    $('div.btn-clear-filter').addClass('disabled');
                } else {
                    $('div.btn-clear-filter').removeClass('disabled');
                }

                fetch_cust_data();
            });
            // Filter Modal

            // Clear Filter Button
            $(document).on('click', 'div.btn-clear-filter', function() {
                // Reset Filter Modal
                filter_datetime = '';
                filter_gender = '';
                filter_doctor = '';

                // Reset Filter Modal UI
                $('.filter-datetime-d').prop('disabled', true);
                $('.filter-datetime-m').prop('disabled', true);
                $('.filter-datetime-y').prop('disabled', true);
                $('#allTimeCheck').prop('checked', true);
                $('#gender-check-1').prop('checked', true);
                $('#gender-check-2').prop('checked', true);
                $('.filter-doctor option[value=""]').prop('selected', 'selected');

                $(this).addClass('disabled');

                fetch_cust_data();
            });
            // Clear Filter Button

            // Refresh Button
            $(document).on('click', 'div.btn-refresh', function() {
                fetch_cust_data();
            });
            // Refresh Button
        });
    </script>
    <!-- End of Main Menu -->
@endsection
