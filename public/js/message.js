async function message() {
    const messages = JSON.parse($('.messages').val());
    let content = "";
    messages.forEach(msg => {
        let h = msg.created_at.substr(11, 2);
        let m = msg.created_at.substr(14, 2);
        msg.created_at = h + "：" + m;
        let user_icon = $('.sender_icon').val();
        let user_name = $('.sender_name').val();
        if (msg.user_id == $('.recipient_id').val()) {
            user_icon = $('.recipient_icon').val();
            user_name = $('.recipient_name').val();
        }
        const val = {
            'message': msg,
            'user_icon': user_icon,
            'user_name': user_name
        }
        content += $('.user_id').val() == msg.user_id ? sender_tag(val) : rescipient_tag(val);
    });

    $('.msgArea').html(content); ƒ
}

message();


$('.btn').on('click', function () {
    async function submit() {
        const params = {
            msg: $('.form-control').val(),
            board_id: $('.board_id').val(),
            user_id: $('.user_id').val(),
        }

        await axios.post(`/boards/${params.board_id}/messages`, params)
            .then(res => {
                const content = sender_tag(res.data);
                console.log(content);
                $('.msgArea').append(content);
                $('.form-control').val('');
            })
            // .catch(e => {
            //     alert(e.response);
            // });
    }

    submit();
})

function sender_tag(val) {
    return `<li class="mb-3 p-0">
    <div class="container-lg">

        <div class="balloon6 my-0" style="margin-left: 200px;">
            <div class="faceicon float-right">
    　          <img src="../storage/profile_image/${val.user_icon}" alt="icon" class="icon rounded-circle img-fluid">
    　          <p class="user_name">${val.user_name}</p>
            </div>

            <div class="balloon1-right float-right mt-0">
      　         <p class="content says text-warp" style="width: 20rem;">${val.message.msg}</p>
             <p class="date text-info text-right">${val.message.created_at}</p>
            </div>
        </div>
    </div>
            </li>`;

}
function rescipient_tag(val) {
    return `<li class="mb-3 ">
    <div class="container-lg">
        <div class="balloon6 my-0 pl-3">
            <div class="faceicon">
            　<img src="../storage/profile_image/${val.user_icon}" alt="icon" class="icon rounded-circle img-fluid">
            　<p class="user_name">${val.user_name}</p>
            </div>
        
            <div class="balloon1-left float-left mt-0">
            　  <p class="content says text-warp" style="width: 20rem;">${val.message.msg}</p>
            　  <p class="date text-info">${val.message.created_at}</p>
            </div>
        </div>
    </div>
       
                    </li>`;

}