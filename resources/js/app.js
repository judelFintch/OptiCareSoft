import './bootstrap';
import Alpine from 'alpinejs';

window.Alpine = Alpine;
window.loadApexCharts = async () => {
    if (! window.ApexCharts) {
        window.ApexCharts = (await import('apexcharts')).default;
    }

    return window.ApexCharts;
};

Alpine.start();
