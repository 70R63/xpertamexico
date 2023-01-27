import './bootstrap';

import route from 'ziggy';
import { Ziggy } from './ziggy';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

window.route = route;
window.Ziggy = Ziggy;


