<h1>Синхронізація тендерів</h1>
<div class='puch_message'>
    <center>
    <div class='status'></div>
    </center>
    <div class='show_loader' style='display: none;'>
            <div class="loader" >
                    <div class="progress-bar"><div class="progress-stripes"></div><div class="percentage">0%</div></div>
            </div>
            <span>Cинхронізує...</span>
    </div>
    <p class='show_interrupt' style='display: none; color: red' >Припиняю синхронізацію...</p>
</div>
<div style="text-align: center; margin-top: 30px;">
    <form id="synchronicity" action="javascript:void(0);">
            <label for="exampleFormControlInput1">Введіть кількість</label>
            <input  class="synchronicity_count" min="1" max="1000" placeholder="1000" type="number" name="title" class="form-control" id="exampleFormControlInput1" required>
        <button type="submit" class="btn btn-success submit_form">Почати синхронізацію</button>
        <button type="button" class="btn btn-danger interrupt" disabled="">Зупинити</button>
    </form>
</div>


<script>
$('#synchronicity').submit(function() {
    $(".submit_form").attr('disabled', true);	
    $(".interrupt").attr('disabled', false);
    $('.status').html('Зачекайте...');
    $.ajax({
            url: '/synchronicity/handler',
            type: 'POST',
            data: {
                count: $('.synchronicity_count').val()
            },
            success: function(response) {
                if(IsJsonString(response)){
                    response = JSON.parse(response);
                }
                if(response.timeout == 'true'){
                    timeout_step(response);
                }else{	
                    clearInterval(window.myVar);
                    $('.status').html(response);
                    loaded = true;
                    $progress.animate({
                            width: "100%"
                    }, 100, function() {
                            $('span').text('Успішно!').addClass('loaded');
                            $percent.text('100%');
                    });
                    $('.show_interrupt').hide();
                    $(".submit_form").attr('disabled', false);
                    $(".interrupt").attr('disabled', true);
                }
            },
            error: function(response) { 
                    $('.status').html('Ошибка. Данные не отправлены.');
            }
    });
            window.myVar = setInterval(progress_bar, 1000);
});
    $('.interrupt').click(function(){
        $('.show_interrupt').show();
        $.ajax({
                url: '/synchronicity/stop',
                type: "POST", 
                dataType: "html",
                data: $(this).serialize(),  
                success: function(response) { 
                    $('.status').html(response);
                        $(".interrupt").attr('disabled', true);
                },
                error: function(response) { 
                        $('.status').html('Ошибка stop');
                }
        });
    });
    function IsJsonString(str) {
        try {
            JSON.parse(str);
        } catch (e) {
            return false;
        }
        return true;
    }

    function progress_bar(){
        $.ajax({
            url: '/synchronicity/progressbar',
            type: "POST",			
            dataType: "html",
            success: function(response) { 
                    if(IsJsonString(response)){
                    response = JSON.parse(response);
                    }
                    $('.status').html(response.count);
                    $('.show_loader').show();
                    preload(response.percent);
            },
            error: function(response) {
                    $('.status').html('Ошибка');
            }
        });
    }	

function timeout_step(arrayStep){
    $.ajax({
        url: '/synchronicity/timeout',
        type: "POST",		
        data: {
                params: arrayStep,
                },  
        success: function(response) {
             console.log(response);
                if(IsJsonString(response)){
                        response = JSON.parse(response);
                }
                if(response.timeout == 'true'){
                        console.log(response);
                        timeout_step(response);

                }else{	
                        clearInterval(window.myVar);
                        $('.status').html(response);
                        loaded = true;
                        $progress.animate({
                                width: "100%"
                        }, 100, function() {
                                $('span').text('Успешно!').addClass('loaded');
                                $percent.text('100%');
                        });
                        $('.show_interrupt').hide();
                        $(".submit_form").attr('disabled', false);
                        $(".interrupt").attr('disabled', true);
                }
        },
        error: function(response) { 
                $('.status').html('Ошибка. Данные не отправлены.');
        }
    });
}

/* SET RANDOM LOADER COLORS FOR DEMO PURPOSES */	
	var demoColorArray = ['red','blue','green','yellow','purple','gray'];
	setSkin(demoColorArray[2]);
	
	// Stripes interval
	var stripesAnim;
	var calcPercent;
	
	$progress = $('.progress-bar');
	$percent = $('.percentage');
	$stripes = $('.progress-stripes');
	$stripes.text('////////////////////////');
	
	/* CHANGE LOADER SKIN */
	$('.skin').click(function(){
		var whichColor = $(this).attr('id');
		setSkin(whichColor);
	});
	
	// Call function to load array of images
	
	
	// Call function to animate stripes
	stripesAnimate(); 

	
	/* LOADING */
	function preload(increment) {
			$progress.animate({
				width: increment + "%"
			}, 100);
	        $percent.text(increment+'%');		
	}
	function stripesAnimate() {
		animating();
		stripesAnim = setInterval(animating, 2500);
	}

	function animating() {
		$stripes.animate({
			marginLeft: "-=30px"
		}, 2500, "linear").append('/');
	} 
	
	function setSkin(skin){
		$('.loader').attr('class', 'loader '+skin);
		$('span').hasClass('loaded') ? $('span').attr('class', 'loaded '+skin) : $('span').attr('class', skin);
	}
</script>