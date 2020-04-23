const fs = require('fs-extra');
const concat = require('concat');

concatenate = async() => {
    const files = [
        './dist/winky-app/runtime-es5.js',
        //'./dist/winky-app/runtime-es2015.js',
        './dist/winky-app/polyfills-es5.js',
        //'./dist/winky-app/polyfills-es2015.js',
        './dist/winky-app/main-es5.js',
        //'./dist/winky-app/main-es2015.js'
    ];

    await fs.ensureDir('target');
    await concat(files, 'target/winky-client.js');
}
concatenate();