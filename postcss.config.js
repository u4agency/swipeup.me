module.exports = {
    plugins: [
        require('tailwindcss'),
        require('postcss-100vh-fix'),
        require('postcss-viewport-height-correction'),
        require('autoprefixer'),
    ]
};