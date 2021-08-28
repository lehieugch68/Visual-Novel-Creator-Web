function openModalDeleteContext(id, display = "block") {
    document.getElementById("deletecontextform").style.display = display;
    document.getElementById("deletecontextid").value = id;
}

function openModalUpdateContext(id, display = "block") {
    let modal = document.getElementById("updateContextForm");
    let input = {
        contextid: document.getElementById("updateContextId"),
        talker: document.getElementById("updateContextTalker"),
        contextorder: document.getElementById("updateContextOrder"),
        text: document.getElementById("updateContextText")
    };
    let talkerIconSelect = document.getElementById("updateContextSelectTalkerIcon");
    let charImgSelect = document.getElementById("updateContextSelectCharImg");
    removeOptions(talkerIconSelect, 1);
    removeOptions(charImgSelect, 1);

    let submit = document.getElementById("updateContextBtn");
    for (let key in input) input[key].value = "";
    submit.style.cursor = "not-allowed";
    submit.disabled = true;
    submit.innerHTML = `Đang tải thông tin <i class="fa fa-spinner fa-spin"></i>`;
    modal.style.display = display;
    let xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4) { 
            let result = JSON.parse(this.responseText);
            if (this.status != 200 || result.error) {
                submit.innerHTML = result.error||"Tải thông tin thất bại!";
            } else {
                submit.style.cursor = "pointer";
                submit.disabled = false;
                submit.innerHTML = "Cập nhật";
                for (let key in result) {
                    if (input[key]) input[key].value = result[key];
                }
                for (let img of result.userimages) {
                    let html = `<option value="${img.imgid}" url="${img.url}">${img.filename}</option>`;
                    talkerIconSelect.insertAdjacentHTML('beforeend', html);
                    charImgSelect.insertAdjacentHTML('beforeend', html);
                }
                setSelected(talkerIconSelect, result.currtalkericon);
                setSelected(charImgSelect, result.currcharimg);
            }
        }
    }
    xhttp.open("GET", `/api/context/story/get/${id}`, true);
    xhttp.send();
}

function setSelected(select, selectedValue) {
    if (selectedValue == null) return;
    for (let i = 0; i < select.options.length; i++) {
        if (select.options[i].value == selectedValue) {
            select.options[i].selected = "selected";
            break;
        }
    }
}

function removeOptions(selectElement, remain) {
    let i, L = selectElement.options.length - 1 + remain;
    for(i = L; i >= remain; i--) {
        selectElement.remove(i);
    }
}

window.addEventListener('load', () => {
    let updateContext = document.getElementById("updateContextData");
    if (updateContext) updateContext.addEventListener('submit', (ev) => {
        ev.preventDefault();
        let btn = document.getElementById("updateContextBtn");
        let feedback = document.getElementById("updatefeedback");
        feedback.style.display = "none";
        btn.disabled = true;
        btn.innerHTML = `<i class="fa fa-spinner fa-spin"></i>`;
        btn.style.cursor = "not-allowed";
        let formData = new FormData(document.getElementById("updateContextData"));
        let xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4) { 
                if (this.status == 204) {
                    location.reload();
                } else {
                    let result;
                    try { result = JSON.parse(this.responseText) }
                    catch { result = this.responseText };
                    feedback.innerHTML = result ? result.error||result : "Lỗi máy chủ";
                    feedback.style.display = "block";
                    btn.disabled = false;
                    btn.innerHTML = "Cập nhật";
                    btn.style.cursor = "pointer";
                }
            }
        }
        xhttp.open("POST", "/api/context/story/update", true);
        xhttp.send(formData);
    });

    let updatePlayer = document.getElementById("updatePlayerData");
    if (updatePlayer) updatePlayer.addEventListener('submit', (ev) => {
        ev.preventDefault();
        let btn = document.getElementById("updatePlayerBtn");
        let feedback = document.getElementById("updatePlayerFeedback");
        feedback.style.display = "none";
        btn.disabled = true;
        btn.innerHTML = `<i class="fa fa-spinner fa-spin"></i>`;
        btn.style.cursor = "not-allowed";
        let formData = new FormData(document.getElementById("updatePlayerData"));
        let xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4) { 
                if (this.status == 204) {
                    location.reload();
                } else {
                    let result;
                    try { result = JSON.parse(this.responseText) }
                    catch { result = this.responseText };
                    console.log(result);
                    feedback.innerHTML = result ? result.error||result : "Lỗi máy chủ";
                    feedback.style.display = "block";
                    btn.disabled = false;
                    btn.innerHTML = "Cập nhật";
                    btn.style.cursor = "pointer";
                }
            }
        }
        xhttp.open("POST", updatePlayer.action, true);
        xhttp.send(formData);
    });

    let updateEnemy = document.getElementById("updateEnemyData");
    if (updateEnemy) updateEnemy.addEventListener('submit', (ev) => {
        ev.preventDefault();
        let btn = document.getElementById("updateEnemyBtn");
        let feedback = document.getElementById("updateEnemyFeedback");
        feedback.style.display = "none";
        btn.disabled = true;
        btn.innerHTML = `<i class="fa fa-spinner fa-spin"></i>`;
        btn.style.cursor = "not-allowed";
        let formData = new FormData(document.getElementById("updateEnemyData"));
        let xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4) { 
                if (this.status == 204) {
                    location.reload();
                } else {
                    let result;
                    try { result = JSON.parse(this.responseText) }
                    catch { result = this.responseText };
                    console.log(result);
                    feedback.innerHTML = result ? result.error||result : "Lỗi máy chủ";
                    feedback.style.display = "block";
                    btn.disabled = false;
                    btn.innerHTML = "Cập nhật";
                    btn.style.cursor = "pointer";
                }
            }
        }
        xhttp.open("POST", updateEnemy.action, true);
        xhttp.send(formData);
    });
})