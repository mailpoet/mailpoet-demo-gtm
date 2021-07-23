function getCookie(name) {
    const cookieName = `${encodeURIComponent(name)}=`;
    const cookie = document.cookie;
    let value = null;

    const startIndex = cookie.indexOf(cookieName);
    if (startIndex > -1) {
        let endIndex = cookie.indexOf(';', startIndex);
        if (cookie == -1) {
            endIndex = cookie.length;
        }
        value = decodeURIComponent(cookie.substring(startIndex + name.length, endIndex));
    }
    return value;
}

function setCookie(name, value, expires, path, domain, secure) {
    let cookieText = `${encodeURIComponent(name)}=${encodeURIComponent(value)}`;
    if (expires instanceof Date) {
        cookieText += `; expires=${expires.toGMTString()}`;
    }

    if (path) cookieText += `; path=${path}`;
    if (domain) cookieText += `; domain=${domain}`;
    if (secure) cookieText += `; secure`;

    document.cookie = cookieText;
}

function initGTM(w,d,s,l,i) {
    w[l]=w[l]||[];
    w[l].push({'gtm.start': new Date().getTime(),event:'gtm.js'});
    var f=d.getElementsByTagName(s)[0], j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';
    j.async=true;
    j.src= 'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
}


function cookieNotice() {
    const cookieName = 'cookie_notice_accepted';
    const consentElement = document.getElementById('cookie-notice');
    const cookieState = getCookie(cookieName);
    if (cookieState || !consentElement) {
        return null;
    }

    consentElement.classList.remove('hidden');
    const acceptButton = document.getElementById('accept-cookies');
    const denyButton = document.getElementById('deny-cookies');

    const setCookieConsent = (value) => {
        const domainParts = window.location.hostname.split('.');
        let domain = domainParts.slice(Math.max(domainParts.length - 2, 0)).join('.');

        setCookie(
            cookieName,
            value,
            365 * 100, // approx. 100 years,
            '/',
            domain
        );
    };

    acceptButton.addEventListener('click', (event) => {
        event.preventDefault();
        setCookieConsent('true');
        consentElement.classList.add('hidden');
        initGTM(window, document, 'script', 'dataLayer', 'GTM-MX5LJ9Q');
    });

    denyButton.addEventListener('click', (event) => {
        event.preventDefault();
        setCookieConsent('false');
        consentElement.classList.add('hidden');
    });

    return true;
}

window.onload = function() {
    cookieNotice();
}
