/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        "./assets/**/*.js",
        'templates/**/*.html.twig',
        'templates/*.html.twig',
        'src/**/*.php',
        "./node_modules/flowbite/**/*.js"
    ],
    theme: {
        extend: {
            backgroundPosition: {
                '50_4': '50% 50%, 50% 50%',
                '350-50_2': '350% 50%, 350% 50%',
            },
            backgroundSize: {
                '200-100': "200%, 100%",
                '300-200': "300%, 200%",
            },
            backgroundImage: {
                'rainbow': "repeating-linear-gradient(100deg,#000 0%,#000 7%,transparent 10%,transparent 12%,#000 16%), repeating-linear-gradient(100deg,#60a5fa 10%,#e879f9 15%,#60a5fa 20%,#5eead4 25%,#60a5fa 30%)"
            },
            inset: {
                '1/5': '20%', '2/5': '40%', '3/5': '60%', '4/5': '80%',
            },
            minHeight: {
                'hd': '720px',
            },
            maxHeight: {
                'fhd': '1920px',
            },
            maxWidth: {
                '64': '16rem',
                '1/2': '50%',
            },
            fontFamily: {
                'swipe': ['"Reem Kufi"', 'sans-serif'],
            },
            colors: {
                'swipe': {
                    '50': '#f0f9ff',
                    '100': '#e0f2fe',
                    '200': '#bbe6fc',
                    '300': '#7fd2fa',
                    '400': '#3abbf6',
                    '500': '#11a6ea',
                    '600': '#0482c5',
                    '700': '#05689f',
                    '800': '#095983',
                    '900': '#0d4a6d',
                    '950': '#092f48',
                },

            },
            transitionTimingFunction: {
                'gradient': 'cubic-bezier(.1, 0, .9, 1)',
            },
            keyframes: {
                jumbo: {
                    '0%': {backgroundPosition: '50% 50%, 50% 50%'},
                    '100%': {backgroundPosition: '350% 50%, 350% 50%'},
                },
                fadeOut: {
                    '0%': {opacity: '100%'}, '100%': {opacity: '0%'},
                },
                scroll: {
                    '0%': {transform: 'translate(0)'}, '100%': {transform: 'translate(-50%)'}
                },
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
                    '0%': {opacity: 1}, '50%': {opacity: 1}, '100%': {opacity: 1},
                },
            },
            animation: {
                jumbo: 'jumbo 60s linear infinite',
                'gradient-1': 'firstGradient 11s ease infinite',
                'gradient-2': 'secondGradient 11s ease infinite reverse',
                'gradient-3': 'thirdGradient 11s ease infinite',
                'spotlight': 'spotlight 10s infinite',
                'scroll': 'scroll 60s linear infinite',
                'fade-out': 'fadeOut 1s ease-in-out .5s',
            },
        },
    },
    plugins: [
        require('@tailwindcss/forms'),
        require('@tailwindcss/typography'),
        require('flowbite/plugin'),
    ],
}
