$(document).ready(function () {
    const table = $('#tableDocument').DataTable({
        paging: true,
        searching: true,
        ordering: true,
        order: [5, 'desc'],
        dom: 'lrtip'
    })

    $('#searchCode').on('keyup', function () {
        table.column(0).search(this.value).draw()
    })

    // === Custom search box ===
    $('#customSearch').on('keyup', function () {
        table.search(this.value).draw()
    })

    // === Generate dropdown tahun ===
    function updateTahunDropdown () {
        let tahunSet = new Set()
        $('#tableDocument tbody tr').each(function () {
            const tanggal = $(this).find('td').eq(5).text()
            const match = tanggal.match(/\d{2}\/\d{2}\/(\d{4})/)
            if (match && match[1]) tahunSet.add(match[1])
        })

        const tahunSelect = $('#filterTahun')
        tahunSelect.empty().append('<option value="">Semua Tahun</option>')
        Array.from(tahunSet)
            .sort()
            .reverse()
            .forEach(t => {
                tahunSelect.append(`<option value="${t}">${t}</option>`)
            })
    }
    updateTahunDropdown()

    // === Filter logic ===
    function applyAllFilters () {
        const status = $('input[name="filterDocument"]:checked').val()
        const kategori = $('#filterKategori').val()
        const tahun = $('#filterTahun').val()

        // Filter Status
        switch (status) {
            case 'aktif':
                table.column(3).search('Disetujui', true, false)
                break
            case 'kadaluarsa':
                table.column(3).search('Expired', true, false)
                break
            case 'prosesrev':
                table.column(3).search('Proses Revisi', true, false)
                break
            case 'pengajuanrev':
                table.column(3).search('Pengajuan Revisi', true, false)
                break
            default:
                table.column(3).search('')
        }

        // ✅ Filter kategori di kolom ke-3 (index 2)
        table.column(2).search(kategori || '', false, false)

        // ✅ Filter tahun di kolom Created At (index 5)
        table.column(5).search(tahun || '', false, false)

        table.draw()
    }

    // === Event listeners ===
    $('input[name="filterDocument"]').on('change', applyAllFilters)
    $('#filterKategori, #filterTahun').on('change', applyAllFilters)
})
