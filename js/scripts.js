/*!
* Start Bootstrap - Clean Blog v6.0.8 (https://startbootstrap.com/theme/clean-blog)
* Copyright 2013-2022 Start Bootstrap
* Licensed under MIT (https://github.com/StartBootstrap/startbootstrap-clean-blog/blob/master/LICENSE)
*/
window.addEventListener('DOMContentLoaded', () => {
    let scrollPos = 0;
    const mainNav = document.getElementById('mainNav');
    const headerHeight = mainNav.clientHeight;
    window.addEventListener('scroll', function () {
        const currentTop = document.body.getBoundingClientRect().top * -1;
        if (currentTop < scrollPos) {
            // Scrolling Up
            if (currentTop > 0 && mainNav.classList.contains('is-fixed')) {
                mainNav.classList.add('is-visible');
            } else {
                console.log(123);
                mainNav.classList.remove('is-visible', 'is-fixed');
            }
        } else {
            // Scrolling Down
            mainNav.classList.remove(['is-visible']);
            if (currentTop > headerHeight && !mainNav.classList.contains('is-fixed')) {
                mainNav.classList.add('is-fixed');
            }
        }
        scrollPos = currentTop;
    });

})

function verifPasswordMaj(pass) {
    let majRegex = new RegExp('[A-Z]');
    return majRegex.test(pass);
}

function verifPasswordMin(pass) {
    let minRegex = new RegExp('[a-z]');
    return minRegex.test(pass);
}

function verifPasswordDigit(pass) {
    let chiffreRegex = new RegExp('[0-9]');
    return chiffreRegex.test(pass);
}

function verifPasswordLength(pass) {
    return pass.length >= 8;
}

function selectIcon(id) {
    let el = document.getElementById(id);
    return [el.querySelector('.fault'), el.querySelector('.check')];
}

function validPassword(id) {
    let icons = selectIcon(id);
    icons[0].classList.add('d-none');
    icons[0].classList.remove('d-block');
    icons[1].classList.add('d-block');
    icons[1].classList.remove('d-none');
}

function invalidPassword(id) {
    let icons = selectIcon(id);
    icons[0].classList.add('d-block');
    icons[0].classList.remove('d-none');
    icons[1].classList.remove('d-block');
    icons[1].classList.add('d-none');
}

function verifPassword(pass) {
    let validMaj = verifPasswordMaj(pass);
    let validMin = verifPasswordMin(pass);
    let validDigit = verifPasswordDigit(pass);
    let validLength = verifPasswordLength(pass);
    if (!validMaj) {
        invalidPassword('maj');
    } else if (validMaj) {
        validPassword('maj');
    }
    if (!validMin) {
        invalidPassword('min');
    } else if (validMin) {
        validPassword('min');
    }
    if (!validDigit) {
        invalidPassword('digit')
    } else if (validDigit) {
        validPassword('digit');
    }
    if (!validLength) {
        invalidPassword('length')
    } else if (validLength) {
        validPassword('length');
    }
}

let inputPassword = document.getElementById('verifPassword')

if (inputPassword) {
    inputPassword.addEventListener('input', Event => {
        verifPassword(Event.target.value);
    })
}
