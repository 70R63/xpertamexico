import './bootstrap';

import route from 'ziggy';
import { Ziggy } from './ziggy';
import ziggy from 'resources/js/app.js';
import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

window.route = route;
window.Ziggy = Ziggy;


