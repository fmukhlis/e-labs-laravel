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
        document.querySelector('#doctor-list').classList.add('d-none');
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
    // End



    // Inisialisasi Metode Bayar
    if(document.querySelector('#metodebayar').value != 'BPJS'){
        document.querySelector('#nosep').readOnly = true;
    }
    // End



    // AJAX Request for Managing Doctor 
        function addDoctor(doctorData){
            // Hide Failed Alert (If Exists)
            document.querySelector('#error-doc-alert').classList.add('d-none');
            $.ajax({
                url: '/pendaftaran/manageDoctor',
                method: 'POST',
                data: {
                    kode: doctorData[0],
                    spesialisasi: doctorData[1],
                    nama: doctorData[2],
                    no_skp: doctorData[3],
                    no_sertif_skp: doctorData[4],
                    ttd: doctorData[5],
                    alamat: doctorData[6],
                    alamat_praktek: doctorData[7],
                    no_telp: doctorData[8],
                    no_hp: doctorData[9],
                    email: doctorData[10]
                },
                dataType: 'json',
                success: function(data) {
                    // Show Success Alert
                    resetDoctorModal();
                    var htmlalert = document.createElement('div');
                    htmlalert.innerHTML = data.alert;
                    document.querySelector('#select-doc-1').prepend(htmlalert);
                },
                error: function(){
                    // Show Failed Alert
                    document.querySelector('#error-doc-alert').classList.remove('d-none');
                }
            })
        }
        function updDoctor(nolab, doctorCode, doctorName, doctorSignature){
            $.ajax({
                url: '/pendaftaran/'+selectedDoctorCode+'/manageDoctor',
                method: 'PUT',
                data: {
                    kode: doctorCode,
                    nama: doctorName,
                    ttd: doctorSignature
                },
                success: function() {
                    refreshDoctorTable(nolab);
                    document.querySelector('button#updateDoctor').previousElementSibling.click();
                },
                error: function(){
                    // Show Failed Alert
                    document.querySelector('#error-edit-doc-alert').classList.remove('d-none');
                    document.querySelector('#kodedokter-edit').classList.add('is-invalid');
                    document.querySelector('#namadokter-edit').classList.add('is-invalid');
                    document.querySelector('#ttddokter-edit').classList.add('is-invalid');
                }
            })
        }
        function destroyDoctor(){
            $.ajax({
                url: '/pendaftaran/'+selectedDoctorCode+'/manageDoctor',
                method: 'DELETE',
                success: function() {
                    // Type function here...
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
        if(document.querySelector('#doctor-list')){
            document.querySelector('#doctor-list').classList.add('d-none');
        } 
        $(document).on('keyup', '#pilihdokter', function() {
            let pilih_dokter = $(this).val();
            $.ajax({
                url: "/pendaftaran/func/getDoctorData",
                method: 'GET',
                data: {
                    searchValue: pilih_dokter
                },
                dataType: 'json',
                success: function(data) {
                    $('#doctor-list').html('');
                    $('#doctor-list').html(data.table_data);
                }
            });
            document.querySelector('#doctor-list').classList.remove('d-none');
            if (pilih_dokter == ''){
                document.querySelector('#doctor-list').classList.add('d-none');
            }
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
                        resetEditDoctorModal();
                    }
                });
            });
        }
    // End



    // Function For Managing Halaman Doctor Modal
        function resetDoctorModal(halaman){
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
        function resetEditDoctorModal(){
            if(document.querySelector('#btn-close-doctor-alert')){
                document.querySelector('#btn-close-doctor-alert').click();
            }
            document.querySelector('#error-edit-doc-alert').classList.add('d-none');
            document.querySelector('#kodedokter-edit').value = document.querySelector('tbody#doctorTable tr').children[1].innerHTML;
            document.querySelector('#namadokter-edit').value = document.querySelector('tbody#doctorTable tr').children[2].innerHTML;
            document.querySelector('#ttddokter-edit').value = '';
            document.querySelector('#kodedokter-edit').classList.remove('is-invalid');
            document.querySelector('#namadokter-edit').classList.remove('is-invalid');
            document.querySelector('#ttddokter-edit').classList.remove('is-invalid');
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
                        inputtedDoctorData.push(document.querySelector('#ttddokter').value);
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
                        // Hide Failed Alert (If Exists)
                        document.querySelector('#error-edit-doc-alert').classList.add('d-none');
                        document.querySelector('#kodedokter-edit').classList.remove('is-invalid');
                        document.querySelector('#namadokter-edit').classList.remove('is-invalid');
                        document.querySelector('#ttddokter-edit').classList.remove('is-invalid');
                        // Get Inputed Data
                        const nolab = document.querySelector('#nolab').value;
                        const kode = document.querySelector('#kodedokter-edit').value;
                        const nama = document.querySelector('#namadokter-edit').value;
                        const ttd = document.querySelector('#ttddokter-edit').value;
                        // Update Doctor pada Tabel 'dokters' (DB)
                        updDoctor(nolab, kode, nama, ttd);
                        // Disabled the EditDoctor and RemoveDoctor Button
                        document.querySelector('#remove-tabledoctor').classList.add('disabled');
                        document.querySelector('#edit-tabledoctor').classList.add('disabled');
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