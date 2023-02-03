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
                firstGradient: {
                    '0%': {transform: 'translateY(-50%) translateX(-50%) rotate(-20deg) translateX(20%)'},
                    '25%': {transform: 'translateY(-50%) translateX(-50%) skew(-15deg, -15deg) rotate(80deg) translateX(30%)'},
                    '50%': {transform: 'translateY(-50%) translateX(-50%) rotate(180deg) translateX(25%)'},
                    '75%': {transform: 'translateY(-50%) translateX(-50%) skew(15deg, 15deg) rotate(240deg) translateX(15%)'},
                    '100%': {transform: 'translateY(-50%) translateX(-50%) rotate(340deg) translateX(20%)'},
                },
                secondGradient: {
                    '0%': {transform: 'translateY(-50%) translateX(-50%) rotate(40deg) translateX(-20%)'},
                    '25%': {transform: 'translateY(-50%) translateX(-50%) skew(15deg, 15deg) rotate(110deg) translateX(-5%)'},
                    '50%': {transform: 'translateY(-50%) translateX(-50%) rotate(210deg) translateX(-35%)'},
                    '75%': {transform: 'translateY(-50%) translateX(-50%) skew(-15deg, -15deg) rotate(300deg) translateX(-10%)'},
                    '100%': {transform: 'translateY(-50%) translateX(-50%) rotate(400deg) translateX(-20%)'},
                },
                thirdGradient: {
                    '0%': {transform: 'translateY(-50%) translateX(-50%) translateX(-15%) translateY(10%)'},
                    '20%': {transform: 'translateY(-50%) translateX(-50%) translateX(20%) translateY(-30%)'},
                    '40%': {transform: 'translateY(-50%) translateX(-50%) translateX(-25%) translateY(-15%)'},
                    '60%': {transform: 'translateY(-50%) translateX(-50%) translateX(30%) translateY(20%)'},
                    '80%': {transform: 'translateY(-50%) translateX(-50%) translateX(5%) translateY(35%)'},
                    '100%': {transform: 'translateY(-50%) translateX(-50%) translateX(-15%) translateY(10%)'},
                },
                spotlight: {
                    '0%': {opacity: 1},
                    '50%': {opacity: 1},
                    '100%': {opacity: 1},
                },
            },
            animation: {
                'gradient-1': 'firstGradient 11s ease infinite',
                'gradient-2': 'secondGradient 11s ease infinite reverse',
                'gradient-3': 'thirdGradient 11s ease infinite',
                'spotlight': 'spotlight 10s infinite',
            },
        },
    },
    plugins: [
        require('@tailwindcss/forms'),
    ],
}
