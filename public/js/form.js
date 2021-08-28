window.addEventListener('load', () => {
    let closeBtns = document.getElementsByClassName("form-close-btn");
    for (let closeBtn of closeBtns) {
        closeBtn.addEventListener('click', closeAllModal);
    }
})

function openModal(id, display = "block") {
    let modal = document.getElementById(id);
    if (modal) modal.style.display = display;
}


function closeAllModal() {
    let modals = document.getElementsByClassName("form-modal");
    for (let modal of modals) modal.style.display = "none";
}

function selectFile(chooseFile, fileUpload, noFile) {
    let filename = chooseFile.value;
    if (/^s*$/.test(filename)) {
        document.getElementById(fileUpload).classList.remove('active');
        document.getElementById(noFile).innerText = "Chưa có tệp..."; 
    } else {
        document.getElementById(fileUpload).classList.add('active');
        document.getElementById(noFile).innerText = filename.replace("C:\\fakepath\\", ""); 
    }
}