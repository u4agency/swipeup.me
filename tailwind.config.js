/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        "./assets/**/*.js",
        'templates/**/*.html.twig',
    ],
    theme: {
        extend: {
            inset: {
                '1/5': '20%',
                '2/5': '40%',
                '3/5': '60%',
                '4/5': '80%',
            },
            minHeight: {
                'hd': '720px',
            },
            maxHeight: {
                'fhd': '1920px',
            },
            fontFamily: {
                'swipe': ['"Reem Kufi"', 'sans-serif'],
            },
            colors: {
                'swipe': {
                    '100': "#05fefc",
                    '200': "#08e8f8",
                    '300': "#0bd2f3",
                    '400': "#0ebcef",
                    '500': "#11a6ea",
                    '600': "#1490e6",
                    '700': "#177ae1",
                    '800': "#1a64dd",
                    '900': "#1d4ed8",
                }
            },
            transitionTimingFunction: {
                'gradient': 'cubic-bezier(.1, 0, .9, 1)',
            },
            keyframes: {
                secondGradient: {
                    '0%': {transform: 'translateY(-50%) translateX(-50%) rotate(40deg) translateX(-20%)'},
                    '25%': {transform: 'translateY(-50%) translateX(-50%) skew(15deg, 15deg) rotate(110deg) translateX(-5%)'},
                    '50%': {transform: 'translateY(-50%) translateX(-50%) rotate(210deg) translateX(-35%)'},
                    '75%': {transform: 'translateY(-50%) translateX(-50%) skew(-15deg, -15deg) rotate(300deg) translateX(-10%)'},
                    '100%': {transform: 'translateY(-50%) translateX(-50%) rotate(400deg) translateX(-20%)'},
                },
            },
            animation: {
                'gradient-2': 'secondGradient 11s ease-gradient infinite',
            },
        },
    },
    plugins: [],
}
