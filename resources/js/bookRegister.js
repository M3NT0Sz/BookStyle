document.querySelector(".button-genre").addEventListener("click", function() {
    document.querySelector(".form-genre").classList.toggle("active");
});

document.getElementById("images").addEventListener("change", function(event) {
    let previewContainer = document.querySelector(".image-preview");

    Array.from(event.target.files).forEach(file => {
        if (file.type.startsWith("image/")) { // Verifica se o arquivo é uma imagem
            let reader = new FileReader();
            reader.onload = function(e) {
                let img = document.createElement("img");
                img.src = e.target.result;
                previewContainer.appendChild(img);
            };
            reader.readAsDataURL(file);
        }
    });
});

