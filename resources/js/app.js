import './bootstrap';

import route from 'ziggy';
import { ZiggyVue } from 'ziggy/dist/vue';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

window.route = route;
window.Ziggy = Ziggy;


