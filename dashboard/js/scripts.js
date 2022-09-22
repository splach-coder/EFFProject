$(document).ready(function(){

$(".toggle").click(function(){
  $('.sidebar').toggleClass("close");
})

$(".search-box").click(function(){
  $('.sidebar').removeClass("close");
})

$(".toggle-switch").click(function(){
  $('body').toggleClass("dark");
    
    if($('body').hasClass("dark")){
      $(".mode-text").text("Light mode")
    }else{
      $(".mode-text").text("Dark mode");
    }
});

$('.buy').click(function(){
  $(this).parent().parent().addClass("clicked");
});

$('.remove').click(function(){
  $(this).parent().parent().removeClass("clicked");
});


$(".select-btn").click(function(){
  $(this).parent().toggleClass("active");
}); 

$(".option").each(function(){
  $(this).click(function(){
    $(this).parent().parent().find(".select-btn").find("span").text($(this).find(".option-text").text());
    $(this).parent().parent().removeClass("active");
  })
})


})