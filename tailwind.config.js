/** @type {import('tailwindcss').Config} */

const colors = require('tailwindcss/colors')

module.exports = {
    prefix: 'tw-',
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./app/Datatables/**/*.php",
    ],
    theme: {
        // extend: {
        //     colors: {
        //         'primary': '#0e406a',
        //         'primary-light': '#145994',
        //         'accent': '#ffc530',

        //         'success': #4caf50,    // green-500
        //         'danger': #f44336,     // red-500
        //         'warning': #ffc107,    // yellow-400
        //         'info': #1e88e5,       // sky-500
        //     },
        // },
    },
    plugins: [
        require('@tailwindcss/forms'),
    ]
}
