function openModalDeleteScene(id, display = "block") {
    document.getElementById("deletesceneform").style.display = display;
    document.getElementById("deletesceneid").value = id;
}
function openModalUpdateScene(id, display = "block") {
    let modal = document.getElementById("updatesceneform");
    let input = {
        introid: document.getElementById("updatesceneid"),
        title: document.getElementById("updatescenetitle"),
        introorder: document.getElementById("updatesceneorder"),
        content: document.getElementById("updatescenecontent")
    };
    let submit = document.getElementById("updatescenebtn");
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
                submit.innerHTML = result ? result.error||result : "Tải thông tin thất bại";
            } else {
                submit.style.cursor = "pointer";
                submit.disabled = false;
                submit.innerHTML = "Cập nhật";
                for (let key in result) {
                    if (input[key]) input[key].value = result[key];
                }
            }
        }
    }
    xhttp.open("GET", `/api/intro/get/${id}`, true);
    xhttp.send();
}

function submitUpdate(btn) {
    let feedback = document.getElementById("updatefeedback");
    feedback.style.display = "none";
    btn.disabled = true;
    btn.innerHTML = `<i class="fa fa-spinner fa-spin"></i>`;
    btn.style.cursor = "not-allowed";
    let formData = new FormData(document.getElementById("updatedata"));
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
    xhttp.open("POST", "/api/intro/update", true);
    xhttp.send(formData);
}