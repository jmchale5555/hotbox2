import 'flowbite';
import { Chart, registerables } from 'chart.js';
window.Chart = Chart;
Chart.register(...registerables);


import Alpine from 'alpinejs';

window.Alpine = Alpine;
Alpine.start();


