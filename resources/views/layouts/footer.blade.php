<!-- jQuery & Bootstrap -->
<script src="{{ asset('assets/libs/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ asset('assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>

<!-- Additional Libraries -->
<script src="{{ asset('assets/js/app.min.js') }}"></script>
<script src="{{ asset('assets/libs/apexcharts/dist/apexcharts.min.js') }}"></script>
<script src="{{ asset('assets/libs/simplebar/dist/simplebar.js') }}"></script>
<script src="{{ asset('assets/js/dashboard.js') }}"></script>

<!-- jQuery (CDN & Local) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- DataTables -->
<script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.5/js/dataTables.bootstrap5.min.js"></script>

<!-- Dropdown Notification -->
<script src="{{ asset('assets/js/dropdownNotification.js') }}"></script>

<!-- Inisialisasi DataTable -->
<script>
    $(document).ready(function() {

        var url = window.location.pathname;
        var orderConfig;

        if (url === '/notifications') {
            orderConfig = [
                [2, "desc"]
            ];
        } else if (url === '/categories') {
            orderConfig = [
                [0, "asc"]
            ];
        } else {
            orderConfig = [
                [5, "desc"]
            ];
        }

        // DataTable Biasa
        $('#myTable').DataTable({
            "paging": true,
            "searching": true,
            "ordering": true,
            "order": orderConfig
        });
    });
</script>

<!-- SweetAlert Feedback Script -->
<script>
    $(document).ready(function() {
        // Check for success message
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                confirmButtonText: 'OK',
                confirmButtonColor: '#28a745',
                timer: 3000,
                timerProgressBar: true,

                customClass: {
                    popup: 'my-custom-popup'
                }
            });
        @endif

        // Check for error message
        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: '{{ session('error') }}',
                confirmButtonText: 'OK',
                confirmButtonColor: '#dc3545'
            });
        @endif

        // Check for validation errors
        @if($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Terdapat Kesalahan!',
                html: '<ul class="text-left">' + 
                      @foreach($errors->all() as $error)
                          '<li>{{ $error }}</li>' +
                      @endforeach
                      '</ul>',
                confirmButtonText: 'OK',
                confirmButtonColor: '#dc3545'
            });
        @endif
        
        // Prevent modal from showing when cancel/back button is clicked
        $(document).on('click', '.btn-danger', function(e) {
            // If it's a link (not button), allow normal navigation
            if ($(this).is('a')) {
                return true;
            }
            
            // If it's a button, check if it's a cancel/back button
            var buttonText = $(this).text().toLowerCase();
            if (buttonText.includes('kembali') || buttonText.includes('cancel') || buttonText.includes('batal')) {
                e.preventDefault();
                e.stopPropagation();
                
                // Try to go back in history, fallback to specific routes
                if (window.history.length > 1) {
                    window.history.back();
                } else {
                    // Fallback redirect based on current URL
                    var currentPath = window.location.pathname;
                    if (currentPath.includes('document')) {
                        window.location.href = '{{ route("document_revision.index") }}';
                    } else if (currentPath.includes('categor')) {
                        window.location.href = '{{ route("categories.index") }}';
                    } else {
                        window.location.href = '/dashboard';
                    }
                }
                return false;
            }
        });
        
        // Prevent form submission when Enter is pressed on cancel buttons
        $(document).on('keydown', '.btn-danger', function(e) {
            if (e.which === 13 || e.which === 32) { // Enter or Space
                var buttonText = $(this).text().toLowerCase();
                if (buttonText.includes('kembali') || buttonText.includes('cancel') || buttonText.includes('batal')) {
                    e.preventDefault();
                    $(this).click();
                    return false;
                }
            }
        });
    });
</script>

@yield('customJS')
</body>

</html>
