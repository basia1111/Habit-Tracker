
$(document).ready(function(){
    const allowedFormats = ['image/jpeg', 'image/png', 'image/gif'];
    let formData;
    $('#row-content').on('dragover', '#dropArea', function (e) {
        e.stopPropagation();
        e.preventDefault();
        $('#dropArea').addClass("highlight");
    });

    $('#row-content').on('dragleave', '#dropArea', function (e) {
        e.stopPropagation();
        e.preventDefault();
        $('#dropArea').removeClass("highlight");
    });


    $('#row-content').on('drop', '#dropArea', function (e) {
        console.log('drop');
        e.stopPropagation();
        e.preventDefault();

        $('#dropArea').removeClass("highlight");


        const [file] = e.originalEvent.dataTransfer.files;

        if (!allowedFormats.includes(file.type)) {
            changeMessageMissmatch('provide a vaid jpg or png file');
            setTimeout(changeMessageDrop, 5000);
        } else if (file.size > 5000000) {
            changeMessageMissmatch('provide a file at max size 5Mb');
            setTimeout(changeMessageDrop, 5000);
        } else {
            if (file) {
                $('#preview-create').attr('src', URL.createObjectURL(file));
            }
            formData = new FormData()
            formData.append('post[cover]', file)
            console.log(formData);

        }

    });

    $('#row-content').on('change', '#post_cover', function (e) {
        console.log('drop');
        e.stopPropagation();
        e.preventDefault();
        const [file] = e.target.files;
        if (file) {
            $('#preview-create').attr('src', URL.createObjectURL(file));
        }
        formData = new FormData()
        formData.append('post[cover]', file)
        console.log(formData)
    });

    function changeMessageMissmatch(){
        $('#dropArea-message').innerText = 'incorrect file type';
    }

    function changeMessageDrop() {
        $('#dropArea-message').innerText = 'drop file';
    }

    $('#row-content').on('submit', '#form-create-post', function (e){
        e.preventDefault();
        $('#modal-image').modal('hide');

        const csrfToken = document.querySelector('input[name="post[_token]"]').value;
        formData.append('post[_token]', csrfToken);
        const title = document.querySelector('input[name="post[title]"]').value;
        formData.append('post[title]', title);
        const content = document.querySelector('textarea[name="post[content]"]').value;
        formData.append('post[content]', content)

        console.log(formData);

        $.ajax({
            url: '/post_create',
            method: 'POST',
            data: formData,
            dataType: 'json',
            processData: false,
            contentType: false,
            success: function (response) {
                changePage('posts');
            },
        });

    });

    $('#row-content').on('submit', '#form-edit-post', function (e){
        console.log( formData);
        e.preventDefault();

        if (formData == null){
            formData = new FormData()
        }
        const csrfToken = document.querySelector('input[name="post[_token]"]').value;
        formData.append('post[_token]', csrfToken);
        const title = document.querySelector('input[name="post[title]"]').value;
        formData.append('post[title]', title);
        const content = document.querySelector('textarea[name="post[content]"]').value;
        formData.append('post[content]', content)
        let id =  document.querySelector('#form-edit-post').getAttribute("var");

        console.log(formData);
        var timestamp = new Date().getTime();
        var ajaxUrl = '/post_edit/'+id + '?_=' + timestamp;

        $.ajax({
            url: ajaxUrl,
            method: 'POST',
            data: formData,
            dataType: 'json',
            processData: false,
            contentType: false,
            success: function (response) {
                $('#post-details').toggleClass('d-block d-none');
                $('#post-edit').toggleClass('d-block d-none');
                $('#page-container').html(response.html);
            },
        });

    });

});