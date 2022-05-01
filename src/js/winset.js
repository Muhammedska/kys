function detectMob() {
    const toMatch = [
        /Android/i,
        /webOS/i,
        /iPhone/i,
        /iPad/i,
        /iPod/i,
        /BlackBerry/i,
        /Windows Phone/i
    ];

    return toMatch.some((toMatchItem) => {
        return navigator.userAgent.match(toMatchItem);
    });
}

/* System Runners */

if (detectMob() == true) {
    document.getElementById('mnav').classList.add('toggled');
    document.getElementById('page-top').classList.add('sidebar-toggled');
    console.log('ok')
}