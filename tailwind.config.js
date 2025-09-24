import flowbite from "flowbite/plugin";
/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
        "./node_modules/flowbite/**/*.js",
    ],
    theme: {
        extend: {
            colors: {
                custom: {
                    primary: "#3D2369",
                },
            },
        },
    },
    plugins: [flowbite],
};
