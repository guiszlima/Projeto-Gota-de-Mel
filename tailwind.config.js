/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    safelist: [
        "h-[400px]", // ✅ evita que seja removida na produção
        "overflow-y-auto",
        "flex",
        "flex-wrap",
        "gap-6",
        "justify-start",
    ],
    theme: {
        extend: {
            screens: {
                print: { raw: "print" },
            },
        },
    },
    plugins: [],
};
