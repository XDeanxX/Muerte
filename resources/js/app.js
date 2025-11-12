
import './bootstrap';
import 'driver.js/dist/driver.css';
import 'vanilla-toast/vanilla-toast.css';
import toast from 'vanilla-toast';
import {driver} from 'driver.js'
import Chart from 'chart.js/auto';

import 'boxicons/css/boxicons.min.css';

window.toast = toast;
window.Chart = Chart;

window.driver = { 
    js: { 
        driver: driver 
    } 
};

window.addEventListener('show-toast', e => {
    
    const data = e.detail[0] || e.detail; 
    console.log(e)
    const message = data.message 
    const type = data.type 
    const duration =  1500;
    const fadeDuration = 300;
    
    window.toast.success(message, {
        type: type,
        duration: duration,
        fadeDuration: fadeDuration,
        closeButtom: true,
    });
});

window.addEventListener('error-toast', e => {
    
    const data = e.detail[0] || e.detail; 
    console.log(e)
    const message = data.message 
    const type = data.type 
    const duration =  1500;
    const fadeDuration = 300;
    
    window.toast.error(message, {
        type: type,
        duration: duration,
        fadeDuration: fadeDuration,
        closeButtom: true,
    });
});

/**
 * 
 * @param {string} canvasId
 */
window.generarPDF = function (canvasId) {

    const miGrafico = charts[canvasId];

    if (!miGrafico) {
        console.error(`No se logro imprimir el reporte`);
        return;
    }

    const chartDataURL = miGrafico.canvas.toDataURL('image/png');

    if (canvasId === 'chart1') {
        Livewire.dispatch('generate-pdf-event-chart1', { dataUrl: chartDataURL });
    }

    if (canvasId === 'chart2') {
        Livewire.dispatch('generate-pdf-event-chart2', { dataUrl: chartDataURL });
    }

    if (canvasId === 'chart3') {
        Livewire.dispatch('generate-pdf-event-chart3', { dataUrl: chartDataURL });
    }

    if (canvasId === 'chart4') {
        Livewire.dispatch('generate-pdf-event-chart4', { dataUrl: chartDataURL });
    }
    
};

// GRAFICAS DE REPORTES
const charts = {};
function crearGrafico(canvasId, tipo, labels, values, titulo, borderColor, bgColor) {
  const ctx = document.getElementById(canvasId);

  if (ctx) {
    if (charts[canvasId] && typeof charts[canvasId].destroy === 'function') {
      charts[canvasId].destroy();
    }

    charts[canvasId] = new Chart(ctx, {
      type: tipo,
      data: {
        labels: labels,
        datasets: [{
          label: titulo,
          data: values,
          backgroundColor: Array.isArray(bgColor) ? bgColor : bgColor,
          borderColor: Array.isArray(borderColor) ? borderColor : borderColor,
          borderWidth: 1
        }]
      },
      options: {
        responsive: true,
        scales: {
          y: {
            beginAtZero: true
          }
        }
      }
    });
  }
} 

document.addEventListener('DOMContentLoaded', function () {
    Livewire.on('chart-updated', (event) => {
        const { id, datos } = event;
        crearGrafico(
            datos.canvasId,
            datos.tipo,
            datos.labels,
            datos.values,
            datos.titulo,
            datos.borderColor,
            datos.bgColor
        );
    });
});

    document.addEventListener('DOMContentLoaded', function() {
        // 1. Ocultar el toast con animación
        window.hideToast = function() {
            const toast = document.getElementById('cumpleanos-toast');
            if (toast) {
                toast.style.transform = 'translateX(100%)';
                toast.style.opacity = '0';
                setTimeout(() => {
                    toast.style.display = 'none';
                }, 500);
            }
        };

        // 2. Buscador en tiempo real
        const searchInput = document.getElementById("searchInput");
        if (searchInput) {
            searchInput.addEventListener("keyup", function () {
                const filter = this.value.toLowerCase();
                const rows = document.querySelectorAll("#trabajadoresTable tbody tr");

                rows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    row.style.display = text.includes(filter) ? "" : "none";
                });
            });
        }

        // 3. Mostrar notificación de cumpleaños
        function showCumpleanosNotification() {
            const cumpleanerosHoy = [];
            const rows = document.querySelectorAll('#trabajadoresTable tbody tr');

            rows.forEach(row => {
                if (row.classList.contains('birthday-today-row')) {
                    const celdas = row.querySelectorAll('td');
                    // El nombre completo está en la primera celda ahora (índice 0)
                    const nombreCompleto = celdas[0]?.textContent.replace(/[🎂🎁]/g, '').trim(); 

                    if (nombreCompleto && !cumpleanerosHoy.includes(nombreCompleto)) {
                        cumpleanerosHoy.push(nombreCompleto);
                    }
                }
            });

            if (cumpleanerosHoy.length > 0) {
                const toast = document.getElementById('cumpleanos-toast');
                const toastTitle = document.getElementById('toast-title');
                const toastMessage = document.getElementById('toast-message');

                toastTitle.textContent = cumpleanerosHoy.length === 1
                    ? '🎉 ¡Feliz Cumpleaños!'
                    : '🎊 ¡Felicidades Múltiples!';

                toastMessage.textContent = cumpleanerosHoy.length === 1
                    ? `${cumpleanerosHoy[0]} está cumpliendo años hoy 🥳`
                    : `${cumpleanerosHoy.join(', ')} están cumpliendo años hoy 🎁`;

                toast.style.display = 'block';
                setTimeout(() => {
                    toast.style.transform = 'translateX(0)';
                    toast.style.opacity = '1';
                }, 10);

                setTimeout(window.hideToast, 7000);
            }
        }

        function hayCumpleañosHoy() {
            return document.querySelector('#trabajadoresTable tbody tr.birthday-today-row') !== null;
        }

        setTimeout(() => {
            hayCumpleañosHoy() ? showCumpleanosNotification() : mostrarNotificacionPrueba();
        }, 1000);

    });

