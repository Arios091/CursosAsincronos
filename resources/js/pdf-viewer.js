window.renderPDF = function (containerId, pdfUrl) {
    var container = document.getElementById(containerId);
    if (!container) return;

    container.innerHTML = '<div class="text-center py-5"><i class="fas fa-spinner fa-spin fa-2x"></i><p class="mt-2">Cargando PDF...</p></div>';

    pdfjsLib.getDocument(pdfUrl).promise.then(function (pdf) {
        container.innerHTML = '';
        var pageCount = pdf.numPages;

        var nav = document.createElement('div');
        nav.className = 'd-flex justify-content-between align-items-center mb-3 sticky-top bg-white py-2';
        nav.style.borderBottom = '1px solid #dee2e6';
        nav.innerHTML = '\
            <small class="text-muted">Pagina <span id="pageNum">1</span> de ' + pageCount + '</small>\
            <div>\
                <button class="btn btn-sm btn-outline-secondary" id="prevPage"><i class="fas fa-chevron-left"></i></button>\
                <button class="btn btn-sm btn-outline-secondary" id="nextPage"><i class="fas fa-chevron-right"></i></button>\
            </div>';
        container.appendChild(nav);

        var canvasWrapper = document.createElement('div');
        canvasWrapper.className = 'text-center';
        container.appendChild(canvasWrapper);

        var canvas = document.createElement('canvas');
        canvasWrapper.appendChild(canvas);
        var ctx = canvas.getContext('2d');

        var currentPage = 1;

        function renderPage(num) {
            pdf.getPage(num).then(function (page) {
                var viewport = page.getViewport({ scale: 1.5 });
                canvas.height = viewport.height;
                canvas.width = viewport.width;
                canvas.style.maxWidth = '100%';
                canvas.style.height = 'auto';

                return page.render({
                    canvasContext: ctx,
                    viewport: viewport
                }).promise;
            }).then(function () {
                document.getElementById('pageNum').textContent = num;
            });
        }

        renderPage(currentPage);

        document.getElementById('prevPage').addEventListener('click', function () {
            if (currentPage > 1) {
                currentPage--;
                renderPage(currentPage);
            }
        });

        document.getElementById('nextPage').addEventListener('click', function () {
            if (currentPage < pageCount) {
                currentPage++;
                renderPage(currentPage);
            }
        });
    }).catch(function (err) {
        container.innerHTML = '<div class="alert alert-danger"><i class="fas fa-exclamation-circle mr-1"></i> Error al cargar el PDF: ' + err.message + '</div>';
    });
};
