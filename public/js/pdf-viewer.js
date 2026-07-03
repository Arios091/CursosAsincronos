/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "/";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 1);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./node_modules/pdfjs-dist/build/pdf.js":
/*!**********************************************!*\
  !*** ./node_modules/pdfjs-dist/build/pdf.js ***!
  \**********************************************/
/*! no static exports found */
/***/ (function(module, exports) {

throw new Error("Module parse failed: Unexpected character '#' (1413:9)\nYou may need an appropriate loader to handle this file type, currently no loaders are configured to process this file. See https://webpack.js.org/concepts#loaders\n| \n| class PDFDocumentLoadingTask {\n>   static #docId = 0;\n| \n|   constructor() {");

/***/ }),

/***/ "./resources/js/pdf-viewer.js":
/*!************************************!*\
  !*** ./resources/js/pdf-viewer.js ***!
  \************************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var pdfjs_dist__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! pdfjs-dist */ "./node_modules/pdfjs-dist/build/pdf.js");
/* harmony import */ var pdfjs_dist__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(pdfjs_dist__WEBPACK_IMPORTED_MODULE_0__);

pdfjs_dist__WEBPACK_IMPORTED_MODULE_0__["GlobalWorkerOptions"].workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.worker.min.js';
window.renderPDF = function (containerId, pdfUrl) {
  var container = document.getElementById(containerId);
  if (!container) return;
  container.innerHTML = '<div class="text-center py-5"><i class="fas fa-spinner fa-spin fa-2x"></i><p class="mt-2">Cargando PDF...</p></div>';
  pdfjs_dist__WEBPACK_IMPORTED_MODULE_0__["getDocument"](pdfUrl).promise.then(function (pdf) {
    container.innerHTML = '';
    var pageCount = pdf.numPages;
    var nav = document.createElement('div');
    nav.className = 'd-flex justify-content-between align-items-center mb-3 sticky-top bg-white py-2';
    nav.style.borderBottom = '1px solid #dee2e6';
    nav.innerHTML = "\n            <small class=\"text-muted\">Pagina <span id=\"pageNum\">1</span> de ".concat(pageCount, "</small>\n            <div>\n                <button class=\"btn btn-sm btn-outline-secondary\" id=\"prevPage\"><i class=\"fas fa-chevron-left\"></i></button>\n                <button class=\"btn btn-sm btn-outline-secondary\" id=\"nextPage\"><i class=\"fas fa-chevron-right\"></i></button>\n            </div>\n        ");
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
        var viewport = page.getViewport({
          scale: 1.5
        });
        canvas.height = viewport.height;
        canvas.width = viewport.width;
        canvas.style.maxWidth = '100%';
        canvas.style.height = 'auto';
        var renderContext = {
          canvasContext: ctx,
          viewport: viewport
        };
        return page.render(renderContext).promise;
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
  })["catch"](function (err) {
    container.innerHTML = '<div class="alert alert-danger"><i class="fas fa-exclamation-circle mr-1"></i> Error al cargar el PDF: ' + err.message + '</div>';
  });
};

/***/ }),

/***/ 1:
/*!******************************************!*\
  !*** multi ./resources/js/pdf-viewer.js ***!
  \******************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! C:\Users\riosa\Documents\PPP\Proyectos\CursosAsincronos\resources\js\pdf-viewer.js */"./resources/js/pdf-viewer.js");


/***/ })

/******/ });