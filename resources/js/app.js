require('./bootstrap');

window.$ = window.jQuery = require('jquery');
require('bootstrap');
import Chart from 'chart.js/auto';
import 'chartjs-adapter-date-fns/';
import { tr } from 'date-fns/locale'
window.Chart = Chart;
window.tr = tr;