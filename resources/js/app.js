import $ from 'jquery';

window.$ = $;
window.jQuery = $;

const moduleFiles = import.meta.glob(
    [
        '../../Modules/*/Resources/assets/js/app.js',
        '!../../Modules/*/Resources/assets/js/_*.js',
    ]
);

for (const loadModule of Object.values(moduleFiles)) {
    loadModule();
}
