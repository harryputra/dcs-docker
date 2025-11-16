$(document).ready(function () {
    // DataTable Approval
    let tableApproval = $('#tableApproval').DataTable({
        paging: true,
        searching: true,
        ordering: true,
        dom: "<'d-flex justify-content-between'lf>rtip",
        order: [5, 'desc']
    })

    let searchBoxApproval = $('#tableApproval_wrapper .dataTables_filter')
    searchBoxApproval.addClass('d-flex align-items-center')

    let radioButtonsApproval = `
                <div class="me-3">
                    <label class="me-1"><input type="radio" name="filterApproval" value="all" checked> Semua</label>
                    <label class="me-1"><input type="radio" name="filterApproval" value="approved"> Approved</label>
                    <label class="me-1"><input type="radio" name="filterApproval" value="pending"> Pending</label>
                </div>
            `

    searchBoxApproval.prepend(radioButtonsApproval)

    $('input[name="filterApproval"]').on('change', function () {
        let filterValue = $('input[name="filterApproval"]:checked').val()
        let labelText = 'Menampilkan: Semua'
        //masukin fungsi untuk setiap radio disini
        if (filterValue === 'all') {
            tableApproval.column(3).search('').draw()
            labelText = 'Menampilkan: Semua'
        } else if (filterValue === 'approved') {
            tableApproval.column(3).search('Disetujui', true, false).draw()
            labelText = 'Menampilkan: Disetujui'
        } else if (filterValue === 'pending') {
            tableApproval.column(3).search('Draft', true, false).draw()
            labelText = 'Menampilkan: Pending'
        }

        $('#dynamicLabel').html(labelText)
    })

    // Approve Logic
    $(document).on('click', '#btn-modalTerima', function () {
        let id = $(this).data('id') // get the data attribute value from the button
        let status = $(this).data('status') // get the data attribute value from the button
        fetchDocumentData(id) // fetch data based on the id
    })

    $(document).on('click', '#btn-modalTolak', function () {
        let id = $(this).data('id') // get the data attribute value from the button
        let status = $(this).data('status') // get the data attribute value from the button
        fetchDocumentData(id) // fetch data based on the id
    })

    function fetchDocumentData (id) {
        $.ajax({
            url: '/document_revision/data/', // replace with your endpoint
            type: 'GET', // or 'POST', depending on your backend logic
            data: {
                id: id
            },
            success: function (response) {
                populateModal(response.data) // assume the server sends back a response with 'data'
            },
            error: function (xhr) {
                console.error('Error fetching document data:', xhr)
            }
        })
    }

    function populateModal (data) {
        $('#reason_container').css('display', 'none')
        $('#acc_judul_doc').val(data.judul)

        console.log('Document code:', data.code)
        console.log('Classification ID:', data.classification_id)
        console.log(
            'Has /-/ ?',
            data.code ? data.code.indexOf('/-/') : 'no code'
        )

        // Jika code sudah ada (dokumen revisi dengan nomor lengkap), sembunyikan field klasifikasi
        if (
            data.code &&
            data.code !== '' &&
            data.code !== null &&
            data.code.indexOf('/-/') === -1
        ) {
            console.log('HIDING classification field - document has full code')
            // Dokumen sudah punya nomor lengkap, sembunyikan klasifikasi
            $('#classification_container').hide()
            $('#classification_select').removeAttr('required')

            // Tambahkan hidden input untuk tetap kirim classification_id
            if ($('#hidden_classification_id').length === 0) {
                $('#classification_select').after(
                    '<input type="hidden" name="classification_id" id="hidden_classification_id" value="' +
                        data.classification_id +
                        '">'
                )
            } else {
                $('#hidden_classification_id').val(data.classification_id)
            }
        } else {
            console.log(
                'SHOWING classification field - new document or partial code'
            )
            // Dokumen baru atau belum ada nomor lengkap, tampilkan klasifikasi
            $('#classification_container').show()

            // Load classifications
            const classificationSelect = $('#classification_select')

            // Clear existing options except first
            classificationSelect.find('option:not(:first)').remove()

            // Fetch classifications
            fetch('/api/classifications/all')
                .then(response => response.json())
                .then(classifications => {
                    classifications.forEach(classification => {
                        const option = new Option(
                            `${classification.kode_klasifikasi} - ${classification.nama_klasifikasi}`,
                            classification.id
                        )
                        classificationSelect.append(option)
                    })

                    // Set value jika ada (parsial code dengan /-/-)
                    if (
                        data.classification_id &&
                        data.classification_id !== '' &&
                        data.classification_id !== null
                    ) {
                        classificationSelect.val(data.classification_id)
                    } else {
                        classificationSelect.val('')
                    }
                })
                .catch(error =>
                    console.error('Error loading classifications:', error)
                )

            classificationSelect.attr('disabled', false)
            classificationSelect.attr('required', true)
            $('#hidden_classification_id').remove()
        }

        // Jika code sudah ada, tampilkan value dan disable field. Jika belum ada, biarkan editable untuk Pengendali Dokumen
        if (data.code && data.code !== '' && data.code !== null) {
            $('#acc_code_input').val(data.code)
            $('#acc_code_input').attr('readonly', true)
            $('#acc_code_input').attr('disabled', true)
            $('#acc_code_input').removeAttr('required')
            $('#acc_code_input').closest('.row').hide() // Sembunyikan field jika code sudah ada
        } else {
            $('#acc_code_input').val('')
            $('#acc_code_input').closest('.row').show() // Tampilkan field jika code belum ada
            // Hanya enable untuk Pengendali Dokumen
            if (data.roles.includes('pengendali-dokumen')) {
                $('#acc_code_input').attr('readonly', false)
                $('#acc_code_input').attr('disabled', false)
                $('#acc_code_input').attr('required', true)
            } else {
                $('#acc_code_input').attr('readonly', true)
                $('#acc_code_input').attr('disabled', true)
            }
        }

        $('#acc_category_doc').val(data.category)
        $('#acc_uplodeder_doc').val(data.uploader)
        $('#acc_url_doc').attr('href', data.url)
        $('#acc_status1_doc').prop('checked', data.acc_format)
        $('#acc_status2_doc').prop('checked', data.acc_content)

        if (data.reason != '') {
            $('#reason_container').css('display', 'block')
            $('#acc_reason').val(data.reason)
        }

        $('#rev_judul_doc').val(data.judul)
        $('#rev_code_doc').val(data.code || '-')
        $('#rev_category_doc').val(data.category)
        $('#rev_uploader_doc').val(data.uploader)
        $('#rev_url_doc').attr('href', data.url)

        if (
            data.status !== 'Draft' ||
            (data.roles.includes('administrator') &&
                data.acc_format &&
                data.acc_content) ||
            (data.roles.includes('bagian-mutu') && data.acc_content) ||
            (data.roles.includes('pengendali-dokumen') && data.acc_format) ||
            (data.roles.includes('bagian-mutu') && !data.acc_format) ||
            (data.roles.includes('kepala-puskesmas') &&
                (!data.acc_format || !data.acc_content))
        ) {
            $('#acc-btn').css('display', 'none')
            $('#acc_status1_doc').prop('disabled', true)
            $('#acc_status2_doc').prop('disabled', true)
        } else {
            if (data.roles.includes('administrator')) {
                $('#acc_status1_doc').prop('disabled', false)
                $('#acc_status2_doc').prop('disabled', false)
            }
            $('#acc-btn').css('display', 'block')
        }

        const dataId = data.id
        const baseUrl = '/document_approval/'

        $('#formTolak').attr('action', `${baseUrl}${dataId}`)
        $('#formTerima').attr('action', `${baseUrl}${dataId}`)
    }
})
