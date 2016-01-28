var page = require('webpage').create(),
    system = require('system'),
    address,output, width,height;

if (system.args.length === 1) {
    console.log('Usage: loadspeed.js <some URL>');
    phantom.exit();
}else {

    address = system.args[1];
    output = system.args[2];
    sizeX = system.args[3];
    sizeY = system.args[4];

    page.viewportSize = {width: sizeX, height: sizeY};
    page.clipRect = {
        top: 0,
        left: 0,
        width: sizeX,
        height: sizeY
    };
    page.open(address, function (status) {
        if (status !== 'success') {
            console.log('FAIL to load the address');
        } else {
            console.log('success');
            window.setTimeout(function () {
                page.render(output);
                phantom.exit();
            }, 200);
        }
    });
}
