$(function(){
    var lw = $(".warning");
    var alphaNum = /^[a-zA-Z0-9]*$/;
    $('#loginForm').submit(function(e){
        e.preventDefault();

        var form = $(this);

        if(!$("#loginPass").val() || !$("#loginMail").val()){
            lw.css("visibility","visible");
            lw.html("Both fields need to be filled!");
        }
        else{
            lw.css("visibility","hidden");
            lw.html("");
            $.ajax({
                url: form.attr("action"),
                type: "POST",
                dataType: "json",
                data: form.serialize(),
                success: function(data){
                },
                error: function(){
                    alert("There appears to be a problem with the server, try again in a little while.");
                }
            }).done(function(e){
                if(e.success){
                    $("#loginModal").modal("hide");
                    location.reload();
                }
                else {
                    lw.css("visibility","visible");
                    lw.html(e.error);
                }
            })
        }
    });

    $('#signupForm').submit(function(e){
        e.preventDefault();

        var form = $(this);

        if(!$("#signupPass1").val() || !$("#signupPass2").val() || !$("#signupUser").val() || !$("#signupMail").val()){
            lw.css("visibility","visible");
            lw.html("All fields need to be filled!");
        }
        else if($("#signupPass1").val() !== $("#signupPass2").val()){
            lw.css("visibility","visible");
            lw.html("Your passwords need to be the same!");
        }
        else if(!alphaNum.test($("#signupUser").val())){
            lw.css("visibility","visible");
            lw.html("Only letters and numbers can be used in your username.");
        }
        else if($("#signupUser").val().length > 20){
            lw.css("visibility","visible");
            lw.html("Your username can't be more than 20 characters.");
        }
        else{
            lw.css("visibility","hidden");
            lw.html("");
            $.ajax({
                url: form.attr("action"),
                type: "POST",
                dataType: "json",
                data: form.serialize(),
                success: function(){
                },
                error: function(){
                    alert("There appears to be a problem with the server, try again in a little while.");
                }
            }).done(function(e){
                if(e.success){
                    console.log(e);
                    $("#loginModal").modal("hide");
                    location.reload();
                }
                else {
                    lw.css("visibility","visible");
                    lw.html(e["error"]);
                }
            })
        }

    });

    $('#commentForm').submit(function(e){
        e.preventDefault();

        var form = $(this);

        if(!$("#commentTitle").val() || !$("#commentText").val()){
            lw.css("visibility","visible");
            lw.html("All fields need to be filled!");
        }
        else{
            lw.css("visibility","hidden");
            lw.html("");
            $.ajax({
                url: form.attr("action"),
                type: "POST",
                dataType: "json",
                data: form.serialize(),
                success: function(){
                },
                error: function(){
                    alert("There appears to be a problem with the server, try again in a little while.");
                }
            }).done(function(e){
                if(e.success){
                    location.reload();
                }
                else {
                    lw.css("visibility","visible");
                    lw.html(e["error"]);
                }
            })
        }

    });

    $('#addCategory').submit(function(e){
        e.preventDefault();

        var form = $(this);

        if(!$("#categoryInput").val()){
            lw.css("visibility","visible");
            lw.html("Category name can't be empty!");
        }
        else if($("#categoryInput").val().length > 40){
            lw.css("visibility","visible");
            lw.html("Category name can't be this long!");
        }
        else if($("#categoryInput").val().length < 3){
            lw.css("visibility","visible");
            lw.html("Category name can't be this short!");
        }
        else{
            lw.css("visibility","hidden");
            lw.html("");
            $.ajax({
                url: form.attr("action"),
                type: "POST",
                dataType: "json",
                data: form.serialize(),
                success: function(){
                },
                error: function(){
                    alert("There appears to be a problem with the server, try again in a little while.");
                }
            }).done(function(e){
                if(e.success){

                }
                else {
                    lw.css("visibility","visible");
                    lw.html(e["error"]);
                }
            })
        }

    });

    $('#createpostBtn').click(function(){
        window.location.href = "makepost.php"
    });

    $('#logoutBtn').click(function(){
        window.location.href = "logic/logout.php";
    });
});