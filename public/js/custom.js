// Variabel untuk Doctor Modal
let selectedDoctorCode = '';
let selectedTestCode = [];

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

function hidePatientList(){
    document.querySelector('.select-field').value = '';
    setTimeout(function(){
        document.querySelector('#patient-list').classList.add('d-none');
    }, 100);
}

function hideDoctorList(){
    document.querySelector('#pilihdokter').value = '';
    setTimeout(function(){
        document.querySelector('#doctor-list').firstElementChild.classList.add('d-none');
    }, 100);
}

function onMetodeBayarChange(){
    if(document.querySelector('#metodebayar').value == 'BPJS'){
        document.querySelector('#nosep').readOnly = false;
    }else{
        document.querySelector('#nosep').readOnly = true;
    }
}


// Function untuk Paying Summary Section
function onlyNumInput(evt){
    var ASCIICode = (evt.which) ? evt.which : evt.keyCode;
    if (ASCIICode > 31 && (ASCIICode < 48 || ASCIICode > 57)) 
        return false;
    return true;
}
//End

$(document).ready(function() {
    // Event untuk Sub Navbar
        if (document.querySelectorAll('.sub-nav')){
            document.querySelectorAll('.sub-nav').forEach(function(childNode) {
                childNode.addEventListener('click', function(e){
                    if (e.target.classList.contains('btn-hide-nav')){
                        const btn_show_nav = e.target.parentElement.parentElement.parentElement.previousElementSibling.querySelector('.btn-show-nav');
                        const hideable_sub_nav = e.target.parentElement.parentElement.parentElement;
                        
                        btn_show_nav.classList.remove('d-none');
                        hideable_sub_nav.classList.add('d-none');    
                    }

                    if (e.target.classList.contains('btn-show-nav')){
                        const btn_show_nav = e.target;
                        const hideable_sub_nav = e.target.parentElement.parentElement.nextElementSibling;

                        btn_show_nav.classList.add('d-none');
                        hideable_sub_nav.classList.remove('d-none');    
                    }
                    if (e.target.classList.contains('have-page')){
                        if (e.target.classList.contains('active')){
                            e.preventDefault();
                        }
                    }
                });
            });
        }
    // End
    


    // Event Pendaftaran Registrasi
        // Event untuk Live Search Existing Patient
        if(document.querySelector('#patient-list')){
            document.querySelector('#patient-list').classList.add('d-none');
        } 
        $(document).on('keyup', '.select-field', function() {
            let select_value = $(this).val();
            $.ajax({
                url: "/pendaftaran/func/getPatientData",
                method: 'GET',
                data: {
                    searchValue: select_value
                },
                dataType: 'json',
                success: function(data) {
                    $('#patient-list').html('');
                    $('#patient-list').html(data.table_data);
                }
            });
            document.querySelector('#patient-list').classList.remove('d-none');
            if (select_value == ''){
                document.querySelector('#patient-list').classList.add('d-none');
            }
        });

        // Event untuk pilih foto profil
        if(document.querySelector('div.btn-order-photo')){ 
            document.querySelector('div.btn-order-photo').addEventListener('click', function(){
                document.querySelector('input#fotopasien').click();
            });
        }
        if(document.querySelector('input#fotopasien')){
            document.querySelector('input#fotopasien').addEventListener('change', function(e){
                document.querySelector('img#fotothumbnailpasien').src = URL.createObjectURL(e.target.files[0]);
                document.querySelector('img#fotothumbnailpasien').onload = function() {
                    URL.revokeObjectURL(document.querySelector('img#fotothumbnailpasien').src) // Free memory
                }
            })
        }
    // End



    // Inisialisasi Metode Bayar
    if(document.querySelector('#metodebayar')){
        if(document.querySelector('#metodebayar').value != 'BPJS'){
            document.querySelector('#nosep').readOnly = true;
        }
    }

    // End



    // AJAX Request for Managing Doctor 
        function addDoctor(doctorData){
            // Hide Failed Alert (If Exists)
            document.querySelector('div#select-doc-2').classList.add('d-none');
            document.querySelector('#error-doc-alert').classList.add('d-none');
            // Store Doctor Data Into FormData Object
            let formData = new FormData();
            formData.append('kode', doctorData[0]);
            formData.append('spesialisasi', doctorData[1]);
            formData.append('nama', doctorData[2]);
            formData.append('no_skp', doctorData[3]);
            formData.append('no_sertif_skp', doctorData[4]);
            formData.append('ttd', doctorData[5]);
            formData.append('alamat', doctorData[6]);
            formData.append('alamat_praktek', doctorData[7]);
            formData.append('no_telp', doctorData[8]);
            formData.append('no_hp', doctorData[9]);
            formData.append('email', doctorData[10]);
            $.ajax({
                url: '/pendaftaran/manageDoctor',
                method: 'POST',
                data: formData,
                dataType: 'json',
                processData: false,
                contentType: false,
                success: function(data) {
                    // Show Success Alert
                    resetDoctorModal(1);
                    var htmlalert = document.createElement('div');
                    htmlalert.classList.add("mt-0");
                    htmlalert.innerHTML = data.alert;
                    document.querySelector('#select-doc-1').prepend(htmlalert);
                },
                error: function(){
                    // Show Failed Alert
                    document.querySelector('div#select-doc-2').classList.remove('d-none');
                    document.querySelector('#error-doc-alert').classList.remove('d-none');
                }
            })
        }
        function updDoctor(nolab, doctorData){
            // Store Updated Doctor Data Into FormData Object
            let formData = new FormData();
            formData.append('spesialisasi', doctorData[1]);
            formData.append('nama', doctorData[2]);
            formData.append('no_skp', doctorData[3]);
            formData.append('no_sertif_skp', doctorData[4]);
            if(doctorData[5]) formData.append('ttd', doctorData[5]);
            else formData.append('ttd', '');
            formData.append('alamat', doctorData[6]);
            formData.append('alamat_praktek', doctorData[7]);
            formData.append('no_telp', doctorData[8]);
            formData.append('no_hp', doctorData[9]);
            formData.append('email', doctorData[10]);
            $.ajax({
                url: '/pendaftaran/'+doctorData[0]+'/manageDoctor',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function() {
                    refreshDoctorTable(nolab);
                    document.querySelector('button#updateDoctor').previousElementSibling.click();
                },
                error: function(){
                    // Show Failed Alert
                    document.querySelector('#error-edit-doc-alert').classList.remove('d-none');
                }
            })
        }
        function destroyDoctor(nolab){
            $.ajax({
                url: '/pendaftaran/'+selectedDoctorCode+'/manageDoctor',
                method: 'DELETE',
                data: {
                    nolab: nolab
                },
                success: function() {
                    refreshDoctorTable(nolab);
                    document.querySelector('button#updateDoctor').previousElementSibling.click();
                }
            })
        }
        function getDoctor(nolab) {
            $.ajax({
                url: '/pendaftaran/manageDoctor',
                method: 'GET',
                data: {
                    nolab: nolab
                },
                dataType: 'json',
                success: function(data) {
                    resetEditDoctorModal(data);
                }
            })
        }
        function selectDoctor(nolab, doctorCode = '', remove = false){
            $.ajax({
                url: '/pendaftaran/'+nolab+'/order',
                method: 'PUT',
                data: {
                    kode: doctorCode,
                    isRemove: remove
                },
                success: function() {
                    refreshDoctorTable(nolab);
                }
            })
        }
        function refreshDoctorTable(nolab){ 
            $('tbody#doctorTable').html('<tr><td class="text-center" colspan="3">Loading...</td></tr>');
            $.ajax({
                url: '/pendaftaran/manageDoctor',
                method: 'GET',
                data: {
                    nolab: nolab
                },
                dataType: 'json',
                success: function(data) {
                    selectedDoctorCode = '';
                    $('tbody#doctorTable').html(data.table_data);
                }
            })
        }
        // Event untuk Live Search Existing Doctor
        $(document).on('keyup', '#pilihdokter', function() {
            let inputField = $(this);
            setTimeout(function(){
                let pilih_dokter = inputField.val();
                if (pilih_dokter != ''){
                    // Fx loading ketika sedang mencari data dokter yang ada
                    document.querySelector('#doctor-list').firstElementChild.innerHTML = '<div class="row g-0 text-center"><div class="col"><div class="card-body p-0 border border-top-0"><p class="card-text mb-0 p-2"><small class="text-muted">Loading...</small></p></div></div></div>';
                    document.querySelector('#doctor-list').firstElementChild.classList.remove('d-none');
                }
                $.ajax({
                    url: "/pendaftaran/func/getDoctorData",
                    method: 'GET',
                    data: {
                        searchValue: pilih_dokter
                    },
                    dataType: 'json',
                    success: function(data) {
                        document.querySelector('#doctor-list').firstElementChild.innerHTML = data.table_data;
                        document.querySelector('#doctor-list').firstElementChild.classList.remove('d-none');
                        if (pilih_dokter == ''){
                            document.querySelector('#doctor-list').firstElementChild.classList.add('d-none');
                        }
                    }
                });
            }, 1);
        });
    // End



    // Event untuk Doctor Table Section
        if (document.querySelectorAll('#doctorTableSection')){
            document.querySelectorAll('#doctorTableSection').forEach(function(childNode) {
                childNode.addEventListener('click', function(e){
                    if (e.target.tagName == 'TD'){
                        if(e.target.parentElement.firstElementChild.firstElementChild){
                            e.target.parentElement.firstElementChild.firstElementChild.click();
                        }
                    }
                    if (e.target.classList.contains('form-check-input')){
                        if (e.target.checked){
                            selectedDoctorCode = e.target.parentElement.parentElement.children[1].innerHTML;
                            document.querySelector('#remove-tabledoctor').classList.remove('disabled');
                            document.querySelector('#edit-tabledoctor').classList.remove('disabled');
                        }else{
                            selectedDoctorCode = '';
                            document.querySelector('#remove-tabledoctor').classList.add('disabled');
                            document.querySelector('#edit-tabledoctor').classList.add('disabled');
                        }
                    }
                    if (e.target.id == 'remove-tabledoctor'){
                        const nolab = document.querySelector('#nolab').value;
                        selectDoctor(nolab, selectedDoctorCode, true);
                        selectedDoctorCode = '';
                        document.querySelector('#remove-tabledoctor').classList.add('disabled');
                        document.querySelector('#edit-tabledoctor').classList.add('disabled');                    
                    }
                    if (e.target.id == 'add-tabledoctor'){
                        resetDoctorModal(1);
                        if(document.querySelector('tbody#doctorTable tr').firstElementChild.firstElementChild){
                            if(document.querySelector('tbody#doctorTable tr').firstElementChild.firstElementChild.checked){
                                document.querySelector('tbody#doctorTable tr').firstElementChild.firstElementChild.click();
                            }
                        }
                        document.querySelector('#remove-tabledoctor').classList.add('disabled');
                        document.querySelector('#edit-tabledoctor').classList.add('disabled');
                    }
                    if (e.target.id == 'edit-tabledoctor'){
                        document.querySelector('#error-edit-doc-alert').classList.add('d-none');
                        const noLab = document.querySelector('#nolab').value;
                        getDoctor(noLab);
                    }
                });
            });
        }
    // End



    // Function For Managing Halaman Doctor Modal
        function resetDoctorModal(halaman = 0){
            isSelectDoctor = false;
            tempDoctorCode = '';
            if(halaman == 1){
                // Untuk reset dan pindah ke Doctor Modal Halaman 1
                document.querySelector('h5#modalTitle').innerHTML = 'Pilih Dokter Pengirim';
                document.querySelector('#select-doc-1').classList.remove('d-none');
                document.querySelector('#select-doc-2').classList.add('d-none');
                if(document.querySelector('#btn-close-doctor-alert')){
                    document.querySelector('#btn-close-doctor-alert').click();
                }
                document.querySelector('#save-doc-btn-1').classList.add('d-none');
                document.querySelector('#save-doc-btn-2').classList.remove('d-none');
            }else if (halaman == 2){
                // Untuk reset dan pindah ke Doctor Modal Halaman 2
                document.querySelector('h5#modalTitle').innerHTML = 'Tambah Dokter';
                document.querySelector('#select-doc-1').classList.add('d-none');
                document.querySelector('#select-doc-2').classList.remove('d-none');
                document.querySelector('#save-doc-btn-1').classList.remove('d-none');
                document.querySelector('#save-doc-btn-2').classList.add('d-none');
                document.querySelector('#error-doc-alert').classList.add('d-none');
            }
            // Reset Input
            document.querySelector('#kodedokter').value = '';
            $('#spesialisasi option[value=""]').prop('selected', 'selected');
            document.querySelector('#namadokter').value = '';
            document.querySelector('#noskp').value = '';
            document.querySelector('#nosertifskp').value = '';
            document.querySelector('#ttddokter').value = '';
            document.querySelector('#alamatdokter').value = '';
            document.querySelector('#alamatpraktek').value = '';
            document.querySelector('#notelpdokter').value = '';
            document.querySelector('#nohpdokter').value = '';
            document.querySelector('#emaildokter').value = '';
        }
        function resetEditDoctorModal(doctorData){
            // Reset to current data
            document.querySelector('#kodedokter-edit').value = doctorData.kode;
            document.querySelector('#namadokter-edit').value = doctorData.nama;
            document.querySelector('#spesialisasi-edit').value = doctorData.spesialisasi;
            document.querySelector('#noskp-edit').value = doctorData.no_skp;
            document.querySelector('#nosertifskp-edit').value = doctorData.no_sertif_skp;
            document.querySelector('#ttddokter-edit').value = '';
            document.querySelector('#alamatdokter-edit').value = doctorData.alamat;
            document.querySelector('#alamatpraktek-edit').value = doctorData.alamat_praktek;
            document.querySelector('#notelpdokter-edit').value = doctorData.no_telp;
            document.querySelector('#nohpdokter-edit').value = doctorData.no_hp;
            document.querySelector('#emaildokter-edit').value = doctorData.email;
        }
    // End



    // Event untuk Doctor Modal : Select dan Add
        if(document.querySelectorAll('#selectDoctorModal')){
            document.querySelectorAll('#selectDoctorModal').forEach(function(childNode){
                childNode.addEventListener('keydown', function(e){
                    if(e.keyCode == 13){
                        e.preventDefault();
                    }
                });
                childNode.addEventListener('click', function(e){
                    // Remove Alert Element
                    if(e.target.id == 'btn-close-doctor-alert'){
                        e.target.parentElement.parentElement.classList.add('d-none');
                    }
                    // Event untuk Select Doctor pada Live Search
                    if(e.target.classList.contains('dokterlist')){
                        selectedDoctorCode = e.target.nextElementSibling.innerHTML;
                        document.querySelector('#save-doc-btn-2').click();
                    }
                    if(e.target.id == 'select-doc-btn'){
                        resetDoctorModal(1);
                    }
                    if(e.target.id == 'add-doc-btn'){
                        resetDoctorModal(2);
                    }
                    // Event untuk Select-Doctor-Button
                    if(e.target.id == 'save-doc-btn-1'){
                        // Get inputed data
                        const inputtedDoctorData = [];
                        inputtedDoctorData.push(document.querySelector('#kodedokter').value);
                        inputtedDoctorData.push(document.querySelector('#spesialisasi').value);
                        inputtedDoctorData.push(document.querySelector('#namadokter').value);
                        inputtedDoctorData.push(document.querySelector('#noskp').value);
                        inputtedDoctorData.push(document.querySelector('#nosertifskp').value);
                        inputtedDoctorData.push(document.querySelector('#ttddokter').files[0]);
                        inputtedDoctorData.push(document.querySelector('#alamatdokter').value);
                        inputtedDoctorData.push(document.querySelector('#alamatpraktek').value);
                        inputtedDoctorData.push(document.querySelector('#notelpdokter').value);
                        inputtedDoctorData.push(document.querySelector('#nohpdokter').value);
                        inputtedDoctorData.push(document.querySelector('#emaildokter').value);
                        // Add Doctor pada Tabel 'dokters' (DB)
                        addDoctor(inputtedDoctorData);
                    }
                    // Event untuk Select-Doctor-Button
                    if(e.target.id == 'save-doc-btn-2'){
                        // Add/Update Doctor pada Tabel 'periksas' (DB)
                        const nolab = document.querySelector('#nolab').value;
                        selectDoctor(nolab, selectedDoctorCode);
                    }
                });
            });
        }
    // End



    // Event untuk Doctor Modal Edit
        if(document.querySelectorAll('#editDoctorModal')){
            document.querySelectorAll('#editDoctorModal').forEach(function(childNode){
                childNode.addEventListener('keydown', function(e){
                    if(e.keyCode == 13){
                        e.preventDefault();
                    }
                });
                childNode.addEventListener('click', function(e){
                    if(e.target.id == 'updateDoctor'){
                        document.querySelector('#error-edit-doc-alert').classList.add('d-none');
                        const nolab = document.querySelector('#nolab').value;
                        // Get inputed data
                        const inputtedDoctorData = [];
                        inputtedDoctorData.push(document.querySelector('#kodedokter-edit').value);
                        inputtedDoctorData.push(document.querySelector('#spesialisasi-edit').value);
                        inputtedDoctorData.push(document.querySelector('#namadokter-edit').value);
                        inputtedDoctorData.push(document.querySelector('#noskp-edit').value);
                        inputtedDoctorData.push(document.querySelector('#nosertifskp-edit').value);
                        inputtedDoctorData.push(document.querySelector('#ttddokter-edit').files[0]);
                        inputtedDoctorData.push(document.querySelector('#alamatdokter-edit').value);
                        inputtedDoctorData.push(document.querySelector('#alamatpraktek-edit').value);
                        inputtedDoctorData.push(document.querySelector('#notelpdokter-edit').value);
                        inputtedDoctorData.push(document.querySelector('#nohpdokter-edit').value);
                        inputtedDoctorData.push(document.querySelector('#emaildokter-edit').value);
                        // Update Doctor pada Tabel 'dokters' (DB)
                        updDoctor(nolab, inputtedDoctorData);
                        // Disabled the EditDoctor and RemoveDoctor Button
                        document.querySelector('#remove-tabledoctor').classList.add('disabled');
                        document.querySelector('#edit-tabledoctor').classList.add('disabled');
                    }
                    if(e.target.id == 'deleteDoctor'){
                        if(confirm('Anda yakin ingin menghapus data dokter ini ? \nAksi ini tidak dapat diurungkan !')){
                            const nolab = document.querySelector('#nolab').value;
                            destroyDoctor(nolab);
                            // Disabled the EditDoctor and RemoveDoctor Button
                            document.querySelector('#remove-tabledoctor').classList.add('disabled');
                            document.querySelector('#edit-tabledoctor').classList.add('disabled');
                        }
                    }
                });
            });
        }
    // End



    // AJAX Request for Managing Test 
        function selectTest(nolab, testCode = [], remove = false){
            $.ajax({
                url: '/pendaftaran/'+nolab+'/syncOrderTest',
                method: 'PUT',
                data: {
                    testCode: testCode,
                    isRemove: remove
                },
                success: function() {
                    refreshTestTable(nolab);
                }
            })
        }
        function refreshTestTable(nolab){ 
            document.querySelector('tbody#testTablePrice').firstElementChild.lastElementChild.innerHTML = 'Calculating...';
            document.querySelector('tbody#testTablePrice').lastElementChild.lastElementChild.innerHTML = 'Calculating...';
            $.ajax({
                url: '/pendaftaran/'+nolab+'/syncOrderTest',
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    $('tbody#testTable').html(data.table_data);
                    document.querySelector('tbody#testTablePrice').firstElementChild.lastElementChild.innerHTML = 'Rp. '+data.bruto+' ,-';
                    document.querySelector('tbody#testTablePrice').lastElementChild.lastElementChild.innerHTML = 'Rp. '+data.netto+' ,-';
                }
            })
        }
    // End



    // Event untuk Test Table Section
        if (document.querySelectorAll('#testTableSection')){
            document.querySelectorAll('#testTableSection').forEach(function(childNode) {
                childNode.addEventListener('click', function(e){
                    if (e.target.tagName == 'TD'){
                        if(e.target.parentElement.firstElementChild.firstElementChild){
                            e.target.parentElement.firstElementChild.firstElementChild.click();
                        }
                    }
                    if (e.target.classList.contains('testSelected')){
                        if (e.target.checked){
                            const index = selectedTestCode.indexOf(e.target.parentElement.parentElement.children[1].innerHTML);
                            if(index == -1){
                                selectedTestCode.push(e.target.parentElement.parentElement.children[1].innerHTML);
                            }
                            document.querySelector('#remove-tabletest').classList.remove('disabled');
                        }else{
                            const index = selectedTestCode.indexOf(e.target.parentElement.parentElement.children[1].innerHTML);
                            if(index != -1){
                                selectedTestCode.splice(index, 1);                                
                            }
                            if(!selectedTestCode.length){
                                document.querySelector('#remove-tabletest').classList.add('disabled');
                            }
                        }
                    }
                    if (e.target.id == 'remove-tabletest'){
                        // Get Inputed Data
                        const nolab = document.querySelector('#nolab').value;
                        selectTest(nolab, selectedTestCode, true);
                        selectedTestCode.length = 0;
                        document.querySelector('#remove-tabletest').classList.add('disabled');
                    }
                    if (e.target.id == 'add-tabletest'){ 
                        const tableRow = document.querySelector('#testTable').children;
                        for(i = 0; i < tableRow.length; i++){
                            if(tableRow[i].firstElementChild.firstElementChild){
                                if(tableRow[i].firstElementChild.firstElementChild.checked){
                                    tableRow[i].firstElementChild.firstElementChild.click();
                                }
                            }
                        }
                        resetTestModal(1);
                    }
                });
            });
        }
    // End of Event untuk Test Table Section



    // Function For Managing Halaman Test Modal
        function resetTestModal(resetTest = 0){
            // resetTest is true will reset selectedTestCode as well 
            document.querySelector('#pilihan-kategori-pemeriksaan').classList.remove('d-none');
            document.querySelector('#pilihan-pemeriksaan').classList.add('d-none');
            for (let i = 0; i < document.querySelector('#pilihan-pemeriksaan').children.length; i++) {
                document.querySelector('#pilihan-pemeriksaan').children[i].classList.add('d-none');
                if(resetTest){
                    for (let j = 3; j < document.querySelector('#pilihan-pemeriksaan').children[i].children.length; j++) {
                        if(document.querySelector('#pilihan-pemeriksaan').children[i].children[j].firstElementChild.firstElementChild.checked){
                            document.querySelector('#pilihan-pemeriksaan').children[i].children[j].firstElementChild.firstElementChild.click();
                        }
                    }
                }
            }
            if(resetTest){
                selectedTestCode.length = 0;
            }
        }
    // End



    // Event untuk Test Modal
        if(document.querySelectorAll('#testModal')){
            document.querySelectorAll('#testModal').forEach(function(childNode){
                childNode.addEventListener('keydown', function(e){
                    if(e.keyCode == 13){
                        e.preventDefault();
                    }
                });
                childNode.addEventListener('click', function(e){
                    if(e.target.id == 'kategori-pemeriksaan-back'){
                        e.preventDefault();
                        resetTestModal();
                    }
                    if(e.target.hasAttribute('data-value')){
                        const selectedCategory = document.querySelector('#kategori-pemeriksaan-'+e.target.getAttribute('data-value'));
                        selectedCategory.classList.remove('d-none');
                        document.querySelector('#pilihan-pemeriksaan').classList.remove('d-none');  
                        e.target.parentElement.parentElement.classList.add('d-none');
                    }
                    if(e.target.type == 'checkbox'){
                        if (e.target.checked){
                            const index = selectedTestCode.indexOf(e.target.value);
                            if(index == -1){
                                selectedTestCode.push(e.target.value);
                            }
                        }else{
                            const index = selectedTestCode.indexOf(e.target.value);
                            if(index != -1){
                                selectedTestCode.splice(index, 1);                                
                            }
                        }
                    }
                    if(e.target.id == 'saveTest'){
                        const nolab = document.querySelector('#nolab').value;
                        selectTest(nolab, selectedTestCode);
                        selectedTestCode.length = 0;
                        e.target.previousElementSibling.click();
                    }
                });
            });
        }
    // End



    // AJAX Request for Managing Paying
    function refreshTestTableFixed(nolab){ 
        document.querySelector('tbody#testTablePriceFixed').lastElementChild.firstElementChild.innerHTML = 'Calculating...';
        document.querySelector('tbody#testTablePriceFixed').lastElementChild.lastElementChild.innerHTML = 'Calculating...';
        $.ajax({
            url: '/pendaftaran/'+nolab+'/syncOrderTest',
            method: 'GET',
            data: {
                tableFixed: true
            },
            dataType: 'json',
            success: function(data) {
                $('tbody#testTableFixed').html(data.table_data);
                document.querySelector('input#tagihan').value = data.netto;
                const kekuranganDana = data.netto - document.querySelector('input#bayar').value;
                document.querySelector('input#kekurangan').value = (kekuranganDana > 0) ? kekuranganDana : 0;
                document.querySelector('tbody#testTablePriceFixed').lastElementChild.firstElementChild.innerHTML = 'Rp. '+data.bruto+' ,-';
                document.querySelector('tbody#testTablePriceFixed').lastElementChild.lastElementChild.innerHTML = 'Rp. '+data.netto+' ,-';
            }
        })
    }
    // End



    // Function For Managing Halaman Test Modal
    function recalculatePaying(){
        setTimeout(function(){
            const tagihan = document.querySelector('#tagihan').value;
            const bayar = document.querySelector('#bayar').value;
            const kekurangan = tagihan - bayar;
            document.querySelector('#kekurangan').value = (kekurangan > 0) ? kekurangan : 0;
        }, 300);
    }
    // End



    // Event untuk Paying Summary Section
    if (document.querySelectorAll('#payingSummary')){
        document.querySelectorAll('#payingSummary').forEach(function(childNode) {
            childNode.addEventListener('keydown', function(e){
                if (e.target.id == 'bayar'){ 
                    recalculatePaying();
                }
            });
            childNode.addEventListener('click', function(e){
                if (e.target.id == 'bayar'){ 
                    if(e.target.value == 0) e.target.value = '';
                    recalculatePaying();
                }
            });
            childNode.addEventListener('focusout', function(e){
                if (e.target.id == 'bayar'){ 
                    if(e.target.value == 0) e.target.value = 0;
                    recalculatePaying();
                }
            });
        });
    }
    // End of Event untuk Test Table Section



    // Event untuk Mengelola Halaman Pendaftaran
        // Function untuk ganti halaman
        function get_content_data_normal(){
            const pageNum = document.querySelector('#pagenum');
            const noLab = document.querySelector('#nolab').value;
            if(pageNum.getAttribute('value') == 1){
                document.querySelector('#content-1').classList.add('d-none');
                document.querySelector('#content-2').classList.remove('d-none');
                document.querySelector('#extra-1').classList.add('d-none');
                document.querySelector('#extra-2').classList.remove('d-none');
                pageNum.setAttribute('value', 2);
                refreshDoctorTable(noLab);
                refreshTestTable(noLab);
            }else if (pageNum.getAttribute('value') == 2){
                document.querySelector('#content-2').classList.add('d-none');
                document.querySelector('#content-3').classList.remove('d-none');
                pageNum.setAttribute('value', 3);
                refreshTestTableFixed(noLab);
            }
        }
        function get_content_data_reverse(){
            const pageNum = document.querySelector('#pagenum');
            if(pageNum.getAttribute('value') == 2){
                document.querySelector('#content-2').classList.add('d-none');
                document.querySelector('#content-1').classList.remove('d-none');
                document.querySelector('#extra-2').classList.add('d-none');
                document.querySelector('#extra-1').classList.remove('d-none');
                pageNum.setAttribute('value', 1);
            }else if (pageNum.getAttribute('value') == 3){
                document.querySelector('#content-3').classList.add('d-none');
                document.querySelector('#content-2').classList.remove('d-none');
                pageNum.setAttribute('value', 2);
            }
        }
        // Efek transisi tiap ganti halaman
        const contentController = document.querySelectorAll('#content-controller');
        contentController.forEach(function(item){
            item.addEventListener('click', function(e){
                if (e.target.classList.contains('btn-order-next')){
                    $([document.documentElement, document.body]).animate({
                        scrollTop : document.querySelector('#pendaftaran-menu').offsetTop - 75
                    });
                    get_content_data_normal();
                }else if (e.target.classList.contains('btn-order-prev')){
                    $([document.documentElement, document.body]).animate({
                        scrollTop : document.querySelector('#pendaftaran-menu').offsetTop - 75
                    });
                    get_content_data_reverse();
                }
            });
        });
    // End of Event untuk Mengelola Halaman Pendaftaran
});
