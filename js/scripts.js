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

function addToDOM(message, id) {
    // crée un nouvel élément div
    let div = document.getElementById('errorpassword');
    // et lui donne un peu de contenu
    if (document.getElementById(id) === null) {
        let newDiv = document.createElement('div');
        newDiv.setAttribute("id", id);
        let newContent = document.createTextNode(message);
        // ajoute le nœud texte au nouveau div créé
        newDiv.appendChild(newContent);
        div.appendChild(newDiv);
    }
}

function removeToDOM(id) {
    let el = document.getElementById(id);
    if (el !== null) {
        el.remove();
    }
    return
}

function verifPassword(pass) {
    let validMaj = verifPasswordMaj(pass);
    let validMin = verifPasswordMin(pass);
    let validDigit = verifPasswordDigit(pass);
    let validLength = verifPasswordLength(pass);
    if (!validMaj) {
        addToDOM('Le mot de passe doit comporter au moins une majuscule', 'maj');
    } else if (validMaj) {
        removeToDOM('maj')
    }
    if (!validMin) {
        addToDOM('Le mot de passe doit comporter au moins une minuscule', 'min');
    } else if (validMin) {
        removeToDOM('min')
    }
    if (!validDigit) {
        addToDOM('Le mot de passe doit comporter au moins un chiffre', 'digit');
    } else if (validDigit) {
        removeToDOM('digit')
    }
    if (!validLength) {
        addToDOM('le mot de passe doit comporter au moins 8 characters', 'length');
    } else if (validLength) {
        removeToDOM('length')
    }
}

let inputPassword = document.querySelectorAll('input[type = "password"]')

inputPassword.forEach(input => {
    input.addEventListener('input', Event => {
        verifPassword(Event.target.value);
    })
})
