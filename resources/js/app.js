import './bootstrap';
import './index.js';
import './notifications.js';

import Honeybadger from '@honeybadger-io/js';
window.Honeybadger = Honeybadger;

import Alpine from 'alpinejs';
window.Alpine = Alpine;

import 'datatables.net';
import 'jquery-ui/ui/widgets/accordion';
import 'jquery-ui/ui/widgets/autocomplete';
import floatthead from 'floatthead';
import flatpickr from 'flatpickr';

import BigPicture from 'bigpicture';
window.BigPicture = BigPicture;


import Choices from 'choices.js';
window.Choices = Choices;

import * as FilePond from 'filepond';
window.FilePond = FilePond;

Alpine.start();
