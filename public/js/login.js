window.addEventListener('load', () => {
    const loginBtn = document.getElementById("loginsubmit");
    loginBtn.onclick = () => {
        const feedback = document.getElementById("loginfeedback");
        let formData = new FormData(document.getElementById("logindata"));
        let xhttp = new XMLHttpRequest();
        loginBtn.innerHTML = `<i class="fa fa-spinner fa-spin"></i>`;
		loginBtn.disabled = true;
        loginBtn.style.cursor = "not-allowed";
        feedback.style.display = "none";
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4) { 
                loginBtn.disabled = false;
                if (this.status == 204) {
                    location.reload();
                } else {
                    let result;
                    try { result = JSON.parse(this.responseText) }
                    catch { result = this.responseText };
                    feedback.innerHTML = result ? result.error||result : "Lỗi máy chủ";
                    feedback.style.display = "block";
                    loginBtn.innerHTML = "Đăng nhập";
                    loginBtn.style.cursor = "pointer";
                }
            }
        }
        xhttp.open("POST", "/login", true);
        xhttp.send(formData);
    }
});