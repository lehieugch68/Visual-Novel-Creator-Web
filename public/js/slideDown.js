function slideDown(title) {
    let des = title.nextElementSibling;
    if (!des) return;
    if (des.getAttribute('expand') === 'false') {
        des.style.height = "150px";
        des.setAttribute('expand', 'true');
    } else {
        des.style.height = "0px";
        des.setAttribute('expand', 'false');
    }
}