
$(document).ready(function() {
    let input = document.getElementById('profile_picture_form_image');
    let dropArea = document.getElementById('dropArea');
    let dropAreaMessage = document.getElementById('dropArea-message');
    let output = document.getElementById('preview');
    let deleteImage = document.getElementById('delete-image');
    let form = document.getElementById('image-form');
    const allowedFormats = ['image/jpeg', 'image/png', 'image/gif'];
    let formData;

    input.addEventListener('change', fileSelectHandler, false)
    dropArea.addEventListener('dragover', fileDragHover, false);
    dropArea.addEventListener('dragleave', fileDragHover, false);
    dropArea.addEventListener('drop', fileSelectHandler, false);
    deleteImage.addEventListener('click', fileRemove, false);
    form.addEventListener('submit', fileSubmit, false );

    function fileDragHover(e){
        e.stopPropagation();
        e.preventDefault();
        dropArea.className = (e.type === "dragover" ? "highlight" : "");

    }

    function fileSelectHandler(e){
        e.stopPropagation();
        e.preventDefault();
        e.target.className=(e.type==="dragover" ? "highlight" : "")

        const [file] = e.target.files || e.dataTransfer.files;

        if (!allowedFormats.includes(file.type)) {
            changeMessageMissmatch('provide a vaid jpg or png file');
            setTimeout(changeMessageDrop, 5000);
        } else if(file.size> 5000000) {
            changeMessageMissmatch('provide a file at max size 5Mb');
            setTimeout(changeMessageDrop, 5000);
        }
        else
        {
            if (file) {
                output.src = URL.createObjectURL(file)
            }
            formData = new FormData()
            formData.append('profile_picture_form[image]', file)
            console.log(formData)
        }

    }

    function changeMessageMissmatch(){

        dropAreaMessage.innerText = 'incorrect file type';
    }
    function changeMessageDrop(){
        dropAreaMessage.innerText = 'drop file';
    }

    function fileRemove(){
        $('#modal-delete').modal('hide');
        $.ajax({
            url: '/default',
            method: 'GET',
            dataType: 'json',
            success: function (response) {
                $('#user-pic-image').attr('src', 'uploads/profile_images/default_user.png');
                $('#preview').attr('src', 'uploads/profile_images/default_user.png');
            },
        });
    }

    function fileSubmit(e){
        e.preventDefault();
        $('#modal-image').modal('hide');
        const csrfToken = document.querySelector('input[name="profile_picture_form[_token]"]').value;
        formData.append('profile_picture_form[_token]', csrfToken)
        console.log(formData);

        $.ajax({
            url: '/test',
            method: 'POST',
            data: formData,
            dataType: 'json',
            processData: false,
            contentType: false,
            success: function (response) {
                $('#user-pic-image').attr('src', "uploads/profile_images/"+response.html);
                $('#preview').attr('src', "uploads/profile_images/"+response.html);

            },
        });

    }






});


