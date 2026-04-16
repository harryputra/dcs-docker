<!-- Modal Preview Dokumen -->
<div class="modal fade" id="previewModal" tabindex="-1" aria-labelledby="previewModalLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-xl modal-dialog-centered" style="height: 90vh;">
        <div class="p-0 modal-content" style="height: 100%;">
            <div class="bg-white modal-header border-bottom shadow-sm">
                <div class="d-flex align-items-center">
                    <div class="p-2 rounded bg-admin-subtle me-3">
                        <i class="ti ti-file-text fs-6 text-admin"></i>
                    </div>
                    <div>
                        <h5 class="mb-0 modal-title fw-bold" id="previewModalLabel">Pratinjau Dokumen</h5>
                        <small class="text-muted">Mode Lihat Saja (Secure View)</small>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="p-0 modal-body position-relative bg-light d-flex align-items-center justify-content-center" style="height: calc(100% - 70px); overflow: hidden;">
                <!-- Loader -->
                <div id="previewLoader" class="text-center position-absolute top-50 start-50 translate-middle w-100">
                    <div class="spinner-border text-admin mb-3" role="status" style="width: 3rem; height: 3rem;"></div>
                    <p class="fw-medium text-secondary">Menyiapkan pratinjau aman...</p>
                </div>
                
                <!-- PDF Viewer -->
                <iframe id="pdfPreviewFrame" src="" width="100%" height="100%" frameborder="0" style="display: none; background: #525659; border: none;"></iframe>
                
                <!-- Overlay to discourage right-click/some interactions -->
                <div id="pdfOverlay" class="top-0 start-0 position-absolute w-100 h-100" style="display: none; background: transparent; z-index: 10;"></div>
            </div>
            <div class="bg-white modal-footer border-top py-2">
                <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
    /**
     * Helper to convert Base64 to Blob
     */
    function base64ToBlob(base64, mime) {
        const byteCharacters = atob(base64);
        const byteArrays = [];
        
        for (let offset = 0; offset < byteCharacters.length; offset += 512) {
            const slice = byteCharacters.slice(offset, offset + 512);
            const byteNumbers = new Array(slice.length);
            for (let i = 0; i < slice.length; i++) {
                byteNumbers[i] = slice.charCodeAt(i);
            }
            const byteArray = new Uint8Array(byteNumbers);
            byteArrays.push(byteArray);
        }
        
        return new Blob(byteArrays, { type: mime });
    }

    /**
     * Preview document using JSON-wrapped Base64 to bypass IDM interception.
     * IDM cannot intercept JSON data transfers.
     */
    async function previewDocument(url) {
        const modalElement = document.getElementById('previewModal');
        const modal = new bootstrap.Modal(modalElement);
        const frame = document.getElementById('pdfPreviewFrame');
        const loader = document.getElementById('previewLoader');
        
        // Show modal and loader
        modal.show();
        frame.style.display = 'none';
        loader.style.display = 'block';
        
        try {
            // Fetch document data (JSON)
            const previewUrl = new URL(url, window.location.origin);
            previewUrl.searchParams.set('t', Date.now());

            const response = await fetch(previewUrl.toString(), {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });
            
            if (!response.ok) throw new Error('Gagal mengambil data dokumen');
            
            const data = await response.json();
            
            if (!data.success) throw new Error(data.message || 'Gagal memproses dokumen');
            
            // Reconstruct Blob from Base64 data
            const pdfBlob = base64ToBlob(data.content, data.mime || 'application/pdf');
            const blobUrl = URL.createObjectURL(pdfBlob);
            
            // Set source to blob URL with toolbar hidden
            frame.src = blobUrl + '#toolbar=0&navpanes=0&scrollbar=0';
            
            // Wait for iframe to load
            frame.onload = function() {
                loader.style.display = 'none';
                frame.style.display = 'block';
            };
            
            // Clean up Blob URL when modal is closed
            modalElement.addEventListener('hidden.bs.modal', function() {
                if (frame.src.startsWith('blob:')) {
                    URL.revokeObjectURL(frame.src.split('#')[0]);
                    frame.src = '';
                }
            }, { once: true });
            
        } catch (error) {
            console.error(error);
            loader.innerHTML = `<div class="alert alert-danger mx-3 my-5 shadow-sm text-center">
                <i class="ti ti-alert-circle fs-7 d-block mb-3"></i>
                <h5 class="fw-bold">Gagal Memuat Dokumen</h5>
                <p class="mb-0 text-muted">${error.message}. Silakan coba lagi nanti.</p>
            </div>`;
        }
    }
</script>
